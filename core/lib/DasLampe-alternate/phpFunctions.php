<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
/**
 * Add useful PHP functions wich blocked or extensions not loaded.
 * E.g. json_encode
 */

if(!function_exists("json_encode"))
{
	function json_encode(array $array){
		require_once dirname(__FILE__).'/lib/PEAR/JSON.php';
		$json = new Services_JSON;

		return $json->encode($array);
	}
}

if(!function_exists('json_decode'))
{
	function json_decode($content, $assoc=false){
		require_once dirname(__FILE__).'/lib/PEAR/JSON.php';
		if ( $assoc ){
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} else {
			$json = new Services_JSON;
		}
		return $json->decode($content);
	}
}
