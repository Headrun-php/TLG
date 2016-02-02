<?php

class CustomerMembership extends \Eloquent {
	protected $fillable = [];
	protected $table = 'customer_membership';
	
	public function Customers(){
	
		return $this->hasMany('Customers', 'customer_id');
	}
	
	public function MembershipTypes(){
	
		return $this->belongsTo('MembershipTypes', 'membership_type_id');
	}
	
	static function addMembership($inputs){
		
		$customerMembership = new CustomerMembership();
		$customerMembership->customer_id        = $inputs['customer_id'];
		$customerMembership->membership_type_id = $inputs['membership_type_id'];
		$customerMembership->status             = "active";
		$customerMembership->action             = "default";		
		$customerMembership->created_by         = Session::get('userId');
		$customerMembership->created_at         = date("Y-m-d H:i:s");
		$customerMembership->save();
		
		return $customerMembership;
		
	}
	
	static function getCustomerMembership($customerId){
		return CustomerMembership::with('MembershipTypes')->where("customer_id", "=", $customerId)
									->where("status", "=", 'active')
									->first();
									//->where("status", "=", "active")->get();
	}
         static function getMembertodaysRegCount(){
            return CustomerMembership::join('customers', 'customer_membership.customer_id', '=', 'customers.id')
                                            ->whereDate('customer_membership.created_at', '=',  date("Y-m-d"))                                         
					    ->where('customers.franchisee_id', '=',  Session::get('franchiseId'))
					    ->count();
        }
        static function getMemberCount(){
            return CustomerMembership::join('customers', 'customer_membership.customer_id', '=', 'customers.id')                                       
                                            ->where('customers.franchisee_id', '=',  Session::get('franchiseId'))
                                            ->count();
        
        }
        static function getNonMembertodaysRegCount(){
                           $s=DB::table('customers')
                                ->leftJoin('customer_membership', 'customer_id', '=', 'customers.id')        
                                ->whereDate('customers.created_at', '=',  date("Y-m-d"))                                     
                                ->get();    
                            $i=0;
                            foreach ($s as $user)
                               {
                                if($user->membership_type_id==''){
                                           $i++;   
                                }   
                               }
                       return $i; 
                    }
        static function getNonMemberCount(){
                            $s=DB::table('customers')
                                ->leftJoin('customer_membership', 'customer_id', '=', 'customers.id')
                                ->where('customers.franchisee_id', '=',  Session::get('franchiseId'))
                                ->get();    
                            $i=0;
                            foreach ($s as $user)
                               {
                                if($user->membership_type_id==''){
                                           $i++;   
                                }   
                               }
                       return $i; 
        
        }
}