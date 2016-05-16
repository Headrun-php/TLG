<?php

class Batches extends \Eloquent {
	protected $fillable = [];
	
	
	public function Classes(){
		
		return $this->belongsTo('Classes', 'class_id');
	}
	
	public function BatchSchedule(){
		return $this->belongsTo('Batches', 'batch_id');
	}
	
	
	public function PaymentDues(){
		return $this->hasMany('PaymentDues','batch_id');
	}
	
	public function LeadInstructors(){
		return $this->belongsTo('User','lead_instructor');
	}
	
	public function AlternateInstructors(){
		return $this->belongsTo('User','alternate_instructor');
	}
	
	public function Seasons(){
		return $this->belongsTo('Seasons','season_id');
	}
	static function addBatches($input){
		
		
		
		$batch = new Batches();
		$batch->batch_name         = $input['batchName'];
		$batch->franchisee_id      = Session::get('franchiseId');
                $batch->season_id          =$input['season_id'];
		$batch->class_id           = $input['classId'];
		$batch->course_id          = $input['courseId'];
		$batch->start_date         = $input['startDate'];
		$batch->end_date           = $input['endDate'];
//                if(isset($input['classAmount'])){
//                    $batch->class_amount=$input['classAmount'];
//                }
		$batch->preferred_time     = $input['preferredTime'];
		$batch->preferred_end_time = $input['preferredEndTime'];
                if(isset($input['location_id'])){
                    $batch->location_id=$input['location_id'];
                }
		if($input['leadInstructor'] != ""){
			$batch->lead_instructor      = $input['leadInstructor'];
		}else{
			$batch->lead_instructor      = null;
		}
		
		if($input['alternateInstructor']  != ""){
			$batch->alternate_instructor = $input['alternateInstructor'];
		}else{
			$batch->alternate_instructor = null;
		}
		
		
		$batch->created_by         = Session::get('userId');
		$batch->created_at         = date("Y-m-d H:i:s");
		$batch->save();
		
		return $batch;
	}
	
	
	static function getAllBatchesByFranchiseeId($franchiseId){
		
		//Batches::where('','',$franchiseId)->get();
		
		//$batches = DB::table('batches')->join('classes', 'classes.id', '=', 'batches.id')->where('classes.franchisee_id', '=', $franchiseId)->get();
		$batches = Batches::with('Classes')->where('franchisee_id', '=', Session::get('franchiseId'))->get();
		return $batches;
		
		
		
	}
        
        static function getAllBatchesbySeasonId($franchiseId,$seasonId){
            return Batches::where('franchisee_id', '=', Session::get('franchiseId'))
                                            ->where('season_id','=',$seasonId)
                                            ->get();
        }

        static function getAllBatchesDatabySeasonId($franchiseId,$seasonId){
            return Batches:: 
                            where('franchisee_id', '=', Session::get('franchiseId'))
                                            ->where('season_id','=',$seasonId)
                                            ->get();
        }

        
	static function batchesByClassIdSeasonId($classId,$seasonId){
		
		
		return Batches::with('LeadInstructors')
                                                ->where('season_id','=',$seasonId)
                                                ->where('class_id', '=', $classId)
						->where('franchisee_id', '=', Session::get('franchiseId'))
		
						//->select('id')
						->get();
                 
             //   return Batches:://where('season_id','=',$seasonId)
                                //                ->where('class_id', '=', $classId)
                                //                ->where('franchisee_id', '=', Session::get('franchiseId'))
             //                                   get();
		//return Batches::with('Classes')->where('class_id', '=', $classId)->get();
	}
	
	
	
        static function getBatchDetailsById($batchId){
            $data= Batches::where('id','=',$batchId)
                            ->where('franchisee_id', '=', Session::get('franchiseId'))
                            ->get();
            $data=$data[0];
            return $data;
        }
        
        
        static function deleteBatchById($batchId){
            return Batches::where('id','=',$batchId)->delete();
        }
	
	
	
}