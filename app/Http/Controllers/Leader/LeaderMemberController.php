<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $members = Member::with('division')->whereNull('deleted_at')->orderBy('nik')->get();
        return view('leaders.members.index', compact('page', 'user', 'members'));
    }

    public function checkMember(Request $request)
    {
        $request->validate([
            'nik' => 'required|max:20',
        ]);

        $member = Member::with('division')
            ->where('nik', $request->nik)
            ->whereNull('deleted_at')
            ->first();

        if ($member) {
            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Member tidak ditemukan.'
        ], 404);
    }

    public function checkMemberByNik($nik)
    {
        $member = Member::with('division')
            ->where('nik', $nik)
            ->whereNull('deleted_at')
            ->first();

        if ($member) {
            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Member tidak ditemukan.'
        ], 404);
    }
}
