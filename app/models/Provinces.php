<?php

class Provinces extends \Eloquent {
	protected $fillable = [];
	
	static function getProvinces($countryCode = "IN"){
		
		$provinces   = DB::table('provinces')->where('country','=','IN')->lists('name','code','ID');
		//$provinces   = DB::table('provinces')->lists('name','code','ID')->where('country','=','IN')->get();
		//$provinces   = Provinces::where('country','=',$countryCode)->lists('name','ID as id');
		return $provinces;
	}
}