<?php

class ClassesMaster extends \Eloquent {
	protected $fillable = [];
	protected $table= 'classes_master';
	
	
	static function getClassesMasterForDropDown($courseMasterId){
		
		return ClassesMaster::where('course_master_id', '=', $courseMasterId)->lists('class_name', 'id');
	}

	static function InsertNewClass($data){
		
		$insert = new ClassesMaster();
		$insert->course_master_id = $data['courseId'];
		$insert->class_name = $data['className'];
		$insert->slug = $data['slug'];
		$insert->class_start_age = $data['s_age'];
		$insert->age_start_limit_unit = "months";
		$insert->class_end_age = $data['e_age'];
		$insert->age_end_limit_unit = "months";
		$insert->gender = $data['gender'];
		$insert->created_by = Session::get('userId');
		$insert->updated_by = Session::get('userId');
		$insert->save();
		return $insert;
	}

	static function getAllClassesMasters(){
		return ClassesMaster::all();
	}

	static function updateClassesMaster($data){
		$update = ClassesMaster::find($data['ClassId']);

		$update->course_master_id = $data['courseId'];
		$update->class_name = $data['ClassName'];
		$update->slug = $data['ClassSlug'];
		$update->class_start_age = $data['s_age'];
		$update->age_start_limit_unit = "months";
		$update->class_end_age = $data['e_age'];
		$update->age_end_limit_unit = "months";
		$update->gender = $data['gender'];
		$update->created_by = Session::get('userId');
		$update->updated_by = Session::get('userId');
		$update->save();
		return $update;
	}
}