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
                $batch_data=  Batches::find($inputs['batchId']);
                $eachClassCost=$batch_data->class_amount;
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
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 20;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (20 * $eachClassCost);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * $eachClassCost);
			
			$modulus = $availableSession % 10;
			if ($modulus) {
				$round = ($availableSession - $modulus);
			}
			
			$arrayCount ['multipay'] ['eligible'] = "YES";
			$arrayCount ['multipay'] ['installments'] = 4;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * $eachClassCost);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * $eachClassCost);
			
			$arrayCount ['multipay'] ['pays'] ['3'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['3'] ['amount'] = (10 * $eachClassCost);
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
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = $bipaySecondInstallment;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = ($bipaySecondInstallment * $eachClassCost);
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
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * $eachClassCost);
			
			$arrayCount ['multipay'] ['eligible'] = "YES";
			$arrayCount ['multipay'] ['installments'] = 3;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * $eachClassCost);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * $eachClassCost);
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
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * $eachClassCost);
			
			$arrayCount ['multipay'] ['eligible'] = "NO";
			$arrayCount ['multipay'] ['installments'] = 3;
			
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * $eachClassCost);
			
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * $eachClassCost);
		
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
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['bipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['bipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
			$arrayCount ['bipay'] ['pays'] ['1'] ['dues'] = 20;
			$arrayCount ['bipay'] ['pays'] ['1'] ['amount'] = (20 * $eachClassCost);
				
			$arrayCount ['multipay'] ['eligible'] = "NO";
			$arrayCount ['multipay'] ['installments'] = 3;
				
			if ($modulus) {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = $modulus;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = ($modulus * $eachClassCost);
			} else {
				$arrayCount ['multipay'] ['pays'] ['0'] ['dues'] = 10;
				$arrayCount ['multipay'] ['pays'] ['0'] ['amount'] = (10 * $eachClassCost);
			}
				
			$arrayCount ['multipay'] ['pays'] ['1'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['1'] ['amount'] = (10 * $eachClassCost);
				
			$arrayCount ['multipay'] ['pays'] ['2'] ['dues'] = 10;
			$arrayCount ['multipay'] ['pays'] ['2'] ['amount'] = (10 * $eachClassCost);
		}
		
		$arrayCount ['round'] = $round;
		$arrayCount ['modulus'] = $modulus;
		$arrayCount ['singlepay'] = $availableSession * $eachClassCost;
		$arrayCount ['status'] = "success";
		
                
                //working for simple bipay  bipay=(enrolled classes/2)
                if($availableSession>5){
                    $arrayCount['modifiedbipay']['elligible']='YES';
                    $arrayCount['modifiedbipay']['installments']=2;
                    if($availableSession%2==0){
                     $arrayCount['modifiedbipay']['pays']['0']['dues']=$availableSession/2;
                     $arrayCount['modifiedbipay']['pays']['0']['amount']=($availableSession/2)*$eachClassCost;
                     $arrayCount['modifiedbipay']['pays']['1']['dues']=$availableSession/2;
                     $arrayCount['modifiedbipay']['pays']['1']['amount']=($availableSession/2)*$eachClassCost;
                    }else{
                     $firstsessioncount=(int)($availableSession/2);
                     $secondsessioncount=((int)($availableSession/2))+1;
                     $arrayCount['modifiedbipay']['pays']['0']['dues']=$firstsessioncount;
                     $arrayCount['modifiedbipay']['pays']['0']['amount']=$firstsessioncount*$eachClassCost;
                     $arrayCount['modifiedbipay']['pays']['1']['dues']=$secondsessioncount;
                     $arrayCount['modifiedbipay']['pays']['1']['amount']=$secondsessioncount*$eachClassCost;
                    }
                }
                
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
                if(isset($order_data['payment_dues_id'])){
                $payment_due_data=  PaymentDues::where('id','=',$order_data['payment_dues_id'])->get();
                $payment_due_data=$payment_due_data[0];
                     if(isset($payment_due_data->membership_id)){
                        $membershipData=  CustomerMembership::find($payment_due_data->membership_id);
                        $membershipTypeData=  MembershipTypes::getMembershipTypeByID($membershipData->membership_type_id);
                        $payment_due_data->description=$membershipTypeData->description;
                     }
                }
		$customer_data = $customer_data [0];
		$birthday_data = $birthday_data [0];
		$student_data = $student_data [0];
		$data = array (
				'order_data',
				'customer_data',
				'birthday_data',
				'student_data',
                                'payment_due_data',
		);
		
		// print_r($data);
		return View::make ( 'pages.orders.bdayprintorder', compact ( $data ) );
	}


  public static function addorviewprices(){
      if(Auth::check()){
          $inputs=Input::all();
          $currentPage = "AddPrices_LI";
          $mainMenu = "DISCOUNTS_MENU_MAIN";
          if(isset($inputs['base_price'])){
              ClassBasePrice::insertBasePrice($inputs);
              return Redirect::action('PaymentsController@addorviewprices');
          }
          $base_price_data=ClassBasePrice::getBasePricebyFranchiseeId();
          for($i=0;$i<count($base_price_data);$i++){
              $user=User::find($base_price_data[$i]['created_by']);
              $base_price_data[$i]['created_by']=$user->first_name.$user->last_name;
              if($base_price_data[$i]['updated_by']!=0){
                  $user1=User::find($base_price_data[$i]['updated_by']);
                  $base_price_data[$i]['updated_by']=$user1->first_name.$user1->last_name;
              }
          }
          $data=array('currentPage','mainMenu','base_price_data');
         return View::make('pages.prices.add_or_view_prices',compact($data));
      }
  }      
  
  public static function deletebaseprice(){
      if(Auth::check()){
          $inputs=Input::all();
          ClassBasePrice::where('id','=',$inputs['baseprice_id'])->delete();
          return Response::json(array('status'=>'success'));
      }
  }
  
  public static function updatebaseprice(){
      if(Auth::check()){
          $inputs=Input::all();
          ClassBasePrice::where('id','=',$inputs['baseprice_id'])->update(array('base_price'=>$inputs['base_price'],'updated_by'=>Session::get('userId')));
          return Response::json(array('status'=>'success'));
      }
  }
        
}

