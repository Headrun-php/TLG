<?php

class PaymentReminders extends \Eloquent {
	protected $fillable = [];
	protected $table = 'payment_reminders';
	
	
	public function Classes(){
		
		return $this->belongsTo('Classes', 'class_id');
	}
	
	
	static function addReminderDates($inputs){
		
		
		$PaymentReminder = new PaymentReminders();
		
                if(isset($inputs['seasonId'])){
                    $PaymentReminder->season_id         =$inputs['seasonId'];
                }
                if(isset($inputs['classId']) && isset($inputs['batchId']) && isset($inputs['reminder_date'])){
                $PaymentReminder->customer_id       = $inputs['customerId'];
		
                $PaymentReminder->student_id        = $inputs['studentId'];
                $PaymentReminder->season_id        = $inputs['seasonId'];
                $PaymentReminder->enrolled_class_id = $inputs['classId'];    
                $PaymentReminder->enrolled_batch_id = $inputs['batchId'];
                $PaymentReminder->reminder_date     = $inputs['reminder_date'];
                }else{
                    if(isset($inputs['birthday_party_date'])&& isset($inputs['id'])){
                        $PaymentReminder->customer_id       = $inputs['customer_id'];
                        $PaymentReminder->student_id        = $inputs['student_id'];
                        $PaymentReminder->reminder_date     = $inputs['birthday_party_date'];
                    }
                }
		$PaymentReminder->created_by        = Session::get('userId');
		$PaymentReminder->created_at        = date("Y-m-d H:i:s");
		$PaymentReminder->save();		
	}
	
}