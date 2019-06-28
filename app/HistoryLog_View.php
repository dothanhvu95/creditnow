<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CicLog;
use App\User;
use App\HistoryLog_View;
use App\HistoryLog;
use App\UserTax;
use App\Lender;
use App\Provincial;
use App\LenderResults;
use App\UserGroup;
use App\Referal;

class HistoryLog_View extends Model
{
    public $table = 'history_view';


    /**
     * Manager contract customers
     * @param $request
     * @return array
     */
    
    public static function listHsv($params){


        $sql = 'status = 1';
        $sort = ['created_at','desc'];


        if(!empty($params)){
        	$agence       = isset($params['agence']) ? $params['agence'] : '';
	        $tctd        = isset($params['tctd']) ? $params['tctd'] : '';
	        $type         = isset($params['type']) ? $params['type'] : '';
	        $status       = isset($params['status']) ? $params['status'] : '';
	        $key          = isset($params['key']) ? $params['key'] : '';
	        $from_date_tn = isset($params['from_date_tn']) ? $params['from_date_tn'] :'';
	        $to_date_tn   = isset($params['to_date_tn']) ? $params['to_date_tn'] :'';
	        $point        = isset($params['point']) ? $params['point'] : '';
	        $show        = isset($params['show']) ? $params['show'] : '';
	        $qlead       = isset($params['qlead']) ? $params['qlead'] : '';

	        if(!empty($type)){
	                if($type === '1'){
	                    $sql= $sql." AND history_log_id IS NOT NULL"; 
	                }else
	                {
	                    $sql= $sql." AND history_log_id IS NULL "; 
	                }
	            }

	        if(!empty($tctd)) {
	            $sql = $sql." AND tctd_id_hist = '{$tctd}'";
	        }
	        if(!empty($agence)){
	            $sql = $sql." AND user_ref = '{$agence}'";
	        }

	        if(!empty($status)){
	            $sql = $sql." AND trangthai = {$status}";   
	        }

	        if(!empty($key)){
	            $sql = $sql." AND (cmnd LIKE '%{$key}%' OR phone LIKE '%{$key}%' OR name LIKE '%{$key}%' )";
	        }

	         if(!empty($from_date_tn)){
	            $sql = $sql." AND created_at >=  '{$from_date_tn} 00:00:00' ";
	        }
	         if(!empty($to_date_tn)){
	            $sql = $sql." AND created_at <=  '{$to_date_tn} 23:59:59' ";
	        }
	           
	         if(!empty($qlead)){
	            $sql = $sql." AND qlead = {$qlead}";
	        }
	            
	         if(!empty($point)){
	            if($point === '1'){
	            $sort = ['final_score','asc'];
	            }
	            if($point === '2' ){
	                $sort = ['final_score','desc'];
	            } 
	            if($point === '3'){
	                $sort = ['khoanvay','asc'];
	            }
	            if($point === '4' ){
	                $sort = ['khoanvay','desc'];
	            }


	        }
        }
        



            $list = HistoryLog_View::whereRaw($sql)->orderBy($sort[0],$sort[1])->get()->toArray();
			
            
            $total_chichamdiem = HistoryLog_View::whereRaw($sql)->where([['progress_info','=',5],['history_log_id','=',null],['status','=',1]])->distinct('cmnd')->count();
            $total_gui = HistoryLog_View::whereRaw($sql)->where([['progress_info','>',0],['history_log_id','!=',null],['status','=',1]])->distinct('cmnd')->count();
        
        	$result = array(
        		'history' => $list,
        		'total_chichamdiem'=> $total_chichamdiem,
        		'total_gui' => $total_gui
        	);
        	return $list;
        
        
    }
    protected static function getList($sql,$sort1,$rort2,$show){
    	$history = HistoryLog_View::select('name','cid_customer_name','agencies','history_log_id','id','phone','cmnd','khoanvay','status_name','address1','tctd_id_hist','age','final_score','created_at','debt','qlead','progress_info','referal_name','trangthai','status')->whereRaw($sql)->orderBy($sort1,$sort1)->paginate($show);
    	return $history;
    }
    protected static function getTotalPoint($sql){
    	$data = HistoryLog_View::whereRaw($sql)->where([['history_log_id','=',null],['status','=',1]])->count();
    	return $data;
    }
    protected static function getTotalHsv($sql){
    	$data = HistoryLog_View::whereRaw($sql)->where([['history_log_id','!=',null],['status','=',1]])->count();
    	return $data;
    }
}
