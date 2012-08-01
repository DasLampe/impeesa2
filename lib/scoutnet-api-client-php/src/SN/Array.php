<?php
/* used as a universally unique identifier within SN_Array */
define( "SN_UNIQUE_TOKEN", "Oo5nIZ3XTtPa CjI1rBUMfZSJzrBQa_1xv-EkagrOdLtygVupPAPyYamSu ilAaQd4Vp-vMjU5tDs1EUNr8Zbm8r8dz QQxxEHvDOM3yDFTtyZcUYX-8f  fKR2cxKHHhhy1dSOYq23Y5sdXK8Si5NYkCota0o060E1WJmeaS80ndhFHeUf0sfLNU7zW K4F-AT DO6ilNkD7hQ0wUdU09L1oJ8BRADBuYq07MRtudhcK5x-JvucoGUiaBZ5J0o" );
if( !defined('DISABLE_SHORT_SN_ARRAY_PLACEHOLDER_TOKEN') ){
	define( "_", SN_UNIQUE_TOKEN );
}
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Array extends SN_Object implements ArrayAccess, Countable, IteratorAggregate{
	protected $_error_proxy;
	protected $php;
	protected $array;
	protected $result_class = "SN_Array";
	function __construct( $array = array() ){
		assert(is_array($array));
		$this->_error_proxy = new _SN_Array_Error_Proxy("Do no use reset(...), current(...), etc. on SN_Collection_*. Use e.g. ->first() instead.");
		$this->array = $array;
		$this->php = new _SN_Array_PHPFunction_Proxy( $this, $this->result_class );
	}
	function contains( $item ){
		return in_array( $item, $this->array );
	}
	function implode( $separator ){
		return $this->php->implode( $separator, _ );
	}
	function map( $callback ){
		return $this->php->array_map( $callback, _ );
	}
	function filter( $callback ){
		return $this->php->array_filter( _, $callback );
	}
	function reduce( $function, $initial = NULL ){
		$array = $this->array;
		assert( count($array) >= 2 );
		$result = array_reduce( $array, $function, $initial );
		if( is_array($result) ){
			return new SN_Data_Collection_Custom($result);
		}
		return $result;
	}
	private function _flatten_helper( $a, $b ){
		if( $a === NULL || $a === 0 ){ // 0 is a workaround for different array_reduce behavior
			return $b;
		}
		if( is_object($a) && is_object($b) && (get_class($a) == get_class($b)) ){
			$coll_class = get_class($a);
		} else {
			$coll_class = "SN_Data_Collection";
		}
		if( is_object($a) ){
			assert( is_a($a,'SN_Array') || is_a($a,'ArrayObject') );
			$a_array = $a->getArrayCopy();
		} else{
			if( !is_array($a) ){
				trigger_error( "Expected SN_Array, ArrayObject or array, got ".var_export($a,true), E_USER_WARNING );
			}
			$a_array = $a;
		}
		if( is_object($b) ){
			assert( is_a($b,'SN_Array') || is_a($b,'ArrayObject') );
			$b_array = $b->getArrayCopy();
		} else{
			if( !is_array($b) ){
				trigger_error( "Expected SN_Array, ArrayObject or array, got ".var_export($b,true), E_USER_WARNING );
			}
			$b_array = $b;
		}
		return new $coll_class( array_merge($a_array,$b_array) );
	}
	/**
	 * Turns an SN_Array of SN_Arrays into a flat SN_Array, e.g. (new SN_Array( new SN_Array(1,2), new SN_Array(3) ))->flatten()->getArrayCopy() == array( 1,2,3 )
	 */
	function flatten(){
		return $this->reduce( array($this,'_flatten_helper') );
	}
	
//	function copy(){
//		$new = clone $this;
//		$new->array_object = clone $this->array_object;
//		return $new;
//	}
//	function exchangeArray ($array) {
//		$new = $this->copy();
//		$new->array_object->exchangeArray($array);
//		return $new;
//	}

	function to( $class ){
		return new $class( $this->array );
	}
	
	function toArray      () { return $this->array; }
	function getArrayCopy () { return $this->array; }
	private function _getArrayCopyDeep( $obj ){
		if(method_exists($obj,'getArrayCopyDeep')){
			return $obj->getArrayCopyDeep();
		} elseif( is_array($obj) ){
			return array_map(array($this,'_getArrayCopyDeep'),$array);
		}
		return $obj;
	}

	function getArrayCopyDeep(){ return array_map(array($this,'_getArrayCopyDeep'),$this->array); }
	
	// Countable interface
	function count ()        { return count($this->array); }

	// IteratorAggregate interface
	function getIterator ()  { return new ArrayIterator($this->array); }
	
	// ArrayAccess interface
	function offsetExists ($index) {
		return array_key_exists( $index, $this->array );
	}
	function offsetGet ($index){
		return $this->array[$index];
	}
	function offsetSet ($index, $newval){
		if( is_null($index) ){
			$this->array[] = $newval;
		} else {
			$this->array[$index] = $newval;
		}
	}
	function offsetUnset($index){
		unset($this->array[$index]);
	}

/*	// Iterator interface
    public function rewind() {
        return reset($this->array);
    }
    public function current() {
        return current($this->array);
    }
    public function key() {
        return key($this->array);
    }
    public function next() {
        return next($this->array);
    }
    public function valid() {
        return key($this->array) !== NULL;
    }*/
    function __toString(){
    	return $this->result_class.substr( print_r( $this->array, true ), 5 );
    }
}
class _SN_Array_PHPFunction_Proxy{
	function __construct( $collection, $result_class ){
		$this->_collection = $collection;
		$this->result_class = $result_class;
	}
	function __call( $name, $args ){
		if( !is_callable($name) ){
			throw new Exception("not a valid callback: ".var_export($name, true) );
		}
		if( in_array(_, $args) ){
			$args[ array_search(_, $args) ] = $this->_collection->getArrayCopy();
			$result = sn_call_user_func_array( $name, $args );
			if( is_array($result) ){
				$result_class = $this->result_class;
				return new $result_class($result);
			} else {
				return $result;
			}
		}
		throw new Exception("undefined method "+$name);
	}
}
/**
 * catches uses of reset( ... ), etc. on an object when being the first field of the object.
 * @see https://bugs.php.net/bug.php?id=38478
 * @see https://bugs.php.net/bug.php?id=49369
 */
class _SN_Array_Error_Proxy{
	function __construct($msg){
		$this->msg = $msg;
	}
	function __toString(){
		throw new Exception($this->msg);
	}
	function __call($a,$b){
		throw new Exception($this->msg);
	}
	function __invoke($a,$b){
		throw new Exception($this->msg);
	}
	function __get($a){
		throw new Exception($this->msg);
	}
	function offsetGet($a){
		throw new Exception($this->msg);
	}
}
