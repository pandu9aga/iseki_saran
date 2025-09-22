<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index()
    {
        $page = "home";

        return view('members.home', compact('page'));
    }

}
