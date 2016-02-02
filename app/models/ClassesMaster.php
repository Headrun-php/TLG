<?php

class ClassesMaster extends \Eloquent {
	protected $fillable = [];
	protected $table= 'classes_master';
	
	
	static function getClassesMasterForDropDown($courseMasterId){
		
		return ClassesMaster::where('course_master_id', '=', $courseMasterId)->lists('class_name', 'id');
	}
}