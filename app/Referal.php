<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Referal extends Model
{
    public $table = 'referals';
    protected $fillable = [
        'user_id','name','slug','count','url','created_at','updated_at','status','agency_id'
    ];
}
