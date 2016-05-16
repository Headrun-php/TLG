<?php

class Inquiry extends \Eloquent {
	protected $fillable = [];
        protected $table='inquiry';
        
    static function createInquiry($input){
           $inquiry=new Inquiry();
           $inquiry->customer_id=$input['customer_id'];
          // $inquiry->student_id=$input['student_id'];
           $inquiry->franchisee_id=Session::get('franchiseId');
           $inquiry->created_at= date("Y-m-d H:i:s");
           $inquiry->created_by    = Session::get('userId');
           $inquiry->save();
           return $inquiry;
    }
    static function getInquiryByCustomerId($customerId){
            return Inquiry::where('customer_id','=',$customerId)
                             ->where('franchisee_id','=',Session::get('franchiseId'))
                             ->get();
        }
}