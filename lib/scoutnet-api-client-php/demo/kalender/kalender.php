<?php
/**
 * This is a demonstration on using the ScoutNet Webservice in PHP
 * @author Christopher Vogt, chris@scoutnet.de
 */
header( "Content-Type: text/html; charset=UTF-8" );
setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');
require "../../ScoutNet.php";
if( !isset($_GET['id']) ){
	die("Bitte id in der URL angeben");
}
$sn = scoutnet();
$group = $sn->group()->get($_GET['id']);
function h($str){
	return htmlentities( $str, ENT_COMPAT, 'UTF-8' );
}
$parents = $group->parents(array('inclusive'=>true));
if( isset($_GET['upto']) ){
	foreach( $parents as $p ){
		$parents_ids[] = $p->global_id;
		if( $p->layer == $_GET['upto'] ){
			break;
		}
	}
	$events = $sn->event()->find(
		array("group",implode(",",$parents_ids)),
		array("Start_Date >=", date("Y-m-d") )
	);
} else {
	$events = $group->events();
}
require 'ScoutNet.tpl.php';