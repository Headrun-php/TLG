<?php

class Complaint extends \Eloquent {
	protected $fillable = [];
        protected $table='complaints';
        
        static function createComplaint($input){
           $complaint=new Complaint();
           $complaint->customer_id=$input['customer_id'];
           $complaint->student_id=$input['student_id'];
           $complaint->franchisee_id=Session::get('franchiseId');
           $complaint->created_at= date("Y-m-d H:i:s");
           $complaint->created_by    = Session::get('userId');
           $complaint->save();
           return $complaint;
           
        }
        static function getComplaintByCustomerId($customerId){
            return Complaint::where('customer_id','=',$customerId)
                             ->where('franchisee_id','=',Session::get('franchiseId'))
                             ->get();
        }
}