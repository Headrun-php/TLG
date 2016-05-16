<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

Route::any('/', "VaultController@login");
Route::any('/try', "TryController@index");

Route::any('/courses', 'CoursesController@viewCourses');
//Route::any('/classes', 'ClassesController@index');

Route::any('/classes', 'ClassesController@add_new_classes');
Route::any('/add_new_class_franchise', 'ClassesController@add_new_class_franchise');


Route::group(array('prefix' => 'vault'), function() {
	Route::any('login', "VaultController@login");
	Route::get('logout', "VaultController@logout");
	
});

Route::group(array('prefix' => 'courses'), function() {
	Route::any('/add', "CoursesController@addCourses");
        Route::any('/name_list', "CoursesController@courseNameList");
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
	Route::any('/nonenrolled', "StudentsController@index");
        Route::any('/enrolled', "StudentsController@enrolledstudents");
	Route::any('/view/{id}', ['uses' =>"StudentsController@view"]);
	Route::any('/profile/picture', "StudentsController@uploadProfilePicture");

});

Route::group(array('prefix' => 'customers'), function() {
	Route::any('/memberslist', "CustomersController@index");
        Route::any('/prospectslist', "CustomersController@getNonMembersList");
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


Route::group(array('prefix'=>'season'),function(){
        Route::any('/add','SeasonsController@add');
        Route::any('/viewseasons','SeasonsController@index');
        
});

Route::group(array('prefix'=>'prices'),function(){
      Route::any('/add_or_view_prices','PaymentsController@addorviewprices');
});

Route::group(array('prefix'=>'Discounts'),function(){
        Route::any('/add_or_view_discounts','DiscountsController@add_or_view_discounts');
        Route::any('/enable_or_desable','DiscountsController@enable_or_desable');
        
});



/*****************************************************  AJAX ROUTES ********************************************************/

Route::group(array('prefix' => 'quick'), function() {
    
        /**
*  --------------------------------------------------------------------------------------------------------------------------------------
* Courses related Ajax calls
*  --------------------------------------------------------------------------------------------------------------------------------------
*/
        Route::any('deleteCoursesMaster', "CoursesController@deleteCoursesMaster");
        Route::any('updateCoursesMaster', "CoursesController@updateCoursesMaster");	
        Route::any('InsertNewCoursesMaster', "CoursesController@InsertNewCoursesMaster"); 
        
        
        
        Route::group(array('prefix'=>'baseprice'),function(){
            Route::any('/deletebaseprice','PaymentsController@deletebaseprice');
            Route::any('/updatebaseprice','PaymentsController@updatebaseprice');
        });
        
        
    	Route::any('/addMultipleDiscounts','DiscountsController@addMultipleDiscounts');
    	Route::any('/approvingDiscounts','DiscountsController@approvingDiscounts');
        
        
        Route::group(array('prefix'=>'season'),function(){
                     Route::any('/getWeekstartenddayseason','SeasonsController@getstartenddays');
                     Route::any('/addSeason','SeasonsController@addSeason');
                     Route::any('/getSeasonsForBatches','SeasonsController@getSeasonsForBatches');
                     Route::any('/getSeasonsForEnrollment','SeasonsController@getSeasonsForEnrollment');
                     Route::any('/getLocationBySeasonId','SeasonsController@getLocationBySeasonId');
                     Route::any('/getSeasonDataBySeasonId','SeasonsController@getSeasonDataBySeasonId');
        });
        
        Route::group(array('prefix'=>'discount'),function(){
        Route::any('/getdiscount','ClassesController@getDiscount');
        });

	/**
	 * --------------------------------------------------------------------------------------------------------------------------------------
	 * Classes related Ajax calls
	 * --------------------------------------------------------------------------------------------------------------------------------------
	 */
        
	Route::any('classesbymaster', "ClassesController@classesbymaster");	
	Route::any('classesbyCourse', "ClassesController@classesbyCourse");
	Route::any('eligibleClassess', "ClassesController@eligibleClassess");
        Route::any('eligibleClassessForOtherBatches', "ClassesController@eligibleClassessForOtherBatches");
        Route::any('batchesByClassSeasonId', "ClassesController@batchesByClassSeasonId");
	Route::any('getMembershipTypesDetails', "CustomersController@getMembershipTypesDetails");
	Route::any('getScheduledIntrovisitByCustomerId','CustomersController@getScheduledIntrovisitByCustomerId');
        Route::any('getIntrovisitByCustomerStatus','CustomersController@getIntrovisitByCustomerStatus');
        Route::any('changeIvStatustoAttendedByIVid','CustomersController@changeIvStatustoAttendedByIVid');
	Route::any('checkUserExistance', "FranchiseeAdministration@checkUser");
        Route::any('InsertNewClass', "ClassesController@InsertNewClass");
        Route::any('updateClassesMaster', "ClassesController@updateClassesMaster");
        Route::any('InsertNewClassFromFranchise', "ClassesController@InsertNewClassFromFranchise");
        Route::any('updateClassesBasePrice', "ClassesController@updateClassesBasePrice");
        
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
        Route::any('getStudentDetailsByIdForBatches','StudentsController@getStudentDetailsByIdForBatches');
	Route::any('saveKids', "StudentsController@saveKids");
	Route::any('enrollkid', "StudentsController@enrollKid2");
        Route::any('checkBiPayOrderDate',"StudentsController@checkBiPayOrderDate");
        Route::any('checkmultiPayOrderDate',"StudentsController@checkmultiPayOrderDate");
	Route::any('checkenrollmentExists', "StudentsController@checkenrollmentExists");
	Route::any('addbirthdayParty', "StudentsController@addbirthdayParty");
	Route::any('checkExistingBirthdayParty', "StudentsController@checkExistingBirthdayParty");
        Route::any('getBirthdayOrderPendingDetails',"StudentsController@getBirthdayOrderPendingDetails");
        Route::any('modifyBirthdayPendingOrder',"StudentsController@modifyBirthdayPendingOrder");
	Route::any('getStudentsByBatch', "StudentsController@getStudentsByBatch");
	Route::any('addStudentAttendance', "StudentsController@addStudentAttendance");
	Route::any('createorder',"StudentsController@createPendingorder");
	Route::any('creatependingorder',"StudentsController@createPendingOrderForEnrollment");
	Route::any('getStudentsByCustomerid','StudentsController@getStudentsByCustomerid');
        
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Estimate related Ajax calls
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 */
        Route::any('insertEstimateDetails','EstimateController@insertEstimateDetails');
        Route::any('insertEstimateMasterDetails','EstimateController@insertEstimateMasterDetails');
        Route::any('cancelBatchEstimate','EstimateController@cancelBatchEstimate');
        
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Batches related Ajax calls
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 */
	Route::any('checkslots', "BatchesController@checkslots");
	Route::any('getBatcheSchedules', "BatchesController@getBatchesSchedules");	
        Route::any('getBatchData','BatchesController@getBatchData');
        Route::any('getBatchRemainingClassesByBatchId','BatchesController@getBatchRemainingClassesByBatchId');
        Route::any('/checkbatchesslot','BatchesController@checkBatchExistBySeasonIdLocationId');
        Route::any('getBatchDetailsById','BatchesController@getBatchDetailsById');
        Route::any('editbatchByBatchId','BatchesController@editbatchByBatchId');
	Route::any('deleteBatchById','BatchesController@deleteBatchById');
		
	
	/**
	 *  --------------------------------------------------------------------------------------------------------------------------------------
	 * Other Ajax Calls
	 *  -------------------------------------------------------------------
	 */
	Route::any('checkSlotAvailableForIntrovisit', "EventsController@checkSlotAvailableForIntrovisit");
	Route::any('addIntroVisit', "StudentsController@addIntroVisit");	
	Route::any('editIntrovisit', "StudentsController@editIntroVisit");
        Route::any('editEnrollment','StudentsController@editEnrollment');
        Route::any('getIntrovisitHistory',"StudentsController@getIntrovisitHistory");
        Route::any('getInquiryHistoryById','StudentsController@getInquiryHistoryById');
        Route::any('UpdateInquiryFollowup','StudentsController@UpdateInquiryFollowup');
        Route::any('getIvdataByCustomerId',"StudentsController@getIvdataByCustomerId"); 
        Route::any('getBirthdayHistory',"StudentsController@getBirthdayHistoryDataByBirthdayId"); 
        Route::any('editBirthdayCelebrationFollowup',"StudentsController@editBirthdayCelebrationFollowup");
        Route::any('createFollowup',"StudentsController@createFollowup");
        Route::any('getComplaintHistoryById',"StudentsController@getComplaintHistoryById");
        Route::any('getRetentionHistoryById',"StudentsController@getRetentionHistoryById");
        Route::any('getEnrollmetHistory','StudentsController@getEnrollmetHistory');
        Route::any('getFollowupByMembershipId','CustomersController@getMembershipHistory');
        Route::any('updateMembershipFollowup','CustomersController@updateMembershipFollowup');
        Route::any('UpdateRetentionFollowup','StudentsController@UpdateRetentionFollowup');
        Route::any('UpdateFollowup','StudentsController@UpdateFollowup');
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



Route::get('/test', function(){
    $base_price_no= Batches::find(130)->classes()->select('base_price_no')->get();
    $base_price_no=$base_price_no[0]['base_price_no'];
    echo $base_price_no;
    die(); 
    var_dump(ClassBasePrice::where('base_price_no','=',Batches::find(130)->classes()->base_price_no)->select('base_price')->get());
    
                       
    $classes_count=  StudentClasses::where('student_id','=',88)
                                        ->where('status','=','enrolled')
                                        //->whereDate('enrollment_start_date','>=',date("Y-m-d"))
                                        //->whereDate('enrollment_end_date','<=',date("Y-m-d"))
                                        ->distinct('class_id')
                                        ->count();
    
    var_dump($classes_count);
    exit();
    echo PaymentMaster::max('payment_no');
      exit();

    echo Batches::where('id','=','2')->select('class_amount')->get();
    exit();
 $data=IntroVisit::join('students','student_id','=','students.id')
                              ->where('introvisit.customer_id','=','44')
                              ->get();
                        var_dump($data);
    exit();
  
                      //  for($i=0;$i<count($birthday_data);$i++){
                      //      $customer_data= Customers::where('id','=',$birthday_data[$i]['customer_id'])->get();
                      //      $birthday_data[$i]['customer_name']=  $customer_data[0]['customer_name'];
                      //      $birthday_data[$i]['membership']=  CustomerMembership::where('customer_id','=',$birthday_data[$i]['customer_id'])->count();
                     //       
                     //   }
});