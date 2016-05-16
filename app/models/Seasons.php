<?php

class Seasons extends \Eloquent {
    protected $table = 'seasons';
	protected $fillable = [];
        
        public function Batches(){
		return $this->hasMany('Batches','batch_id');
	}
        
        public  static function addSeason($data){
            $season_data=new Seasons();
            $season_data->franchisee_id=$data['franchisee_id'];
            $season_data->season_no=$data['season_no'];
            $season_data->season_name=$data['season_name'];
            if(isset($data['seasonType'])){
            $season_data->season_type=$data['seasonType'];
            }
            $season_data->start_date=$data['startdate'];
            $season_data->end_date=$data['enddate'];
            $season_data->session_no=$data['sessionno'];
            $season_data->created_by=Session::get('userId');
            $season_data->created_at=date("Y-m-d H:i:s");
            $season_data->save();
            return   $season_data->id;
         }
}