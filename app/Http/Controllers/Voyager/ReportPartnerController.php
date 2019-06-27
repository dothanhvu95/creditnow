<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportPartnerController extends Controller
{
    public function __construct() {
    }
    
    public function index(Request $req , $name)
    {
        return $name;
    }
}
