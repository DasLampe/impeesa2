<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated

interface ScoutNet_Group extends ScoutNet_Kind{
	/**
	 *
	 * @return string
	 */
	function kind();
	/**
	 *
	 * @return int
	 */
	function global_id();
	function name();
	function zip();
	function city();
	function district();
	function internal_id();
	function layer();
	function federal_state();
	function country();
	/**
	 * 
	 * @return ScoutNet_Group
	 */
	function parent( $layer = null );
	function parents( $options = array() );
	function children();
	function sections();
	function events( $query = NULL, $args = array() );
	function urls( $query = NULL, $args = array() );
}
