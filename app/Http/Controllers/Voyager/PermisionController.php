<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MenItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;



class PermisionController extends Controller
{
	public function index($id)
	{
		return view('vendor.voyager.roles.edit-add');
	}
}
