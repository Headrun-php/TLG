<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
class ClassesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$currentPage  =  "COURSES";
		$mainMenu     =  "COURSES_MAIN";
		
		$inputs = Input::all();
		
			
		if(isset($inputs['className']) && isset($inputs['franchiseeCourse'])){
			
			
			/* echo '<pre>';
			print_r($inputs);
			exit(); */
			
			$classadd = Classes::addClasses($inputs);
			if ($classadd == "exists") {
				Session::flash('error', "Sorry, Class you are trying to add already exists");				
					
			} elseif(!$classadd) {
					
				Session::flash('error', "Sorry, Class Could not be added at the moment.");
				
					
			}else{
				Session::flash('msg', "Class added successfully.");
			}
		}
		
		$courseList = CoursesMaster::getCoursesList();
		//$classesMaster = ClassesMaster::getClassesMasterForDropDown();
		
		$franchiseeCourses = Courses::getFranchiseCoursesList(Session::get('franchiseId'));
		$classes = Classes::getAllClasses(1);
		return View::make('pages.classes.classes', compact('classesMaster', 'classes', 'courseList','franchiseeCourses','currentPage','mainMenu'));
	}

	
	public function classesbymaster(){
		$franchiseeCourse = Input::get('franchiseeCourse');
		//$classess = ClassesMaster::getClassesMasterForDropDown($courseMasterId);
		$classess = $classess = Classes::getClassessByFranchiseeCourseId(Session::get('franchiseId'), $franchiseeCourse);
		header('Access-Control-Allow-Origin: *');
		return Response::json($classess);
		
	}
	
	
	public function classesbyCourse(){
		
		$franchiseeCourse = Input::get('franchiseeCourse');
		//$classess = ClassesMaster::getClassesMasterForDropDown($courseMasterId);
		$classess = $classess = Classes::getAllClassesLists(Session::get('franchiseId'), $franchiseeCourse);
		header('Access-Control-Allow-Origin: *');
		return Response::json($classess);
		
	}
	
	
	public function eligibleClassess(){

		$ageYear  = Input::get('ageYear');
		$ageMonth = Input::get('ageMonth');
		$gender   = Input::get('gender');
		
		
		
		if($ageYear == "0"){
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageMonth)
			->where("class_end_age", ">", $ageMonth)
			->where("age_start_limit_unit", "=", "months")
			->get();
				
				
		}elseif($ageYear == 1){
				
			$yearandMonth = (12+$ageMonth);
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", number_format($yearandMonth, 1, '.', ''))
			->where("class_end_age", "<=", 30)
			//->where("age_end_limit_unit", "=", 'years')
			->where("age_end_limit_unit", "=", "months")
			->get();
			//dd(DB::getQueryLog());
			//echo "1year";
				
		}
		elseif($ageYear == 2 &&  $ageMonth <= 5 ){
		
			//$yearandMonth = (12+$ageMonth);
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", ">=", 19)
			->where("class_end_age", "<=", 30)
			//->where("age_end_limit_unit", "=", 'years')
			->where("age_end_limit_unit", "=", "months")
			->get();
		
		
		}
		elseif($ageYear == 2 &&  $ageMonth >=6 ){
		
			//$yearandMonth = (12+$ageMonth);
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", ">=", 2.5)
			->where("class_end_age", "<=", 3)
			->where("age_end_limit_unit", "=", 'years')
			//->where("age_end_limit_unit", "=", "months")
			->get();
		
		
		}		
		else if($ageYear ==3 && $ageYear <4){
				
			$classesMaster = ClassesMaster::select('id')
			//->whereBetween('class_start_age',[3,4])
			->where("class_start_age", "<=", 3)
			->where("class_end_age", ">=", 4)
			->where("age_start_limit_unit", "=", "years")
			->get();
			//echo $ageYear;
			//dd(DB::getQueryLog());
		
		}
		else if($ageYear >=4 && $ageYear<6){
				
			$classesMaster = ClassesMaster::select('id')
			->whereBetween('class_start_age',[4,6])
			//->where("class_start_age", "<", 6)
			->where("class_end_age", "<", 6)
			->where("age_start_limit_unit", "=", "years")
			->get();
			//echo $ageYear;
			//dd(DB::getQueryLog());
		
		}else if($ageYear >= 6 && $ageYear<12){
				
			$classesMaster = ClassesMaster::select('id')
			->whereBetween('class_start_age',[6,12])
			/* ->where("class_start_age", "<=", $ageYear)
			 ->where("class_end_age", ">=", $ageYear)
			->where("age_start_limit_unit", "=", "years") */
			->where("gender", "=", $gender)
			->get();
				
			//dd(DB::getQueryLog());
		
		}/* else{
		$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageYear)
		->where("class_end_age", ">", $ageYear)
		->where("age_start_limit_unit", "=", "years")
		->get();
		//dd(DB::getQueryLog());
		} */
		
		$masterClassIDs = array();
		$i = 0;
		foreach($classesMaster->toArray() as $masterClass){
				
			$masterClassIDs[$i] = $masterClass['id'];
			$i++;
		}
		
		
		
		$classesEligible = DB::table('classes')
		->whereIn('class_master_id', $masterClassIDs)
		->where('franchisee_id', '=', Session::get('franchiseId'))
		->get();
		
		header('Access-Control-Allow-Origin: *');
		return Response::json($classesEligible);
	}
	
	
	public function batchesByClass(){
		
		$classId = Input::get('classId');
		$batches = Batches::batchesByClassId($classId);
		
		
		$batchesJson = array();
		$i = 0;
		foreach ($batches as $batch){
				
			$batchesJson[$i]['id'] = $batch->id;
			$batchesJson[$i]['batch_name'] = $batch->batch_name;
			$batchesJson[$i]['day'] = date('l', strtotime($batch->start_date));
			$batchesJson[$i]['start_time'] = date('G:i a', strtotime($batch->preferred_time));
			$batchesJson[$i]['end_time'] = date('G:i a', strtotime($batch->preferred_end_time));
			if(isset($batch->LeadInstructors->first_name)){
				$batchesJson[$i]['instructor'] = '('.$batch->LeadInstructors->first_name.' '.$batch->LeadInstructors->last_name.')';
			}else{
				$batchesJson[$i]['instructor'] = '';
			}
			$i++;
		}
		
		header('Access-Control-Allow-Origin: *');
		return Response::json($batchesJson);
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
