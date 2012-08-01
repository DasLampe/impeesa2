<?php
class SN_API_Client extends SN_API_Generated_Client{
	function create_manager( $kind ){
		return new SN_Data_Manager_Client( $this, $kind );
	}
	/**
	 *
	 * @return ScoutNet_API
	 */
	static function instance(){
		return self::_instance( __CLASS__ );
	}
}
