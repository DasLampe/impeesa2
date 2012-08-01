<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Object extends ArrayObject{
	/**
	 * This effectively turns 'Fatal error: Call to undefined method' into a catchable exception
	 */
	function __call( $name, $args ){
		throw new SN_Exception_UndefinedMethod("Call to undefined method ".get_class($this)."::".$name);
	}
	private $_manager;
	function _manager(){
		return $this->_manager;
	}
	function __construct( $array, $options = array() ){
		parent::__construct($array);
		$this->_manager = $options['manager'];
	}
	function __get( $name ){
		if( $name[0] == '_' ){
			return $this->$name;
		} else {
			return $this->$name();
		}
	}
	function __set( $name, $value ){
		if( $name[0] == '_' ){
			$this->$name = $value;
		} else {
			$this[$name] = $value;
		}
	}
	function __isset( $name ){
		if( $name[0] == '_' ){
			return isset($this->$name);
		} else {
			return isset($this[$name]);
		}		
	}
	function __unset( $name ){
		if( $name[0] == '_' ){
			unset($this->$name);
		} else {
			unset($this[$name]);
		}		
	}
	/**
	 * this is for debug output. do NOT parse this!
	 * @return string
	 */
	function __toString(){
		$array = $this->getArrayCopy();
		foreach( $array as $key => $value ){
			if( is_object($value) ){
				$array[$key] = (string)$value;
			}
		}
		// ucwords leads to wrong class name for ScoutNet_URL
		return (isset($this->kind) ?"ScoutNet_".ucwords($this->kind):"SN_Data_Object").substr( print_r($array,true), 5 );
	}
}
