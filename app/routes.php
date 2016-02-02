<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

Route::any('/', "VaultController@login");
Route::any('/try', "TryController@index");

Route::any('/courses', 'CoursesController@addCourses');
Route::any('/classes', 'ClassesController@index');


Route::group(array('prefix' => 'vault'), function() {
	Route::any('login', "VaultController@login");
	Route::get('logout', "VaultController@logout");
	
});

Route::group(array('prefix' => 'courses'), function() {
	Route::any('/add', "CoursesController@addCourses");
	Route::get('logout', "VaultController@logout");

});



Route::group(array('prefix' => 'admin'), function() {
	Route::any('/users/add', "FranchiseeAdministration@adduser");
	Route::get('/users', "FranchiseeAdministration@users");
	Route::any('/users/view/{id}', ['uses' =>"FranchiseeAdministration@viewUser"]);

});



Route::group(array('prefix' => 'dashboard'), function() {
	Route::any('/', "DashboardController@index");
	Route::get('logout', "VaultController@logout");

});

Route::group(array('prefix' => 'students'), function() {
	Route::any('/', "StudentsController@index");
	Route::any('/view/{id}', ['uses' =>"StudentsController@view"]);
	Route::any('/profile/picture', "StudentsController@uploadProfilePicture");

});

Route::group(array('prefix' => 'customers'), function() {
	Route::any('/', "CustomersController@index");
	Route::any('/add', "CustomersController@add");
	Route::any('/view/{id}', "CustomersController@details");
	Route::any('/profile/picture', "CustomersController@uploadProfilePicture");

});


Route::group(array('prefix' => 'batches'), function() {
	Route::any('/', "BatchesController@index");
	Route::any('/view/{id}', "BatchesController@view");
	Route::any('/attendance/{id}', "BatchesController@attendance");
	Route::get('logout', "VaultController@logout");

});



Route::group(array('prefix' => 'events'), function() {
	Route::any('/', "EventsController@index");
	Route::any('/types', "EventsController@eventTypes");
	

});


Route::group(array('prefix' => 'orders'), function() {
	Route::any('/print/{id}', "PaymentsController@printOrder");
        Route::any('/Bprint/{id}',"PaymentsController@printBdayOrder");
	Route::any('/types', "EventsController@eventTypes");


});



/*****************************************************  AJAX ROUTES ********************************************************/

Route::group(array('prefix' => 'quick'), function() {
	
	/**
	 * --------------------------------------------------------------------------------------------------------------------------------------
	 * Classes related Ajax calls
	 * --------------------------------------------------------------------------------------------------------------------------------------
	 */
	Route::any('classesbymaster', "ClassesController@classesbymaster");	
	Route::any('classesbyCourse', "ClassesController@classesbyCourse");
	Route::any('eligibleClassess', "ClassesController@eligibleClassess");
	Route::any('batchesByClass', "ClassesController@batchesByClass");
	Route::any('getMembershipTypesDetails', "CustomersController@getMembershipTypesDetails");
	
	Route::any('checkUserExistance', "FranchiseeAdministration@checkUser");
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Customer related Ajax calls
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 */
	Route::any('customerexistence', "CustomersController@checkCustomerExists");
	Route::any('editCustomer', "CustomersController@editCustomer");
	
	
	
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Students related Ajax calls
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 */
	Route::any('addstudent', "StudentsController@addstudent");	
	Route::any('getStudentById', "StudentsController@getStudentById");
	Route::any('saveKids', "StudentsController@saveKids");
	Route::any('enrollkid', "StudentsController@enrollKid");
	Route::any('checkenrollmentExists', "StudentsController@checkenrollmentExists");
	Route::any('addbirthdayParty', "StudentsController@addbirthdayParty");
	Route::any('checkExistingBirthdayParty', "StudentsController@checkExistingBirthdayParty");
	Route::any('getStudentsByBatch', "StudentsController@getStudentsByBatch");
	Route::any('addStudentAttendance', "StudentsController@addStudentAttendance");
	Route::any('createorder',"StudentsController@createPendingorder");
	Route::any('creatependingorder',"StudentsController@createPendingOrderForEnrollment");
	
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Batches related Ajax calls
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 */
	Route::any('checkslots', "BatchesController@checkslots");
	Route::any('getBatcheSchedules', "BatchesController@getBatchesSchedules");	
	
		
	
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Other Ajax Calls
	 *  -------------------------------------------------------------------
	 */
	Route::any('checkSlotAvailableForIntrovisit', "EventsController@checkSlotAvailableForIntrovisit");		
	Route::any('addIntroVisit', "StudentsController@addIntroVisit");	
	Route::any('editIntrovisit', "StudentsController@editIntroVisit");
	Route::any('getEvents', "EventsController@getEvents");		
	Route::any('getPaymentTypes', "PaymentsController@index");
	Route::any('navigateToProfile', "VaultController@navigateToProfile");	
	
	Route::any('eventTypeById', "EventsController@getEventTypeById");
	Route::any('saveEventType', "EventsController@saveEventType");
	
	Route::any('getEventById', "EventsController@getEventById");	
	Route::any('saveEvent', "EventsController@saveEvent");
	
	Route::any('saveSchedule', function(){
	
		$input = Input::all();
		if($input['studentId'] != ""){
			$studentSchedule = StudentSchedule::addSchedule($input);
				
			if($studentSchedule){
				$status = array("status"=>"success");
			}
		}
		header('Access-Control-Allow-Origin: *');
		return Response::json($status);
	
	});
	
	
	
	
	
	Route::any('savecomment', function(){

		$inputs = Input::all();

		$comments = Comments::addComments($inputs);

		header('Access-Control-Allow-Origin: *');
		if($comments){
			return Response::json(array("status"=> "success"));
		}else{
			return Response::json(array("status"=> "failed"));
		}

	});
	
	Route::any('customerStudentSearch', function(){
	
		$inputs       = Input::all();
		$term         = $inputs['term'];
		$franchiseeId = Session::get('franchiseId');
		
		$result = DB::select('call sp_searchStudentCustomers(?, ?, ?)',array($term, $franchiseeId, '@result'));
		
		if(isset($result)){
			return Response::json($result);
		}
		return Response::json(array("status"=>"clear"));
	
	});
	
	
	
	Route::any('getWeekendsForBday', function(){
	
		$inputs = Input::all();
		$startDate = "01 ".$inputs['dateSelected'];
		$lastDateofMonth = date('t', strtotime($inputs['dateSelected']));
		$endDate   = $lastDateofMonth." ".$inputs['dateSelected'];		
		$saturdays = getWeekends($startDate, $endDate, "Sat");
		$sundays   = getWeekends($startDate, $endDate, "Sun");
		$result['saturdays'] = $saturdays;
		$result['sundays']   = $sundays;
	
		if(isset($result)){
			return Response::json($result);
		}
		return Response::json(array("status"=>"clear"));
	
	});
	
	Route::any('getCities', function(){
	
		$inputs = Input::all();
		$id     = $inputs['id'];
		$countryCode = $inputs['countryCode'];
		$cities = Cities::getCities($id,$countryCode);
	
		return Response::json($cities);
	
	});
	
	Route::get('logout', "VaultController@logout");
        
});




Route::get('/test', function()
{
	//echo '<pre>';
	
	/*$o=Orders::join('students', 'orders.student_id','=','students.id')
                   ->join('birthday_parties','orders.birthday_id','=','birthday_parties.id')
                                ->where('orders.customer_id','=','45')
                                ->orderBy('birthday_id','DESC')
                                ->get();
                        var_dump($o[1]);
          */
    $startdate='2016-04-01';
    $dt=Carbon::now();
    $dt->year=2016;
    $dt->month=01;
    $dt->day=5;
    $dt->addWeeks(2);
    echo $dt->toDateString();  
    exit();
 $o=BatchSchedule::where('batch_id', '=', '44')
							->whereBetween('schedule_date', array('2016-04-01','2016-07-6'))
							->count();
     var_dump($o);       
 exit();
	$id =1;
	
	$createBOrder = Orders::with('Customers', 'Students', 'StudentClasses')->where('id', '=', $id)->get();
	$orders = $orders['0'];
	$paymentDues = PaymentDues::where('id', '=', $orders->payment_dues_id)->get();
	$batchDetails = Batches::where('id', '=', $orders->StudentClasses->batch_id)->get();
	$class = Classes::where('id', '=', $orders->StudentClasses->class_id)
	->where('franchisee_id', '=', Session::get('franchiseId'))->first();
	$customerMembership = CustomerMembership::getCustomerMembership($orders->customer_id);
	
	
	
	
	/* echo "<pre>";
	 print_r($customerMembership);
	exit();  */
	
	$data = compact('orders','class', 'paymentDues', 'batchDetails','customerMembership');
	
	return View::make('pages.orders.printorder', $data);
	//exit();
	
	
	
	
	$res = StudentClasses::discount(1, 2);
	print_r($res);
	
	
	
	exit();
	
	
	$csv = array_map('str_getcsv', file('customer.csv'));
	$parentName = '';
	foreach($csv as $item){
		echo $parentName.' ==== <br>';
		if($item['0'] == 'parent'){
			echo "parent Insert";
			$parentName = $item['1'];
			print_r($item);
		}elseif($item['0'] == 'student'){
			echo "Student Insert";
			print_r($item);
		}
		
		
	}
	
	//print_r($csv);
	
	
	exit();
	
	
	
	
	
	
	$row = 1;
	if (($handle = fopen ( "customer.csv", "r" )) !== FALSE) {
		while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
			$num = count ( $data );
			echo "<p> $num fields in line $row: <br /></p>\n";
			$row ++;
			for($c = 0; $c < $num; $c ++) {
				echo $data [$c] . "<br />\n";
			}
		}
		fclose ( $handle );
	}
			
	print_r($data);
			
			
	exit();
	
	
	echo Session::get('franchiseId');
	
	//$batches = Batches::getAllBatchesByFranchiseeId(Session::get('franchiseId'));
	$batches = Batches::with('Classes')->where('franchisee_id', '=', Session::get('franchiseId'))->get();
	
	print_r($batches);
	
	exit();
	
	$classId = 2;
	$batches = Batches::batchesByClassId($classId);
	
	
	$batchesJson = array();
	$i = 0;
	foreach ($batches as $batch){
	
		$batchesJson[$i]['id'] = $batch->id;
		$batchesJson[$i]['batch_name'] = $batch->batch_name;
		$batchesJson[$i]['day'] = date('l', strtotime($batch->start_date));
		$batchesJson[$i]['start_time'] = date('G:i a', strtotime($batch->preferred_time));
		$batchesJson[$i]['end_time'] = date('G:i a', strtotime($batch->preferred_end_time));
		$batchesJson[$i]['instructor'] = '('.$batch->LeadInstructors->first_name.' '.$batch->LeadInstructors->last_name.')';
		
		
		//print_r($batch->LeadInstructors);
		
		
		$i++;
	}
	print_r($batchesJson);
	
	exit();
	
	$res = BatchSchedule::checkIntroslotAvailable('19-01-2016', 1);
	print_r($res);
	exit();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$orders = Orders::with('Customers', 'Students', 'StudentClasses')->where('id', '=', 1)->get();
	$orders = $orders['0'];
	$class = Classes::where('id', '=', $orders->StudentClasses->class_id)
							->where('franchisee_id', '=', Session::get('franchiseId'))->first();
	
	print_r($class);
	exit();	
	
	
	
	$orders = Orders::with('Customers', 'Students', 'StudentClasses')->where('id', '=', $id)->get();
	$orders = $orders['0'];
	$class  = Classes::find($orders->StudentClasses->class_id)->first();
		
	Mail::send('emails.account.enrollment', $customer, function($msg) use ($customer){
			
		$msg->from(Config::get('constants.EMAIL_ID'), Config::get('constants.EMAIL_NAME'));
		$msg->to($customer['customerEmail'], $customer['customerName'])->subject('The Little Gym - Kids Enrollment Successful');
			
	});
	
	
	
	exit();
	
	
	$weeksDues = array("6", "9", "14", "19", "24", "29", "34");
	$date = date('Y/m/d', strtotime('2016-02-01'));
	foreach($weeksDues as $weeksDue){
	
		$weeks = $weeksDue;
		// Create and modify the date.
		$dateTime = DateTime::createFromFormat('Y/m/d', $date);
		$dateTime->add(DateInterval::createFromDateString($weeks . ' weeks'));
		//$dateTime->modify('next monday');
			
		// Output the new date.
		echo $dateTime->format('Y-m-d')."<br>";
	
	}
	
	exit();
	
	
	
	
	$date = date('Y/m/d', strtotime('2016-02-01'));
		
	
	$weeks = 1;
		
	// Create and modify the date.
	$dateTime = DateTime::createFromFormat('Y/m/d', $date);
	$dateTime->add(DateInterval::createFromDateString($weeks . ' weeks'));
	//$dateTime->modify('next monday');
		
	// Output the new date.
	echo $dateTime->format('Y-m-d');
	
	exit();
	session_start();
	$_SESSION['userIdTlg'] = "test from sincerity";
	print_r($_SESSION);
	
	exit();
	
	
	$res = Batches::with('AlternateInstructors')->find(1);
	print_r($res);
	
	
	
	
	
	exit();
	
	$password = Hash::make('secretHsrLayoutLittlegym');
	echo $password; 
	
	
	exit();		
	
	
	
/*Kolkata – Ballygunge - Maitreyi Email: mk@kindlemag.in                 Password: secretKolkataLittlegym
Bangalore - Whitefield –        Email: tlgwhitefield@thelittlegym.in   Password: secretWhitefieldLittlegym
Bangalore – HSR –               Email: lorna.pothan@gmail.com          Password: secretHsrLayoutLittlegym
Chennai – TTK -                 Email: divya134@gmail.com              Password: secretTtkLittlegym
Pune – NIBM – Nikhil Agarwal    Email: nikhil@cloverbuilders.com       Password: secretNikhil@Littlegym
                               Email: Saneya Malani saneya.malani@gmail.com    Password: secretSaneya@Littlegym
							   
							   */


	//secretKolkataLittlegym
	//secretWhitefieldLittlegym
	//secretHsrLayoutLittlegym
	//secretTtkLittlegym
	//secretNikhil@Littlegym
	
	
	
	
	
	
	
	
	
	
	
	
	
	$res = $classesMaster = ClassesMaster::select('id')->where("class_start_age", ">=", 2.5)
			->where("class_end_age", "<=", 3)
			->where("age_end_limit_unit", "=", 'years')
			//->where("age_end_limit_unit", "=", "months")
			->get();
	print_r($res);
	
	
	
	
	
	exit();		
			
			
			
	
	
	$res = Students::getStudentById(1);
	
	print_r($res['0']->customer_id);
	
	
	
	
	
	exit();
	

	$batchId = 1;
	$selectedDate = '2015-12-08';
	
		
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
	exit();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($studentsByBatchId){
		return Response::json($studentsByBatchId);
	}
	return Response::json(array("status"=>"failed"));
	
	
	
	exit();
	
	$password = Hash::make('secret');
	echo $password;
	exit();
	
	$res = Customers::getCustomersById(1);
	print_r($res);
	
	exit();
	
	$begin  = new DateTime('2015-11-01');
	$end    = new DateTime('2015-11-30');
	while ($begin <= $end) // Loop will work begin to the end date
	{
		if($begin->format("D") == "Sun") //Check that the day is Sunday here
		{
			echo $begin->format("Y-m-d") . "<br>";
		}
	
		$begin->modify('+1 day');
	}
	
	
	
	
	exit();
	$result = Events::where('name','=','asdf')->select('name as value', 'id')->get();
	print_r($result);
	
	exit();
	
	$res = Events::getAllEvents();
	print_r($res);
	
	exit();
	
	
	
	
	
	
	
	//$db = DB::connection('tlg');
	
	$result = DB::select('call sp_searchStudentCustomers(?, ?)',array("ad", '@result'));
	print_r($result);
	
	
	//$stmt->bindParam(2, $score);
	
	/* $modulus = 22%10;
	
	//echo $modulus;
	
	echo 30/10; */
	
	
	
	/* $password = Hash::make('secret');
	echo $password; */
	
	
	
	/* if (Auth::attempt(array('email' => 'prasath@sincerity.in', 'password' => 'ssecret')))
	{
		echo "authenticated";
	} */
	
	//$courses = Courses::getFranchiseCourses(null);
	
	//var_dump($courses);
	
	//$res = $classess = Classes::getClassessByFranchiseeCourseId(Session::get('franchiseId'), 8);
	
	
	$res = StudentSchedule::getStudentSchedule();
	
	return View::make('pages.test',compact('res'));
	
/* 	exit
	$res = StudentSchedule::getStudentSchedule();
	print_r($res);
	exit();
	
	$res = Batches::batchesByClassId(1);
	print_r($res);
	exit();
	
	
	$res =  Batches::getAllBatchesByFranchiseeId(Session::get('franchiseId'));
	print_r($res); */
});