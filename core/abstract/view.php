<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
abstract class AbstractView {
	protected $tpl;
	protected $model;
	
	public function __construct() {
		$this->tpl	= impeesaTemplate::getInstance();
	}
	
	abstract public function MainView();
}