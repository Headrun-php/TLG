<?php

class EventTypes extends \Eloquent {
	protected $fillable = [];
	protected $table = "event_types";
	
	
	static function getAllEventTypes(){
		
		return  EventTypes::lists('name','id');
	}
	
	static function getEventTypes(){
	
		return  EventTypes::all();
	}
	
	static function addEventType($inputs){
		
		$eventType = new EventTypes();
		$eventType->name           = $inputs['eventTypeName'];
		$eventType->created_by     = Session::get('userId');
		$eventType->created_at     = date("Y-m-d H:i:s");
		$eventType->save();
		return $eventType;
	}
}