<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Collection extends SN_Array{
	protected $_error_proxy; // <- nötig für debugging, bitte an erster Stelle lassen
	protected $result_class = "SN_Collection";
	function first(){
		if(!count($this->array)){
			throw new Exception("collection is empty");
		}
		return reset($this->array);
	}
/*	function sort(){
		$args = func_get_args();
		$copy = $this->getArrayCopy();
		array_unshift( $args, $copy );
		sort( $copy );
		$class = get_class( $this );
		return new $class( $copy );
	}*/
/*
//	function exists($index){
//		return $this->offsetExists($index);
//	}
	function get($index){
		return $this->offsetGet($index);
	}
	function set($index, $value){
		$array = $this->array;
		$array[$index] = $value;
		$class = get_class($this);
		return new $class( $array );
	}
	function remove($index){
		$array = $this->array;
		unset($array[$index]);
		$class = get_class($this);
		return new $class( $array );
	}
*/
}