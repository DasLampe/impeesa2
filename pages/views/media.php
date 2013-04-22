<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class MediaView extends AbstractView {
	public function MainView() {
		//Dummy
	}
	
	public function Overview() {
		include_once(PATH_MODEL."media.php");
		$this->model	= new MediaModel();
		
		$layer			= impeesaLayer::getInstance();
		$layer->AddButton('<a href="'.LINK_ACP.'media/upload">Datei hinzufügen</a>');
		
		$return			= "";
		foreach($this->model->getMedia() as $media) {
			$this->tpl->vars("link",		LINK_MAIN."m/".rawurlencode($media['name']));
			$this->tpl->vars("thumb_link",	$media['thumb']);
		
			$return		.= $this->tpl->load("_media_block", PATH_PAGES_TPL."media/");
		}
		
		$this->tpl->vars("media",	$return);
		return $this->tpl->load("overview", PATH_PAGES_TPL."media/");
	}
	
	public function UploadView($file) {
		include_once(PATH_MODEL."media.php");
		$this->model	= new MediaModel();
		
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		$form 			= new impeesaForm();
		
		$form_fields	= array(
			array('fieldset', '', array(
					array('upload', "Datei auswählen", 'file', '', True),
					array('submit',	'Hochladen', 'submit'),
				),
			),
		);
		
		if(empty($file)) {
			return $form->GetForm($form_fields, CURRENT_PAGE, "post", "form", true);
		} else {
			if($this->model->uploadFile($file)) {
				return impeesaLayer::SetInfoMsg($_SESSION, "Datei wurde erfolgreich hochgeladen.", LINK_ACP."media/");
			}
			return impeesaLayer::SetInfoMsg($_SESSION, "Datei konnte nicht hochgeladen werden.", LINK_ACP."media/", "error");
		}
	}
}