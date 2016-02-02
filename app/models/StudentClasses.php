<?php

class StudentClasses extends \Eloquent {
	protected $fillable = [];
	public $table = "student_classes";
	
	
	
	public function Courses(){
	
		return $this->belongsTo('Courses', 'course_id');
	}
	
	
	public function Classes(){
		
		return $this->belongsTo('Classes', 'class_id');
	}
	
	public function Students(){
		return $this->belongsTo("Students", 'student_id');
	}
	
	public function Orders(){
		return $this->hasMany('Orders','student_classes_id');
	}
	
	
	
	static function addStudentClass($input){
		
		
		$StudentClasses = new StudentClasses();
		$StudentClasses->student_id    = $input['studentId'];
		$StudentClasses->class_id      = $input['classId'];		
		$StudentClasses->enrollment_start_date  = $input['enrollment_start_date'];
		$StudentClasses->enrollment_end_date  = $input['enrollment_end_date'];		
		$StudentClasses->selected_sessions  = $input['selected_sessions'];
		$StudentClasses->batch_id      = $input['batchId'];
		$StudentClasses->created_by    = Session::get('userId');
		$StudentClasses->created_at    = date("Y-m-d H:i:s");
		$StudentClasses->save();
		
		return $StudentClasses;
	}
	
	static function getStudentEnrollments($id){
		
		return StudentClasses::with('Classes')->where('student_id', '=', $id)->get();
	}
	
	static function getEnrolledCustomers(){
		
		/* $enrolledCustomers =   DB::table('customers')
					            ->join('students', 'students.customer_id', '=', 'customers.id')
					            ->join('student_classes', 'student_classes.student_id', '=', 'students.id')
					            ->where('customers.franchisee_id', '=',  Session::get('franchiseId'))
					            ->select('customers.id', DB::raw('count(customers.id) as enrolledCustomers'))  
								->groupBy('customers.id')		
					            ->get(); */
		
		$franchiseeId = Session::get('franchiseId');
		
		$enrolledCustomers = DB::table('students')
		->join('student_classes', 'student_classes.student_id', '=', 'students.id')
		->join('batches', 'batches.id', '=', 'student_classes.batch_id')
		->where('batches.franchisee_id','=',$franchiseeId)
		->count();
		
		
		/* DB::select( DB::raw("SELECT id FROM students WHERE some_col = :somevariable"), array(
		'somevariable' => $someVariable,
		));
 */
		//echo $enrolledCustomers;
		//exit();
		
		//$enrolledCustomers = Students::with('StudentClasses')->where('franchisee_id', '=', Session::get('franchiseId'))->count();
		
		
		if($enrolledCustomers){
		return $enrolledCustomers;
		}
		return false;
	}
	
	static function getStudentByBatchId($batchId, $selectedDate){
		$selectedDate = date('Y-m-d', strtotime($selectedDate));
		$studentByBatchId  =   StudentClasses::with('Students')
								->where('batch_id', '=', $batchId)
								->whereDate('enrollment_start_date', '<', $selectedDate)
								->whereDate('enrollment_end_date', '>', $selectedDate)
								->get();
		//dd(DB::getQueryLog());
		return $studentByBatchId;
		
		
	}
	
	static function discount($studentId, $customerId){
		
		/* return StudentClasses::where('student_id', '=', $studentId)
						->where('class_id', '=', $classid)
						->first(); */
		
		$isEligibleforTwenty = "NO";
		$isEligibleforFifty  = "NO";
		
		
		$Students = Students::where("customer_id", "=", $customerId)
							  ->where("id", '<>', $studentId)
							  ->get();
		
		
		$enrolledStudent = 0;
		foreach($Students as $student){
			
				$enrolledStudent = StudentClasses::where('student_id', '=', $student->id)
				->count();
				
			
		}
		
		if($enrolledStudent > 0){			
			$isCurrentStudentEnrolled = StudentClasses::where('student_id', '=', $studentId)
			->count();			
			if($isCurrentStudentEnrolled == 0){
				$isEligibleforTwenty = "YES";
			}			
		}
		
		$isCurrentStudentEnrolled = StudentClasses::where('student_id', '=', $studentId)
		->count();
		if($isCurrentStudentEnrolled > 0){
			$isEligibleforFifty = "YES";
		}
		
		return array("eligibleForTwenty"=>$isEligibleforTwenty, "eligibleForFifty"=>$isEligibleforFifty);
		
		
	
	}
	
        static function getStudentClassbyId($student_class_id){
            return StudentClasses::where('id','=',$student_class_id)
                                   ->get();
        }
	
	
	
}