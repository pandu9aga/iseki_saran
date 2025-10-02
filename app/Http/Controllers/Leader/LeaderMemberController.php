<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;

class LeaderMemberController extends Controller
{
    public function index()
    {
        $page = "member";

        $Id_User = session('Id_User');
        $user = User::find($Id_User);

        // Ambil semua member dari database RIFA
        $members = Member::with('division')->get();
        return view('leaders.members.index', compact('page', 'user', 'members'));
    }
}
