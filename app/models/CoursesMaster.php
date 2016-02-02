<?php

class CoursesMaster extends \Eloquent {
	protected $fillable = [];
	
	public $table = "courses_master";
	
	private $rules = array(
			"courseName" => "required", //|unique:courses_master, course_name
			"masterCourse" => "required",
	);
	
	private $errors = array();
	
	public function Courses(){
		
		return $this->hasMany('Courses', 'master_course_id');
	}
	
	
	static function getCoursesList(){
	
		$courses = CoursesMaster::orderBy('course_name')->lists('course_name', 'id');
		return $courses;
	}
	
	static function addCourseMaster($inputs){
		
		
		
		
	}
	
	
	public function validate() {
	
		$validation = Validator::make(Input::all(), $this->rules);
		if ($validation->fails()) {
			$this->errors = $validation->errors();
			return false;
		}
		return true;
	}
	
	public function errors() {
		return $this->errors;
	}
}