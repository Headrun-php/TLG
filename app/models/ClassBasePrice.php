<?php

class ClassBasePrice extends \Eloquent {
    protected $fillable = [];
    protected $table = 'classes_baseprice';
    
    public function Users(){
        return $this->belongsTo('User','created_by');
    }
    
    public static function insertBasePrice($inputs){
        $base_price=new ClassBasePrice();
        $base_price->base_price_no=(ClassBasePrice::max('base_price_no')+1);
        $base_price->base_price=$inputs['base_price'];
        $base_price->franchise_id=Session::get('franchiseId');
        $base_price->created_by=Session::get('userId');
        $base_price->created_at=date("Y-m-d H:i:s");
        $base_price->save();
        return $base_price;
    }
    public static function getBasePricebyFranchiseeId(){
        return ClassBasePrice::where('franchise_id','=',Session::get('franchiseId'))->get();
    }

    static function updateClassesBasePrice($data){
        //return $data;
        if($data['BasePriceNo'] == 0){
            return true;
        }else{
            $update =  ClassBasePrice::where('base_price_no', '=', $data['BasePriceNo'])
                           ->update(array('base_price'=> $data['BasePrice']));
            return $update;
        }
    }
}