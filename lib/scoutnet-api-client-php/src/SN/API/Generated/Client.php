<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated


abstract class SN_API_Generated_Client extends SN_API_Base{
	protected $_kinds = array('group','event','section','url',);
	/**
	 * find a group given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Group 
	 */
	function group( $id_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'group', $args );
	}
	/**
	 * returns the collection of groups matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Group 
	 */
	function groups( $ids_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'groups', $args );
	}
	/**
	 * find a event given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Event 
	 */
	function event( $id_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'event', $args );
	}
	/**
	 * returns the collection of events matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Event 
	 */
	function events( $ids_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'events', $args );
	}
	/**
	 * find a section given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Section 
	 */
	function section( $id_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'section', $args );
	}
	/**
	 * returns the collection of sections matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Section 
	 */
	function sections( $ids_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'sections', $args );
	}
	/**
	 * find a url given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Url 
	 */
	function url( $id_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'url', $args );
	}
	/**
	 * returns the collection of urls matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Url 
	 */
	function urls( $ids_or_query, $args = array() ){
		$args = func_get_args();
		return SN_API_REST_Client::request( $this, 'urls', $args );
	}
}
