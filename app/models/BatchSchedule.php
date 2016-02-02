<?php

class BatchSchedule extends \Eloquent {
	protected $fillable = [];
	protected $table= 'batch_schedule';
	
	public function Customers(){
		return $this->belongsTo('Customers','customer_id');
	}
	
	public function Students(){
		return $this->belongsTo('Students','student_id');
	}
	
	
	
	public function Batches(){
		return $this->belongsTo('Batches','batch_id');
	}
	
	static function addSchedule($batchScheduleInput){
		$batchSchedule = new BatchSchedule();
		
		$batchSchedule->batch_id        = $batchScheduleInput['batchId'];
		$batchSchedule->franchisee_id   = Session::get('franchiseId');
		$batchSchedule->schedule_date   = $batchScheduleInput['scheduleDate'];
		$batchSchedule->start_time      = $batchScheduleInput['startTime'];
		$batchSchedule->end_time        = $batchScheduleInput['endTime'];
		
		$batchSchedule->schedule_type   = $batchScheduleInput['scheduleType'];
		$batchSchedule->created_by      = Session::get('userId');
		$batchSchedule->created_at      = date("Y-m-d H:i:s");
		$batchSchedule->save();
		
	}
	
	
	static function getBatcheSchedulesbyCourseandClassID($batchId){
		
		$batch = Batches::where('id', "=", $batchId)->get();
		
		$batchSchedules = BatchSchedule::where("batch_id", "=", $batchId)->get();
		
		$calenderData = array();
		$i=0;
		$calenderData['gotoDate'] = $batch['0']->start_date;
		foreach ($batchSchedules as $batchSchedule){
			/* 
			 * 
			title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false,
				
				[id] => 1
                            [batch_id] => 8
                            [franchisee_id] => 1
                            [schedule_date] => 2015-11-03
                            [start_time] => 15:00:00
                            [end_time] => 16:00:00
                            [created_by] => 2
                            [updated_by] => 
                            [created_at] => 2015-11-07 20:54:12
                            [updated_at] => 2015-11-07 20:54:12
			*/	
				
			$calenderData['event'][$i]['title']  = $batch['0']->batch_name;
			$calenderData['event'][$i]['id']  = $batch['0']->id;
			$calenderData['event'][$i]['start']  = $batchSchedule->schedule_date.' '.$batchSchedule->start_time;
			$calenderData['event'][$i]['end']    = $batchSchedule->schedule_date.' '.$batchSchedule->end_time;
			$calenderData['event'][$i]['allDay'] = 'false';
			$calenderData['event'][$i]['className'] = 'info';
			
			
			
			
			$i++;
		}
		return $calenderData;
	
	}
	
	static function getScheduleCountForBatch($batchId, $startDate, $endDate){	
		
		$batchEndDate = Batches::select('end_date')->where("id","=", $batchId)->get();
		//$batchEndDate['0']->end_date;
		
		/* BatchSchedule::where('batch_id', '=', $batchId)
		->whereBetween('schedule_date', array($startDate, $endDate))
		->count();
		
		print_r(DB::getQueryLog());
		
		exit(); */
		return BatchSchedule::where('batch_id', '=', $batchId)
							->whereBetween('schedule_date', array($startDate, $endDate))
							->count();
		
	}
	
	
	
	static function getTodaysIntroVisitCount(){
		
		$today = date('Y-m-d');
		return BatchSchedule::where("franchisee_id", "=", Session::get('franchiseId'))
		->where("schedule_type", "=", "introvisit")
		->where("schedule_date", "=", $today)
		->count();
	}
	
	static function getTodaysIntroVisits(){
	
		$today = date('Y-m-d');
		return BatchSchedule::with('Customers','Batches')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("schedule_type", "=", "introvisit")
		->where("schedule_date", "=", $today)
		->get();
	}
	
	
	
	static function checkIntroslotAvailable($scheduleDate, $batchId){
		
		return BatchSchedule::where('schedule_date','=',$scheduleDate)
							->where('batch_id','=',$batchId)
							->get();
	}
	
	
	static function addIntrovisit($batchScheduleInput){
		$batchSchedule = new BatchSchedule();
	
		$batchSchedule->batch_id        = $batchScheduleInput['batchId'];
		$batchSchedule->customer_id     = $batchScheduleInput['customerId'];
		$batchSchedule->student_id      = $batchScheduleInput['studentId'];
		$batchSchedule->franchisee_id   = Session::get('franchiseId');
		$batchSchedule->schedule_date   = $batchScheduleInput['scheduleDate'];
		$batchSchedule->status          = "introvisit_scheduled";
		/* $batchSchedule->start_time      = $batchScheduleInput['startTime'];
		$batchSchedule->end_time        = $batchScheduleInput['endTime']; */
	
		$batchSchedule->schedule_type   = $batchScheduleInput['scheduleType'];
		$batchSchedule->created_by      = Session::get('userId');
		$batchSchedule->created_at      = date("Y-m-d H:i:s");
		$batchSchedule->save();
		return $batchSchedule;
	
	}
	
	static function getIntroVisitByStudent($studentId){
		
		return BatchSchedule::with('Batches')->where('student_id','=',$studentId)
		->where('schedule_type','=','introvisit')
		->get();
	}
	
	
	static function getAttendanceTable($batchId){
		
		
		$studentsInBatch = StudentClasses::with('Students')->where('batch_id', '=', $batchId)->get(array('student_id', 'enrollment_start_date', 'enrollment_end_date'));
		
		
		$studentBatchDates = array();
		
		
		
		
		
		$i = 0;
		foreach($studentsInBatch as $student){
			
			 $studentBatchDates[$i]['Student']    = $student;
			 $studentBatchDates[$i]['Attendance'] = BatchSchedule::where("batch_id", '=', $batchId)
													//->whereBetween('schedule_date',[$student->enrollment_start_date, $student->enrollment_end_date])
													->where('schedule_date', '>=', $student->enrollment_start_date)
													->where('schedule_date', '<=', $student->enrollment_end_date)
													->get(); 
			
			$batchdates = BatchSchedule::where("batch_id", '=', $batchId)
													//->whereBetween('schedule_date',[$student->enrollment_start_date, $student->enrollment_end_date])
													->where('schedule_date', '>=', $student->enrollment_start_date)
													->where('schedule_date', '<=', $student->enrollment_end_date)
													->get(); 
			
			
			$attendanceIncrement = 0;
			
			$presentDays = 0;
			$eaDays = 0;
			$absentDays = 0;
			$totalSessions = 0;
			
			foreach($batchdates as $date ){
				
				
				
				/* echo "<pre>";
				print_r($student);
				echo "</pre>"; */
				
				$attendance = Attendance::getDaysAttendanceForStudent($student->student_id, $batchId, $date->schedule_date);
				$studentBatchDates[$i]['Attendance'][$attendanceIncrement]['attendStat'] = $attendance;
				$studentBatchDates[$i]['Attendance'][$attendanceIncrement]['attenddate'] = $date->schedule_date;
				
				if($attendance){
					if($attendance->status == "P"){
						$presentDays++;
					}
					if($attendance->status == "EA"){
						$eaDays++;
					}
					if($attendance->status == "A"){
						$absentDays++;
					}
				}
				
				
				
				$attendanceIncrement++;
				$totalSessions++;
				
			}
			
			$studentBatchDates[$i]['statistics']['present'] = $presentDays;
			$studentBatchDates[$i]['statistics']['absent'] = $absentDays;
			$studentBatchDates[$i]['statistics']['ea'] = $eaDays;
			$studentBatchDates[$i]['statistics']['totalSessions'] = $totalSessions;
			
			/* echo "<pre>";
				print_r(DB::getQueryLog());
				print_r($studentBatchDates[$i]);
				echo "</pre>";
			 */			
			$i++;	
		}
		
		
		/* echo "<pre>";
		print_r($studentBatchDates);
		echo "</pre>";
		exit(); */
		
		return $studentBatchDates;
		
	}
	
	
}