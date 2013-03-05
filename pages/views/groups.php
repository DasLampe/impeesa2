<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2013 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class GroupsView extends AbstractView {
	public function MainView() {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		
		$groups	= "";
		foreach($this->model->GetAllGroups() as $group) {
			$this->tpl->vars("group_name",			$group['name']);
			$this->tpl->vars("group_description",	$group['description']);
			$this->tpl->vars("group_youngest",		$group['youngest']);
			$this->tpl->vars("group_oldest",		$group['oldest']);
			$this->tpl->vars("group_day",			$group['day']);
			$this->tpl->vars("group_begin",			date("H:i", strtotime($group['begin'])));
			$this->tpl->vars("group_end",			date("H:i", strtotime($group['end'])));
			$this->tpl->vars("group_leader",		implode(', ', $this->model->GetLeader($group['id'])));
			$this->tpl->vars("group_logo",			$group['logo']);
			$groups	.= $this->tpl->load("_group", PATH_PAGES_TPL."groups/");
		}
		
		$this->tpl->vars("headline",				"Gruppen");
		$this->tpl->vars("content",					$groups);
		return $this->tpl->load("content");
	}
	
	public function LeaderView() {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		
		$groups	= "";
		foreach($this->model->GetAllGroups(0) as $group) {
			$leaders	= "";
			foreach($this->model->GetLeader($group['id']) as $leader) {
				$this->tpl->vars("leader_name",		$leader);
				$leaders	.= $this->tpl->load("_leader", PATH_PAGES_TPL."groups/");
			}
			$this->tpl->vars("content",		$leaders);
			$this->tpl->vars("headline",	$group['name']);
			$groups		.= $this->tpl->load("_content_box_h2");
		}
		
		$this->tpl->vars("headline",	"Leiterrunde");
		$this->tpl->vars("content",		$groups);
		return $this->tpl->load("content");
	}
	
	public function AdminView() {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		$layer			= impeesaLayer::getInstance();
		
		$layer->AddButton('<a href="'.LINK_ACP.'groups/edit" class="ym-add">Gruppe erstellen</a>');
		
		$groups	= "";
		foreach($this->model->GetAllGroups(0) as $group) {
			$this->tpl->vars("group_id",		$group['id']);
			$this->tpl->vars("group_name",		$group['name']);
			$this->tpl->vars("in_overview",		($group['in_overview'] == 1) ? "Kindergruppe" : "Sonstige Gruppe");
			$this->tpl->vars("group_leader",	implode(", ", $this->model->GetLeader($group['id'])));
			
			$groups	.= $this->tpl->load("overview", PATH_PAGES_TPL."groups/");
		}
		$this->tpl->vars("headline",	"Gruppen verwalten");
		$this->tpl->vars("content",		$groups);
		return $this->tpl->load("content_table");
	}
	
	public function EditView($data, $group_id=null) {
		include_once(PATH_CORE_CLASS."impeesaForm.class.php");
		include_once(PATH_CORE_CLASS."impeesaUser.class.php");
		include_once(PATH_MODEL."groups.php");
		$form 			= new impeesaForm();
		$user			= new impeesaUser($_SESSION);
		$this->model	= new GroupsModel(); 
		
		if(is_numeric($group_id) && !isset($data['submit'])) {
			$data	= $this->model->GetGroup($group_id);
		}
		
		if(empty($data)) {
			//Fill data to vars
			$data	= array(
							"name"			=> "",
							"in_overview"	=> True,
							"youngest"		=> "",
							"oldest"		=> "",
							"day"			=> "",
							"begin"			=> "",
							"end"			=> "",
							"description"	=> "",
							"leader"		=> "",
						);
		}
		if(!isset($data['in_overview'])) {
			$data['in_overview']	= False;
		}
		
		$form_fields	= array(
								array('fieldset', '', array(
										array('text', 'Name', 'name', $data['name'], True),
										array('checkbox', 'Reguläre Gruppe', 'in_overview', 'in_overview', False, $data['in_overview']),
										array('select', 'Logo', 'logo', array(
											array('option', 'Bitte auswählen', '', True),
											//Fill with data below
											), 
										True),
									),
								),
								array('fieldset', 'Allgemeine Infos', array(
									array('fieldset', 'Alter der Grüpplinge', array(
											array('text', 'Jüngste', 'youngest', $data['youngest'], True),
											array('text', 'Älteste', 'oldest', $data['oldest'], True),
											),
										),
									array('fieldset', 'Gruppenstunden', array(
										array('select', 'Tag', 'day', array(
												array('option', 'Bitte auswählen', '', True, True),
												//Fill with data below
												),
											True),
											array('time', 'Begin', 'begin', date("H:i", strtotime($data['begin'])), True),
											array('time', 'Ende', 'end', date("H:i", strtotime($data['end'])), True),
											),
										),
									),
								),
								array('fieldset', 'Weiter Infos zur Gruppe', array(
									array('textarea', '', 'description', $data['description']),
									),
								),
								array('fieldset', 'Leiter', array(
										//Fill with data below
									),
								),
								array('fieldset', '', array(
										array('submit', 'Speichern', 'submit'),
									),
								),
							);
		
		//Fill logo
		foreach($this->model->ReadLogos() as $logo) {
			$selected			= (isset($data['logo']) && $data['logo'] == $logo['filename']) ? True : False;
			$form->fillData($form_fields, array("fieldset", "select"), array('option', $logo['name'], $logo['filename'], 0, $selected));
		}

		//Fill day
		foreach(array("Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag") as $day) {
			$selected	= (isset($data['day']) && $day == $data['day']) ? True : False;
			$form->fillData($form_fields, array('fieldset', 'fieldset', 'select'), array('option', $day, $day, 0, $selected));
		}
		
		//Fill leader
		$all_leader	= $this->model->GetLeader($group_id);
		foreach($user->GetAllUserIds() as $leader) {
			$userinfo				= $user->GetUserInfo($leader['id']);
			$name					= $userinfo['first_name'].' '.$userinfo['name'];
			$is_leader				= False;
			if((!empty($data['leader']) && in_array($leader['id'], $data['leader'])) || (!isset($data['submit']) && in_array($name, $all_leader))) {
				$is_leader = True;
			}
			
			$form->fillData($form_fields, array(array('fieldset', 'Leiter')), array('checkbox', $userinfo['first_name'].' '.$userinfo['name'], 'leader[]', $leader['id'], False, $is_leader));
		}
		
		if(!isset($data['submit']) || $form->Validation($form_fields, $data) == false) {
			return $form->GetForm($form_fields, CURRENT_PAGE, "POST", "form ym-columnar");
		}
		
		if(empty($data['leader'])) {
			$form->SetErrorMsg("Es muss mindestens ein Leiter ausgewählt sein!");
			return $form->GetForm($form_fields, CURRENT_PAGE, "POST", "form ym-columnar");
		}
		
		//Save
		if($this->model->SaveGroup($data['name'], $data['description'], $data['youngest'], $data['oldest'], $data['day'], $data['begin'], $data['end'], $data['logo'], $data['leader'], $data['in_overview'], $group_id) == true) {
			return impeesaLayer::SetInfoMsg($_SESSION, "Gruppe ".$data['name']." wurde erfolgreich gespeichert.", LINK_MAIN."groups/");
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Gruppe konnte nicht gespeichert werden!", CURRENT_PAGE, "error");
	}
	
	public function DeleteView($group_id) {
		include_once(PATH_MODEL."groups.php");
		$this->model	= new GroupsModel();
		
		if($this->model->DeleteGroup($group_id) == true) {
			return impeesaLayer::SetInfoMsg($_SESSION, "Gruppe wurde erfolgreich gelöscht.", LINK_ACP."groups");
		}
		return impeesaLayer::SetInfoMsg($_SESSION, "Gruppe konnte nicht gelöscht werden.", LINK_ACP."groups", "error");
	}
	
	public function SidebarView() {
		$menu_array	= array(
				array(
					"page_url"		=> LINK_MAIN."groups/leader",
					"page_title"	=> "Leiterrunde",
				),
			);
		
		$menu_items	= "";
		foreach($menu_array	as $menu_item)
		{
			$this->tpl->vars("page_url",		$menu_item['page_url']);
			$this->tpl->vars("page_title",		$menu_item['page_title']);
			$menu_items		.= $this->tpl->load("_nav_li");
		}
		
		$this->tpl->vars("submenu_items",		$menu_items);
		return $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");
	}
}