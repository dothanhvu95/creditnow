<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
    *@param NULL
    *@return redirect url login
    */
    public function redirect()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/backend/admin/login';
        header( 'Location: '.$url );die;    
    }
}
