<?php

class StudentSchedule extends \Eloquent {
	protected $fillable = [];
	public $table = "student_schedule";
	
	public function Students(){
		
		return $this->belongsTo('Students', 'student_id');
	}
	
	public function Courses(){
	
		return $this->belongsTo('Courses', 'course_id');
	}
	
	
	public function Classes(){
		
		return $this->belongsTo('Classes', 'class_id');
	}
	
	
	static function addSchedule($input){
		
		
		
		$StudentSchedule = new StudentSchedule();
		$StudentSchedule->student_id    = $input['studentId'];
		$StudentSchedule->schedule_date = $input['scheduleDate'].' '.$input['scheduleTime'];
		$StudentSchedule->course_id     = $input['courseId'];
		$StudentSchedule->class_id      = $input['classId'];
		$StudentSchedule->save();
		
		StudentClasses::addSchedule($input);
		//date("Y-m-d H:i:s");
		return $StudentSchedule;
	}
	
	static function getStudentSchedule(){
		
		$schedules = StudentSchedule::with('Students','courses')->get();
		
		$resultArray = array();
		$i = 0;
		foreach($schedules as $schedule){
		
			$resultArray[$i]['title'] = $schedule->Courses->course_name.' '. $schedule->Students->student_name;
			$resultArray[$i]['start'] = $schedule->schedule_date;
		$i++;
		}
		
		return json_encode($resultArray);
		
	}
	
	
	
}