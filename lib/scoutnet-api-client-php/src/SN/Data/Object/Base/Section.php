<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Object_Base_Section extends SN_Data_Object_Generated_Section{
	function __toString(){
		return $this->name;
	}
}