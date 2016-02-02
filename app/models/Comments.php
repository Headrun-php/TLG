<?php

class Comments extends \Eloquent {
	protected $fillable = [];
	public $table = "customer_logs";
	
	
	public function Customers(){
		
		return $this->belongsTo("Customers", "customer_id");
	}
	
	public function Users(){
	
		return $this->belongsTo("User", "created_by");
	}

	
	
	static function addComments($input){		
		
		$comments = new Comments();
		$comments->customer_id   = $input['customerId'];
		$comments->log_text      = $input['commentText'];
		$comments->comment_type = $input['commentType'];
		$comments->franchisee_id = Session::get('franchiseId');
		if(isset($input['reminderDate'])){
			$comments->reminder_date = date('Y-m-d H:i:s',strtotime($input['reminderDate']));
		}else{
			$comments->reminder_date = null;
		}	
		$comments->created_by    = Session::get('userId');
		$comments->created_at    = date("Y-m-d H:i:s");
		$comments->save();
		
		return $comments;
	}
	
	static function getCommentByCustomerId($customerId){
		
		return Comments::with("Customers","Users")
						->where('customer_id', "=", $customerId)
						->orderBy('created_at', 'DESC')
						->get();
		
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
		->where("comment_type", "=", "FOLLOW_UP")
		->whereDate("reminder_date", "=", $today)
		->get();
	
	}
	
	
	static function getAllFollowup(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		//->where("reminder_date", "!=", "NULL")
		->whereDate("reminder_date", "=", $today)
		->whereIn("comment_type", ["FOLLOW_UP", "CALL_BACK"])		
		->orderBy('reminder_date', 'desc')
		//->where("reminder_date", "LIKE", "".$today."%")
		->get();
		
		/* ->where("comment_type", "=", "FOLLOW_UP")
		//->orWhere("comment_type", "=", "CALL_BACK")
		->orWhere(function ($query) {
			$query->where("comment_type", "=", "CALL_BACK");
		}) */
	
	}
	
	static function getAllFollowupActive(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("reminder_date", "!=", "NULL")
		//->where("comment_type", "=", "followup")
		//->where("reminder_status", "=", "active")
		->orderBy('reminder_date', 'desc')
		//->where("reminder_date", "LIKE", "".$today."%")
		->get();
	
	}
	
	
	
	
	
	
	
	
}