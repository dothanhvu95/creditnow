<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LenderCs extends Model
{
    public $table = 'lenders_cs';
    protected $fillable = [
        'lender_id', 'agenci_id', 'cs_id' , 'job' ,'contact' , 'note','file','create_date','update_date'
    ];
}
