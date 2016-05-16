<?php
use Carbon\Carbon;

class Students extends \Eloquent {
	protected $fillable = [];
	
	public function Customers(){
		return $this->belongsTo('Customers','customer_id');
	}
	
	
	public function StudentSchedule(){
	
		return $this->hasMany('StudentSchedule', 'student_id');
	}
	
	
	public function StudentClasses(){
	
		return $this->hasMany('StudentClasses', 'student_id');
	}
	
	public function BatchSchedule(){
	
		return $this->hasMany('BatchSchedule', 'student_id');
	}
	
	public function Orders(){
		return $this->hasMany('Orders','student_id');
	}
	
	/*
	static function getAllNonEnrolledStudents($franchiseeId){
		//$presentdate=Carbon::now();
		//$students = Students::where('franchisee_id','=', $franchiseeId)->get();
                $students=  Students::with('StudentClasses')
                                      ->whereDate('enrollment_start_date','>=',date("Y-m-d"))
                                      ->whereDate('enrollment_end_date','<=', date("Y-m-d"))
                                      ->where('franchisee_id','=', $franchiseeId)
                                      ->get();
                                      
		return $students;
	}
	
	*/
	
	
	
	static function addStudent($inputs){
		
		
		$student = new Students();
		
		
		$student->customer_id           = $inputs['customerId'];
		$student->franchisee_id         = Session::get('franchiseId');		
		$student->student_name          = $inputs['studentName'];
		$student->student_gender        = $inputs['studentGender'];
		$student->student_date_of_birth = date('Y-m-d',strtotime($inputs['studentDob']));
		$student->nickname              = $inputs['nickname'];
		$student->school                = $inputs['school'];
		$student->location              = $inputs['location'];
		$student->hobbies               = $inputs['hobbies'];
		$student->emergency_contact      = $inputs['emergencyContact'];
		$student->remarks               = $inputs['remarks'];
		$student->health_issue           = $inputs['healthIssue'];		
		$student->created_by            = Session::get('userId');
		$student->created_at            = date("Y-m-d H:i:s");
		
		/* echo "<pre>";
		print_r($student);
		echo "</pre>"; */
		$student->save();
		
		if($student){
			
			
			
			
			$commentsInput['customerId']     = $inputs['customerId'];
			$commentsInput['commentText']    = '('.$inputs['studentName'].') '.Config::get('constants.KIDS_ADDED');
			$commentsInput['commentType']    = 'ACTION_LOG';
			//$commentsInput['reminderDate']   = $inputs['reminderTxtBox'];
			Comments::addComments($commentsInput);
		}
		
		return $student;
		
	}
	
	static function updateStudent($inputs){
	
		$student = Students::find($inputs['studentId']);
		$student->student_name          = $inputs['studentName'];
		$student->student_gender        = $inputs['studentGender'];
		$student->student_date_of_birth = date('Y-m-d',strtotime($inputs['studentDob']));
		$student->nickname              = $inputs['nickname'];
		$student->school                = $inputs['school'];
		$student->location              = $inputs['location'];
		$student->hobbies               = $inputs['hobbies'];
		$student->emergency_contact     = $inputs['emergencyContact'];
		$student->remarks               = $inputs['remarks'];
		$student->health_issue          = $inputs['healthIssue'];
		$student->updated_by            = Session::get('userId');
		$student->updated_at            = date("Y-m-d H:i:s");
		$student->save();	
		return $student;
	
	}
	
	
	
	public static function getStudentById($id){
		
		return Students::with('Customers')->where("id","=",$id)->get();
	}
	
	
	static function addStudentsToBatch(){
		$students = new Students();
		$students->student_id = $inputs[''];
	}
	
	
	static function studentList($franchiseeID){
		
		$courses = Students::where('franchisee_id', '=', $franchiseeID)->lists('student_name', 'id');
		return $courses;
	}
	
	static function getStudentsForSelectBox($id){
		return Students::where('customer_id','=',$id)->lists('student_name','id'); 
	}
	
	static function getStudentByCustomer($customerId){
		
		$students = Students::with('StudentClasses')->where('customer_id','=',$customerId)->get();					
		return $students;
	}
	
	
        static function  getStudentId($id){
            return Students::where('id','=',$id)->get();
        }
	
	
	
}