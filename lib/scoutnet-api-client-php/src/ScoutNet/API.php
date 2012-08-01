<?php
# DO NOT CHANGE THIS FILE
# This file has been auto-generated


interface ScoutNet_API{
	/**
	 * find a group given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Group 
	 */
	function group( $id_or_query, $args = array() );
	/**
	 * returns the collection of groups matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Group 
	 */
	function groups( $ids_or_query, $args = array() );
	/**
	 * find a event given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Event 
	 */
	function event( $id_or_query, $args = array() );
	/**
	 * returns the collection of events matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Event 
	 */
	function events( $ids_or_query, $args = array() );
	/**
	 * find a section given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Section 
	 */
	function section( $id_or_query, $args = array() );
	/**
	 * returns the collection of sections matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Section 
	 */
	function sections( $ids_or_query, $args = array() );
	/**
	 * find a url given its id or a query
	 * @param $id_or_query int|string the id or query
	 * @param $args [array] optional query parameters
	 * @return ScoutNet_Url 
	 */
	function url( $id_or_query, $args = array() );
	/**
	 * returns the collection of urls matching the given ids or query
	 * @param $ids_or_query mixed either an array of int ids or a PfadiQL query string
	 * @param $args array arguments to fill the placeholders
	 * @return ScoutNet_Collection_Url 
	 */
	function urls( $ids_or_query, $args = array() );
}
