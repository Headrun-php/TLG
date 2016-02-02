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

	public function addCourses(){
		
		$currentPage  =  "COURSES";
		$mainMenu     =  "COURSES_MAIN";
		
		$inputs = Input::all();
		if (isset($inputs['courseName'])) {
		
			$courses = new Courses();
			if(!$courses->validate()){
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
				
				
				return Redirect::to('/courses/add');
			}
		}
		
		$courseList = CoursesMaster::getCoursesList();
		$courses    = Courses::getFranchiseCourses(Session::get('franchiseId'));
		return View::make('pages.courses.addCourse', compact('courseList', 'courses','currentPage','mainMenu'));
	
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
