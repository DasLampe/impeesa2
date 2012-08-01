<?php
# Note to ScoutNet team:
# All public methods in this class are exposed via the webservice
# and therefore publicly available over the Internet.
class SN_Data_Collection_Base_Event extends SN_Data_Collection_Generated_Event{
	protected $_error_proxy; // <- nötig für debugging, bitte an erster Stelle lassen
	function Group_By_Start_Date_Time( $format ){
		$result = new ArrayObject();
		foreach( $this as $event ){
			$key = strftime( $format, strtotime($event->start_date." ".$event->start_time) );
			if( !isset($result[$key]) ){
				$class = get_class($this);
				$result[$key] = new $class();
			}
			$result[$key][] = $event;
		}
		return $result;
	}
}
