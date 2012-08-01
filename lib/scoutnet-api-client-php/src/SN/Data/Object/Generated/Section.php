<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated

abstract class SN_Data_Object_Generated_Section extends SN_Data_Object_Custom implements ScoutNet_Section{
	/**
	 *
	 * @return string
	 */
	function kind(){
		return "section";
	}
	function id(){
		return $this['id'];
	}
	function name(){
		return $this['name'];
	}
	function start_age(){
		return $this['start_age'];
	}
	function end_age(){
		return $this['end_age'];
	}
	function color(){
		return $this['color'];
	}
}

