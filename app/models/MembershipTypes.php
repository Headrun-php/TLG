<?php

class MembershipTypes extends \Eloquent {
	protected $fillable = [];
	protected $table= 'membership_types';
	
	public function CustomerMembership(){
	
		return $this->has('CustomerMembership', 'membership_type_id');
	}
	
	
	static function getMembershipTypesForSelectBox(){
		
		return MembershipTypes::lists('name', 'id');
	}
	
	static function getMembershipTypes(){
	
		return MembershipTypes::all();
	}
	
	
	static function getMembershipTypeByID($membershipId){
		return MembershipTypes::find($membershipId);
	}
}