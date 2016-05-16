<?php

class PaymentMaster extends \Eloquent {
	protected $fillable = [];
        protected $table='payment_master';
        
  public static function createPaymentMaster($inputs){
      $paymentmaster=new PaymentMaster();
      $paymentmaster->payment_no=(PaymentMaster::max('payment_no'))+1;
      $paymentmaster->payment_due_id=$inputs->id;
      $paymentmaster->created_by=Session::get('userId');
      $paymentmaster->save();
      return $paymentmaster;
  }
  public static function createPaymentMasterWithSamePaymentNo($inputs,$payment_no){
      $paymentmaster=new PaymentMaster();
      $paymentmaster->payment_no=$payment_no;
      $paymentmaster->payment_due_id=$inputs->id;
      $paymentmaster->created_by=Session::get('userId');
      $paymentmaster->save();
      return $paymentmaster;
      
  }
  
}