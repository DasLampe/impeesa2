<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated

abstract class SN_Data_Object_Generated_Url extends SN_Data_Object_Custom implements ScoutNet_Url{
	/**
	 *
	 * @return string
	 */
	function kind(){
		return "url";
	}
	function id(){
		return $this['id'];
	}
	function group_id(){
		return $this['group_id'];
	}
	function url(){
		return $this['url'];
	}
	function text(){
		return $this['text'];
	}
}

