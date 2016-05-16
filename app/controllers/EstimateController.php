<?php

class EstimateController extends \BaseController {



	public function insertEstimateDetails(){
                if(Auth::check()){
			$inputs = Input::all();
                        $update['estimate_master_id']=0;
                        if($inputs['estimate_master_no']==0){
                            //** create new estimate MasterDetails **//
                            $data['estimate_master_no']=Estimate::max('estimate_master_no');
                            $data['estimate_master_no']=$data['estimate_master_no']+1;
                            $sendEstimateMasterDetails = EstimateMaster::insertEstimateMasterDetails($data);
                            $inputs['estimate_master_no']=$sendEstimateMasterDetails->estimate_master_no;
                            $update['estimate_master_id']=$sendEstimateMasterDetails->id;
                        }
			$sendEstimateDetails = Estimate::insertEstimateDetails($inputs);
                        if($update['estimate_master_id']!=0){
                            $update_estimate_master=  EstimateMaster::find($update['estimate_master_id']);
                            $update_estimate_master->estimate_id=$sendEstimateDetails->id; 
                            $update_estimate_master->save();
                        }else{
                            //** create estimate_master for new estimate with same estimate_master_no  **//
                            $data['estimate_master_no']=$sendEstimateDetails->estimate_master_no;
                            $data['estimate_id']=$sendEstimateDetails->estimate_id;
                            EstimateMaster::insertEstimateMasterDetails($data);
                        }
			if($sendEstimateDetails){
				return Response::json(array('status'=> "success", 'data'=>$sendEstimateDetails));
			}else{
				return Response::json(array('status'=> "failure",));
			}
		}
	}


	public function insertEstimateMasterDetails(){
		if(Auth::check()){
			$inputs = Input::all();
			$sendEstimateMasterDetails = EstimateMaster::insertEstimateMasterDetails($inputs);
			if($sendEstimateMasterDetails){
				return Response::json(array('status'=> "success", $sendEstimateMasterDetails));
			}else{
				return Response::json(array('status'=> "failure",));
			}
		}
	}
        
        
        public function cancelBatchEstimate(){
            if(Auth::check()){
                $inputs = Input::all();
                $sendBatchId = Estimate::cancelBatchEstimate($inputs);
                if($sendBatchId){
                    return Response::json(array('status'=> "success", $sendBatchId));
                }else{
                    return Response::json(array('status'=> "failure",));
            }
}
}

	/**
	 * Display a listing of the resource.
	 * GET /estimate
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /estimate/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /estimate
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /estimate/{id}
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
	 * GET /estimate/{id}/edit
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
	 * PUT /estimate/{id}
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
	 * DELETE /estimate/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}