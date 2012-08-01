<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Collection extends SN_Collection{
	protected $_error_proxy; // <- nötig für debugging, bitte an erster Stelle lassen
	protected $result_class = "SN_Data_Collection_Custom";
	/**
	 * 
	 * @deprecated Bitte stattdessen select_one( $attribut_name ) verwenden.
	 */
	function field( $name ){
		$result_class = $this->result_class;
		$field = new $result_class();
		foreach( $this as $element ){
			$field[] = $element->$name;
		}
		return $field;
	}
	/**
	 * Erzeugt aus einer Liste von Datenobjekte eine Liste mit den Werten des angegeben Attributs jedes Objekts
	 * @param $name
	 */
	function select_one( $attribut ){
		$result_class = $this->result_class;
		$field = new SN_Data_Collection_Custom();
		foreach( $this as $element ){
			$field[] = $element->$attribut;
		}
		return $field;
	}
	/**
	 * Erzeugt aus einer Liste von Datenobjekte eine Liste mit den Datenobjekten mit den angegebenen Attributen der Ausgangsobjekte (wie SQL SELECT)
	 * @varargs
	 * @param $attribute
	 */
	function select( $attributes ){
		if( is_array($attributes) ){
			$names = $attributes;
		} elseif(is_object($attributes) && method_exists($attributes,'getArrayCopy')){
			$names = $attributes->getArrayCopy();
		} else {
			$names = func_get_args();
		}
		$result_class = $this->result_class;
		$result = new SN_Data_Collection_Custom();
		foreach( $this as $element ){
			$row = new SN_Data_Object( array(), NULL );
			foreach( $names as $name ){
				$row[$name] = $element->$name;
			}
			$result[] = $row;
		}
		return $result;
	}
}