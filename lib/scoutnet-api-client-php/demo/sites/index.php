<?php
ini_set('short_open_tag',0);
/*if(ini_get('short_open_tag')){
	die("Diese demo läuft nur wenn short_open_tag in der php config erlaubt wurde")
}*/
ob_start("ob_gzhandler");
header("Content-Type: text/html;charset=UTF-8");

if( isset($_SERVER['HTTP_USER_AGENT']) && ($_SERVER['HTTP_USER_AGENT'] === 'Sphider' || substr_count($_SERVER['HTTP_USER_AGENT'], "icjobs.de")) ){
	header('401 Unauthorized');
	die();
	//die('YOU ARE DISRESPECTING robots.txt. Access Denied!');
}

require "../../src/scoutnet.php";
$sn = scoutnet();
ob_start();
if(isset($_GET['id'])) {
	$group = $sn->group($_GET['id']);
	require 'views/group.tpl.php';
}else{
	?><div class="hero-unit"><?
	require 'views/searchbox.tpl.php';
	if( !empty($_GET['q']) ){
		// geschickt die query erzeugen
		$words = array_filter(mb_split("[^äöüÄÖÜßa-zA-Z0-9\.]+",$_GET['q']));
		$searched_fields = array('name','city','district','zip');
		$query_parts = array();
		$arguments   = array();
		foreach( $words as $word ){
			$word_query_parts = array();
			foreach( $searched_fields as $field ){
				$word_query_parts[] = $field  . ' LIKE ?';
				if( $field == 'zip' ){
					$arguments[] = $word.'%';
				} else {
					$arguments[] = '%'.$word.'%';
				}
			}
			$query_parts[] = implode( " OR ", $word_query_parts );
		}
		$query = '('.implode(") AND (", $query_parts).')';
		$groups = $sn->groups( $query, $arguments );
		require 'views/search.tpl.php';
	}
	?></div><?
}
$body = ob_get_clean();
require 'views/main.tpl.php';
