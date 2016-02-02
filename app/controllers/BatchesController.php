<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
//use tlg;
class BatchesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		
		if(Auth::check()){
			
			$currentPage  =  "CLASSES";
			$mainMenu     =  "COURSES_MAIN";
			
			$inputs = Input::all();
			if(isset($inputs['startTime'])){
				
				$startDate = date('Y-m-d', strtotime($inputs['startDate']));
				$courseId  = $inputs['franchiseeCourse'];
				$classId   = $inputs['className'];
				$startTime = $inputs['startTime'];
				$endTime   = $inputs['endTime'];
				$leadInstructor   = $inputs['leadInstructor'];
				$alternateInstructor   = $inputs['alternateInstructor'];
				
				
				$day       = date('N', strtotime($inputs['startDate']));
				
				
				
				if(date('m', strtotime($inputs['startDate'])) >= 1 && date('m', strtotime($inputs['startDate'])) <= 1){
					
					$endDateYear = date('Y', strtotime($inputs['startDate']));
				}else{
					$endDateYear = date('Y', strtotime($inputs['startDate']));
					$endDateYear = ($endDateYear+1);
				}
				
				//echo $startDate.'  =   '.$endDateYear.'-03-31';
				/* exit(); */
				$months = getMonthsBetweenDates($startDate, $endDateYear.'-03-31');
				
				
				/* echo "<pre>";
				print_r($months);
				echo "</pre>";
				
				exit(); */
				
				
				$i = 0;
				foreach($months as $month){
						
					$yearAndMonth = explode('-', $month['month']);
					$daysFound[$i] = getDaysFromMonth($yearAndMonth['1'], $yearAndMonth['0'], $day);
					$i++;
				}
				
				
				
				$timeString = date('Y-m-d', strtotime($inputs['startDate'])).$inputs['startTime'];
				$timestamp =  strtotime($timeString);
				$startTime24Hours = date('H:i:s',$timestamp);
					
				$timeString = $endDateYear.'-3-5 '.$inputs['endTime'];
				$timestamp =  strtotime($timeString);
				
				$endTime24Hours = date('H:i:s',$timestamp);
				
				
				
				$batchSlug =  Courses::getBatchID($courseId, $classId, $startDate, null);
				$inputBatch['batchName']     = $batchSlug;
				$inputBatch['classId']       = $classId;
				$inputBatch['courseId']      = $courseId;
				$inputBatch['startDate']     = $startDate;
				$inputBatch['preferredTime'] = $startTime24Hours;
				$inputBatch['preferredEndTime'] = $endTime24Hours;
				$inputBatch['leadInstructor'] = $leadInstructor;
				$inputBatch['alternateInstructor'] = $alternateInstructor;
				
				
				$newBatch = Batches::addBatches($inputBatch);
				
				$days = 1;
				foreach($daysFound as $monthdays){
					
					foreach($monthdays as $dayFound){
						
						if($days <= 40){
						$batchScheduleInput['batchId']      = $newBatch->id;
						$batchScheduleInput['scheduleDate'] = $dayFound;
						$batchScheduleInput['startTime']    = $startTime24Hours;
						$batchScheduleInput['endTime']      = $endTime24Hours;
						$batchScheduleInput['scheduleType']= 'class';
						BatchSchedule::addSchedule($batchScheduleInput);
						}
						
						$days++;
					}
				}
				Session::flash('msg', "Batch added successfully.");
				return Redirect::to('batches');
			}
			$franchiseeId = Session::get('franchiseId');
			$batches = Batches::getAllBatchesByFranchiseeId($franchiseeId);
			$courseList = Courses::getFranchiseCoursesList($franchiseeId);
			$franchiseeCourses = Courses::getFranchiseCoursesList(Session::get('franchiseId'));
			$Instructors = User::getInstructors();
			
			$dataToView = array('batches','courseList','currentPage', 'mainMenu','franchiseeCourses','mondays','Instructors');
			
			return View::make('pages.batches.batchesfinal', compact($dataToView));
			
		
		}else{
			return Redirect::to("/");
		}
	}

	
	
	
	public function view($id){
		
		if(Auth::check()){
			
			$currentPage  =  "BATCHES";
			$mainMenu     =  "COURSES_MAIN";
			$batchSchedules = BatchSchedule::getBatcheSchedulesbyCourseandClassID($id);
			$batchSchedules = json_encode($batchSchedules['event']);
			$dataToView = array('currentPage', 'mainMenu', 'batchSchedules');
			return View::make('pages.batches.batchview', compact($dataToView));
		
		}else{
			return Redirect::to("/");
		}		
	}
	
	
	public function getBatchesSchedules(){
		
		$inputs    = Input::all();
		$batchId   = $inputs['batchId'];
		$startDate = date('Y-m-d', strtotime($inputs['enrollmentStartDate']));
		$endDate   = date('Y-m-d', strtotime($inputs['enrollmentEndDate']));
		
		$countofSessions = BatchSchedule::getScheduleCountForBatch($batchId, $startDate, $endDate);
		$amountTotal     = ($countofSessions*500);
		
		
		
		
		
		if($countofSessions){
				
			return Response::json(array("status"=>"success",
					"availableSession"=>$countofSessions,
					"amountTotal"=>$amountTotal
						
			));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	public function checkslots(){
		
		$inputs = Input::all();
		$datetime     = $inputs['datetime'];
		$date = date('Y-m-d H:i:s', strtotime($datetime));
		$dateSplit = explode(" ", $date);
		
		$splitDate = $dateSplit['0'];
		$splitTime = $dateSplit['1'];
		
		$batchSchedule = BatchSchedule::where("schedule_date", "=", $splitDate)
		->where("start_time", "=",$splitTime)
		->count();
		//->get();
		
		if(!$batchSchedule){
			return Response::json(array("status"=>"success"));
		}
		return Response::json(array("status"=>"failed"));
	}
	
	
	public function attendance($id){
		
		if(Auth::check()){
				
			$currentPage  =  "BATCHES";
			$mainMenu     =  "COURSES_MAIN";
			
			$studentsInBatch = StudentClasses::with('Students')->where('batch_id', '=', $id)->count();
			$batch  =  Batches::where('id', '=', $id)->first();
			$attendanceArray = BatchSchedule::getAttendanceTable($id);
			
			$lead = Batches::with('LeadInstructors')->find($id);
			$alternate = Batches::with('AlternateInstructors')->find($id);
			
			if(isset($lead->LeadInstructors)){
				$leadInstructor      = $lead->LeadInstructors->first_name.' '.$lead->LeadInstructors->last_name;
			}else{
				
				$leadInstructor      = "";
			}
			
			if(isset($lead->LeadInstructors)){
				$alternateInstructor = $alternate->AlternateInstructors->first_name.' '.$alternate->AlternateInstructors->last_name;
			}else{
				$alternateInstructor = "";
			}
			
			$dataToView = array('currentPage', 'mainMenu', 'attendanceArray', 'batch', 'studentsInBatch','leadInstructor','alternateInstructor');
			return View::make('pages.batches.attendance', compact($dataToView));
		
		}else{
			return Redirect::to("/");
		}
		
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
