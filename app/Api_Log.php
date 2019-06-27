<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api_Log extends Model
{
    public $table = 'api_logs';
    public $primary='id';
    public $timestamps=true;
    protected $fillable = [
        'name','cmnd','cccd','phone','score','loan','interest_rate','duration','status','user_id','referal_id','tctd_id','content'
    ];
    
}
