<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Publications extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->helper('url');		
		$this->load->helper('form');
		$this->load->library('crypto');
		
		$this->load->model('locations_model');
		$this->load->model('common_model');
		$this->load->model('publications_model');
		
		$countries = $this->locations_model->getCountries();
		$membershipCategories = $this->common_model->getMembershipCategories();
		
		
		$data['result']['countries'] = $countries;
		$data['result']['membershipCategories'] = $membershipCategories;
		
		$data['result']['ERROR_MESSAGE'] = '';
		$data['result']['SUCCESS_MESSAGE'] = '';
		$data['result']['posted'] = array();
		

		$this->load->library('session');
		$data['user_data'] = $this->session->all_userdata();
		
		
		if($this->input->post('memberId')){
			
			/*
			$this->load->library('form_validation');
			$this->load->library('crypto');
			
			/* $this->form_validation->set_rules('fullName', 'First Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|email|required|xss_clean');
			$this->form_validation->set_rules('officialAddress', 'Official Address', 'trim|required|xss_clean');
			$this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
			$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
			$this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean');
			$this->form_validation->set_rules('publicationName', 'Publication Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('publicationType', 'Publication Type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('publicationSubCategory', 'Publication SubCategory', 'trim|required|xss_clean');
			$this->form_validation->set_rules('subscriptionFee', 'Publication Subscription', 'trim|required|xss_clean');
			 */
			$postedData = array();
			
			/* if ($this->form_validation->run() == FALSE) {
				 
				echo 'Validation false';
				echo '<pre>';
				echo "Error List - ".validation_errors();
				echo '</pre>'; 
				$data['result']['ERROR_MESSAGE'] = '<div class="alert alert-danger">All the fields are required. Please fill all the fields marked as *</div>';
				$data['result']['SUCCESS_MESSAGE'] = '';
				$data['result']['ERROR_LIST'] = validation_errors();
			
			} else { */
				
				/* $randomText = $this->crypto->generateRandomString(6);
				$password = $this->crypto->encrypt($randomText);
					
				$postedData['first_name'] = $this->input->post('fullName');
				$postedData['last_name'] = $this->input->post('lastName');				
				$postedData['email'] = $this->input->post('email');
				$postedData['mobile'] = $this->input->post('mobileNumber');
				$postedData['password'] = $password;
				$postedData['official_address'] = $this->input->post('officialAddress');
				$postedData['pincode'] = $this->input->post('pincode');
				$postedData['city'] = $this->input->post('city');
				$postedData['state'] = $this->input->post('state');
				$postedData['country'] = $this->input->post('country');
				$postedData['prant'] = $this->input->post('prant');				
				$postedData['publication_category_id'] = $this->input->post('publicationCategory');
				$postedData['publication_subcategory_id'] = $this->input->post('publicationSubCategory');
				$postedData['publication_subscription'] = $this->input->post('subscriptionFee');
				$postedData['publication_name'] = $this->input->post('publicationName');
				$postedData['publication_type'] = $this->input->post('publicationType'); */
			
				$this->load->model('membership_model');
			
				$memberDetails = $this->membership_model->getMemberDetails($this->input->post('memberId'));
				
				
				//$insertResult = $this->publications_model->createPublication($postedData);
				
				if($memberDetails){
					
					$ordersArray['publications_id']            = $memberDetails['memberId'];					
					$ordersArray['publication_category_id']    = $this->input->post('publicationCategory');
					$ordersArray['publication_sub_category_id'] = $this->input->post('publicationSubCategory');
					$ordersArray['publication_subscription_id']   = $this->input->post('subscriptionFee');
					$ordersArray['registration_type']          = "ONLINE";
					/* $ordersArray['payment_type']               = $this->input->post('paymentMethod');
					$ordersArray['order_amount']               = $this->input->post('amountPaid');					
					$ordersArray['transaction_id']             = $this->input->post('transactionId');
					$ordersArray['payment_date']               = $this->input->post('paymentDate');
					$ordersArray['bank_name']                  = $this->input->post('bankName'); */
					$ordersArray['created_date']               = date('Y-m-d H:i:s');
					$ordersArray['order_status']               = 'INITIATED';
					
					/*  echo '<pre>';
					print_r($ordersArray); 
					exit(); */
					
					$orderId = $this->publications_model->createOrder($ordersArray);
					
					
					/* if($orderId){
						
						echo 'created order';
						
					}else{
						
						echo 'failed order';
					}
					 */
					$orderDetails = $this->publications_model->getOrderDetailsByOrderId($orderId);
					
					$this->load->model('locations_model');
					$provinceObject = $this->locations_model->getProvincesById($this->input->post('state'), "IN");
					$cityObject = $this->locations_model->getCityById($this->input->post('city'));
					
					
					
					$this->load->library('crypto');
					$ccavenueVars ['merchant_id']      = CCAVENUE_MERCHANT_ID;
					$amountTotalFee = (int)$this->input->post('totalFeeForSchool');
					
					
					$ccavenueVars ['order_id']         = $orderId;
					$ccavenueVars ['amount']           = $this->input->post('totalAmountAfterCalculation');
					$ccavenueVars ['redirect_url']     = CCAVENUE_SUCCESS_URL;
					$ccavenueVars ['cancel_url']       = CCAVENUE_CANCEL_URL;
					$ccavenueVars ['language'] 			= 'EN';
					$ccavenueVars ['billing_name'] 		= $memberDetails['full_name'];
					$ccavenueVars ['billing_address'] 	= $memberDetails['address'];
					$ccavenueVars ['billing_city'] 		= $memberDetails ['cityName'];
					$ccavenueVars ['billing_state'] 	= $memberDetails ['ProvinceName'];
					$ccavenueVars ['billing_zip'] 		= $memberDetails ['pincode'];
					$ccavenueVars ['billing_country'] 	= $memberDetails['CountryName'];
					$ccavenueVars ['billing_tel'] 		= $memberDetails['mobile'];
					$ccavenueVars ['billing_email'] 	= $memberDetails['email'];
					$ccavenueVars ['delivery_name'] 	= $memberDetails['full_name'];;
					$ccavenueVars ['delivery_address'] 	= $memberDetails['address'];;
					$ccavenueVars ['delivery_city'] 	= $memberDetails ['cityName'];
					$ccavenueVars ['delivery_state'] 	= $memberDetails ['ProvinceName'];
						
					$ccavenueVars ['delivery_zip'] 		= $memberDetails ['pincode'];
					$ccavenueVars ['delivery_country'] 	= $memberDetails['CountryName'];
					$ccavenueVars ['delivery_tel'] 		= $memberDetails['mobile'];
					$ccavenueVars ['merchant_param1'] = $memberDetails['memberId'];
					$ccavenueVars ['merchant_param2'] = 'Param';
					$ccavenueVars ['merchant_param3'] = 'Param';
					$ccavenueVars ['merchant_param4'] = 'Param';
					$ccavenueVars ['merchant_param5'] = 'Param';
					$ccavenueVars ['promo_code'] = '';
					$ccavenueVars ['customer_identifier'] = '';
					$ccavenueVars ['currency']         = $this->input->post('currencyAfterCalculation');
					
					
					/* echo "<pre>";
					print_r($ccavenueVars);
					echo "</pre>"; */ 
					//exit();
					
					
					$this->load->helper ( 'ccavenue_crypto' );
					
					$merchant_data = '';
					$working_key = WORKING_KEY; // Shared by CCAVENUES
					$access_code = ACCESS_CODE; // Shared by CCAVENUES
					
					foreach ( $ccavenueVars as $key => $value ) {
						$merchant_data .= $key . '=' . $value . '&';
						//echo $key.'='.$value.'<br>';
					}
					
					//echo $merchant_data;
					
					$encrypted_data = encrypt ( $merchant_data, WORKING_KEY ); // Method for encrypting the data.
					// echo '<br>Ence<br>'.$encrypted_data;
					// exit();
					$paymentDataArray ['encrypted_data'] = $encrypted_data;
					$paymentDataArray ['access_code'] = ACCESS_CODE;
					
					//$this->load->view ( 'template/header', $paymentDataArray );
					$this->load->view ( 'ccavenue_checkout_redirect', $paymentDataArray );
					
					
					/* $orderDetails['password'] = $this->crypto->decrypt($orderDetails['password']);
					
					$title     = 'VibhaIndia.org - Publications Registration'; 
					$toEmailId = 'prasath@sincerity.in,'.$orderDetails['email'];
					$subject   = 'VibhaIndia.org - Publications Registration';
					$message   = 'Dear '.$orderDetails['fullName'].'<br>';
					
					$this->sendEmail($title, $toEmailId, $subject, $message, $orderDetails);
					
					
					$data['result']['ERROR_MESSAGE'] = '<div class="alert alert-success">Your Membership has been registered successfully. Please check your inbox. You will receive an email. </div>';
					$data['result']['SUCCESS_MESSAGE'] = ''; */
					
				//}
				
			}
		
			
		}
		
		
		
		$data['publicationCategories'] = $this->publications_model->getPublicationCategories();
		
		$this->load->view('publication_registration', $data);
	}
	
	private  function sendEmail($title, $toEmailId, $subject, $message, $orderDetails)
	{
	
		date_default_timezone_set('Asia/Kolkata');
	
		$this->load->view('email/publicationRegistration', $orderDetails);
		
		/* echo '<pre>';
		print_r($orderDetails);
		echo '</pre>'; */
		
		$message = $this->load->view('email/publicationRegistration', $orderDetails, true);
		$this->load->helper('url');
		$from = "info@vibhaindia.org";
		$to = $toEmailId;
		
			
		$this->load->library('email');
		
		$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => 'admin@vibhaindia.org', // change it to yours
				'smtp_pass' => 'mail890@gh', // change it to yours
				'mailtype' => 'html'
		
		); 
		
		
		$this->email->set_newline("\r\n");
		$this->email->initialize($config);
		
		$this->email->from($from);
		$this->email->to($to);
		//$this->email->bcc($cc);
		$this->email->subject('Vibhaindia.org VVM -  School registration');
		$this->email->message($message);
		
		$mailsendResult = $this->email->send();
		//echo $this->email->print_debugger();
		if($mailsendResult){
			return true;
		}
		return false;
	}
	
	
	public function login(){
		
		$this->load->helper('url');
		$this->load->helper('form');
		$data = array();
		
		$this->load->library ( 'session' );
		$sessionData = $this->session->userdata;
		
		$data['result']['SUCCESS_MESSAGE'] = "";
		$data['result']['ERROR_MESSAGE'] = "";
		
		
		
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		
		
		if($email && $password){
			
			
			$this->load->model('user_model');
			$loginResult = $this->user_model->getUserForLogin($email, $password);
			
			if($loginResult){
				
				
				$this->session->set_userdata($loginResult);
				redirect('members/profile/','location');
			}
			
		}
		$this->load->view('login',$data);
	}
	
	
	public function profile(){
		
		$this->load->helper('url');
		$this->load->helper('form');
		$data = array();
		
		$this->load->library('session');
		$user_data = $this->session->all_userdata();
	
		
		if(isset($user_data['id'])){
			
			$this->load->model('user_model');
			$this->load->model('locations_model');
			
			
			
			
			if($this->input->post()){
			
				$userDetails = $_POST;
				$userDetails['full_name'] = $_POST['fullName'];
				unset($userDetails['fullName']);
				unset($userDetails['email']);
				
				$updateResult = $this->user_model->updateMemberDetails($userDetails, $user_data['id']);
				if($updateResult){
					$data['message']["SUCCESS_MESSAGE"] = '<p class="alert alert-success">Profile Updated Successfully</p>';
				}
			}
			
			$loginResult = $this->user_model->getUserById($user_data['id']);
			$data['result'] = $loginResult;
			$countries = $this->locations_model->getCountries();
			$data['result'] ['countries'] = $countries;
			
			
			$this->load->view('profile',$data);
			
		}else{
			redirect('members/login/','location');
		}
		
		
	}
	
	
	
	
	public function logout(){
		
		$this->load->library('session');	
		$user_data = $this->session->all_userdata();
		
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}
		$this->session->sess_destroy();
	
		$this->load->helper('url');
		redirect('members/login/','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */