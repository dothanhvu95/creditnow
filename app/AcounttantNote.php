<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcounttantNote extends Model
{
    public $table = 'accountant_note';

    protected $fillable = [
        'date_note', 'comment', 'user_id' , 'tctd_id' ,'role_id' 
    ];
}
