<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Object{
	function __get( $name ){
		trigger_error( "object of class '".get_class($this)."' has no attribute '$name'", E_USER_WARNING );
	}
	/**
	 * This effectively turns 'Fatal error: Call to undefined method' into a catchable exception
	 * (Note: Is this needed? Or is this a Catchable Fatal Error? Maybe only in newer PHP versions?)
	 */
	function __call( $name, $args ){
		trigger_error( "object of class '".get_class($this)."' has no method '$name'", E_USER_WARNING );
	}
}
