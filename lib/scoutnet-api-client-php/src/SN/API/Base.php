<?php
abstract class SN_API_Base extends SN_Object implements ScoutNet_API{
	private static $_instances = array();
	protected function __construct(){
		$this->_managers = array();
		foreach( $this->_kinds as $kind ){
			if( $this->isValidKind($kind) ){
				$this->_managers[$kind] = $this->create_manager( $kind );
			} else {
				die( "$kind is not a valid kind" );
			}
		}
	}
	public static function _instance( $class ){
		if( !isset(self::$_instances[$class]) ){
			self::$_instances[$class] = new $class();
		}
		return self::$_instances[$class];
	}
	/**
	 * @return SN_Data_Manager_Base
	 */
	function getManager( $kind ){
		if( isset($this->_managers[$kind]) ){
			return $this->_managers[$kind];
		}
		throw new Exception( "count not find manager for $kind" );
	}
	function isValidKind( $kind ){
		return in_array( $kind, $this->_kinds );
	}
}
