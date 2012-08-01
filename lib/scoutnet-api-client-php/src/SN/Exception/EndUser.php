<?php
class SN_Exception_EndUser extends ScoutNet_Exception{
	function __construct( $message = "", $data = array() ){
		parent::__construct( $message );
		$this->data = $data;
	}
}
