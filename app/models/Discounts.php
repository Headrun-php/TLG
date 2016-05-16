<?php

class Discounts extends \Eloquent {
      protected $table = 'discount_enrollment';
	protected $fillable = [];
     /*     // for old table discount  
     public static function createDiscountForSeason($data){
         $discount=new Discounts();
         $discount->season_id=$data['id'];
         $discount->franchisee_id=$data['franchisee_id'];
         $discount->discount_second_child=$data['discount_second_child'];
         $discount->discount_second_class=$data['discount_second_class'];
         $discount->created_by=Session::get('userId');
         $discount->created_at=date("Y-m-d H:i:s");
         $discount->save();
         return $discount;
     }
       
      */

        static function InsertMultipleDiscounts($data){
            $discount = new Discounts();
            $discount->franchisee_id = Session::get('franchiseId');
            $discount->number_of_classes = $data['no_of_class'];
            $discount->discount_percentage = $data['discount_prcnt'];
            $discount->discount_second_child = $data['DiscountForSecondChaild'];
            $discount->discount_second_class = $data['DiscountForSecondClass'];
            $discount->created_by = Session::get('userId');
            $discount->updated_by = Session::get('userId');
            $discount->created_at = date("Y-m-d H:i:s");
            $discount->updated_at = date("Y-m-d H:i:s");
            $discount->save();
            //return $discount;
            return Discounts::where('franchisee_id', '=', Session::get('userId'))
                                    ->update(array('discount_second_child'=> $data['DiscountForSecondChaild'], 'discount_second_class'=> $data['DiscountForSecondClass']));
        }
        
        
        public static function getEnrollmentDiscontByFranchiseId(){
            return Discounts::where('franchisee_id','=',Session::get('franchiseId'))
                             ->get();
        }

        static function approveDiscounts($data){
            return Discounts::where('franchisee_id', '=', Session::get('franchiseId'))
                                ->update(array('discount_second_class_approve'=> $data['classCheck'], 'discount_second_child_approve'=> $data['childCheck']));

        }
}