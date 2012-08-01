<?php
class SN_API_REST_Helpers{
	function __construct( $api ){
		$this->api = $api;
	}
	function objectify( $data ){
		if( is_array($data) && array_key_exists("kind",$data) ){
			if($data['kind'] === 'exception'){
				throw new SN_Exception_EndUser( $data['message'] );
			}
			if( substr( $data['kind'], -strlen('collection') ) == 'collection' ){
				if(!empty($data['element_kind'])){
					$coll_class = "SN_Data_Collection_Custom_".ucwords($data['element_kind']);
				}else{
					$coll_class = "SN_Data_Collection";
				}
				assert( class_exists($coll_class) );
				return new $coll_class( array_map( array($this,'objectify'), $data['elements'] ) ); 
			}
			$class = 'SN_Data_Object_Custom_'.ucwords($data['kind']);
			assert( class_exists($class) );
			return new $class(
				array_map( array($this,'objectify'), $data ),
				array('manager'=>$this->api->getManager($data['kind']))
			);
		}elseif(is_array($data)) {
		        return array_map( array($this,'objectify'), $data );
	    }
		return $data;
	}
	function json_serialize($result){
		if( !is_object($result) && !is_array($result) ){
			if( $_SERVER['SERVER_NAME'] == 'localhost'){
				echo "Fehler: Derzeit können nur ganze Objekte abgefragt werden, keine Attribute!";
				echo "<pre>\n\n";
				var_dump($result);
				echo "\n\n";
				debug_print_backtrace();
				echo "\n\n</pre>";
				
			}
			throw new SN_Exception_EndUser( "Derzeit können nur ganze Objekte abgefragt werden, keine Attribute!" );
			die();
		}
		$json = json_encode ( $this->arrayify($result) );
		if(array_key_exists('beautify',$_GET)){
			$json = json_indent($json);
		}
		return $json;
	}

	function arrayify( $data ){
		if( !is_array($data) && !is_object($data) ){
			return $data;
		}
		$data_ = $data;
		if( $data instanceof SN_Data_Collection ){ // || is_array($result) ){ # last operand checks for 2d array
			$data_ = array('kind' => 'collection');
			if( !empty($data->element_kind) ){
				$data_['element_kind'] = $data->element_kind;
			}		
			$data_['elements'] = $data->getArrayCopy();
		}elseif( $data instanceof SN_Data_Object ){
			$data_ = $data->getArrayCopy();
		}
		$returnme = array();
		foreach( $data_ as $k=>$v ){
			$returnme[$k] = self::arrayify( $v );
		}
		return $returnme;
	}
	
	function json_dserialize( $args ){
		if( get_magic_quotes_gpc() ){
			$args = stripslashes($args);
		}
		$nojson = json_decode($args,true);
		if ( is_null($nojson) ){
			throw new SN_Exception_EndUser("Kein gültiges JSON in der URL: ".$args );
		}
		return $this->objectify( $nojson );
	}

	function decode_args( $exploded ){
		$returnme = array();
		foreach( $exploded as $arg ){
			$matches = array();
			if( preg_match('/^Kind:([a-zA-Z]*)\([0-9]*\)$/', (string)$arg, $matches) ) {
				$returnme[] = $this->api->getManager($matches[1])->findById($matches[2]);
			} else {
				$returnme[] = $arg;
			}
		}
		return $returnme;
	}
}
