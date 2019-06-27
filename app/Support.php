<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    public $table = 'support';
    //
    protected $fillable = [
        'name', 'phone', 'email', 'content_support', 'created_at', 'updated_at'
    ];
}
