<?php

class Location extends \Eloquent {
	protected $fillable = [];
        protected $table='location';
        
        public static function addLocation($dataArray,$season_id) {
            
            for($i=0;$i<count($dataArray);$i++){
                if($dataArray[$i]!=''){
                $location_data= new location();
                $location_data->season_id=$season_id;
                $location_data->location_name=$dataArray[$i];
                $location_data->franchisee_id=Session::get ( 'franchiseId' );
                $location_data->created_by=Session::get('userId');
                $location_data->created_at=date("Y-m-d H:i:s");
                $location_data->save();
                }
            }
        }
        
        public static function getLocationBySeasonId($seasonId){
            return Location::where('season_id','=',$seasonId)->get();
        }
}