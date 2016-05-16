<?php

class PaymentFollowups extends \Eloquent {
	protected $fillable = [];
        protected $table='payment_followup';
        
        static function createPaymentFollowup($payment_due,$payment_no){
            $payment_followup=new PaymentFollowups();
            $payment_followup->customer_id=$payment_due->customer_id;
            $payment_followup->student_id=$payment_due->student_id;
            $payment_followup->season_id=$payment_due->season_id;
            $payment_followup->payment_no=$payment_no;
            //$payment_followup->payment_due_id=$payment_due->id;
            $payment_followup->created_at=date("Y-m-d H:i:s");
            $payment_followup->created_by=Session::get('userId');
            $payment_followup->save();
            return $payment_followup;
        } 
        
        static function getPaymentFollowupByCustomerId($customerId){
            return PaymentFollowups::where('customer_id','=',$customerId)
                                    ->orderBy('id','DESC')
                                    ->get();
        }
        
       
}