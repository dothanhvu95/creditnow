<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use App\HistoryLog_View;
use App\Referal;
use App\Agencys;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class ReferalsController extends Controller
{
    public function index(){
    	if(!Auth::user()){
            $this->redirect(); 
        }
      	$role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        $referal = Referal::paginate(10);
		return view('vendor.voyager.referals.browse',compact('referal'));
    }
    public function add_referal(Request $req)
    {
    	$param = $req->all();
    	// return $param;
    	if(!Auth::user()){
            $this->redirect(); 
        }

      	$role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;

        $email = Auth::user()->email;

        $agency_id = Agencys::select('id')->where('email',$email)->get();
        // return $user_id;
        (count($agency_id) > 0 ) ? $agency = $agency_id[0]['id'] : $agency = "";
        
        $data = array('name' => $param['name'] , 
        			  'slug' => $param['fullname'],
        			  'user_id' => $user_id,
                      'status' => 1,
        			  'agency_id' => $agency,
    					);
        $check_isset = Referal::where('name',$param['name'])->orWhere('slug',$param['fullname'])->count();
        // return $check_isset;
    	$check = Referal::where('user_id',$user_id)->count();
    	if ($role_id !== 1 && $role_id !== 4) {
    		if ($check >= 3) {
    			echo "<script>alert('Mỗi partner chỉ được tạo tối đa 3 referal');</script>";
                return redirect('admin/referals');
    		}
    		else if ($check_isset > 0) {
    			echo "<script>alert('Referal này đã tồn tại! vui lòng chọn tên khác');</script>";
                return redirect('admin/referals');
    		}
    		else
    		{
    			Referal::create($data);
    			echo "<script>alert('Thêm thành công');</script>";
                return redirect('admin/referals');
    		}
    	}
    	else
    	{
    		if ($check_isset > 0) {
    			echo "<script>alert('Referal này đã tồn tại! vui lòng chọn tên khác');</script>";
                return redirect('admin/referals');
    		}
    		else
    		{
    			Referal::create($data);
				echo "<script>alert('Thêm thành công');</script>";
	            return redirect('admin/referals');
    		}
    	}
    	// return $check;
    }
    public function trash_referal($id)
    {
        // return $id;
        Referal::where('id',$id)->delete();
        return redirect('admin/referals');
    }
    

}
