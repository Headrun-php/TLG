<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
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
			
                        //customers
                        $todaysCustomerReg= 1; //Customers::getCustomertodaysRegCount();                        
                        $customerCount = Customers::getCustomerCount();
                        
                        //Members
                        $todaysMemberReg= CustomerMembership::getMembertodaysRegCount();
                        $membersCount= CustomerMembership::getMemberCount();
                        
                        //Non Members
                        $todaysNonmemberReg= CustomerMembership::getNonMembertodaysRegCount();
                        $NonmembersCount= CustomerMembership::getNonMemberCount();
                        
                        //Enrolled customers(kids)
			$reminderCount = Comments::getReminderCountByFranchiseeId();
			$enrolledCustomers = StudentClasses::getEnrolledCustomers();
			
			//Introvisit
                        $totalIntrovisitCount = IntroVisit::getIntrovistCount();
			$introVisitCount = IntroVisit::getIntrovisitBytoday();
			$allIntrovisits  = IntroVisit::getAllActiveIntrovisit();
			
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
			
			
			$viewData = array('currentPage', 'mainMenu', 
                                                           'todaysMemberReg','membersCount',
                                                           'todaysNonmemberReg','NonmembersCount',
                                                            'customerCount', "reminderCount", 
                                                            'totalbpartyCount','todaysbpartycount',
                                                           'totalParentchildCourse','totalPrekgKindergarten','totalGradeschool','totalCourses',
							  'todaysCustomerReg','enrolledCustomers','totalIntrovisitCount', 'introVisitCount', 'allIntrovisits', 'todaysFollowup', 
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
