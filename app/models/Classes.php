<?php

class Classes extends \Eloquent {
	protected $fillable = [];
	protected $table = 'classes';
	
	public function Users(){
	
		return $this->belongsTo('User','created_by');
	}
	
	public function Courses(){
		
		return $this->belongsTo('Courses', 'course_id');
	}
	
	public function StudentSchedule(){
	
		return $this->belongsTo('StudentSchedule','class_id');
	}
	
	
	public function Batches(){
	
		return $this->hasManny('Batches', 'class_id');
	}
	
	
	static function getAllClasses($franchiseeId){
		
		return Classes::with('Users','Courses')
				->where('franchisee_id', '=', $franchiseeId)
				->get();
		
	}
	
	static function getAllClassesLists($franchiseeId, $franchiseeCourseId){
	
		return Classes::where('franchisee_id','=',$franchiseeId)
				->where('course_id','=',$franchiseeCourseId)
				->lists('class_name','id');
	
	}
	
	static function getClassessByFranchiseeCourseId($franchiseeId, $franchiseeCourseId){
		
		$courseMasterId = Courses::find($franchiseeCourseId);
		$franchiseeClasses = ClassesMaster::getClassesMasterForDropDown($courseMasterId->master_course_id);
		return $franchiseeClasses;
		
	}
	
	static function addClasses($inputs){	
		
		if(self::ifClassExists($inputs['classId'], $inputs['franchiseeCourse'])){
			
			
			return "exists";
		}else{
			$Class = new Classes();
			$Class->class_name = $inputs['className'];
			$Class->course_id = $inputs['franchiseeCourse'];
			$Class->class_master_id = $inputs['classId'];
			$Class->franchisee_id = Session::get('franchiseId');
			$Class->created_by       = Session::get('userId');
			$Class->created_at       = date("Y-m-d H:i:s");
			$Class->save();
			
			return $Class;
		}
		
		
		
		
	}
	
	static function ifClassExists($classMasterId, $courseId){
		
		
		$classExistence = Classes::where('class_master_id','=',$classMasterId)
				->where('course_id','=',$courseId)->count();
		
		return $classExistence;
		
	}
	
        
        
        
        public static function getallParentchildCourseCount(){
            return DB::table('classes_master')
                                            ->join('classes', 'classes_master.id', '=', 'classes.class_master_id')
                                            ->join('student_classes', 'student_classes.class_id', '=', 'classes.id')
                                            ->where('classes.franchisee_id', '=', Session::get('franchiseId'))
                                            ->where('course_master_id','=','1')
                                            ->count();
        }
	public static function getallPrekgKindergartenCount(){
            return DB::table('classes_master')
                                            ->join('classes', 'classes_master.id', '=', 'classes.class_master_id')
                                            ->join('student_classes', 'student_classes.class_id', '=', 'classes.id')
                                            ->where('classes.franchisee_id', '=', Session::get('franchiseId'))
                                            ->where('course_master_id','=','2')
                                            ->count();
        }
        public static function getallGradeschoolCount(){
            return DB::table('classes_master')
                                            ->join('classes', 'classes_master.id', '=', 'classes.class_master_id')
                                            ->join('student_classes', 'student_classes.class_id', '=', 'classes.id')
                                            ->where('classes.franchisee_id', '=', Session::get('franchiseId'))
                                            ->where('course_master_id','=','3')
                                            ->count();
        } 
        public static function getstudentclasses($classId){
            return Classes::where('id','=',$classId)->get();
        }


        static function InsertNewClassFromFranchise($data){
        
        	$insert = new classes();
        	$insert->class_name = $data['className'];
        	$getSlug = ClassesMaster::select('slug','id')->where('class_name', '=', $data['className'])->get();
        	//return $getSlug[0]['slug'];
        	$insert->slug = $getSlug[0]['slug'];
        	$insert->base_price_no = $data['basePriceNo'];
        	$insert->course_id = $data['Course_id'];
        	$insert->franchisee_id = Session::get('franchiseId');
        	$insert->class_master_id = $getSlug[0]['id'];
        	$insert->created_by = Session::get('userId');
        	$insert->updated_by = Session::get('userId');
        	$insert->save();
        	return $insert;

        }

        static function getAllClassesForFranchise(){
        	return Classes::where('franchisee_id', '=', Session::get('franchiseId'))->get();
        }
	
}