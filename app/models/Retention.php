<?php

class Retention extends \Eloquent {
	protected $fillable = [];
        protected  $table='retention';
        
     static function createRetention($input){
           $retention=new Retention();
           $retention->customer_id=$input['customer_id'];
           $retention->student_id=$input['student_id'];
           $retention->franchisee_id=Session::get('franchiseId');
           $retention->created_at= date("Y-m-d H:i:s");
           $retention->created_by    = Session::get('userId');
           $retention->save();
           return $retention;
     }
     static function getRetentionByCustomerId($customerId){
            return Retention::where('customer_id','=',$customerId)
                             ->where('franchisee_id','=',Session::get('franchiseId'))
                             ->get();
        }
}