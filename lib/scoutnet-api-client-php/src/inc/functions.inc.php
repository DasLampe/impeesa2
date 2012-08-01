<?php
function sn_call_user_func_array( $name, $args ){
	if( !is_callable($name) ){
		throw new Exception("Unexpected Argument: ".var_export($name, true) );
	}
	if( is_array($name) ){
		list( $object, $method ) = $name;
		$rclass = new ReflectionClass( $object );
		$rmethod = $rclass->getMethod( $method );
		if( is_string($object) ){
			//static method
			return $rmethod->invokeArgs( null, $args );
		} else {
			return $rmethod->invokeArgs( $object, $args );
		}
	}
	$func = new ReflectionFunction( $name );
	return $func->invokeArgs( $args );
}

if(!function_exists("json_encode")) {
    function json_encode($content){
        $json = new Services_JSON;
        return $json->encode($content);
    }
}

if(!function_exists('json_decode')) {
    function json_decode($content, $assoc=false){
        if ( $assoc ){
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
            $json = new Services_JSON;
        }
        return $json->decode($content);
    }
} 