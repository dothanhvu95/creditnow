<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincial extends Model
{
    public $table = 'devvn_tinhthanhpho';

    protected static function getCity(){
    	$city = Provincial::pluck('name',"matp");
    	return $city; 
    }
}
