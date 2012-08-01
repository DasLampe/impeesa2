<?php
class SN_API_REST_Client{
	private static $api;
	private static $url;
	private static $helpers;
	static function post( $api, $api_method, $api_method_args, $object_method=null, $object_method_args = array() ){
		die(); // not active yet
		self::$helpers = new SN_API_REST_Helpers( $api );
		$url = SN_API_URL.$api_method.'/';
		if( !is_array($api_method_args) ){
			$url .= $api_method_args.'/';
			if($object_method){
				$url .= $object_method.'/';
				if($object_method_args){
					$data = json_encode($object_method_args);
				}
			}
			
		}else{
			$data = json_encode($api_method_args);
		}
		
		$postdata = http_build_query(
		    array(
		        'json' => json_serialize($data)
		    )
		);
		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);
		$context  = stream_context_create($opts);
		$json = file_get_contents($url, false, $context);
		
		$json = file_get_contents( $url );
		$data = json_decode($json,true);
		if( $data === NULL ){
			throw new Exception(''.stripslashes($url)."\n canot parse JSON:\n".$json);
		}
		self::$api = $api;
		self::$url = $url;
		self::objectify( $data ); // this raises exception in case of error
		return true;
	}
	static function request( $api, $api_method, $api_method_args, $object_method=null, $object_method_args = array() ){
		global $sn_api_version;
		self::$helpers = new SN_API_REST_Helpers( $api );
		$url = SN_API_URL.$sn_api_version.'/'.$api_method.'/';
		if( !is_array($api_method_args) ){
			$url .= $api_method_args.'/';
			if($object_method){
				$url .= $object_method.'/';
				if($object_method_args){
					$url .= '?json='.urlencode(json_encode(self::$helpers->arrayify($object_method_args)));
				}
			}
			
		}else{
			$url .= '?json='.urlencode(json_encode(self::$helpers->arrayify($api_method_args)));
		}
		$json = file_get_contents( $url );
		$data = json_decode($json,true);
		if( $data === NULL ){
			throw new Exception(''.stripslashes($url)."\n canot parse JSON:\n".var_export($json,true));
		}
		self::$api = $api;
		self::$url = $url;
		try{
			return self::$helpers->objectify( $data );
		} catch( SN_Exception_EndUser $e ){
			throw new SN_Exception_EndUser( $e->getMessage() . ' (REST URL: '.$url.' )' );
		}
	}
}
