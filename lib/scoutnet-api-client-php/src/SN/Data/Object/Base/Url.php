<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Object_Base_Url extends SN_Data_Object_Generated_Url{
	function group(){
		return $this->_manager()->group( $this );
	}
}