<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Auth::check()){
			$currentPage  =  "";
			$mainMenu     =  "DASHBOARD";
			
                        //customers or Inquiries
                        $todaysCustomerReg= Customers::getCustomertodaysRegCount();                        
                        $customerCount = Customers::getCustomerCount();
                        
                        //Members or Family Members
                        $todaysMemberReg= CustomerMembership::getMembertodaysRegCount();
                        $membersCount= CustomerMembership::getMemberCount();
                        
                        //Non Members or prospects
                        $todaysNonmemberReg= CustomerMembership::getNonMembertodaysRegCount();
                        $NonmembersCount= CustomerMembership::getNonMemberCount();
                        
                        //Enrolled customers(kids)
                        $todaysEnrolledCustomers=StudentClasses::getTodaysEnrolledCustomers();
			$enrolledCustomers = StudentClasses::getEnrolledCustomers();
			
                        
                        //for followups
                        $reminderCount = Comments::getReminderCountByFranchiseeId();
                        
			//Introvisit
                        $totalIntrovisitCount = IntroVisit::getIntrovistCount();
			$introVisitCount = IntroVisit::getIntrovisitBytoday();
			$allIntrovisits  = IntroVisit::getAllActiveIntrovisit();
                        
                        for($i=0;$i<count($allIntrovisits);$i++){
                            $data=  Comments::where('introvisit_id','=',$allIntrovisits[$i]['id'])
                                                ->orderBy('id','DESC')
                                                ->first();
                            if(isset($data)){
                            $allIntrovisits[$i]['followup_status']=$data['followup_status'];
                            }
                        } 
                         
                         
			
			//for courses
                        
                        $totalParentchildCourse=  Classes::getallParentchildCourseCount();
                        $totalPrekgKindergarten=    Classes::getallPrekgKindergartenCount();
                        $totalGradeschool=          Classes::getallGradeschoolCount();
                        $totalCourses=$totalParentchildCourse+$totalPrekgKindergarten+$totalGradeschool;
                        
			//for birthdayparty
                        
                        $totalbpartyCount=BirthdayParties::getBpartyCount();
                        $todaysbpartycount=BirthdayParties::getBpartyCountBytoday();
                                
			$todaysFollowup = Comments::getAllFollowup();
			$todaysIntrovisit = BatchSchedule::getTodaysIntroVisits();
			
			$activeRemindersCount = Comments::getAllFollowupActive();
			
                        //get birthday dates
                        $startdate=new carbon();
                        $startdate->startOfYear();
                        $endofyear=new carbon();
                        $endofyear=$endofyear->endOfYear();
                        $student_id=array();
                        $birthday_celebration_data=BirthdayParties::where('created_at','>=',$startdate->toDateString())
                                                                    ->where('created_at','<=',$endofyear->toDateString())
                                                                    ->select('student_id')
                                                                    ->get();
                        
                                                    //var_dump($birthday_celebration_data); die();
                        
                        for($i=0;$i<count($birthday_celebration_data);$i++){
                           $student_id[$i]=$birthday_celebration_data[$i]['student_id'];
                        }
                        $dat=new carbon();
                        $month=$dat->month;
                        $presentdate=$dat->day;
                       
                        // for rest of the days of month
                        $birthday_data= Students::whereNotIn('id',$student_id)
                                                 //  where('student_date_of_birth','>',$startdate->toDateString())
                                                  ->where('student_date_of_birth','<>','')
                                                  ->where( DB::raw('MONTH(student_date_of_birth)'), '=', $month )
                                                  ->where( DB::raw('DATE(student_date_of_birth)'), '>', $presentdate )
                                                  ->where('franchisee_id','=',Session::get('franchiseId'))
                                                  //->where('student_date_of_birth','=','0000-'.$month.'-00') 
                                                  ->orderBy(DB::raw('DAY(student_date_of_birth)'))
                                                  -> get();
                        
                       
                      //  echo $month; die(); 
                        
                        for($i=0;$i<count($birthday_data);$i++){
                            $customer_data= Customers::where('id','=',$birthday_data[$i]['customer_id'])->get();
                            $birthday_data[$i]['customer_name']=  $customer_data[0]['customer_name'];
                            $birthday_data[$i]['mobile_no']=  $customer_data[0]['mobile_no'];
                            $birthday_data[$i]['membership']=  CustomerMembership::where('customer_id','=',$birthday_data[$i]['customer_id'])->count();
                            
                        }
                        
                         $m=$month;
                         $m++;
                        while($m<=12){
                            $birthday_data_month[]=
                                                  Students::whereNotIn('id',$student_id)
                                                 //  where('student_date_of_birth','>',$startdate->toDateString())
                                                  ->where('student_date_of_birth','<>','')
                                                  ->where( DB::raw('MONTH(student_date_of_birth)'), '=', $m )
                                                //  ->where( DB::raw('DATE(student_date_of_birth)'), '>', $presentdate )
                                                  ->where('franchisee_id','=',Session::get('franchiseId'))
                                                  //->where('student_date_of_birth','=','0000-'.$month.'-00') 
                                                  ->orderBy(DB::raw('DAY(student_date_of_birth)'))
                                                  -> get();
                            $m++;
                        
                        }
                        // for starting months
                      $m=1;
                        while($m<$month){
                            $birthday_data_month[]=
                                                  Students::whereNotIn('id',$student_id)
                                                 //  where('student_date_of_birth','>',$startdate->toDateString())
                                                  ->where('student_date_of_birth','<>','')
                                                  ->where( DB::raw('MONTH(student_date_of_birth)'), '=', $m )
                                                //  ->where( DB::raw('DATE(student_date_of_birth)'), '>', $presentdate )
                                                  ->where('franchisee_id','=',Session::get('franchiseId'))
                                                  //->where('student_date_of_birth','=','0000-'.$month.'-00') 
                                                  ->orderBy(DB::raw('DAY(student_date_of_birth)'))
                                                  -> get();
                            $m++;
                        }
                        
                        
                        for($i=0;$i<count($birthday_data_month);$i++){
                            for($j=0;$j<count($birthday_data_month[$i]);$j++){
                            $customer_data= Customers::where('id','=',$birthday_data_month[$i][$j]['customer_id'])->get();
                            $birthday_data_month[$i][$j]['customer_name']=  $customer_data[0]['customer_name'];
                            $birthday_data_month[$i][$j]['mobile_no']=  $customer_data[0]['mobile_no'];
                            $birthday_data_month[$i][$j]['membership']=  CustomerMembership::where('customer_id','=',$birthday_data_month[$i][$j]['customer_id'])->count();
                            }
                        }
                        
                       // var_dump($birthday_data_month);exit();
                       //for starting days of present month
                        
                        $birthday_month_startdays= Students::whereNotIn('id',$student_id)
                                                 //  where('student_date_of_birth','>',$startdate->toDateString())
                                                  ->where('student_date_of_birth','<>','')
                                                  ->where( DB::raw('MONTH(student_date_of_birth)'), '=', $month )
                                                  ->where( DB::raw('DATE(student_date_of_birth)'), '<', $presentdate )
                                                  ->where('franchisee_id','=',Session::get('franchiseId'))
                                                  //->where('student_date_of_birth','=','0000-'.$month.'-00') 
                                                  ->orderBy(DB::raw('DAY(student_date_of_birth)'))
                                                  -> get();
                        
                        
                        for($i=0;$i<count($birthday_month_startdays);$i++){
                            $customer_data= Customers::where('id','=',$birthday_month_startdays[$i]['customer_id'])->get();
                            $birthday_month_startdays[$i]['customer_name']=  $customer_data[0]['customer_name'];
                            $birthday_month_startdays[$i]['mobile_no']=  $customer_data[0]['mobile_no'];   
                            $birthday_month_startdays[$i]['membership']=  CustomerMembership::where('customer_id','=',$birthday_month_startdays[$i]['customer_id'])->count();
                            
                        }
                        
                        
                        
                        
                        
                        //for birthday celebration this week
                        
                        $presentdate=new carbon();
                        $weeekdate=new carbon();
                        $weeekdate->addDays(7);
                        $birthdayPresentWeek=BirthdayParties::
                                                              where('birthday_party_date','>=',$presentdate->toDateString())
                                                              ->where('birthday_party_date','<=',$weeekdate->toDateString())
                                                              //->where('franchisee_id','=',Session::get('franchiseId'))
                                                              ->get();
                        for($i=0;$i<count($birthdayPresentWeek);$i++){
                          $customer_data=Customers::where('id','=',$birthdayPresentWeek[$i]['customer_id'])->get();
                          $birthdayPresentWeek[$i]['customer_name']= $customer_data[0]['customer_name'];
                          $birthdayPresentWeek[$i]['mobile_no']= $customer_data[0]['mobile_no'];
                          $birthdayPresentWeek[$i]['franchisee_id']= $customer_data[0]['franchisee_id'];
                          $student_data=  Students::where('id','=',$birthdayPresentWeek[$i]['student_id'])->get();
                          $birthdayPresentWeek[$i]['student_name']=$student_data[0]['student_name'];
                        }
                        $f_id=Session::get('franchiseId');
                        
                        
                        
			$viewData = array('currentPage', 'mainMenu', 'f_id',
                                                           'birthday_data','birthday_data_month','birthday_month_startdays','birthdayPresentWeek',
                                                           'todaysMemberReg','membersCount',
                                                           'todaysNonmemberReg','NonmembersCount',
                                                            'customerCount', "reminderCount", 
                                                            'totalbpartyCount','todaysbpartycount',
                                                           'totalParentchildCourse','totalPrekgKindergarten','totalGradeschool','totalCourses',
							  'todaysCustomerReg','todaysEnrolledCustomers','enrolledCustomers','totalIntrovisitCount', 'introVisitCount', 'allIntrovisits', 'todaysFollowup', 
							  'todaysIntrovisit','activeRemindersCount');
			return View::make('pages.dashboard.upcoming',compact($viewData));
		}else{
			return Redirect::to("/");
		}
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
