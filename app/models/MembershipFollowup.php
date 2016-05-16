<?php

class MembershipFollowup extends \Eloquent {
	protected $fillable = [];
        protected $table='membership_followup';
        
   static function createMembershipfollowup($input){
       $membershipFollowup=new MembershipFollowup();
       $membershipFollowup->customer_id=$input['customer_id'];
       $membershipFollowup->membership_id=$input['id'];
       $membershipFollowup->franchisee_id=Session::get('userId');
       $membershipFollowup->created_at=date('Y-m-d H:i:s');
       $membershipFollowup->created_by=Session::get('userId');
       $membershipFollowup->save();
       return $membershipFollowup;
   }
}