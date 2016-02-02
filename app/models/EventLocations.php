<?php

class EventLocations extends \Eloquent {
	protected $fillable = [];
	protected $table = "event_locations";
	
	
	public function Events(){
		return $this->hasMany('EventLocations', 'location_id');
		
	}
	
	static  public function getAllEventLocations(){
		
	}
	
}