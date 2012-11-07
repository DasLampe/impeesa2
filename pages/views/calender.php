<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class CalenderView extends AbstractView
{
	public function MainView()
	{
		include_once(PATH_MODEL."calender.php");
		$this->model	= new CalenderModel();
		
		return $this->ShowCalender($this->model);
	}
	
	public function SpecificView($id)
	{
		try
		{
			include_once(PATH_MODEL."calender.php");
			$this->model	= new CalenderModel();
			$this->model->SetScoutNetId($id);
		
			return $this->ShowCalender($this->model);
		}
		catch(Exception $e)
		{
			throw new impeesaException("No group with ID ".$id);
		}
	}
	
	private function ShowCalender($model)
	{
		$events		= "";
		foreach($model->GetAllEvents() as $event)
		{
			$this->tpl->vars("title",			$event->title);
			$this->tpl->vars("groups",			implode(", ", $this->model->FilterGroupFromKeywords($event->keywords->implode("--.--"))));
			$this->tpl->vars("categorie",		implode(", ", $this->model->FilterCategorieFromKeywords($event->keywords->implode("--.--"))));
			$this->tpl->vars("start_date",		$this->model->CreateReadableDateTime($event->start_date, $event->start_time));
			$this->tpl->vars("end_date",		$this->model->CreateReadableDateTime($event->end_date, $event->end_time));
			$this->tpl->vars("description",	$event->description);
			$events	.= $this->tpl->load("_event", PATH_PAGES_TPL."calender/");
		}
		
		$this->tpl->vars("headline",	"Termine");
		$this->tpl->vars("content",		"<h2>".$model->GetGroupName()."</h2>".$events);
		return $this->tpl->load("content");
	}
	
	public function SidebarView()
	{
		include_once(PATH_MODEL."calender.php");
		$this->model	= new CalenderModel();
		$submenu		= "";
		
		//All children
		if(count($this->model->GetAllChildren()) > 0)
		{
			$submenu		.= "<h4>Mitglieder dieser Gruppe</h4>";
			$submenu_item	= "";
			foreach($this->model->GetAllChildren() as $child)
			{
				$this->tpl->vars("page_url",	LINK_MAIN."calender/".$child->global_id);
				$this->tpl->vars("page_title",	$child->name());
				$submenu_item	.= $this->tpl->load("_nav_li");
			}
			$this->tpl->vars("submenu_items",		$submenu_item);
			$submenu	.= $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");
		}
	
		//Parents
		$submenu		.= "<h4>Übergeordnete Gruppen</h4>";
		$submenu_item	= ""; //Clear
		foreach($this->model->GetAllParents() as $parent)
		{
			$this->tpl->vars("page_url",	LINK_MAIN."calender/".$parent->global_id);
			$this->tpl->vars("page_title",	$parent->name());
			$submenu_item	.= $this->tpl->load("_nav_li");
		}
		$this->tpl->vars("submenu_items",		$submenu_item);
		$submenu	.= $this->tpl->load("submenu", PATH_PAGES_TPL."sidebar/");
		
		$this->tpl->vars("content",		'<p class="bordered dimmed">Daten via ScoutNet API. :)<br/><a href="http://www.scoutnet.de">www.scoutnet.de</a></p>');
		$info		= $this->tpl->load("_content");
		
		return $submenu.$info;
	}
}