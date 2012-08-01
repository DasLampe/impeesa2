<?php
if(file_exists( $init_file = dirname(__FILE__).'/../../../init.inc.php' )){
	include_once $init_file;
}

defined('SN_URL') || define( 'SN_URL', 'http://www.scoutnet.de/' );
define( 'SN_API_URL', SN_URL.'api/' );

global $sn_api_version;
$version_file = dirname(__FILE__).'/api_version.txt';
if( file_exists($version_file) ){
	$sn_api_version = trim(file_get_contents($version_file));
} else {
	$sn_api_version = 'beta';
}

/**
 * 
 * @access private
 */
function _ScoutNetAPI_autoload( $class ){
	$name_path = explode( '_', $class );
	$base_path = dirname(__FILE__).'/../';
	$hierarchy = explode( "_", $class );
	$files = array(
		$base_path.'src/'.implode('/',$hierarchy).".php",
		$base_path.'src_custom/'.implode('/',$hierarchy).".php",
	);
	foreach( $files as $file ){
		if( file_exists($file) ){
			require_once $file;
			return true;
		}
	}
	return false;
}
spl_autoload_register( '_ScoutNetAPI_autoload' );

require dirname(__FILE__).'/inc/functions.inc.php'; // <- muss nach dem autoloader kommen

global $sn_api_auto_detect;
$sn_api_auto_detect = false;
/**
 * ScoutNet API
 * This returns the ScoutNet API Instance.
 * Usage example:
 * scoutnet()->group(7)->name();
 * 
 * @return ScoutNet_API
 */
function scoutnet(){
	global $sn_api_auto_detect;
	if($sn_api_auto_detect && class_exists('SN_API_Internal')){
		return SN_API_Internal::instance();
	}
	return SN_API_Client::instance();
}

