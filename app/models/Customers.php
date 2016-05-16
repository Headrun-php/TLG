<?php
use Carbon\Carbon;

class Customers extends \Eloquent {
	protected $fillable = [];
	
	
	public function Students(){
		return $this->hasMany('Students','customer_id');
	}
	
	public function Comments(){
		return $this->hasMany('Comments','customer_id');
	}
	
	public function BatchSchedule(){
		return $this->hasMany('BatchSchedule','customer_id');
	}
	
	public function Events(){
		return $this->belongsTo('Events','source_event');
	}
	
	public function CustomerMembership(){
		return $this->hasMany('CustomerMembership','customer_id')->where('status', '=','active');
	}
	
	
	public function Orders(){
		return $this->hasMany('Orders','customer_id');
	}
	
	
	
	static function addCustomers($inputs){
		
		
		/* [customerName] => dasf
		[customerEmail] => asdfasdf
		[customerMobile] => asdf
		[building] => asdf
		[apartment] => asdfasdf
		[lane] => asdfasdf
		[locality] => asdf
		[state] => asdfasdf
		[city] => asdf
		[zipcode] => sdfasdf
		[source] => asdfa
		[referredBy] => asdf
		[introVisit] => on */
		
		$customer = new Customers();
		$customer->franchisee_id  = Session::get('franchiseId');
		$customer->customer_name  = $inputs['customerName'];
                $customer->customer_lastname=$inputs['customerLastName'];
		$customer->customer_email = $inputs['customerEmail'];
		$customer->mobile_no      = $inputs['customerMobile'];	
		$customer->building       = $inputs['building'];		
		$customer->apartment_name = $inputs['apartment'];
		$customer->lane           = $inputs['lane'];
		$customer->locality       = $inputs['locality'];
		$customer->state          = $inputs['state'];
		$customer->city           = $inputs['city'];
		$customer->zipcode        = $inputs['zipcode'];
		$customer->source         = $inputs['source'];
		$customer->stage          = "INIITATED";
		
		if(isset($inputs['eventsId']) && $inputs['eventsId'] != ""){
			
			$customer->source_event     = $inputs['eventsId'];
			
		}
		
		
		
		$customer->referred_by    = $inputs['referredBy'];
		$customer->created_by     = Session::get('userId');
		$customer->created_at     = date("Y-m-d H:i:s");
		$customer->save();
		return $customer;
	}
	
	
	static function saveCustomers($inputs){
	
	
		$customer = Customers::find($inputs['customerId']);
		$customer->franchisee_id  = Session::get('franchiseId');
		$customer->customer_name  = $inputs['customerName'];
                $customer->customer_lastname  = $inputs['customerLastName'];
		$customer->customer_email = $inputs['customerEmail'];
		$customer->mobile_no      = $inputs['customerMobile'];
		$customer->building       = $inputs['building'];
		$customer->apartment_name = $inputs['apartment'];
		$customer->lane           = $inputs['lane'];
		$customer->locality       = $inputs['locality'];
                if($inputs['state']==''){
		 $customer->state          = 0;
                }else{
                $customer->state          = $inputs['state'];
                }
                if(isset($inputs['city'])){
                if($inputs['city']==''){
		 $customer->city          = 0;
                }else{
                $customer->city          = $inputs['city'];
                }
                }
                
		$customer->zipcode        = $inputs['zipcode'];
		$customer->source         = $inputs['source'];
		//$customer->stage          = 'inquire';
		$customer->referred_by    = $inputs['referredBy'];
		$customer->created_by     = Session::get('userId');
		$customer->updated_at     = date("Y-m-d H:i:s");
		/* if(isset($inputs['eventsId']) && $inputs['eventsId'] != ""){
		
		$customer->source_event     = $inputs['eventsId'];
		
		} */
		
		$customer->save();
		return $customer;
	}
        static function getAllCustomersByFranchiseeId($franchiseeId){
            $customers = Customers::where('franchisee_id','=',$franchiseeId) 
                            ->get();
                return $customers;
        }
        
	static function getAllCustomerMembersByFranchiseeId($franchiseeId){
                $presentdate=  Carbon::now();
		$customer_members=  CustomerMembership::where('membership_start_date','<=',$presentdate->toDateString())
                                                        ->where('membership_end_date','>=',$presentdate->toDateString())
                                                        ->select('customer_id')
                                                        ->get();
               
                $id;
                foreach($customer_members as $c){
                    $id[]=$c['customer_id'];
                }
               
               
                $customers = Customers::where('franchisee_id','=',$franchiseeId)
                            ->whereIn('id',$id)
                            ->orderBy('id','Desc')
                            ->get();
                 return $customers;
                
                 
                
            
                /*
		$customers = Customers:: join('customer_membership','customers.id','=','customer_membership.customer_id')
                                        ->where('status','=','active')
                                        ->where('franchisee_id', '=', $franchiseeId)
                                        ->get();
		return $customers;
		*/
	}
        static function getAllCustomerNonMembersByFranchiseeId($franchiseeId){
		$presentdate=  Carbon::now();
		$customer_members=  CustomerMembership::where('membership_start_date','<=',$presentdate->toDateString())
                                                        ->where('membership_end_date','>=',$presentdate->toDateString())
                                                        ->select('customer_id')
                                                        ->get();
               
                $id;
                foreach($customer_members as $c){
                    $id[]=$c['customer_id'];
                }
                 
                $customers = Customers::where('franchisee_id','=',$franchiseeId)
                            ->whereNotIn('id',$id)
                            ->orderBy('id','Desc')
                            ->get();
    
		return $customers;
               
	}
	
	static function getAllCustomersForDropdown($franchiseeId){
		
		$customers = Customers::where('franchisee_id', '=', $franchiseeId)->lists('customer_name', 'id');
		return $customers;
		
	}
	
	static function getCustomerByEmail($email){
		
		if($email != ""){
			return Customers::where("customer_email","=",$email)->take(1)->get();
		}
	}
	
	
	static function getCustomersById($customerId){
		
		$customer = Customers::with('Events','CustomerMembership')->where('id','=',$customerId)->take(1)->get();
		return $customer['0'];
	}
	
	static function getCustomerCount(){
		
		return Customers::where('franchisee_id', '=',  Session::get('franchiseId'))
						->count();
	}
	
	static function getEnrolledCustomerCount(){
	
		return Customers::count();
	}
        static function getCustomertodaysRegCount(){
            		return Customers::whereDate('created_at', '=',  date("Y-m-d"))
                                                ->where('franchisee_id', '=',  Session::get('franchiseId'))
						->count();
        }        
}