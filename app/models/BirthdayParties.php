<?php

class BirthdayParties extends \Eloquent {
	protected $fillable = [];
	protected $table = 'birthday_parties';
	
	public function Customers(){
	
		return $this->belongsTo('Customers', 'customer_id');
	}
	
	public function Students(){
	
		return $this->belongsTo('Students', 'student_id');
	}
	
	
	static function addbirthdayParty($inputs){
	
		
	
		$birthday = new BirthdayParties();
		$birthday->customer_id                   = $inputs['customerId'];
		$birthday->student_id                    = $inputs['kidsSelect'];
                if(isset($inputs['defaultBirthdayPrice'])){
                    $birthday->default_birthday_cost=$inputs['defaultBirthdayPrice'];
                }
		$birthday->additional_number_of_guests   = $inputs['additionalGuestCount'];
		$birthday->additional_half_hours         = $inputs['additionalHalfHourCount'];
		$birthday->additional_guest_price	 = $inputs['additionalGuestPrice'];
		$birthday->additional_halfhour_price     = $inputs['additionalHalfHourPrice'];
		$birthday->advance_amount_paid           = $inputs['advanceAmount'];
		$birthday->remaining_due_amount          = $inputs['remainingAmount'];
		
		$birthday->grand_total  				 = $inputs['grandTotal'];
		$birthday->birthday_party_date  	     = date('Y-m-d',strtotime($inputs['birthdayCelebrationDate']));
		$birthday->birthday_party_time  		 = date('H:i:s',strtotime($inputs['birthdayTime']));
		$birthday->created_at                    = date("Y-m-d H:i:s");
		$birthday->created_by                    = Session::get('userId');
		
		$birthday->save();
		
                
		return $birthday;
		
	}
	
	
	static function checkWhetherBirthdayPartyExists($student_id){
		
		$currentYear = date('Y');		
		$existingBirthdayParties = BirthdayParties::where('student_id', '=', $student_id)
													->whereYear('birthday_party_date','=', $currentYear)->get();		
		if(isset($existingBirthdayParties['0'])){
			return true;
				
		}
		return false;		
		
	}
	
	static function getBirthdaysByCustomer($customerId){
		return BirthdayParties::with('Customers', 'Students')->where('customer_id', '=', $customerId)->get();
	}
               static function getBpartyCount(){
             return DB::table('birthday_parties')
                                             ->join('customers', 'birthday_parties.customer_id', '=', 'customers.id')
                                             ->where('customers.franchisee_id', '=', Session::get('franchiseId'))
                                             ->count();
        }
        static function  getBpartyCountBytoday(){
             return DB::table('birthday_parties')
                                             ->join('customers', 'birthday_parties.customer_id', '=', 'customers.id')
                                             ->whereDate('birthday_parties.birthday_party_date', '=',  date("Y-m-d"))
                                             ->where('customers.franchisee_id', '=', Session::get('franchiseId'))
                                             ->count();
        } 
        static function getBirthdaybyId($id){
            $birthdaydetails= BirthdayParties::where('id','=',$id)->get();
            return ($birthdaydetails);
        }

    
}