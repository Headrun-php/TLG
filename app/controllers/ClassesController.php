<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
class ClassesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function InsertNewClass(){
		$inputs = Input::all();
		$send_details = ClassesMaster::InsertNewClass($inputs);
		if($send_details){
			return Response::json(array('status'=> 'success', $send_details));
		}else{
			return Response::json(array('status'=> 'failure', $send_details));
		}
	}


	public function InsertNewClassFromFranchise(){
		$inputs = Input::all();
		$send_details = Classes::InsertNewClassFromFranchise($inputs);
		if($send_details){
			return Response::json(array('status'=> 'success', $send_details));
		}else{
			return Response::json(array('status'=> 'failure', $send_details));
		}
	}




	public function index()
	{
		//
		$currentPage  =  "COURSES";
		$mainMenu     =  "COURSES_MAIN";
		
		$inputs = Input::all();
		
			
		if(isset($inputs['className']) && isset($inputs['franchiseeCourse'])){
			
			
			/* echo '<pre>';
			print_r($inputs);
			exit(); */
			
			$classadd = Classes::addClasses($inputs);
			if ($classadd == "exists") {
				Session::flash('error', "Sorry, Class you are trying to add already exists");				
					
			} elseif(!$classadd) {
					
				Session::flash('error', "Sorry, Class Could not be added at the moment.");
				
					
			}else{
				Session::flash('msg', "Class added successfully.");
			}
		}
		
		$courseList = CoursesMaster::getCoursesList();
		//$classesMaster = ClassesMaster::getClassesMasterForDropDown();
		
		$franchiseeCourses = Courses::getFranchiseCoursesList(Session::get('franchiseId'));
		$classes = Classes::getAllClasses(1);
		return View::make('pages.classes.classes', compact('classesMaster', 'classes', 'courseList','franchiseeCourses','currentPage','mainMenu'));
	}



	public function add_new_classes(){
		$currentPage  =  "CLASSES";
		$mainMenu     =  "CLASSES_MAIN";
		$franchiseeCourses = CoursesMaster::getAllCourses();
		$getAllClassesMasters = ClassesMaster::getAllClassesMasters();
		for($i=0;$i<sizeof($getAllClassesMasters);$i++){
                $courseName=CoursesMaster::where('id','=',$getAllClassesMasters[$i]['course_master_id'])->get();
                $courseName=$courseName[0];
                $getAllClassesMasters[$i]['course_master_name']=$courseName['course_name'];
        }
		return View::make('pages.classes.add_new_classes', compact('currentPage','mainMenu', 'franchiseeCourses', 'getAllClassesMasters'));
	}


	public function add_new_class_franchise(){
		$currentPage  =  "CLASSES_FRANCHISE";
		$mainMenu     =  "CLASSES_MAIN";
		$franchiseeCourses = Courses::where('franchisee_id', '=', Session::get('franchiseId'))->get();
		$franchiseeBaseprice = ClassBasePrice::getBasePricebyFranchiseeId();
		$getAllClassesForFranchise = Classes::getAllClassesForFranchise();

		for($i=0;$i<sizeof($getAllClassesForFranchise);$i++){
			$courseMasterId = Courses::where('id','=',$getAllClassesForFranchise[$i]['course_id'])->get();
			$courseMasterId = $courseMasterId[0];
			$courseName = CoursesMaster::where('id', '=', $courseMasterId['master_course_id'])->get();
			$courseName = $courseName[0];

			$getAllClassesForFranchise[$i]['course_name']=$courseName['course_name'];

			$getBasePrice = ClassBasePrice::where('base_price_no', '=', $getAllClassesForFranchise[$i]['base_price_no'])->get();
			$getBasePrice = ClassBasePrice::where('base_price_no', '=', $getAllClassesForFranchise[$i]['base_price_no'])->get();
                //$getBasePrice = $getBasePrice[0];
                if($getAllClassesForFranchise[$i]['base_price_no'] == 0){
                	$getAllClassesForFranchise[$i]['base_price'] = '';
                }
                else{
                	$getAllClassesForFranchise[$i]['base_price']=$getBasePrice[0]['base_price'];	
                }
		}
		
        //return $getAllClassesForFranchise;
		return View::make('pages.classes.add_new_class_franchise', compact('currentPage','mainMenu', 'franchiseeCourses', 'franchiseeBaseprice', 'getAllClassesForFranchise'));
	}




	public function updateClassesMaster(){
		$inputs = Input::all();
		$send_details = ClassesMaster::updateClassesMaster($inputs);
		if($send_details){
			return Response::json(array('status'=>'success', $send_details));
		}else{
			return Response::json(array('status'=>'failure'));
		}
	}


	public function updateClassesBasePrice(){
		$inputs = Input::all();
		$send_details = ClassBasePrice::updateClassesBasePrice($inputs);
		if($send_details){
			return Response::json(array('status'=>'success', $send_details));
		}else{
			return Response::json(array('status'=>'failure'));
		}
	}

	public function getClassesByCourseId(){
		$inputs = Input::all();
		$ClassName = ClassesMaster::where('course_master_id', '=', $inputs['CoursemasterId'])->get();
		if($ClassName){
			return Response::json(array('status'=>'success', $ClassName));
		}else{
			return Response::json(array('status'=>'failure'));
		}
	}


	
	public function classesbymaster(){
		$franchiseeCourse = Input::get('franchiseeCourse');
		//$classess = ClassesMaster::getClassesMasterForDropDown($courseMasterId);
		$classess = $classess = Classes::getClassessByFranchiseeCourseId(Session::get('franchiseId'), $franchiseeCourse);
		header('Access-Control-Allow-Origin: *');
		return Response::json($classess);
		
	}
	
	
	public function classesbyCourse(){
		
		$franchiseeCourse = Input::get('franchiseeCourse');
		//$classess = ClassesMaster::getClassesMasterForDropDown($courseMasterId);
		$classess = $classess = Classes::getAllClassesLists(Session::get('franchiseId'), $franchiseeCourse);
		header('Access-Control-Allow-Origin: *');
		return Response::json($classess);
		
	}
	
	
	public function eligibleClassess(){

		$ageYear  = Input::get('ageYear');
		$ageMonth = Input::get('ageMonth');
		$gender   = Input::get('gender');
		$yearandMonth = Input::get('yearAndMonth');
                if($yearandMonth >= 4){
                     $classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $yearandMonth)
			->where("class_end_age", ">=", $yearandMonth)
			->where("age_start_limit_unit", "=", "months")
                        ->where("age_end_limit_unit", "=", "months")
			->get();
                }
		
                
		/*
		if($ageYear == 0){
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageMonth)
			->where("class_end_age", ">=", $ageMonth)
			->where("age_start_limit_unit", "=", "months")
			->get();
				
				
		}elseif(($ageYear > 0) &&($ageYear <= 3)){
				
			$yearandMonth = Input::get('yearAndMonth');
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $yearandMonth)	
			//$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", number_format($yearandMonth, 1, '.', ''))
			->where("class_end_age", ">=", $yearandMonth)
			//->where("age_end_limit_unit", "=", 'years')
                        ->where("age_start_limit_unit", "=", "months")
			->where("age_end_limit_unit", "=", "months")
			->get();
			//dd(DB::getQueryLog());
			//echo "1year";
				
		}
		elseif(($ageYear >= 3) &&  ($ageYear <= 6) ){
		
			//$yearandMonth = (12+$ageMonth);
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageYear)
			->where("class_end_age", ">=", $ageYear)
			//->where("age_end_limit_unit", "=", 'years')
			->where("age_end_limit_unit", "=", "years")
                        ->where("age_start_limit_unit", "=", "years")
			->get();
		
		
                }
		elseif(($ageYear >= 6) &&  $ageYear <=12 ){
		
			//$yearandMonth = (12+$ageMonth);
				
			$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageYear)
			->where("class_end_age", ">=", $ageYear)
			->where("age_end_limit_unit", "=", 'years')
                        ->where("age_start_limit_unit", "=", "years")
			//->where("age_end_limit_unit", "=", "months")
			->get();
		
		
		} *//*		
		else if($ageYear ==3 && $ageYear <4){
				
			$classesMaster = ClassesMaster::select('id')
			//->whereBetween('class_start_age',[3,4])
			->where("class_start_age", "<=", 3)
			->where("class_end_age", ">=", 4)
			->where("age_start_limit_unit", "=", "years")
			->get();
			//echo $ageYear;
			//dd(DB::getQueryLog());
		
		}
		else if($ageYear >=4 && $ageYear<6){
				
			$classesMaster = ClassesMaster::select('id')
			->whereBetween('class_start_age',[4,6])
			//->where("class_start_age", "<", 6)
			->where("class_end_age", "<", 6)
			->where("age_start_limit_unit", "=", "years")
			->get();
			//echo $ageYear;
			//dd(DB::getQueryLog());
		
		}else if($ageYear >= 6 && $ageYear<12){
				
			$classesMaster = ClassesMaster::select('id')
			->whereBetween('class_start_age',[6,12])
			/* ->where("class_start_age", "<=", $ageYear)
			 ->where("class_end_age", ">=", $ageYear)
			->where("age_start_limit_unit", "=", "years") */
		//	->where("gender", "=", $gender)
		//	->get();
				
			//dd(DB::getQueryLog());
		
		//}
                /* else{
		$classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $ageYear)
		->where("class_end_age", ">", $ageYear)
		->where("age_start_limit_unit", "=", "years")
		->get();
		//dd(DB::getQueryLog());
		} */
		
		$masterClassIDs = array();
		$i = 0;
		foreach($classesMaster->toArray() as $masterClass){
				
			$masterClassIDs[$i] = $masterClass['id'];
			$i++;
		}
		
		
		
		$classesEligible = DB::table('classes')
		->whereIn('class_master_id', $masterClassIDs)
		->where('franchisee_id', '=', Session::get('franchiseId'))
		->get();
		
		header('Access-Control-Allow-Origin: *');
		return Response::json($classesEligible);
	}
        
        
        public function eligibleClassessForOtherBatches(){
            $inputs=Input::all();
            $ageYearForBatch2=date_diff(date_create(date('Y-m-d',strtotime($inputs['studentDob']))), date_create($inputs['FutureAgeDate']))->y;
            $ageMonthForBatch2=date_diff(date_create(date('Y-m-d',strtotime($inputs['studentDob']))), date_create($inputs['FutureAgeDate']))->m;
            $yearAndMonthForBatch2 =($ageYearForBatch2 * 12) + ($ageMonthForBatch2);
            
            if($yearAndMonthForBatch2 >= 4){
                     $classesMaster = ClassesMaster::select('id')->where("class_start_age", "<=", $yearAndMonthForBatch2)
			->where("class_end_age", ">=", $yearAndMonthForBatch2)
			->where("age_start_limit_unit", "=", "months")
                        ->where("age_end_limit_unit", "=", "months")
			->get();
            }
            $masterClassIDs = array();
		$i = 0;
		foreach($classesMaster->toArray() as $masterClass){
				
			$masterClassIDs[$i] = $masterClass['id'];
			$i++;
		}
		
		$classesEligible = DB::table('classes')
                                        ->whereIn('class_master_id', $masterClassIDs)
                                        ->where('franchisee_id', '=', Session::get('franchiseId'))
                                        ->get();
		
                header('Access-Control-Allow-Origin: *');
		return Response::json($classesEligible);
        }
	
        
        
	public function batchesByClassSeasonId(){
		$inputs=Input::all();
		$classId = Input::get('classId');
                $seasonId=Input::get('seasonId');
		$batches = Batches::batchesByClassIdSeasonId($classId,$seasonId);
           
		$batchesJson = array();
		$i = 0;
		foreach ($batches as $batch){
				
			$batchesJson[$i]['id'] = $batch->id;
			$batchesJson[$i]['batch_name'] = $batch->batch_name;
			$batchesJson[$i]['day'] = date('l', strtotime($batch->start_date));
			$batchesJson[$i]['start_time'] = date('G:i a', strtotime($batch->preferred_time));
			$batchesJson[$i]['end_time'] = date('G:i a', strtotime($batch->preferred_end_time));
			if(isset($batch->LeadInstructors->first_name)){
				$batchesJson[$i]['instructor'] = '('.$batch->LeadInstructors->first_name.' '.$batch->LeadInstructors->last_name.')';
			}else{
				$batchesJson[$i]['instructor'] = '';
			}
			$i++;
		}
		
		header('Access-Control-Allow-Origin: *');
		return Response::json($batchesJson);
	}
	
	
	public function getDiscount(){
       $inputs=Input::all();
       //return Response::json(array('status'=>'success'));
       $discount_second_child;
       $discount_second_class;
       
       $discount_second_child_elligible=0;
       $discount_second_class_elligible=0;
       
       $DiscountApprove = Discounts::where('franchiseId', '=', Session::get('franchiseId'))->first();
       if($DiscountApprove['discount_second_child_approve'] != 0 && $DiscountApprove['discount_second_class_approve'] != 0){
        $discount_second_child_elligible=1;
        $discount_second_class_elligible=1;
       	$discount_second_child = $DiscountApprove['discount_second_child'];
       	$discount_second_class = $DiscountApprove['discount_second_class'];
       }elseif($DiscountApprove['discount_second_child_approve'] == 0 && $DiscountApprove['discount_second_class_approve'] != 0){
       	$discount_second_class_elligible=1;
        $discount_second_class = $DiscountApprove['discount_second_class'];
       }elseif($DiscountApprove['discount_second_child_approve'] != 0 && $DiscountApprove['discount_second_class_approve'] == 0){
       	$discount_second_child_elligible=1;
        $discount_second_child = $DiscountApprove['discount_second_child'];	
       }
       
       if($discount_second_class){
           
          
       }
       

       return Response::json(array('status'=>'success','discount_second_child'=> $discount_second_child, 'discount_second_class'=> $discount_second_class));
            
            
            
            
//        $inputs=Input::all();
//        $classes_count=  StudentClasses::where('student_id','=',$inputs['studentId'])
//                                        ->where('status','=','enrolled')
//                                        //->whereDate('enrollment_start_date','>=',date("Y-m-d"))
//                                        //->whereDate('enrollment_end_date','<=',date("Y-m-d"))
//                                        ->distinct('class_id')
//                                        ->count();
//        $discount=0;
//        if($classes_count>=1){
//            $discount_data= Discounts::where('season_id','=',$inputs['seasonId'])->get();
//            $discount=$discount_data[0]['discount_second_class'];
//        }else{
//            
//            $student_data = Students::where('id','=',$inputs['studentId'])->get();
//            $student_data = Students::where('customer_id','=',$student_data[0]['customer_id'])->get();
//            $sid;
//            foreach($student_data as $s){
//                $sid[]=$s['id'];
//            }
//            $count=0;
//            for($i=0;$i<count($sid);$i++){
//                if(StudentClasses::where('student_id','=',$sid[$i])->exists()){
//                 $count++;   
//                }
//            }
//            if($count>=1){
//                $discount_data= Discounts::where('season_id','=',$inputs['seasonId'])->get();
//                $discount=$discount_data[0]['discount_second_child'];
//            }
//            
//        }
        
//        return Response::json(array('status'=>'success','discount'=>$discount));
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
