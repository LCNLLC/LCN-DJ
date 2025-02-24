<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ReferralController extends Controller
{

    public function index()
    {
         return view('frontend.user.refer.affiliate_rules_banner');
    }

}
