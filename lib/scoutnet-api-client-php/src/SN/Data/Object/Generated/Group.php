<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated

abstract class SN_Data_Object_Generated_Group extends SN_Data_Object_Custom implements ScoutNet_Group{
	/**
	 *
	 * @return string
	 */
	function kind(){
		return "group";
	}
	/**
	 *
	 * @return int
	 */
	function global_id(){
		return $this['global_id'];
	}
	function name(){
		return $this['name'];
	}
	function zip(){
		return $this['zip'];
	}
	function city(){
		return $this['city'];
	}
	function district(){
		return $this['district'];
	}
	function internal_id(){
		return $this['internal_id'];
	}
	function layer(){
		return $this['layer'];
	}
	function federal_state(){
		return $this['federal_state'];
	}
	function country(){
		return $this['country'];
	}
}

