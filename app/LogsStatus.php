<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;

class LogsStatus extends Model
{
   	public $table = 'logs_status';

   	protected static function getStatus(){
   		
   		
		return $data = Cache::remember('Expe_hot',100,function() {
            $status = LogsStatus::where("status","1")->pluck("name","id");
   			return $status;
        });
   	}
}
