<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencys extends Model
{
    public $table = 'agency';
    protected $fillable = [
        'name','description','number_hsv','ref_id','status','api','address','support','sdt','email','cs_id','ratio_share','contract','created_at','updated_at'
    ];
}
