<?php

use Illuminate\Support\Facades\DB;
class Cities extends \Eloquent {
	protected $fillable = [];
	
	static function getCities($regionId, $countryId = "IN"){	
		
		$cities = Cities::where('country', '=', $countryId)->where('region', '=', $regionId)->lists('name','ID');
		$queries = DB::getQueryLog();
		
		/* print_R($queries);
		
		dd($cities); */
		
		return $cities;
	}
}