<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Lender extends Model
{
    public $table = 'lenders';

    protected $fillable = [
        'name', 'description', 'number_hsv' , 'logo' ,'status' , 'api','address','support','sdt','email','cs_id' ,'ratio_share'
    ];

    protected static function getLender(){
    		
    	return $data = Cache::remember('get_lender',100,function(){
            $lender = Lender::where('status',1)->pluck('name','id');
    		return $lender;
        });
    	    	
    }
}
