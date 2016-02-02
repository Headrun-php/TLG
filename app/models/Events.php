<?php

use Illuminate\Support\Facades\Event;
class Events extends \Eloquent {
	protected $fillable = [];
	protected $table = "events";
	
	public function EventLocations(){
		return $this->belongsTo('EventLocations', 'location_id');
	}
	
	
	public function Customers(){
		return $this->hasMany('Customers', 'source_event');
	}
	
	static function getAllEvents(){
		
		//return Events::with('EventLocations')->get()
		
		return  DB::table('events')
					            
					            ->join('cities', 'events.city', '=', 'cities.ID')
					            ->join('provinces',  'provinces.code',  '=','events.state') 
					           
					            ->where('provinces.country', '=', 'IN')
					            ->select('events.id as eventId', 'events.name as eventName',
					            		'events.area as area',
					            		'cities.name as city',
					            		'provinces.name as state'
					            		
					            )            
								//->groupBy('customers.id')			
					            ->get();
		
	}
	
	
	static function addEvent($inputs){
		
		$event = new Events();
		$event->name              = $inputs['eventName'];
		$event->event_description = $inputs['eventDescription'];
		$event->event_date        = date("Y-m-d", strtotime($inputs['eventDate']));
		$event->area              = $inputs['eventLocation'];
		$event->type              = $inputs['eventType'];
		$event->state             = $inputs['state'];
		$event->city              = $inputs['city'];
		$event->created_by        = Session::get('userId');
		$event->created_at        = date("Y-m-d H:i:s");
		$event->save();
		
		return $event;
		
		
	}
}