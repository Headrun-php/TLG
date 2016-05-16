<?php
use Carbon\Carbon;

class SeasonsController extends \BaseController {

    
        public function add(){
            if (Auth::check ()) {
                
            $currentPage = "AddSeasons_LI";
            $mainMenu = "SEASONS_MENU_MAIN";
            $viewdata=array('currentPage','mainMenu');
            return View::make('pages.seasons.seasonadd',  compact($viewdata));
            }
        }
        
        public function getstartenddays(){
            if(Auth::check()){
                $inputs=Input::all();
                $startdate=Carbon::createFromFormat('m/d/Y',$inputs['startdate']);
                $calculatedStartDate=Carbon::createFromFormat('m/d/Y',$inputs['startdate']);
                $enddate=Carbon::createFromFormat('m/d/Y',$inputs['enddate']);
                $calculatedEndDate=Carbon::createFromFormat('m/d/Y',$inputs['enddate']);
                $calculatedStartDate->startOfWeek();
                $calculatedEndDate->endofWeek();
                if((($startdate->toDateString())===($calculatedStartDate->toDateString())) && (($enddate->toDateString())===($calculatedEndDate->toDateString()))){
                $sessionno=($enddate->diffInHours($startdate));
                $sessionno=((floor($sessionno/24)+1)/7);
                return Response::json(array('status'=>'success','sessionstartdate'=>$startdate->toDateString(),'statusofseasondate'=>'correct','sessionenddate'=>$enddate->toDateString(),'sessionno'=>$sessionno));            
                }else{
                 return Response::json(array('status'=>'success','statusofseasondate'=>'wrong'));
                }
                
            }
        }
        
        public function addSeason(){
            if(Auth::check()){
                $inputs=Input::all();
                //calculating for week startdate and endate mon-sun 
                $startdate=Carbon::createFromFormat('m/d/Y',$inputs['startdate']);
                $startdate = $startdate->startOfWeek();
                $enddate=Carbon::createFromFormat('m/d/Y',$inputs['enddate']);
                $enddate=$enddate->endofWeek();
                
                //calculating for total sessions for selected days and getting data to insert to seasons
                $sessionno=($enddate->diffInHours($startdate));
                $sessionno=((floor($sessionno/24)+1)/7);
                $adddata['startdate']=$startdate->toDateString();                
                $adddata['enddate']=$enddate->toDateString();
                $adddata['seasonType']=$inputs['seasonType'];
                $adddata['sessionno']=$sessionno;
                $adddata['franchisee_id']=Session::get ( 'franchiseId' );
                $franchise_data=  Franchisee::where('id','=',$adddata['franchisee_id'])->get();
                
                //getting data for inserting new season no
                $season_no=Seasons::where('franchisee_id','=',$adddata['franchisee_id'])->max('season_no');
                $adddata['season_name']=$franchise_data[0]['franchisee_name'].' '.'Season'.' '.($season_no+1).'-'.$inputs['seasonType'];
                $adddata['season_no']=($season_no+1);
                
                //adding the new season to season table
                $season_id=Seasons::addSeason($adddata);
                $season_data=Seasons::where('id','=',$season_id)->get();
                $season_data=$season_data[0];
                
                
                
                // adding data to location table
                $location_data=Location::addLocation($inputs['location'], $season_id);
                        
                        
                
               // $season_data['discount_second_child']=$inputs['secondchilddiscount'];
               // $season_data['discount_second_class']=$inputs['secondclassdiscount'];
   
                //adding data to discount table and calculating holidays session
       //         $discount_data=Discounts::createDiscountForSeason($season_data);
                $holiday;
                if($inputs['title'][0]!=''){
                  for($i=0;$i<count($inputs['title']);$i++){
                    
                   $startdate=Carbon::createFromFormat('m/d/Y',$inputs['holidaystartdate'][$i]);
                   $enddate=Carbon::createFromFormat('m/d/Y',$inputs['holidayenddate'][$i]);
                   $startdate = $startdate->startOfWeek();
                   $enddate=$enddate->endofWeek();
                   $holiday[$i]['startdate']=$startdate->toDateString();
                   $holiday[$i]['enddate']=$enddate->toDateString();
                   $holiday[$i]['title']=$inputs['title'][$i];
                   $sessionno=($enddate->diffInHours($startdate));
                   $sessionno=((floor($sessionno/24)+1)/7);
                   $holiday[$i]['holidaysession']=$sessionno;
                 }
                 //adding data to holiday table
                 $holiday_data=Holidays::addSeasonHolidays($holiday,$season_data);
                
                 //Calculating and updating back to seasons table
                 $total_session=$adddata['sessionno'];
                 $totalholiday=0;
                 foreach($holiday as $vacation){
                     $totalholiday+=$vacation['holidaysession'];
                 }
                
                 $seasonupdate=Seasons::find($season_id);
                 $seasonupdate->session_no=($season_data['session_no']-$totalholiday);
                 $seasonupdate->save();
                    if($seasonupdate){
                return Response::json(array('status'=>'success','startdate'=>$season_data['start_date'],
                                            'enddate'=>$season_data['end_date'],'sessionafterholidays'=>($season_data['session_no']-$totalholiday)));
                    }
                }else{
                    if($season_data){
                    $seasonupdate=Seasons::find($season_id);
                    return Response::json(array('status'=>'success','startdate'=>$season_data['start_date'],
                                                'enddate'=>$season_data['end_date'],'sessionafterholidays'=>$season_data['session_no']));
                
                    }else{
                        return Response::json(array('status'=>'failure'));
                    }
                }
                
                
               
                }
        }
        public function createseason(){
            Seasons::addSeason();
        }
        public function getSeasonsForBatches(){
            $season_data=Seasons::where('franchisee_id','=',Session::get ( 'franchiseId' ))
                                  ->orderBy('id', 'DESC')
                                  ->get();
            if($season_data){
                return Response::json(array('status'=>'success','season_data'=>$season_data));
            }else{
                return Response::json(array('status'=>'failure'));
            }
        }
                public function getSeasonsForEnrollment(){
            $season_data=Seasons::where('franchisee_id','=',Session::get ( 'franchiseId' ))
                                  ->whereNotIn('season_type', ['Summer Season','Winter Season'])
                                  ->orderBy('id', 'DESC')
                                  ->get();
            if($season_data){
                return Response::json(array('status'=>'success','season_data'=>$season_data));
            }else{
                return Response::json(array('status'=>'failure'));
            }
        }
    
	/**
	 * Display a listing of the resource.
	 * GET /seasons
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::check ()) {
                
            $currentPage = "ViewSeasons_LI";
            $mainMenu = "SEASONS_MENU_MAIN";
            $season_data=  Seasons::where('franchisee_id','=',Session::get('franchiseId'))
                           ->orderBy('id','=','DESC') 
                           ->get();
            for($i=0;$i<sizeof($season_data);$i++){
                $user_data=User::where('id','=',$season_data[$i]['created_by'])->get();
                $user_data=$user_data[0];
                $season_data[$i]['created_by_name']=$user_data['first_name'].$user_data['last_name'];
            }
            $viewdata=array('currentPage','mainMenu','season_data');
            return View::make('pages.seasons.seasonview',  compact($viewdata));
            }
	}
        
        
        
        public function getLocationBySeasonId(){
            $inputs=  Input::all();
            $location_data= Location::where('season_id','=',$inputs['seasonId'])->get();
            
           return Response::json(array('status'=>'success','data'=>$location_data));
            
        }
        
        public function getSeasonDataBySeasonId(){
            $inputs=Input::all();
           // return Response::json(array('status'=>'success'));
            $end_date=Seasons::find($inputs['seasonId']);
            return Response::json(array('status'=>'success','end_date'=>date('m/d/Y', strtotime($end_date->end_date)),'data'=>$end_date));
        }

	/**
	 * Show the form for creating a new resource.
	 * GET /seasons/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /seasons
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /seasons/{id}
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
	 * GET /seasons/{id}/edit
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
	 * PUT /seasons/{id}
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
	 * DELETE /seasons/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}