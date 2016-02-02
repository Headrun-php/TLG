<?php

class Attendance extends \Eloquent {
	protected $fillable = [];
	protected $table = 'attendance';
	
	
	public function Classes(){
		
		return $this->belongsTo('Classes', 'class_id');
	}
	
	
	static function getDaysAttendanceForStudent($studentId, $batchId, $attendanceDate){
		
		
		//echo "student_id".$studentId.' batchId'.$batchId.' attendanceDate'.$attendanceDate;
		
		$attendance =  Attendance::where('student_id', '=', $studentId)
							->where('batch_id', '=', $batchId)
							->where('attendance_date', '=', $attendanceDate)
							->first();
		
		//print_r(DB::getQueryLog());
		
		if($attendance)
		{
			return $attendance;
		}
		return false;
	}
	
	
	
	
	
	
	
	
}