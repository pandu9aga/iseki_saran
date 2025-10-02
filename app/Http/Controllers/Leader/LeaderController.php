<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Member;
use App\Models\Suggestion;
use Carbon\Carbon;

class LeaderController extends Controller
{
    public function index(){
        $page = "dashboard";
        $today = Carbon::today();

        $Id_User = session('Id_User');
        $user = User::find($Id_User);

        return view('leaders.dashboard', compact('page', 'today', 'user'));
    }   
    
    public function memberStats()
    {
        // Total member
        $total = Member::count();

        // Jumlah member per division
        $byDivision = Member::select('divisions.nama', DB::raw('count(employees.id) as total'))
            ->join('divisions', 'employees.division_id', '=', 'divisions.id')
            ->groupBy('divisions.nama')
            ->get();

        return response()->json([
            'total' => $total,
            'byDivision' => $byDivision
        ]);
    }

    public function stats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $byTeam = Suggestion::select('Team_Suggestion')
            ->selectRaw('COUNT(*) as total')
            ->whereYear('Date_First_Suggestion', $currentYear)
            ->whereMonth('Date_First_Suggestion', $currentMonth)
            ->groupBy('Team_Suggestion')
            ->get();

        return response()->json([
            'byTeam' => $byTeam,
            'month' => Carbon::now()->translatedFormat('F Y'),
        ]);
    }
}
