<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lender extends Model
{
    public $table = 'lenders';

     protected $fillable = [
        'name', 'description', 'number_hsv' , 'logo' ,'status' , 'api','address','support','sdt','email','cs_id' ,'ratio_share'
    ];
}
