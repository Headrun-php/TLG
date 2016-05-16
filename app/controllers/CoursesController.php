<?php

use Illuminate\Support\Facades\View;
class CoursesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

//	public function addCourses(){
//		
//		$currentPage  =  "COURSES";
//		$mainMenu     =  "COURSES_MAIN";
//		
//		$inputs = Input::all();
//		if (isset($inputs['courseName'])) {
//		
//			$courses = new Courses();
//			if(!$courses->validate()){
//				return Redirect::back()->withInput()->withErrors($courses->errors());
//			}
//			else{
//				
//				$courseExistence = Courses::checkWhetherCourseAdded(Session::get('franchiseId'), $inputs['masterCourse']);
//				if($courseExistence == 0){
//					
//					if (Courses::addCourse($inputs)) {
//							
//						Session::flash('msg', "Course added successfully.");
//							
//					} else {
//							
//						Session::flash('warning', "Sorry, Course Could not be added at the moment.");
//					
//					}
//					
//				}elseif($courseExistence > 0){
//					Session::flash('error', "Sorry, Course already added to your franchise.");
//				}
//				
//				
//				return Redirect::to('/courses/add');
//			}
//		}
//		
//		$courseList = CoursesMaster::getCoursesList();
//		$courses    = Courses::getFranchiseCourses(Session::get('franchiseId'));
//		return View::make('pages.courses.addCourse', compact('courseList', 'courses','currentPage','mainMenu'));
//	
//	}
        public function addCourses(){
		
		$currentPage  =  "COURSES";
		$mainMenu     =  "COURSES_MAIN";
		
		$inputs = Input::all();
		if (isset($inputs['courseName']) || isset($inputs['masterCourseList'])) {
		
			$courses = new Courses();
                        if(isset($inputs['masterCourseList'])){
                            $inputs['masterCourse'] = $inputs['masterCourseList'];
                            $courseName = json_decode(CoursesMaster::select('slug','course_name')->where("id", "=", $inputs['masterCourse'])->get());
                            $inputs['courseName'] = $courseName[0]->course_name; 
                            $inputs['slug'] = $courseName[0]->slug; 
                        }
                        
			if(!$courses->fill($inputs)->validate()){
				return Redirect::back()->withInput()->withErrors($courses->errors());
			}
			else{
				
				$courseExistence = Courses::checkWhetherCourseAdded(Session::get('franchiseId'), $inputs['masterCourse']);
				if($courseExistence == 0){
					
					if (Courses::addCourse($inputs)) {
							
						Session::flash('msg', "Course added successfully.");
							
					} else {
							
						Session::flash('warning', "Sorry, Course Could not be added at the moment.");
					
					}
					
				}elseif($courseExistence > 0){
					Session::flash('error', "Sorry, Course already added to your franchise.");
				}
				
				if(isset($inputs['masterCourseList'])){
                                   return Redirect::to('/courses/name_list'); 
                                }
				return Redirect::to('/courses/add');
			}
		}
		
		$courseList = CoursesMaster::getCoursesList();
		$courses    = Courses::getFranchiseCourses(Session::get('franchiseId'));
		return View::make('pages.courses.addCourse', compact('courseList', 'courses','currentPage','mainMenu'));
	
	}

        public function courseNameList() {
            $currentPage  =  "COURSES";
            $mainMenu     =  "COURSES_MAIN";
            $courseList = CoursesMaster::getCoursesList();
            $courses    = Courses::getFranchiseCourses(Session::get('franchiseId'));
            return View::make('pages.courses.course-name-list', compact('currentPage','mainMenu','courseList','courses'));
        }
        
        
	public function viewCourses(){
		$currentPage  =  "COURSES";
		$mainMenu     =  "COURSES_MAIN";
		$eligibleForAction = array();
		$allCourse = CoursesMaster::getAllCourses();
		for ($i=0; $i < count($allCourse) ; $i++) { 
			$Master_course_id = $allCourse[$i]['id'];
			$getCoursesCount = Courses::where('master_course_id', '=', $Master_course_id)->get();
			if(count($getCoursesCount) == 0){
				array_push($eligibleForAction, $Master_course_id);
			}
		}
		//return $eligibleForAction;
		return View::make('pages.courses.courses', compact('currentPage','mainMenu', 'allCourse', 'eligibleForAction'));	
	}


	public function deleteCoursesMaster(){
		$inputs = Input::all();
		$sendDataToDelete = CoursesMaster::deleteCoursesMaster($inputs);
		if($sendDataToDelete){
			return Response::json(array('status'=>'success', $sendDataToDelete));
		}else{
			return Response::json(array('status'=>'failure'));
		}
	}

	public function updateCoursesMaster(){
		$inputs = Input::all();
		//return Response::json(array('status'=>'success', Session::get('userId')));
		$sendDataToDelete = CoursesMaster::updateCoursesMaster($inputs);
		if($sendDataToDelete){
			return Response::json(array('status'=>'success', $sendDataToDelete));
		}else{
			return Response::json(array('status'=>'failure'));
		}
	}

	public function InsertNewCoursesMaster(){
		$inputs = Input::all();
		//return Response::json(array('status'=>'success', Session::get('userId')));
		$sendDataToDelete = CoursesMaster::InsertNewCoursesMaster($inputs);
		if($sendDataToDelete){
			return Response::json(array('status'=>'success', $sendDataToDelete));
		}else{
			return Response::json(array('status'=>'failure'));
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
