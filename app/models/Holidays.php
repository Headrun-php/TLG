<?php
use Carbon\Carbon;
class Holidays extends \Eloquent {
	protected $fillable = [];
        protected $table='holidays';
        
      public static function addSeasonHolidays($inputs,$seasondata){
          
          for($i=0;$i<count($inputs);$i++){
          $holiday=new Holidays();
          $holiday->season_id=$seasondata['id'];
          $holiday->franchisee_id=$seasondata['franchisee_id'];
          $holiday->created_by=Session::get('userId');
          $holiday->created_at=date("Y-m-d H:i:s");
          $holiday->startdate=$inputs[$i]['startdate'];
          $holiday->enddate=$inputs[$i]['enddate'];
          $holiday->title=$inputs[$i]['title'];
          $holiday->save();
          }
          return $holiday;
      }
      
      public static function getHolidayDatabySeasonId($seasonId){
          return Holidays::where('season_id','=',$seasonId)
                         //  ->where('franchisee_id','=',Session::get('franchiseId'))
                           ->get();
      }
      
}