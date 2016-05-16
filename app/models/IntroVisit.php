<?php

class IntroVisit extends \Eloquent {
	protected $fillable = [];
	protected $table= 'introvisit';
	
	public function Customers(){
		return $this->belongsTo('Customers','customer_id');
	}
	
	public function Students(){
		return $this->belongsTo('Students','student_id');
	}
	
	public function Classes(){
	
		return $this->belongsTo('Classes', 'class_id');
	}
	
	public function Batches(){
		return $this->belongsTo('Batches', 'batch_id');
	}
	

	
	
	static function addSchedule($inputs){
		
		$introVisit = new IntroVisit();
		
		$introVisit['customer_id']      = $inputs['customerId'];
		$introVisit->student_id         = $inputs['studentIdIntroVisit'];
		
		$introVisit->class_id           = $inputs['eligibleClassesCbx'];
		$introVisit->batch_id           = $inputs['introbatchCbx'];	
		//$introVisit->status             = 'ACTIVE/SCHEDULED';
		
		$introVisit->franchisee_id      = Session::get('franchiseId');		
		$introVisit->iv_date            = date('Y-m-d',strtotime($inputs['introVisitTxtBox']));
		$introVisit->created_by         = Session::get('userId');
		$introVisit->created_at         = date("Y-m-d H:i:s");
		$introVisit->save();
		
		
		$customerObj = Customers::find($inputs['customerId']);
		if($customerObj){
			
			$customerObj->stage = "IV SCHEDULED";
			$customerObj->save();
		}
		
		return $introVisit;
		
	}
	
	static function getIntrovisitBytoday(){
	
		return IntroVisit::whereDate('iv_date', '=',  date("Y-m-d"))
						//	->whereIn('status',array('ACTIVE/SCHEDULED','RESCHEDULED'))
                                                       
							->where('franchisee_id', '=',  Session::get('franchiseId'))
							->count();
	}
	
	static function getAllActiveIntrovisit(){
	
		return IntroVisit::with('Customers', 'Classes', 'Batches', 'Students')
						//	->whereIn('status',array('ACTIVE/SCHEDULED','RESCHEDULED'))
							->where('franchisee_id', '=',  Session::get('franchiseId'))
							->get();
	}
	
	static function getIntrovisitByStudentId($studentId){
		
		return IntroVisit::with('Classes','Batches')
							->where('student_id', '=', $studentId)
							->where('franchisee_id', '=',  Session::get('franchiseId'))
							->get();
	}
        static function getIntrovistCount(){
            return  IntroVisit:: where('franchisee_id', '=',  Session::get('franchiseId'))
                                ->count();
        }
	

	
}