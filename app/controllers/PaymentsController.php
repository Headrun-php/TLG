<?php
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Crypt;
class PaymentsController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {

		$inputs = Input::all ();
		$availableSession = $inputs ['availableSession'];
		
		$paymentTypes = array ();
		
		$modulus = "";
		$round = "";
		if ($availableSession > 30) {
			
			$modulus = $availableSession % 20;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			
			$bipayInstallments = 2;
			$bipayFirstInstallment = $modulus;
			$bipaySecondInstallment = 20;
			
			$arrayCount ['bipay'] ['eligible'] = "YES";
			$arrayCount ['bipay'] ['installments'] = 2;
			if ($modulus) {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 20;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (20 * 500);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * 500);
			
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			
			$arrayCount ['multipay'] ['eligible'] = "YES";
			$arrayCount ['multipay'] ['installments'] = 4;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * 500);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * 500);
			
			$arrayCount ['multipay'] ['pays'] ['3'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['3'] ['amount'] = (10 * 500);
		} else if ($availableSession > 10 && $availableSession <= 20) {
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			$bipayInstallments = 2;
			$bipayFirstInstallment = $modulus;
			$bipaySecondInstallment = 10;
			
			$arrayCount ['bipay'] ['eligible'] = "NO";
			$arrayCount ['multipay'] ['eligible'] = "YES";
			$arrayCount ['multipay'] ['installments'] = $bipayInstallments;
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = $bipaySecondInstallment;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = ($bipaySecondInstallment * 500);
		} else if ($availableSession > 20 && $availableSession <= 30) {
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			
			$bipayInstallments = 2;
			$bipayFirstInstallment = $modulus;
			$bipaySecondInstallment = 10;
			
			$arrayCount ['bipay'] ['eligible'] = "YES";
			$arrayCount ['bipay'] ['installments'] = 2;
			if ($modulus) {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * 500);
			
			$arrayCount ['multipay'] ['eligible'] = "YES";
			$arrayCount ['multipay'] ['installments'] = 3;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * 500);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * 500);
		} else if ($availableSession <= 10) {
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			
			$bipayInstallments = 2;
			$bipayFirstInstallment = $modulus;
			$bipaySecondInstallment = 10;
			
			$arrayCount ['bipay'] ['eligible'] = "NO";
			$arrayCount ['bipay'] ['installments'] = 2;
			if ($modulus) {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * 500);
			
			$arrayCount ['multipay'] ['eligible'] = "NO";
			$arrayCount ['multipay'] ['installments'] = 3;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * 500);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * 500);
		
		} else if ($availableSession === 10) {
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
				
			$bipayInstallments = 2;
			$bipayFirstInstallment = $modulus;
			$bipaySecondInstallment = 10;
				
			$arrayCount ['bipay'] ['eligible'] = "NO";
			$arrayCount ['bipay'] ['installments'] = 2;
			if ($modulus) {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * 500);
				
			$arrayCount ['multipay'] ['eligible'] = "NO";
			$arrayCount ['multipay'] ['installments'] = 3;
				
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * 500);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * 500);
			}
				
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * 500);
				
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * 500);
		}
		
		$arrayCount ['round'] = $round;
		$arrayCount ['modulus'] = $modulus;
		$arrayCount ['singlepay'] = $availableSession * 500;
		$arrayCount ['status'] = "success";
		
		if ($arrayCount) {
			return Response::json ( array (
					"payments" => $arrayCount 
			) );
		}
		return Response::json ( array (
				"status" => "failed" 
		) );
	}
	
	
	
	
	public function printOrder($orderid){
		
		$id = Crypt::decrypt($orderid);
		
		$orders = Orders::with('Customers', 'Students', 'StudentClasses')->where('id', '=', $id)->get();
		$orders = $orders['0'];
		$paymentDues = PaymentDues::where('id', '=', $orders->payment_dues_id)->get();
		$batchDetails = Batches::where('id', '=', $orders->StudentClasses->batch_id)->get();
		$class = Classes::where('id', '=', $orders->StudentClasses->class_id)
		->where('franchisee_id', '=', Session::get('franchiseId'))->first();
		$customerMembership = CustomerMembership::getCustomerMembership($orders->customer_id);
	
	
	
	
/* 	echo "<pre>";
	 print_r($paymentDues);
	exit(); */  
	
	$data = compact('orders','class', 'paymentDues', 'batchDetails','customerMembership');
		
		//$data = compact('orders','class');
		
		return View::make('pages.orders.printorder', $data);
		
		
	}
	
	
	public function printBdayOrder($oid) {
		$orderid = Crypt::decrypt($oid);
		$order_data = Orders::where ( 'orders.id', '=', $orderid )->get();
		$customer_data = Customers::where ( 'id', '=', $order_data [0] ['customer_id'] )->get ();
		$birthday_data = BirthdayParties::where ( 'id', '=', $order_data [0] ['birthday_id'] )->get ();
		$student_data = Students::where ( 'id', '=', $order_data [0] ['student_id'] )->get ();
		$order_data = $order_data [0];
		$customer_data = $customer_data [0];
		$birthday_data = $birthday_data [0];
		$student_data = $student_data [0];
		$data = array (
				'order_data',
				'customer_data',
				'birthday_data',
				'student_data' 
		);
		
		// print_r($data);
		return View::make ( 'pages.orders.bdayprintorder', compact ( $data ) );
	}

	
}
