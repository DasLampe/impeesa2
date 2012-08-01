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
		
		$events		= "";
		foreach($this->model->GetAllEvents() as $event)
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
		$this->tpl->vars("content",		$events);
		return $this->tpl->load("content");
	}
}