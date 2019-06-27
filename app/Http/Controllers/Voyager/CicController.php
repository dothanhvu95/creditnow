<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use Illuminate\Support\Facades\Auth;

class CicController extends Controller
{

    public function index($id){
        $user_id = Auth::id();
        $log = CicLog::find($id)->toArray();
		return view('admin.log', compact('log'));
    }
    /**
    *Update cic note admin
    *@param $id
    *@return boolean
    */
    public function update_note_admin(Request $req , $id)
    {
    	$note = $req->input('admin_note');
    	$log = CicLog::find($id);
    	$log->admin_note = $note;
        
        $log->check_admin = 1;
    	$log->save();
    	if ($log->save() === true) {
    		return redirect('admin/cic-logs');	
    	}
    }
    public function get_DB()
    {
        $email = 'tranquangtuyengss@gmail.com';
        $verify_code = 1334;
        $log = CicLog::where([['email', '=', 'admin@admin.com'],['verify_code','=','1334']])->limit(1)->get()->toArray();
        echo "<pre>";
        echo json_encode($log);
        
        // echo json_encode($log);
    }

}
