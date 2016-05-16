<?php
use Carbon\Carbon;

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
                if(isset($input['introvisit_id'])){
                $comments->introvisit_id=$input['introvisit_id'];
                }
                if(isset($input['paymentfollowup_id'])){
                $comments->paymentfollowup_id=$input['paymentfollowup_id'];
                }
                if(isset($input['followupType'])){
                    $comments->followup_type=$input['followupType'];
                }
                if(isset($input['birthday_id'])){
                    $comments['birthday_id']=$input['birthday_id'];
                }
                if(isset($input['student_id'])){
                    $comments['student_id']=$input['student_id'];
                }
                if(isset($input['inquiry_id'])){
                    $comments['inquiry_id']=$input['inquiry_id'];
                }
                
                if(isset($input['membership_followup_id'])){
                    $comments['membership_followup_id']=$input['membership_followup_id'];
                }
                
                if(isset($input['commentStatus'])){
                    $comments['followup_status']=$input['commentStatus'];
                }
                if(isset($input['complaint_id'])){
                    $comments['complaint_id']=$input['complaint_id'];
                }
                if(isset($input['retention_id'])){
                    $comments['retention_id']=$input['retention_id'];
                }
                $comments->log_text      = $input['commentText'];
		$comments->comment_type = $input['commentType'];
		$comments->franchisee_id = Session::get('franchiseId');
                if(isset($input['reminder-Date'])){
			$comments->reminder_date = date('Y/m/d',strtotime($input['reminder-Date']));
		}
		if(isset($input['reminderDate'])){
			$comments->reminder_date = date('Y-m-d H:i:s',strtotime($input['reminderDate']));
		}else{
			$comments->reminder_date = null;
		}	
                //if(isset($input['reminder-Date'])){
		//	$comments->reminder_date = $input['reminder-Date'];
		//}
		$comments->created_by    = Session::get('userId');
		$comments->created_at    = date("Y-m-d H:i:s");
		$comments->save();
		
		return $comments;
	}
        
        
        
        static function addPaymentComments($inputs){
            
         for($i=0;$i<3;$i++){
            
              
            $comments=new Comments();
            $comments->student_id=$inputs['student_id'];
            $comments->customer_id=$inputs['customer_id'];
            $comments->franchisee_id=$inputs['franchisee_id'];
            $comments->followup_type=$inputs['followup_type'];
            $comments->followup_status=$inputs['followup_status'];
            $comments->comment_type=$inputs['comment_type'];
            $comments->created_by=Session::get('userId');
            $comments->created_at= date('Y-m-d H:i:s');
            if($i==0){  
                $comments->log_text="brush up call for the kid";
                $comments->paymentfollowup_id=$inputs['paymentfollowup_id'];
                $comments->reminder_date=$inputs['firstReminderDate'];
                $comments->save();
                }else  
                if($i==1){
                $comments->log_text="Initial payment call for the kid";
                $comments->paymentfollowup_id=$inputs['paymentfollowup_id2'];
                $comments->reminder_date=$inputs['secondReminderDate'];
                $comments->save();
                }else
                if($i==2){
                $comments->log_text="final payment call for the kid";
                $comments->paymentfollowup_id=$inputs['paymentfollowup_id3'];
                $comments->reminder_date=$inputs['thirdReminderDate'];
                $comments->save();
                }
            }
         
           return $comments;
            
        }
        
        static function addOnebiPaymentComment($inputs){
            $comments=new Comments();
            $comments->student_id=$inputs['student_id'];
            $comments->customer_id=$inputs['customer_id'];
            $comments->franchisee_id=$inputs['franchisee_id'];
            $comments->followup_type=$inputs['followup_type'];
            $comments->followup_status=$inputs['followup_status'];
            $comments->comment_type=$inputs['comment_type'];
            $comments->created_by=Session::get('userId');
            $comments->created_at= date('Y-m-d H:i:s');
            $comments->log_text="final payment call for the kid";
            $comments->paymentfollowup_id=$inputs['paymentfollowup_id'];
            $comments->reminder_date=$inputs['reminderDate'];
            $comments->save();
            return $comments;
        }
        static function addSinglePayComment($inputs){
            $comments=new Comments();
            $comments->student_id=$inputs['student_id'];
            $comments->customer_id=$inputs['customer_id'];
            $comments->franchisee_id=$inputs['franchisee_id'];
            $comments->followup_type=$inputs['followup_type'];
            $comments->followup_status=$inputs['followup_status'];
            $comments->comment_type=$inputs['comment_type'];
            $comments->created_by=Session::get('userId');
            $comments->created_at= date('Y-m-d H:i:s');
            $comments->log_text="Brushup call for the kid";
            $comments->paymentfollowup_id=$inputs['paymentfollowup_id'];
            $comments->reminder_date=$inputs['reminderDate'];
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
		
		//$today = date('Y-m-d');
		return Comments::where("franchisee_id", "=", Session::get('franchiseId'))
					->where("reminder_date", "!=", "NULL")
					//->where("reminder_date", "LIKE", "".$today."%")
                                        ->whereDate('reminder_date','=',date("Y-m-d"))
					->count();
					//->get();
		
	}
	
	static function getTodaysFollowup(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		->where("reminder_date", "!=", "NULL")
		//->where("comment_type", "=", "FOLLOW_UP")
		->whereDate("reminder_date", "=", $today)
		->get();
	
	}
	
	
	static function getAllFollowup(){
	
		$today = date('Y-m-d');
		return Comments::with('Customers')->where("franchisee_id", "=", Session::get('franchiseId'))
		//->where("reminder_date", "!=", "NULL")
		->whereDate("reminder_date", "=", $today)
		//->whereIn("comment_type", ["FOLLOW_UP", "CALL_BACK"])		
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
                ->whereDate("reminder_date", "<", $today)
		->orderBy('reminder_date', 'ASC')
		//->where("reminder_date", "LIKE", "".$today."%")
		->get();
	
	}
	
	
	
	
        static function addFollowupForMembership($customerMembershipData){
            
            $brushupReminderdate=  Carbon::now();
            $finalReminderdate=Carbon::now();
            for($i=0;$i<2;$i++){
                if($i==0){
                    $membership_data=MembershipFollowup::createMembershipfollowup($customerMembershipData);
                    
                    $followup=new Comments();
                    $brushupReminderdate=$brushupReminderdate->createFromFormat('Y-m-d',$customerMembershipData['membership_end_date']);
                    $finalReminderdate=$finalReminderdate->createFromFormat('Y-m-d',$customerMembershipData['membership_end_date']);
                    $followup->customer_id=$customerMembershipData['customer_id'];
                    $followup->franchisee_id=Session::get('franchiseId');
                    $followup->membership_followup_id=$membership_data['id'];
                    $followup->followup_type='MEMBERSHIP';
                    $followup->followup_status='FOLLOW_CALL';
                    $followup->comment_type='INTERESTED';
                    $followup->reminder_date='';
                    $followup->created_by=Session::get('userId');
                    $followup->created_at=date('Y-m-d H:i:s');
            
                    $followup->log_text='Brush Up Call for Membership (Memebership End date:'.$customerMembershipData['membership_end_date'].')';
                    $brushupReminderdate=$brushupReminderdate->subWeek();
                    $followup->reminder_date=$brushupReminderdate->toDateString();
                    $followup->save();
                    
                }
                if($i==1){
                    $membership_data=MembershipFollowup::createMembershipfollowup($customerMembershipData);
                    
                   $followup=new Comments();
                   $brushupReminderdate=$brushupReminderdate->createFromFormat('Y-m-d',$customerMembershipData['membership_end_date']);
                   $finalReminderdate=$finalReminderdate->createFromFormat('Y-m-d',$customerMembershipData['membership_end_date']);
                   $followup->customer_id=$customerMembershipData['customer_id'];
                   $followup->franchisee_id=Session::get('franchiseId');
                   $followup->membership_followup_id=$membership_data['id'];
                   $followup->followup_type='MEMBERSHIP';
                   $followup->followup_status='FOLLOW_CALL';
                   $followup->comment_type='INTERESTED';
                   $followup->reminder_date='';
                   $followup->created_by=Session::get('userId');
                   $followup->created_at=date('Y-m-d H:i:s');
            
                    $followup->log_text='Final Call for Membership (Memebership End date:'.$customerMembershipData['membership_end_date'].')';
                    $finalReminderdate=$finalReminderdate->subWeeks(3);
                    $followup->reminder_date=$finalReminderdate->toDateString();
                    $followup->save();
                }
                
                
            }
        }
        
        static function getMembershipHistoryById($membership_Followup_id){
            return Comments::where('membership_followup_id','=',$membership_Followup_id)
                             ->where('franchisee_id','=',Session::get('franchiseId'))
                             ->orderBy('id','DESC')
                             ->get();
        }
	
	
	
}

