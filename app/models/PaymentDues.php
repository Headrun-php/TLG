<?php

class PaymentDues extends \Eloquent { 
	protected $table = 'payments_dues';
	protected $fillable = [];
	
	public function Batches(){
		
		return $this->belongsTo('Batches', 'batch_id');
	}
	
	public function Orders(){
	
		return $this->belongsTo('Orders', 'batch_id');
	}
	
	static function createPaymentDues($inputs){
		
		$paymentDues = new PaymentDues();
		$paymentDues->student_id           = $inputs['student_id'];
		$paymentDues->season_id            = $inputs['seasonId'];
		$paymentDues->customer_id          = $inputs['customer_id'];
		$paymentDues->batch_id             = $inputs['batch_id'];
		$paymentDues->class_id             = $inputs['class_id'];
                $paymentDues->student_class_id     =$inputs['student_class_id'];
		$paymentDues->payment_due_amount   = $inputs['payment_due_amount'];
		$paymentDues->payment_type         = $inputs['payment_type'];
                $paymentDues->payment_due_for      = 'enrollment';
		$paymentDues->payment_status       = $inputs['payment_status'];
		$paymentDues->selected_sessions    = $inputs['selected_sessions'];
                if(isset($inputs['each_class_cost'])){
                  $paymentDues->each_class_amount=$inputs['each_class_cost'];
                }
                if(isset($inputs['selected_order_sessions'])){
                $paymentDues->selected_order_sessions    = $inputs['selected_order_sessions'];
                }
                if(isset($inputs['start_order_date'])){
                $paymentDues->start_order_date     =$inputs['start_order_date'];
                }
                if(isset($inputs['end_order_date'])){
                $paymentDues->end_order_date=$inputs['end_order_date'];
                }
                if(isset($inputs['discount_amount'])){
                $paymentDues->discount_amount=$inputs['discount_amount'];
                }
                $paymentDues->discount_applied     = $inputs['discount_applied'];
		$paymentDues->created_by              = Session::get('userId');
                if(isset($inputs['created_at'])){
                    $paymentDues->created_at = $inputs['created_at'];
                }else{
		$paymentDues->created_at              = date("Y-m-d H:i:s");
                }
                $paymentDues->save();
		
		return $paymentDues;
		
	}
	
	static function getAllPaymentDuesByStudent($studentId){
		
		return PaymentDues::with('Batches')->where('student_id','=',$studentId)->get();
		
		
	}
                                    
        static function getAllDue($studentId){
            return PaymentDues::
                                      where('student_id','=',$studentId)
                                    ->where('payment_status','=','pending')
                                    ->where('payment_due_for','=','enrollment')
                                    ->Orderby('id','DESC')
                                    ->get();
        }
        static function createBirthdaypaymentFirstdues($addBirthday){
                $paymentDues = new PaymentDues();
		$paymentDues->student_id           = $addBirthday['student_id'];
                $paymentDues->birthday_id          = $addBirthday['id'];
		$paymentDues->customer_id          = $addBirthday['customer_id'];
		$paymentDues->payment_due_amount   = $addBirthday['advance_amount_paid'];
                if(isset($addBirthday['membership_id'])){
                    $paymentDues->membership_id=$addBirthday['membership_id'];
                }
                if(isset($addBirthday['membership_amount'])){
                    $paymentDues->membership_amount=$addBirthday['membership_amount'];
                }
                
		$paymentDues->payment_type         = 'bipay';
                $paymentDues->payment_status       = 'paid';
		//$paymentDues->discount_applied     = $taxAmtapplied;
                $paymentDues->payment_due_for       ="birthday";
		$paymentDues->created_by           = Session::get('userId');
		$paymentDues->created_at           = date("Y-m-d H:i:s");
		$paymentDues->save();		
		return $paymentDues;
        }
        static function createBirthdaypaymentdues($addBirthday){
                $paymentDues = new PaymentDues();
		$paymentDues->student_id           = $addBirthday['student_id'];
                $paymentDues->birthday_id          = $addBirthday['id'];
		$paymentDues->customer_id          = $addBirthday['customer_id'];
                if(isset($addBirthday['membership_id'])){
                    $paymentDues->membership_id=$addBirthday['membership_id'];
                }
                if(isset($addBirthday['membership_amount'])){
                    $paymentDues->membership_amount=$addBirthday['membership_amount'];
                }
		$paymentDues->payment_due_amount   = $addBirthday['remaining_due_amount'];
		$paymentDues->payment_type         = 'bipay';
                $paymentDues->payment_status       = 'pending';
		//$paymentDues->discount_applied     = $taxAmtapplied;
                $paymentDues->payment_due_for       ="birthday";
		$paymentDues->created_by           = Session::get('userId');
		$paymentDues->created_at           = date("Y-m-d H:i:s");
		$paymentDues->save();		
		return $paymentDues;
        }
        static function getPaymentpendingfulldata($id){
            return PaymentDues:: where ('customer_id','=',$id)
                                 ->where ('payment_status','=','pending')
                                 ->where('payment_due_for','=','birthday')
                                 ->Orderby('id','DESC')
                                 ->get();
            
        }
        static function getPaymentpendingdata($id){
            return PaymentDues::where('id','=',$id)
                    ->where('payment_status','=','pending')
                    ->get();
        }
        static function changeStatustopaid($pendingId,$discountAmount){
            $pending=PaymentDues::where('id','=',$pendingId)->update(array('payment_status'=> 'paid','discount_amount'=>$discountAmount));
            return $pending;
            
    }
    static function getAllDuebyStudentId($student_id){
        return PaymentDues:: where('student_id','=',$student_id)
                            ->where('payment_due_for','=','enrollment')
                            ->where('payment_status','=','pending')
                            ->where('student_class_id','!=','0')
                           ->get();
    }
    static function getAllPaymentsMade($student_id){
        return PaymentDues::where('student_id','=',$student_id)
                            ->where('payment_due_for','=','enrollment')
                            ->where('payment_status','=','paid')
                            ->where('student_class_id','!=','0')
                            ->get();
    }
}