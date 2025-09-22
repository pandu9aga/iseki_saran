<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\Suggestion;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index()
    {
        $page = "home";

        $Id_Member = session('Id_Member');
        $member = Member::find($Id_Member);

        return view('members.home', compact('page', 'member'));
    }
}
