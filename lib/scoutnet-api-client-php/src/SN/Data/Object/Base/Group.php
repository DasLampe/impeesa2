<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Object_Base_Group extends SN_Data_Object_Generated_Group{
	/**
	 * 
	 * @return ScoutNet_Group
	 */
	function parent( $layer = null ){
		return $this->_manager()->parent( $this, $layer );
	}
	function parents( $options = array() ){
		return $this->_manager()->parents( $this, $options );
	}
	function children(){
		return $this->_manager()->children( $this );
	}
/*	function _national_association(){
		return $this->parent( 'national_association' );
	}*/
	function sections(){
		return $this->_manager()->sections($this);
	}
	function events( $query = NULL, $args = array() ){
		return $this->_manager()->events( $this, $query, $args );
	}
	function urls( $query = NULL, $args = array() ){
		return $this->_manager()->urls( $this, $query, $args );
	}
/*	function _layer_name(){
		switch($this->layer()){
			case 'wosm':
				return National_Association
			default:
				return parent::name();
		}
	}
	function _short_name(){
		switch($this->layer()){
			case 'wagggs':
			case 'wosm':
			case 'isgf':
			case 'wagggs_region':
			case 'wosm_region':
			case 'isgf_region':
			case 'wagggs_national_federation':
			case 'wosm_national_federation':
			case 'isgf_national_federation':
			case 'national_association':
				return $this->${$this->layer()}();
			default:
				return parent::name();
		}
	}
	function _long_name(){
		switch($this->layer()){
			case 'wagggs':
			case 'wosm':
			case 'isgf':
			case 'wagggs_region':
			case 'wosm_region':
			case 'isgf_region':
			case 'wagggs_national_federation':
			case 'wosm_national_federation':
			case 'isgf_national_federation':
			case 'national_association':
				return parent::name();
			default:
				return parent::name().", ".parent::city()."-".parent::district();
		}
	}*/
}