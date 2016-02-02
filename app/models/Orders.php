
<?php

class Orders extends \Eloquent {
	protected $table = 'orders';
	protected $fillable = [];
	
	
	public function Customers(){
	
		return $this->belongsTo("Customers", "customer_id");
	}
	
	
	public function Students(){
	
		return $this->belongsTo("Students", "student_id");
	}
	
	public function StudentClasses(){
	
		return $this->belongsTo("StudentClasses", "student_classes_id");
	}
	
	static function createOrder($input){
		
		$order = new Orders();
		
		$order->customer_id     = $input['customer_id'];
		
		if(isset($input['student_id'])){
			$order->student_id      = $input['student_id'];
		}
		
		if(isset($input['student_classes_id'])){
			$order->student_classes_id     = $input['student_classes_id'];
		}
		
		$order->payment_for     = $input['payment_for'];
		$order->payment_dues_id = $input['payment_dues_id'];
		$order->payment_mode    = $input['payment_mode'];
		$order->card_last_digit = $input['card_last_digit'];
		$order->card_type       = $input['card_type'];
		$order->bank_name       = $input['bank_name'];
		$order->cheque_number   = $input['cheque_number'];
		$order->amount          = $input['amount'];
		$order->order_status    = $input['order_status'];
		$order->created_by      = Session::get('userId');
		$order->created_at      = date("Y-m-d H:i:s");
		if(isset($input['membershipType'])){
			$order->membership_type = $input['membershipType'];
		}else{
			$order->membership_type = null;
		}
		
		$order->save();
		
		return $order;
		
	}
	
	
   static function createBOrder($addbirthday,$addPaymentDues,$taxAmtapplied){
   	
		$order = new Orders ();
		$order->customer_id = $addbirthday ['customer_id'];
		$order->student_id = $addbirthday ['student_id'];
		$order->birthday_id = $addbirthday ['id'];
		$order->payment_for = "birthday";
                $order->payment_dues_id=$addPaymentDues['id'];
		// $order->payment_mode = 'cash';
		$order->amount = $addbirthday ['advance_amount_paid'];
                $order->tax_amount=$taxAmtapplied;
		// $order->status = 'completed';
		$order->created_by = Session::get ( 'userId' );
		$order->created_at = date ( "Y-m-d H:i:s" );
		$order->save ();
		return $order->id;
	}
    static function getBirthdayfulldata($customerId){
                $order= Orders:://join('students', 'orders.student_id','=','students.id')
                                //->join('birthday_parties','orders.birthday_id','=','birthday_parties.id')
                                //->join('users','orders.created_by','=','users.id')
                                where('orders.customer_id','=',$customerId)
                                ->where('orders.birthday_id','<>','')
                                ->groupBy('orders.id')
                                ->orderBy('birthday_id','DESC')
                                ->get();
                return $order;
                
    }
    static function createPendingorder($paymentDuedata,$taxamount){
        $order=new Orders();
        $order->customer_id=$paymentDuedata[0]['customer_id'];
        $order->student_id=$paymentDuedata[0]['student_id'];
        $order->birthday_id=$paymentDuedata[0]['birthday_id'];
        $order->payment_dues_id=$paymentDuedata[0]['id'];
        $order->payment_for = "birthday";
        //$taxamount=(int)((14.5/100)*$paymentDuedata[0]['payment_due_amount']);
        $order->tax_amount=$taxamount;
        $order->amount=$paymentDuedata[0]['payment_due_amount'];
        $order->created_by = Session::get ( 'userId' );
        $order->created_at = date ( "Y-m-d H:i:s" );

        $order->save ();
        return $order;
    }
    static function createPendingOrderForEnrollment($paymentDuedata){
        $order=new Orders();
        $order->customer_id= $paymentDuedata[0]['customer_id'];
        $order->student_id=$paymentDuedata[0]['student_id'];
        $order->student_classes_id=$paymentDuedata[0]['student_class_id'];
        $order->amount=$paymentDuedata[0]['payment_due_amount'];
        $order->payment_for = "enrollment";
        $order->payment_dues_id=$paymentDuedata[0]['id'];
        $order->created_by = Session::get ( 'userId' );
        $order->created_at = date ( "Y-m-d H:i:s" );

        $order->save ();
        return $order;
    }
    static function getAllPaymentsMade($studentId){
        return Orders:: where ('student_id','=',$studentId)
                       ->where('payment_for','=','enrollment')
                       ->where('order_status','=','completed')
                       ->orderBy('id', 'DESC')
                       ->get();
    }
    static function getpendingPaymentsid($studentId){
        return Orders::where ('student_id','=',$studentId)
                     ->where('payment_for','=','enrollment')
                     ->where('order_status','=','completed')
                     ->distinct('payment_dues_id')->get();
    }
    static function getOrderDetailsbyPaydueId($paydueId){
        return Orders::where('payment_dues_id','=',$paydueId)->get();
    }
}