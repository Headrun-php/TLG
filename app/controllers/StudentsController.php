<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
class StudentsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if(Auth::check())
		{
			$currentPage  =  "STUDENTS";
			$mainMenu     =  "STUDENTS_MAIN";
			
			$inputs = Input::all();
			if (isset($inputs['customerId'])) {
					
				if (Students::addStudent($inputs)) {
					Session::flash('msg', "Student added successfully.");
				} else {
					Session::flash('warning', "Student, Course Could not be added at the moment.");
				}
			}
			$franchiseeId = Session::get('franchiseId');
			$customers = Customers::getAllCustomersByFranchiseeId($franchiseeId);
			$customersDD = Customers::getAllCustomersForDropdown($franchiseeId);
			$students = Students::getAllStudentsByFranchiseeId(Session::get('franchiseId'));
			$dataToView = array("customers",'customersDD', 'students','currentPage', 'mainMenu');
			return View::make('pages.students.studentslist', compact($dataToView));
			
		}else{
			return Redirect::to("/");
		}
		
	}
	
	
	public function addstudent(){
		
		$inputs = Input::all();
		$addStudentResult = Students::addStudent($inputs);
		
		header('Access-Control-Allow-Origin: *');
		if($addStudentResult){
			return Response::json(array("status"=>"success"));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	
	public function getStudentById(){
		
		$inputs = Input::all();
		$id = $inputs['studentId'];
		
		$student = Students::getStudentById($id);
		
		header('Access-Control-Allow-Origin: *');
		if($student){
			return Response::json($student['0']);
		}else{
			return Response::json(array("status"=> "failed"));
		}
	}
	
	


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function view($id)
	{
		if(Auth::check())
		{
			$currentPage  =  "STUDENTS_LIST";
			$mainMenu     =  "STUDENTS_MAIN";
			$student = Students::getStudentById($id);
			$franchiseeCourses = Courses::getFranchiseCoursesList(Session::get('franchiseId'));
			$studentEnrollments = StudentClasses::getStudentEnrollments($id);
			$paymentDues = PaymentDues::getAllPaymentDuesByStudent($id);
			$customermembership = CustomerMembership::getCustomerMembership($student['0']->customer_id);
			$scheduledIntroVisits = IntroVisit::getIntrovisitByStudentId($id);
			$discountEligibility  = StudentClasses::discount($id, $student['0']->customer_id);
			//$paidDue= PaymentDues::getAllPaymentsMade($id);
                        //$Due=PaymentDues::getAllDue($id);
			
                        //for paid payment 
                        
                        $paidAmountdata= PaymentDues::getAllPaymentsMade($id);
                        for($i=0;$i<count($paidAmountdata);$i++){
                            $studentclasssectiondata=  Classes::getstudentclasses($paidAmountdata[$i]['class_id']);
                            $paidAmountdata[$i]['class_name']=$studentclasssectiondata[0]['class_name'];
                            $user_Data=User::getUsersByUserId($paidAmountdata[$i]['created_by']);
                            $paidAmountdata[$i]['receivedname']=$user_Data[0]['first_name'].$user_Data[0]['last_name'];
                            $order_data=  Orders::getOrderDetailsbyPaydueId($paidAmountdata[$i]['id']);
                            $paidAmountdata[$i]['printurl']=  url().'/orders/print/'.Crypt::encrypt($order_data[0]['id']);
                            
                        }
                        
                        /*
                        
                        $paidAmountdata= Orders::getAllPaymentsMade($id);
                        
                        for($i=0;$i<count($paidAmountdata);$i++){
                            $studentClassdata=StudentClasses::getStudentClassbyId($paidAmountdata[$i]['student_classes_id']);
                            $paidAmountdata[$i]['enrollment_start_date']=$studentClassdata[0]['enrollment_start_date'];
                            $paidAmountdata[$i]['enrollment_end_date']=$studentClassdata[0]['enrollment_end_date'];
                            $paidAmountdata[$i]['selected_sessions']=$studentClassdata[0]['selected_sessions'];
                            $studentclasssectiondata=  Classes::getstudentclasses($studentClassdata[0]['class_id']);
                            $paidAmountdata[$i]['class_name']=$studentclasssectiondata[0]['class_name'];
                            $user_Data=User::getUsersByUserId($paidAmountdata[$i]['created_by']);
                            $paidAmountdata[$i]['receivedname']=$user_Data[0]['first_name'].$user_Data[0]['last_name'];
                            $paidAmountdata[$i]['printurl']=  url().'/orders/print/'.Crypt::encrypt($paidAmountdata[0]['id']);
                            
                        }
                         * 
                         */
                        
                        
                        //for dues
                      //  $order_due_data=Orders::getpendingPaymentsid($id);
                       $order_due_data=  PaymentDues::getAllDuebyStudentId($id);
                      //  $dueAmountdata=PaymentDues::getAllDue($id);
                        for($i=0;$i<count($order_due_data);$i++){
                            
                           
                            $studentclasssectiondata=  Classes::getstudentclasses($order_due_data[0]['class_id']);
                            $order_due_data[$i]['class_name']=$studentclasssectiondata[0]['class_name'];
                            $user_Data=User::getUsersByUserId($order_due_data[$i]['created_by']);
                            $order_due_data[$i]['receivedname']=$user_Data[0]['first_name'].$user_Data[0]['last_name'];
                            
                            
                        }
                        
                        
                        
			$dataToView = array("student",'currentPage', 'mainMenu','franchiseeCourses', 
								'studentEnrollments','customermembership','paymentDues',
								'scheduledIntroVisits', 'introvisit', 'discountEligibility','paidAmountdata','order_due_data');
			return View::make('pages.students.details',compact($dataToView));
		}else{
			return Redirect::to("/");
		}
	}
	
	
	public function saveKids() {
		
		$inputs = Input::all();
		$updateStudent = Students::updateStudent($inputs);
		header('Access-Control-Allow-Origin: *');
		if($updateStudent){
			return Response::json(array("status"=>"success"));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	
	public function uploadProfilePicture(){
		
		$file = Input::file('profileImage');
		$studentId = Input::get('studentId');
		
		$destinationPath = 'upload/profile/student/';
		
		$filename = $file->getClientOriginalName();		
		$fileExtension = '.'.$file->getClientOriginalExtension();
		
				
		$filename = 'student_profile_'.$studentId.'_medium'.$fileExtension;
				
		$result = Input::file('profileImage')->move($destinationPath, $filename);
		
		if($result){	
				
			$student = Students::find($studentId);
			$student->profile_image = $filename;
			$student->save();
		
		}
		
		Session::flash ( 'imageUploadMessage', "Profile picture updated successfully." );
		return Redirect::to("/students/view/".$studentId);
		
		
		
	}
	
	
	public function enrollKid(){
		
		$inputs = Input::all();
		
		$studentClasses['classId']               = $inputs['eligibleClassesCbx'];
		$studentClasses['batchId']               = $inputs['batchCbx'];
		$studentClasses['studentId']             = $inputs['studentId'];		
		$studentClasses['enrollment_start_date'] = date('Y-m-d', strtotime($inputs['enrollmentStartDate']));
		$studentClasses['enrollment_end_date']   =   date('Y-m-d', strtotime($inputs['enrollmentEndDate']));
		$studentClasses['selected_sessions']     = $inputs['selectedSessions'];
		
		
		//Batch Start date
		
		$BatchDetails = Batches::where('id', '=', $inputs['batchCbx'])->first();
		$reminderStartDate = $BatchDetails->start_date;
		
		
		
		$enrollment = StudentClasses::addStudentClass($studentClasses);
		
		
		$paymentDuesInput['student_id']        = $inputs['studentId'];
		$paymentDuesInput['customer_id']       = $inputs['customerId'];
		$paymentDuesInput['batch_id']          = $inputs['batchCbx'];
		$paymentDuesInput['class_id']          = $inputs['eligibleClassesCbx'];
		$paymentDuesInput['selected_sessions'] = $inputs['selectedSessions'];
		
		
		
		$order['customer_id']     = $inputs['customerId'];
		$order['student_id']      = $inputs['studentId'];
		
		$order['student_classes_id'] = $enrollment->id;
		$order['payment_mode']    = $inputs['paymentTypeRadio'];
		$order['payment_for']     = "enrollment";
		$order['card_last_digit'] = $inputs['card4digits'];
		$order['card_type']       = $inputs['cardType'];
		$order['bank_name']       = $inputs['bankName'];
		$order['cheque_number']   = $inputs['chequeNumber'];
		if(isset($inputs['membershipType'])){
			$order['membershipType'] = $inputs['membershipType'];
		}
		
		if($inputs['paymentOptionsRadio'] == 'singlepay'){
				
			$paymentDuesInput['payment_due_amount']  = $inputs['singlePayAmount'];
			$paymentDuesInput['payment_type']        = $inputs['paymentOptionsRadio'];
			$paymentDuesInput['payment_status']      = "paid";
			$paymentDuesInput['discount_applied']    = $inputs['discountPercentage'];
                        $paymentDuesInput['student_class_id']   =  $enrollment->id;
			$order['amount']   = $inputs['singlePayAmount'];
				
			$paymentDuesResult = PaymentDues::createPaymentDues($paymentDuesInput);
			$order['payment_dues_id']   = $paymentDuesResult->id;
			$order['order_status']      = "completed";
			
			
			
				
				
		}else if($inputs['paymentOptionsRadio'] == 'multipay'){
				
			$paymentDuesInput['payment_type']        = $inputs['paymentOptionsRadio'];
			for($i = 1; $i<=4; $i++){
		
				if(isset($inputs['multipayAmount'.$i])){
                                        
					if($i ==1){
						$paymentDuesInput['payment_status']      = "paid";
						$order['amount']   =  $inputs['multipayAmount'.$i];
                                                $paymentDuesInput['start_order_date']=$studentClasses['enrollment_start_date'];
                                                //$startdate=  Carbon::create(date('Y,m,d,0', strtotime($inputs['enrollmentStartDate'])));
                                                $startdate=Carbon::now();
                                                $startdate->year=date('Y', strtotime($inputs['enrollmentStartDate']));
                                                $startdate->month=date('m', strtotime($inputs['enrollmentStartDate']));
                                                $startdate->day=date('d', strtotime($inputs['enrollmentStartDate']));
                                                $startdate=$startdate->addWeeks(($inputs['multipayAmount'.$i]/500));
                                                $paymentDuesInput['end_order_date']=$startdate->toDateString();
                                                $paymentDuesInput['discount_amount']=((($inputs['discountPercentage'])/100) *($inputs['multipayAmount'.$i]));
                                        
                                                }else{
                                                $paymentDuesInput['start_order_date']=   $startdate->toDateString();
						$startdate=$startdate->addWeeks(($inputs['multipayAmount'.$i]/500));
                                                $paymentDuesInput['end_order_date']=   $startdate->toDateString();
                                                $paymentDuesInput['payment_status']      = "pending";
                                                
					}
                                        
					$paymentDuesInput['payment_due_amount']  = $inputs['multipayAmount'.$i];
					$paymentDuesInput['discount_applied']    = $inputs['discountPercentage'];
                                        $paymentDuesInput['selected_order_sessions']=($inputs['multipayAmount'.$i]/500);
                                        $paymentDuesInput['student_class_id']   =  $enrollment->id;
					
                                        $paymentDuesResult = PaymentDues::createPaymentDues($paymentDuesInput);
					
					

					
					
					
					
						
					if($i ==1){
		
						$order['payment_dues_id']   = $paymentDuesResult->id;
						$order['order_status']      = "completed";
					}
				}
		
			}
			
			$weeksDues = array("6", "9", "14", "19", "24", "29", "34");
			$date = date('Y/m/d', strtotime($reminderStartDate));
			foreach($weeksDues as $weeksDue){
					
				$weeks = $weeksDue;
				// Create and modify the date.
				$dateTime = DateTime::createFromFormat('Y/m/d', $date);
				$dateTime->add(DateInterval::createFromDateString($weeks . ' weeks'));
				//$dateTime->modify('next monday');
					
				// Output the new date.
				//echo $dateTime->format('Y-m-d')."<br>";
			
			
				$paymentReminderInput['customerId']    = $inputs['customerId'];
				$paymentReminderInput['studentId']     = $inputs['studentId'];
				$paymentReminderInput['classId']       = $inputs['eligibleClassesCbx'];
				$paymentReminderInput['batchId']       = $inputs['batchCbx'];
				$paymentReminderInput['reminder_date'] = $dateTime->format('Y-m-d');
			
				PaymentReminders::addReminderDates($paymentReminderInput);
			
			
					
			}
			
			
				
		}else if($inputs['paymentOptionsRadio'] == 'bipay'){
				
			$paymentDuesInput['payment_type']        = $inputs['paymentOptionsRadio'];
			for($i = 1; $i<=2; $i++){
					
				if(isset($inputs['bipayAmount'.$i])){
						
					if($i ==1){
						$paymentDuesInput['payment_status']      = "paid";
						$order['amount']   =  $inputs['bipayAmount'.$i];
					}else{
						$paymentDuesInput['payment_status']      = "pending";
					}
					$paymentDuesInput['payment_due_amount']  = $inputs['bipayAmount'.$i];
					$paymentDuesInput['discount_applied']    = $inputs['discountPercentage'];
					$paymentDuesInput['student_class_id']   =  $enrollment->id;	
					$paymentDuesResult = PaymentDues::createPaymentDues($paymentDuesInput);
					
					if($i ==1){
						$order['payment_dues_id']   = $paymentDuesResult->id;
						$order['order_status']      = "completed";
					}
				}
					
			}
			
			$weeksDues = array("6", "12", "19", "24", "34");
			$date = date('Y/m/d', strtotime($reminderStartDate));
			foreach($weeksDues as $weeksDue){
					
				$weeks = $weeksDue;
				// Create and modify the date.
				$dateTime = DateTime::createFromFormat('Y/m/d', $date);
				$dateTime->add(DateInterval::createFromDateString($weeks . ' weeks'));
				//$dateTime->modify('next monday');
					
				// Output the new date.
				//echo $dateTime->format('Y-m-d')."<br>";
					
					
				$paymentReminderInput['customerId']    = $inputs['customerId'];
				$paymentReminderInput['studentId']     = $inputs['studentId'];
				$paymentReminderInput['classId']       = $inputs['eligibleClassesCbx'];
				$paymentReminderInput['batchId']       = $inputs['batchCbx'];
				$paymentReminderInput['reminder_date'] = $dateTime->format('Y-m-d');
					
				PaymentReminders::addReminderDates($paymentReminderInput);
					
					
					
			}
				
		}
		
		$orderCreated = Orders::createOrder($order);
		
		
		
		if(isset($inputs['membershipType'])){
			$membershipInputs['customer_id'] = $inputs['customerId'];
			$membershipInputs['membership_type_id'] = $inputs['membershipType'];
			CustomerMembership::addMembership($membershipInputs);
		}
		
		
		
		
		$student = Students::with('Customers','StudentClasses')->where('id','=',$enrollment->student_id)->get();
		$class   = Classes::where('id','=',$enrollment->class_id)->get();
		
		
		$CustomerObject = Customers::find($inputs['customerId']);
		$CustomerObject->stage          = "ENROLLED";
		$CustomerObject->save();
			
		$customer = array();
		$customer['customerName']  = $student['0']->Customers->customer_name;
		$customer['customerEmail'] = $student['0']->Customers->customer_email;
		$customer['kidName']       = $student['0']->student_name;
		$customer['className']     = $class['0']->class_name;
		
		$commentsInput['customerId']     = $inputs['customerId'];
		$commentsInput['commentText']    = Config::get('constants.ENROLLED').' for '.$class['0']->class_name;
		$commentsInput['commentType']    = 'FOLLOW_UP';
		$commentsInput['reminderDate']   = null;
		Comments::addComments($commentsInput);
		
		if(isset($inputs['emailOption']) && $inputs['emailOption'] == 'yes'){
			
			
			
			$orders = Orders::with('Customers', 'Students', 'StudentClasses')->where('id', '=', $orderCreated->id)->get();
			$orders = $orders['0'];
			$class  = Classes::where('id', '=', $inputs['eligibleClassesCbx'])
							->where('franchisee_id', '=', Session::get('franchiseId'))->first(); 
			$batch  = Batches::where('id', '=', $inputs['batchCbx'])->first();
			
			
			
			$orderDetailsTomail['orders'] = $orders;
			$orderDetailsTomail['customers'] = $customer;
			$orderDetailsTomail['class'] = $class;
			$orderDetailsTomail['batch'] = $batch;
			
			$orderDetailsTomail['studentbatch']['start_date'] = date('Y-m-d', strtotime($inputs['enrollmentStartDate']));
			$orderDetailsTomail['studentbatch']['end_date']   = date('Y-m-d', strtotime($inputs['enrollmentEndDate']));
			$orderDetailsTomail['customers']['customerMembership'] = CustomerMembership::getCustomerMembership($orders->customer_id);
			
			
			Mail::send('emails.account.enrollment', $orderDetailsTomail, function($msg) use ($orderDetailsTomail){
					
				$msg->from(Config::get('constants.EMAIL_ID'), Config::get('constants.EMAIL_NAME'));
				$msg->to($orderDetailsTomail['customers']['customerEmail'], $orderDetailsTomail['customers']['customerName'])->subject('The Little Gym - Kids Enrollment Successful');
			
			});
		}
		
		if(isset($inputs['invoicePrintOption']) && $inputs['invoicePrintOption'] == 'yes'){
			$printUrl = url().'/orders/print/'.Crypt::encrypt($orderCreated->id);
		}else{
			$printUrl = "";
		}
		
		
		header('Access-Control-Allow-Origin: *');
		if($enrollment){
			return Response::json(array("status"=>"success", "printUrl"=>$printUrl));
		}else{
			return Response::json(array("status"=>"failed"));
		}
	}
	
	
	public function checkenrollmentExists(){
		
		$inputs = Input::all();
		$studentId = $inputs['studentId'];
		$classId   = $inputs['classId'];
		
		$enrolledClassForStudent = StudentClasses::where("student_id","=",$studentId)
		->where("class_id","=",$classId)->get();
		
		if(isset($enrolledClassForStudent['0'])){
			return Response::json(array("status"=>"exist"));
		}
		return Response::json(array("status"=>"clear"));
	}
	
	
	
	public function addbirthdayParty(){
		
		$inputs = Input::all();
                $taxAmtapplied=$inputs['taxAmount'];
                $addBirthday =  BirthdayParties::addbirthdayParty($inputs);
                $addPaymentDues= PaymentDues::createBirthdaypaymentdues($addBirthday);
               
                $addBirthdayOrder = Orders::createBOrder($addBirthday,$addPaymentDues,$taxAmtapplied);
                $addPaymentremainder= PaymentReminders::addReminderDates($addBirthday);
                if(isset($inputs['invoicePrintOption']) && $inputs['invoicePrintOption'] == 'yes'){
			$printUrl = url().'/orders/Bprint/'.Crypt::encrypt($addBirthdayOrder);
                        //$printUrl = url().'/orders/Bprint/'.$addBirthdayOrder;
                        
		}else{
			$printUrl = "";
		}
                    
                header('Access-Control-Allow-Origin: *');
		if($addBirthdayOrder){
			return Response::json(array("status"=>"success","printUrl"=>$printUrl));
		}
		return Response::json(array("status"=>"failed"));
	}
	
        public function createPendingorder(){
            $inputs = Input::all();
            $taxamount=$inputs['taxamount'];
            $paymentPendingdata=  PaymentDues::getPaymentpendingdata($inputs['pending_id']);
            $createPendingorder=  Orders::createPendingorder($paymentPendingdata,$taxamount);
            $changingpendingstatus=  PaymentDues::changeStatustopaid($inputs['pending_id'],0);
            $print_url=url().'/orders/Bprint/'.Crypt::encrypt($createPendingorder->id);
            header('Access-Control-Allow-Origin: *');
            
            return Response::json(array("status"=>"success","printurl"=>$print_url));
            
        }
        public function createPendingOrderForEnrollment(){
             $inputs = Input::all();
             $discountamount=$inputs['pendingamount'];
             $paymentPendingdata=  PaymentDues::getPaymentpendingdata($inputs['pending_id']);
             $createorder=  Orders::createPendingOrderForEnrollment($paymentPendingdata);
             $changingpendingstatus= PaymentDues::changeStatustopaid($inputs['pending_id'],$discountamount);
             $print_url=url().'/orders/print/'.Crypt::encrypt($createorder->id);
             header('Access-Control-Allow-Origin: *');
             
             return Response::json(array("status"=>"success","printurl"=>$print_url));
                
        }
	public function checkExistingBirthdayParty(){
		
		$inputs = Input::all();
		$existingBirthdayParties = BirthdayParties::checkWhetherBirthdayPartyExists($inputs['kidsSelect']);
		if($existingBirthdayParties){
			return Response::json(array("status"=>"exist"));
		}
		return Response::json(array("status"=>"clear"));
		
	}
	
	
	public function getStudentsByBatch(){
		
		$inputs = Input::all();
		
		$batchId       = $inputs['batchId'];
		$selectedDate  = $inputs['selectedDate'];
		
		$studentsByBatchId = StudentClasses::getStudentByBatchId($batchId, $selectedDate);
		
		if($studentsByBatchId){
			
			$attendanceArray = array();
			$i = 0;
			foreach ($studentsByBatchId as $studentAttendance){
				
				$attendanceArray[$i]['studentName'] = $studentAttendance->Students->student_name;
				$attendanceArray[$i]['studentId']   = $studentAttendance->Students->id;
				$studentAttendanceRecord = Attendance::getDaysAttendanceForStudent($studentAttendance->Students->id, $batchId,  $selectedDate);
				
				if($studentAttendanceRecord){
					
					$attendanceArray[$i]['isAttendanceEntered'] = 'yes';
					$attendanceArray[$i]['attendanceStatus'] = $studentAttendanceRecord->status;
					
				}else{
					$attendanceArray[$i]['isAttendanceEntered'] = 'no';
					
				}
				
				$i++;
			}
			//print_r($attendanceArray);
			return Response::json(array("status"=>"success", 'result'=>$attendanceArray));
		}
		return Response::json(array("status"=>"failed"));
		
	}
	
	
	
	public function addStudentAttendance(){
		
		$inputs = Input::all();
			
		for($i =0; $i<$inputs['totalStudents'];$i++){
			
			$isAttendanceExists = Attendance::getDaysAttendanceForStudent($inputs['student_'.$i], $inputs['batch_'.$i], $inputs['attendanceDate_'.$i]);
			if($isAttendanceExists){

				$isAttendanceExists->status = $inputs['attendance_for_user'.$i];
				$isAttendanceExists->save();
				
			}else{
				
				$attendanceData = new Attendance();
				$attendanceData->attendance_date = $inputs['attendanceDate_'.$i];
				$attendanceData->batch_id        = $inputs['batch_'.$i];
				$attendanceData->student_id      = $inputs['student_'.$i];
				$attendanceData->status          = $inputs['attendance_for_user'.$i];
				$attendanceData->save();
				
			}
			
		}
		return Response::json(array("status"=>"success"));
	}
	
	
	public function addIntroVisit(){
		
		$inputs = Input::all();
		
		
		
		$result = IntroVisit::addSchedule($inputs);
		
		
		$commentsInput['customerId']     = $inputs['customerId'];
		$commentsInput['commentText']    = Config::get('constants.IV_SCHEDULED_COMMENT').'  '.$inputs['customerCommentTxtarea'];
		$commentsInput['commentType']    = 'ACTION_LOG';
		$commentsInput['reminderDate']   = date('Y-m-d', strtotime($inputs['reminderTxtBox']));
		Comments::addComments($commentsInput);
		
		
		if($result){
			return Response::json(array("status"=>"success"));
		}
		return Response::json(array("status"=>"failed"));
		
		
	}
	
	public function editIntroVisit(){
	
		$inputs = Input::all();
	
	
	
		$introVisit =  IntroVisit::find($inputs['id']);
		$introVisit->status = $inputs['status'];
		$result = $introVisit->save();
	
	
		if($result){
			
			if($inputs['status']== 'ATTENDED'){
				
				$commentText = Config::get('constants.IV_ATTENDED_COMMENT').'  '.$inputs['customerCommentTxtarea'];
				
			}elseif($inputs['status']== 'NO_SHOW'){
				
				$commentText = Config::get('constants.IV_NO_SHOW_COMMENT').'  '.$inputs['customerCommentTxtarea'];
				
			}elseif($inputs['status']== 'IN_ACTIVE'){
				
				$commentText = 'Introvisit deleted  '.$inputs['customerCommentTxtarea'];
				
			}elseif($inputs['status']== 'ACTIVE'){
				
				$commentText = ' Intovisit edited. Made active '.$inputs['customerCommentTxtarea'];
				
			}
			
			
			$commentsInput['customerId']     = $introVisit->customer_id;
			$commentsInput['commentText']    = $commentText;
			$commentsInput['commentType']    = 'ACTION_LOG';
			$commentsInput['reminderDate']   = null;
			Comments::addComments($commentsInput);
			
			
			return Response::json(array("status"=>"success"));
		}
		return Response::json(array("status"=>"failed"));
	
	
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
