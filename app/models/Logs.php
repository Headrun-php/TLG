<?php

class Logs extends \Eloquent {
	protected $fillable = [];
	public $table = "comments";
	
	
	public function Customers(){
		
		return $this->belongsTo("Customers", "customer_id");
	}
	
	public function Users(){
	
		return $this->belongsTo("User", "created_by");
	}

	
	
	static function addLog($input){		
		
		$comments = new Comments();
		$comments->customer_id   = $input['customerId'];
		$comments->franchisee_id = Session::get('franchiseId');
		$comments->log_text   = $input['logText'];
		$comments->created_by    = "0";
		$comments->created_at    = date("Y-m-d H:i:s");
		$comments->save();
		
		return $comments;
	}
	
	static function getCommentByCustomerId($customerId){
		
		return Comments::with("Customers","Users")->where('customer_id', "=", $customerId)->get();
		
	}
	
	static function getReminderCountByFranchiseeId(){
		
		$today = date('Y-m-d');
		return Comments::where("franchisee_id", "=", Session::get('franchiseId'))
					->where("reminder_date", "!=", "NULL")
					->where("reminder_date", "LIKE", "".$today."%")
					->count();
					//->get();
		
	}
	
	static function getTodaysFollowup(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("reminder_date", "!=", "NULL")
		->where("reminder_date", "LIKE", "".$today."%")
		->get();
	
	}
	
	
	static function getAllFollowup(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("reminder_date", "!=", "NULL")
		->where("reminder_type", "=", "followup")
		->orderBy('reminder_date', 'desc')
		//->where("reminder_date", "LIKE", "".$today."%")
		->get();
	
	}
	
	static function getAllFollowupActive(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("reminder_date", "!=", "NULL")
		->where("reminder_type", "=", "followup")
		->where("reminder_status", "=", "active")
		->orderBy('reminder_date', 'desc')
		//->where("reminder_date", "LIKE", "".$today."%")
		->get();
	
	}
	
	
	
	
	
	
	
	
}