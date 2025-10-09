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

    public function notSubmit()
    {
        $monthInput = Carbon::now()->format('Y-m');

        // Ambil ID member yang sudah submit bulan tersebut
        $submittedIds = Suggestion::whereYear('Date_First_Suggestion', substr($monthInput, 0, 4))
            ->whereMonth('Date_First_Suggestion', substr($monthInput, 5, 2))
            ->pluck('Id_Member')
            ->toArray();

        // Ambil semua member yang belum submit
        $notSubmittedQuery = Member::with('division')
            ->whereNotIn('id', $submittedIds);

        // Hitung total keseluruhan
        $total = $notSubmittedQuery->count();

        // Hitung per divisi
        $result = (clone $notSubmittedQuery)
            ->selectRaw('division_id, COUNT(*) as total_not_submit')
            ->groupBy('division_id')
            ->with('division:id,nama')
            ->get()
            ->map(function ($item) {
                return [
                    'team' => $item->division->nama ?? '-',
                    'total_not_submit' => $item->total_not_submit,
                ];
            })
            ->values();

        return response()->json([
            'month' => Carbon::now()->translatedFormat('F Y'),
            'total' => $total,
            'byDivision' => $result,
        ]);
    }

    public function notSign(Request $request)
    {
        $rifaDb = config('database.connections.rifa.database');
        $monthInput = $request->input('month', Carbon::now()->format('Y-m'));

        try {
            [$year, $month] = explode('-', $monthInput);
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Format bulan tidak valid. Gunakan format YYYY-MM.'], 400);
        }

        // Ambil total semua saran yang belum ditandatangani bulan ini
        $total = Suggestion::whereNull('Id_User')
            ->whereBetween('Date_First_Suggestion', [$startDate, $endDate])
            ->count();

        // Ambil total per divisi
        $byDivision = Suggestion::selectRaw($rifaDb . '.divisions.nama as division, COUNT(*) as total_not_signed')
            ->leftJoin($rifaDb . '.employees', $rifaDb . '.employees.id', '=', 'suggestions.Id_Member')
            ->leftJoin($rifaDb . '.divisions', $rifaDb . '.divisions.id', '=', $rifaDb . '.employees.division_id')
            ->whereNull('suggestions.Id_User')
            ->whereBetween('suggestions.Date_First_Suggestion', [$startDate, $endDate])
            ->groupBy($rifaDb . '.divisions.nama')
            ->orderBy($rifaDb . '.divisions.nama', 'asc')
            ->get();

        return response()->json([
            'month' => Carbon::now()->translatedFormat('F Y'),
            'total' => $total,
            'byDivision' => $byDivision,
        ]);
    }
}
