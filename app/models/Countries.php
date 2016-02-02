<?php

class Countries extends \Eloquent {
	protected $fillable = [];
	
	
	static function getCountries(){
		
		$countries   = DB::table('countries')->lists('name','code');
		//$provinces   = DB::table('provinces')->lists('name','code','ID')->where('country','=','IN')->get();
		//$provinces   = Provinces::where('country','=',$countryCode)->lists('name','ID as id');
		return $countries;
		
	}
}