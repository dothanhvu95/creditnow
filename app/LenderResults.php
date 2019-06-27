<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LenderResults extends Model
{
    public $table ='lender_results';

     protected $fillable = [
     	'tctd_id',
     	'content',
     	'status',
     	'log_id',
     	'ngay_giai_ngan',
     	'so_tien_giai_ngan',
     	'created_at',
     	'updated_at'
     ];
}
