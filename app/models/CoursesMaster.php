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


	static function getAllCourses(){
	
		$courses = CoursesMaster::all();
		return $courses;
	}

	static function deleteCoursesMaster($id){
		$delete = CoursesMaster::find($id['id']);
		$delete->delete();
		return $delete;
	}


	static function updateCoursesMaster($data){
		
		//return $data;
		$update = CoursesMaster::find($data['id']);
		$update->course_name = $data['name'];
		$update->slug = $data['slug'];
		$update->age_start = $data['s_age'];
		$update->age_end = $data['e_age'];
		$update->created_by = Session::get('userId');
		$update->updated_by = Session::get('userId');
		$update->save();
		return $update;
	}


	static function InsertNewCoursesMaster($data){
		
		//return $data;
		$insert = new CoursesMaster();
		$insert->course_name = $data['name'];
		$insert->slug = $data['slug'];
		$insert->age_start = $data['s_age'];
		$insert->age_end = $data['e_age'];
		$insert->created_by = Session::get('userId');
		$insert->updated_by = Session::get('userId');
		$insert->save();
		return $insert;
	}


}