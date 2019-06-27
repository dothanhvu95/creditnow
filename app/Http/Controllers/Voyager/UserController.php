<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Hash;

class UserController extends Controller
{

    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user($role,$param)
    {
        $result = User::where([['role_id',$role],['email','like','%'.$param.'%']])
            ->orWhere([['role_id',$role],['email_2','like','%'.$param.'%']])
            ->orWhere([['role_id',$role],['phone','like','%'.$param.'%']])
            ->orWhere([['role_id',$role],['phone_2','like','%'.$param.'%']])
            ->orWhere([['role_id',$role],['name','like','%'.$param.'%']])
            ->orderBy('created_at', 'desc')
	    ->get();       
        return $result;
    }

    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user_tctd(Request $req)
    {
        $param = $req->input('name_tctd');
        $user_tctd = $this->get_user(9,$param);
        return view('admin.partner.browse',compact('user_tctd'));
    }
    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user_nguoivay(Request $req)
    {
        $param = $req->input('name_nguoivay');
        $user_nguoivay =  $this->get_user(2,$param);
        return view('admin.borrower.browse',compact('user_nguoivay'));
    }
    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user_agencines(Request $req)
    {
        $param = $req->input('name_agencines');
        $user_agencines =  $this->get_user(8,$param);
        return view('admin.agence.browse',compact('user_agencines'));
    }
    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user_customer_care(Request $req)
    {
        $param = $req->input('name_customer_care');
        $user_customercare =  $this->get_user(5,$param);
        return view('admin.customercare.browse',compact('user_customercare'));
    }
    /**
    *Update cic note admin
    *@param NULL
    *@return View
    */
    public function get_user_customercaremanager(Request $req)
    {
        $param = $req->input('customer_care_manager');
        $user_customercaremanager =  $this->get_user(6,$param);
        return view('admin.customercaremanager.browse',compact('user_customercaremanager'));
    }

    /**
    *Update cic note admin
    *@param id , Request
    *@return bool
    */

    public function update_profile($id , Request $req)
    {
        $param = $req->all();
        $arr = array(
                    'name' => $param['name'],
                    'email' => $param['email'],
                    'avatar' => $param['avatar']
                );
        if ($param['password'] !== null) {
            $arr['password'] = Hash::make($param['password']);
        }

        $update = User::find($id)->update($arr);
        if ($update == true) {
            return redirect('admin/users/'.$id.'/edit'); 
        }
    }

}
