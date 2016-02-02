<?php

class Franchisee extends \Eloquent {
	protected $fillable = [];
	
	protected $table = 'franchisees';
	
	
	public function Users(){
		
		return $this->hasMany('User','franchisee_id');
	}
	
	
}