<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
//use tlg;
class EventsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		
		if(Auth::check()){
			
			$currentPage  =  "EVENTS";
			$mainMenu     =  "EVENTS_MAIN";
			
			$inputs = Input::all();
			if(isset($inputs['eventName'])){
				
				$eventsAddResult = Events::addEvent($inputs);
				if($eventsAddResult){
					Session::flash('msg', "Event added successfully.");
					return Redirect::to('events');
				}
			}
			
			$provinces = Provinces::getProvinces ( "IN" );
			$eventTypes = EventTypes::getAllEventTypes();
			
			
			
			
			$events = Events::getAllEvents();
			
			$dataToView = array('currentPage', 'mainMenu','events','provinces','eventTypes');
			
			return View::make('pages.events.events', compact($dataToView));
			
		
		}else{
			return Redirect::to("/");
		}
	}
	
	public function eventTypes()
	{
		if(Auth::check()){
				
			$currentPage  =  "EVENT_TYPES";
			$mainMenu     =  "EVENTS_MAIN";
				
			$inputs = Input::all();
			if(isset($inputs['eventTypeName'])){
	
				$eventsAddResult = EventTypes::addEventType($inputs);
				if($eventsAddResult){
					Session::flash('msg', "Event Type added successfully.");
					return Redirect::to('/events/types');
				}
			}
			
			$eventTypes = EventTypes::getEventTypes();
			$dataToView = array('currentPage', 'mainMenu','eventTypes');
				
			return View::make('pages.events.eventtypes', compact($dataToView));
	
		}else{
			return Redirect::to("/");
		}
	}
	
	public function getEvents(){
		$inputs = Input::all();
		$term = $inputs['term'];
		
		$result = Events::where('name','LIKE','%'.$term.'%')->select('name as label', 'id')->get();
		
		if(isset($result)){
			return Response::json($result);
		}
		return Response::json(array("status"=>"clear"));
	}
	
	public function getEventById(){
		$inputs = Input::all();
		$id = $inputs['eventId'];
	
		$result = Events::find($id);
	
		if(isset($result)){
			return Response::json($result);
		}
		return Response::json(array("status"=>"clear"));
	}
	
	
	
	public function getEventTypeById(){
		$inputs = Input::all();
		$term = $inputs['eventTypeId'];
	
		$result = EventTypes::where('id','=', $term)->get();
	
		if(isset($result)){
			return Response::json($result);
		}
		return Response::json(array("status"=>"clear"));
	}
	
	public function saveEvent(){
		
		$inputs = Input::all();
		
		/* ["_token"]=>
		string(40) "ZUiNHvF83pNBXJMeBNTFVTLTiyDSYC75y6dMiNeN"
				["eventIdEdit"]=>
				string(1) "1"
						["eventNameEdit"]=>
						string(9) "Baratheon"
								["eventDateEdit"]=>
								string(10) "2015-11-10"
										["eventDescriptionEdit"]=>
										string(5) "afsad"
												["eventLocationEdit"]=>
												string(4) "asdf"
														["eventTypeEdit"]=>
														string(1) "1"
																["stateEdit"]=>
																string(2) "17"
																		["cityEdit"]=>
																		string(6) "175345" */
		$event = Events::find($inputs['eventIdEdit']);
		$event->name               = $inputs['eventNameEdit'];
		$event->event_description  = $inputs['eventDescriptionEdit'];
		$event->type               = $inputs['eventTypeEdit'];
		$event->area               = $inputs['eventLocationEdit'];
		$event->state              = $inputs['stateEdit'];
		$event->city               = $inputs['cityEdit'];
		$event->event_date         = $inputs['eventDateEdit'];
		$event->save();
		
		if($event){
			return Response::json(array("status"=>"success"));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	public function saveEventType(){
		
		$inputs = Input::all();
		
		$id = $inputs['eventTypeIdEdit'];
		$eventTypeName = $inputs['eventTypeNameEdit'];
		
		$eventType = EventTypes::find($id);
		//dd($eventType);
		$eventType->name = $eventTypeName;
		$eventType->save();
		
		if($eventType){
			return Response::json(array("status"=>"success"));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	
	public function addIntroVisit(){
		
		$inputs = Input::all();
		$schedule['batchId']           = $inputs['introbatchCbx'];
		$schedule['scheduleDate']      = date('Y-m-d',strtotime($inputs['introVisitTxtBox']));
		$schedule['scheduleType']      = 'introvisit';
		$schedule['customerId']        = $inputs['customerId'];
		$schedule['studentId']         = $inputs['studentIdIntroVisit'];		
		$introVisitAdd = BatchSchedule::addIntrovisit($schedule);
		
		$commentsInput['customerId']    = $inputs['customerId'];
		$commentsInput['commentText']   = $inputs['customerCommentTxtarea'];
		$commentsInput['reminder_type'] = "iv_scheduled";
		$commentsInput['reminderDate']  = date("Y-m-d",strtotime($inputs['introVisitTxtBox']));
		$comments = Comments::addComments($commentsInput);
		
		$customerObject             = Customers::where("id","=",$inputs['customerId'])->get();
		$customer['customerName']   = $customerObject['0']->customer_name;
		$customer['customerEmail']  = $customerObject['0']->customer_email;
		$customer['introVisitDate'] = date('d M Y',strtotime($inputs['introVisitTxtBox']));
		
		Mail::send('emails.account.introvisit', $customer, function($msg) use ($customer){		
			$msg->from(Config::get('constants.EMAIL_ID'), Config::get('constants.EMAIL_NAME'));
			$msg->to($customer['customerEmail'], $customer['customerName'])->subject('The Little Gym - Introductory Visit');
		
		});
		
		if($introVisitAdd){
			return Response::json(array("status"=>"success"));
		}
		return Response::json(array("status"=>"failed"));
	}
	
	
	public function checkSlotAvailableForIntrovisit(){
		$inputs = Input::all();
		$scheduleDate =date('Y-m-d', strtotime($inputs['scheduleDate']));
		$batchId	  = $inputs['batchId'];
		$batchSchedule = BatchSchedule::checkIntroslotAvailable($scheduleDate, $batchId);
		
		if(isset($batchSchedule['0'])){
			return Response::json(array("status"=>"exists"));
		}
		return Response::json(array("status"=>"clear"));
		
	}

	
	
	
	
	
	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
