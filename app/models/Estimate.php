<?php

class Estimate extends \Eloquent {
	protected $fillable = [];
	protected $table = "estimate";

	static function insertEstimateDetails($data){
		$InsertEstimatedData = new Estimate();

		$InsertEstimatedData->customer_id = $data['customer_id'];
                if(isset($data['estimate_master_no'])){
                    $InsertEstimatedData->estimate_master_no=$data['estimate_master_no'];
                }
		$InsertEstimatedData->student_id = $data['student_id'];
		$InsertEstimatedData->franchise_id = Session::get('franchiseId');
		$InsertEstimatedData->season_id = $data['season_id'];
		$InsertEstimatedData->batch_id = $data['batch_id'];
		$InsertEstimatedData->class_id = $data['class_id'];
		$InsertEstimatedData->enroll_start_date = $data['enroll_start_date'];
		$InsertEstimatedData->enroll_end_date = $data['enroll_end_date'];
		$InsertEstimatedData->total_selected_classes = $data['total_selected_classes'];
		$InsertEstimatedData->no_of_availbale_classes = $data['no_of_available_classes'];
		$InsertEstimatedData->no_of_opted_classes = $data['no_of_opted_classes'];
		$InsertEstimatedData->number_of_classes_used = 0;
		$InsertEstimatedData->batch_amount = $data['batch_amount'];
		$InsertEstimatedData->class_use_status = '';
		$InsertEstimatedData->is_cancelled = 0;
                $InsertEstimatedData->created_at = date("Y-m-d H:i:s");
                $InsertEstimatedData->updated_at = date("Y-m-d H:i:s");
		$InsertEstimatedData->save();
		return $InsertEstimatedData;
	}
        
        static function cancelBatchEstimate($id){
            return Estimate::where('id', '=', $id)->update('is_cancelled','1');
        }

}