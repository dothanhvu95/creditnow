<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    public $table = 'history_log';
    protected $fillable = [
        'tctd_id', 'admin_note', 'status' , 'cs_id' ,'cs_name' ,'user_group_id'
    ];
}
