<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated

abstract class SN_Data_Object_Generated_Event extends SN_Data_Object_Custom implements ScoutNet_Event{
	/**
	 *
	 * @return string
	 */
	function kind(){
		return "event";
	}
	function id(){
		return $this['id'];
	}
	function uid(){
		return $this['uid'];
	}
	function group_id(){
		return $this['group_id'];
	}
	function title(){
		return $this['title'];
	}
	function organizer(){
		return $this['organizer'];
	}
	function target_group(){
		return $this['target_group'];
	}
	function start_date(){
		return $this['start_date'];
	}
	function start_time(){
		return $this['start_time'];
	}
	function end_date(){
		return $this['end_date'];
	}
	function end_time(){
		return $this['end_time'];
	}
	function zip(){
		return $this['zip'];
	}
	function location(){
		return $this['location'];
	}
	function url_text(){
		return $this['url_text'];
	}
	function url(){
		return $this['url'];
	}
	function description(){
		return $this['description'];
	}
	function last_modified_by(){
		return $this['last_modified_by'];
	}
	function last_modified_on(){
		return $this['last_modified_on'];
	}
	function keywords(){
		return $this['keywords'];
	}
}

