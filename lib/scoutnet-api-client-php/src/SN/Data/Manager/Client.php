<?php
class SN_Data_Manager_Client extends SN_Object{
	private $kind;
	private $_api;
	public function __construct( $api, $kind ){
		$this->_api = $api;
		$this->kind = $kind;
	}
	function __call( $method, $args ){
		if( !$args ){
			throw new SN_Exception( "please provide arguments for method ScoutNet_Manager_$this->kind::$method" );
		}
		if( $id = $this->get_id($args[0]) ){
			unset( $args[0] );
		}
		if( !$id && is_numeric($args[0]) ){
			$id = $args[0];
			unset( $args[0] );
		}
		#print "CALLED WEBSERVICE by method: $method(".implode(",",$this->encode_args($args)).") \n";
		return SN_API_REST_Client::request( $this->_api, $this->kind, $id, $method, $this->encode_args($args) );
		#print "CALLED WEBSERVICE via url: $this->url \n\n";
	}
	private function get_id( $object ){
		if( is_a($object,'SN_Data_Object') ){
			if( isset($object['global_id']) ){
				return $object['global_id'];
			}else if( isset($object['id']) ){
				return $object['id'];
			}
		}
	}
	private function encode_args( $args ){
		$encoded_args = array();
		foreach( $args as $a ){
			if( is_a($a,'SN_Data_Object') ){
				$data = $a->getArrayCopy();
				$data['kind'] = $a->kind;
				$encoded_args[] = 'Kind:'.$data['kind'].'('.$this->get_id($a).')';
			} elseif( is_string($a) || is_numeric($a) ){
				$encoded_args[] = $a;
			} elseif( is_null($a) ){
			} else {
				$encoded_args[] = $a;
			}
		}
		return $encoded_args;
	}
}
