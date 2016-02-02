<?php
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
class CustomersController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (Auth::check ()) {
			
			$currentPage = "CUSTOMERS_LIST";
			$mainMenu = "CUSTOMERS_MAIN";
			
			$customers = Customers::getAllCustomersByFranchiseeId ( Session::get ( 'franchiseId' ) );
			$provinces = Provinces::getProvinces ( "IN" );
			$viewData = array (
					'customers',
					'currentPage',
					'mainMenu',
					'provinces' 
			);
			return View::make ( 'pages.customers.customerslist', compact ( $viewData ) );
		} else {
			return Redirect::to ( "/" );
		}
	}
	
	
	public function add() {
		if (Auth::check ()) {
				
			$currentPage = "CUSTOMERS_ADD";
			$mainMenu = "CUSTOMERS_MAIN";
			$inputs = Input::all ();
			if (isset ( $inputs ['customerName'] )) {
				
				/* echo "<pre>";
				print_r($inputs);
				echo "</pre>";
				exit(); */
				
				$addCustomerResult = Customers::addCustomers ( $inputs );
	
				if ($addCustomerResult) {
						
					//if($inputs['customerCommentTxtarea'] != ""){
						$commentsInput['customerId']     = $addCustomerResult->id;
						$commentsInput['commentText']    = Config::get('constants.INITIATED_COMMENT').' '.$inputs['customerCommentTxtarea'];
						$commentsInput['commentType']    = 'FOLLOW_UP';
						if($inputs['reminderTxtBox'] == ''){
							$commentsInput['reminderDate']   = null;
						}else{
							$commentsInput['reminderDate']   = $inputs['reminderTxtBox'];
						}
						Comments::addComments($commentsInput);
					//}
					
					//Membership 
					if($inputs['membershipType'] != ""){
						
						$membershipInput['customer_id']           = $addCustomerResult->id;
						$membershipInput['membership_type_id']    = $inputs['membershipType'];						
						CustomerMembership::addMembership($membershipInput);
						
						$order['customer_id']     = $addCustomerResult->id;
						$order['payment_for']     = "membership";
						$order['payment_dues_id'] = '';
						$order['payment_mode']    = $inputs['paymentTypeRadio'];
						$order['card_last_digit'] = $inputs['card4digits'];
						$order['card_type']       = $inputs['cardType'];
						$order['bank_name']       = $inputs['bankName'];
						$order['cheque_number']   = $inputs['chequeNumber'];
						$order['amount']          = $inputs['membershipPrice'];
						$order['order_status']      = "completed";
						Orders::createOrder($order);
					}
					
					//Upload Image
					if(Input::file('profileImage')){
						
						$file = Input::file('profileImage');
						$destinationPath = 'upload/profile/customer/';
						$filename = $file->getClientOriginalName();
						$fileExtension = '.'.$file->getClientOriginalExtension();
						$customerId = $addCustomerResult->id;
						$filename = 'customer_profile_'.$customerId.'_medium'.$fileExtension;
						$result = Input::file('profileImage')->move($destinationPath, $filename);
						
						if($result){
						
							$customer = Customers::find($customerId);
							$customer->profile_image = $filename;
							$customer->save();
						
						}
					}
					
					
					Session::flash ( 'msg', "Customer account created successfully." );
						
					return Redirect::to ( 'customers/view/' . $addCustomerResult->id );
				} else {
					Session::flash ( 'warning', "Sorry, Customer Could not be added at the moment." );
				}
			}
			
			$provinces = Provinces::getProvinces ( "IN" );
			$membershipTypes = MembershipTypes::getMembershipTypes();
			$viewData = array (
					'currentPage',
					'mainMenu',
					'provinces',
					'membershipTypes'
			);
			return View::make ( 'pages.customers.customeradd', compact ( $viewData ) );
		} else {
			return Redirect::to ( "/" );
		}
	}
	
	
	
	
	public function details($id) {
		
		if(Auth::check()){
			$currentPage = "CUSTOMERS_LIST";
			$mainMenu = "CUSTOMERS_MAIN";
			
			$inputs = Input::all ();
			if (isset ( $inputs ['customerName'] )) {
				if (Customers::addCustomers ( $inputs )) {
					Session::flash ( 'msg', "Customer added successfully." );
				} else {
					Session::flash ( 'warning', "Customer, Course Could not be added at the moment." );
				}
			}
			$customer = Customers::getCustomersById ( $id );
			$students = Students::getStudentByCustomer ( $id );
			$comments = Comments::getCommentByCustomerId ( $id );
			$provinces = Provinces::getProvinces ( "IN" );
			$kidsSelect = Students::getStudentsForSelectBox($id);
			$membershipTypes = MembershipTypes::getMembershipTypesForSelectBox();
			$birthdays = BirthdayParties::getBirthdaysByCustomer($id);
			
			
			
			
			//Membership
			if (isset ($inputs['membershipTypesMembersDiv'])){
				
				/* echo '<pre>';
				print_r($inputs);
				echo '</pre>';
				exit(); */
				if($inputs['membershipTypesMembersDiv'] != ""){
					
				
					$membershipInput['customer_id']           = $id;
					$membershipInput['membership_type_id']    = $inputs['membershipTypesMembersDiv'];
					CustomerMembership::addMembership($membershipInput);
				
					$order['customer_id']     = $id;
					$order['payment_for']     = "membership";
					$order['payment_dues_id'] = '';
					$order['payment_mode']    = $inputs['paymentTypeRadio'];
					$order['card_last_digit'] = $inputs['card4digits'];
					$order['card_type']       = $inputs['cardType'];
					$order['bank_name']       = $inputs['bankName'];
					$order['cheque_number']   = $inputs['chequeNumber'];
					$order['amount']          = $inputs['membershipPrice'];
					$order['order_status']      = "completed";
					Orders::createOrder($order);
				}
			}
			//$customerMembership = "";
			
			/* echo '<pre>';
			print_r($customer);
			echo '</pre>';
			exit(); */
			
			$customerMembershipId = '';
			if(isset($customer->CustomerMembership['0'])){
				$customerMembershipId = $customer->CustomerMembership['0']->membership_type_id;
			}
			$customerMembership = MembershipTypes::getMembershipTypeByID($customerMembershipId);
			$membershipTypesAll = MembershipTypes::getMembershipTypes();
			
                        $birthdaypaiddata =Orders::getBirthdayfulldata($id); 
                       for($i=0;$i<count($birthdaypaiddata);$i++){
                           $studentData=  Students::getStudentById($birthdaypaiddata[$i]['student_id']);
                           $birthdaypaiddata[$i]['student_name']=$studentData[0]['student_name'];
                           $birthdaypaiddata[$i]['student_date_of_birth']=$studentData[0]['student_date_of_birth'];  
                           
                           $birthdayData=BirthdayParties::getBirthdaybyId($birthdaypaiddata[$i]['birthday_id']);
                           $birthdaypaiddata[$i]['birthday_party_date']=$birthdayData[0]['birthday_party_date'];
                           $birthdaypaiddata[$i]['tax_amount']=$birthdaypaiddata[0]['tax_amount'];
                           $user_data=User::getUsersByUserId($birthdaypaiddata[$i]['created_by']);
                           $birthdaypaiddata[$i]['name']=$user_data[0]['first_name'].$user_data[0]['last_name'];
                           $birthdaypaiddata[$i]['encrypted_id']=Crypt::encrypt($birthdaypaiddata[$i]['id']);
                       } 
                       
                       $birthdayDuedata=  PaymentDues::getPaymentpendingfulldata($id);
                         for($i=0;$i<count($birthdayDuedata);$i++){
                             $studentData=Students::getStudentById($birthdayDuedata[$i]['student_id']);
                             $birthdayDuedata[$i]['student_name']=$studentData[0]['student_name'];
                             $user_data=User::getUsersByUserId($birthdayDuedata[$i]['created_by']);
                             $birthdayDuedata[$i]['name']=$user_data[0]['first_name'].$user_data[0]['last_name'];
                             $birthdayData=BirthdayParties::getBirthdaybyId($birthdayDuedata[$i]['birthday_id']);
                             $birthdayDuedata[$i]['birthday_party_date']=$birthdayData[0]['birthday_party_date'];
                             
                         }
			$viewData = array (
                                        'birthdaypaiddata',
                                        'birthdayDuedata',
					'customer',
					'students',
					'currentPage',
					'mainMenu',
					'comments',
					'provinces',
					'customerMembership',
					'kidsSelect',
					'membershipTypes',
					'membershipTypesAll',
					'birthdays'
			);
			return View::make ( 'pages.customers.details', compact ( $viewData ) );
		}else{
			return Redirect::to("/");
		}
	}
	
	public function uploadProfilePicture(){
	
		$file = Input::file('profileImage');
		$destinationPath = 'upload/profile/customer/';
		$filename = $file->getClientOriginalName();
		$fileExtension = '.'.$file->getClientOriginalExtension();
		$customerId = Input::get('customerId');
		$filename = 'customer_profile_'.$customerId.'_medium'.$fileExtension;
		$result = Input::file('profileImage')->move($destinationPath, $filename);
	
		if($result){
	
			$customer = Customers::find($customerId);
			$customer->profile_image = $filename;
			$customer->save();
	
		}
	
		Session::flash ( 'imageUploadMessage', "Profile picture updated successfully." );
		return Redirect::to("/customers/view/".$customerId);
	
	
	
	}
	
	public function checkCustomerExists(){
		$inputs = Input::all();		
		$customer = Customers::getCustomerByEmail($inputs['email']);		
		if(isset($customer['0'])){
			return Response::json(array("status"=>"exists"));
		}
		return Response::json(array("status"=>"clear"));
	}
	
	
	
	public function editCustomer() {
		
		$inputs = Input::all();
		
		if (isset ( $inputs ['customerName'] )) {
			$editCustomerResult = Customers::saveCustomers ( $inputs );
			if($editCustomerResult){
				return Response::json(array("status"=>"success"));
			}
			return Response::json(array("status"=>"failed"));
			
		}
		
	}
	
	
	
		/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function show($id) {
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function edit($id) {
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
		//
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function destroy($id) {
		//
	}
}
