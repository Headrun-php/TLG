<?php

class EstimateMaster extends \Eloquent {
	protected $fillable = [];
	protected $table = "estimate_master";

	static function insertEstimateMasterDetails($data){
		$insert = new EstimateMaster();
                $insert->estimate_master_no = $data['estimate_master_no'];
                if(isset($data['estimate_id'])){
                    $insert->estimate_id = $data['estimate_id'];
                }else{
                    $insert->estimate_id =0;
                }
                $insert->created_at = date("Y-m-d H:i:s");
                $insert->updated_at = date("Y-m-d H:i:s");
		$insert->save();
		return $insert;
	}
        
       
}