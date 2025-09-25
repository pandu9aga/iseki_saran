<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Suggestion;
use Yajra\DataTables\Facades\DataTables;

class LeaderSuggestionController extends Controller
{
    public function index()
    {
        $page = "suggestion";

        $Id_User = session('Id_User');
        $user = User::find($Id_User);

        return view('leaders.suggestions.index', compact('page', 'user'));
    }

    public function getSuggestions()
    {
        $rifaDb = config('database.connections.rifa.database');

        $query = Suggestion::select([
                'suggestions.Id_Suggestion',
                'suggestions.Id_Member',
                'suggestions.Team_Suggestion',
                'suggestions.Theme_Suggestion',
                'suggestions.Date_First_Suggestion',
                'suggestions.Date_Last_Suggestion',
                'suggestions.Status_Suggestion',
                'suggestions.Content_Suggestion',
                'suggestions.Improvement_Suggestion',
                'suggestions.Content_Photos_Suggestion',
                'suggestions.Improvement_Photos_Suggestion',
                'suggestions.Score_A_Suggestion',
                'suggestions.Score_B_Suggestion',
                'suggestions.Comment_Suggestion',
                'suggestions.Id_User',
                'suggestions.Acceptance_First_Suggestion',
                'suggestions.Acceptance_Last_Suggestion',
                $rifaDb.'.employees.nama as member_nama',
                'users.Name_User as user_name',
            ])
            ->leftJoin($rifaDb.'.employees', $rifaDb.'.employees.id', '=', 'suggestions.Id_Member')
            ->leftJoin('users', 'users.Id_User', '=', 'suggestions.Id_User');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('Status_Suggestion', function ($row) {
                if ($row->Status_Suggestion == 1) {
                    return '<span class="badge bg-success">Sudah Selesai</span>';
                }
                return '<span class="badge bg-warning text-dark">Belum Selesai</span>';
            })
            ->editColumn('Score_A_Suggestion', function ($row) {
                if ($row->Score_A_Suggestion === null) {
                    return '';
                }

                $map = [
                    0 => 0,
                    1 => 600,
                    2 => 1200,
                    3 => 3600,
                    4 => 9000,
                    5 => 15000,
                    6 => 21000,
                    7 => 30000,
                    8 => 39000,
                    9 => 48000,
                    10 => 60000,
                    11 => 72000,
                    12 => 84000,
                    13 => 96000,
                    14 => 105000,
                    15 => 129000,
                ];

                $val = $row->Score_A_Suggestion;
                $converted = $map[$val] ?? 0;

                return "{$val} = Rp " . number_format($converted, 0, ',', '.') . " rb/tahun";
            })
            ->editColumn('Score_B_Suggestion', function ($row) {
                if (!$row->Score_B_Suggestion) {
                    return '';
                }

                $scores = json_decode($row->Score_B_Suggestion, true);
                if (!is_array($scores)) {
                    return '';
                }

                $parts = [];
                if (isset($scores['kreatifitas'])) {
                    $parts[] = 'Kreatifitas: ' . $scores['kreatifitas'];
                }
                if (isset($scores['ide'])) {
                    $parts[] = 'Ide: ' . $scores['ide'];
                }
                if (isset($scores['usaha'])) {
                    $parts[] = 'Usaha: ' . $scores['usaha'];
                }

                return implode(', ', $parts);
            })
            ->editColumn('Acceptance_First_Suggestion', function ($row) {
                return $row->Acceptance_First_Suggestion !== null
                    ? str_pad($row->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT)
                    : '';
            })
            ->editColumn('Acceptance_Last_Suggestion', function ($row) {
                return $row->Acceptance_Last_Suggestion !== null
                    ? str_pad($row->Acceptance_Last_Suggestion, 5, '0', STR_PAD_LEFT)
                    : '';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="'.route('leader.suggestion.show', $row->Id_Suggestion).'" class="btn btn-sm btn-primary">
                        <span class="pc-micon"><i class="material-icons-two-tone text-white">edit</i></span>
                    </a>
                    <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="'.$row->Id_Suggestion.'" 
                            data-name="'.$row->Content_Suggestion.'">
                        <span class="pc-micon"><i class="material-icons-two-tone text-white">delete</i></span>
                    </button>
                ';
            })
            ->rawColumns([
                'Status_Suggestion',
                'Score_A_Suggestion',
                'Score_B_Suggestion',
                'Acceptance_First_Suggestion',
                'Acceptance_Last_Suggestion',
                'action'
                ])
            ->make(true);
    }

    public function store(Request $request)
    {
        // $lastNumber = Suggestion::max('Acceptance_First_Suggestion') ?? 0;
        // $newNumber = $lastNumber + 1;

        $request->validate([
            'Id_Member' => 'required',
            'Team_Suggestion' => 'required',
            'Theme_Suggestion' => 'required',
            'Content_Suggestion' => 'required',
        ]);

        $suggestion = Suggestion::create([
            'Id_Member' => $request->Id_Member,
            'Team_Suggestion' => $request->Team_Suggestion,
            'Theme_Suggestion' => $request->Theme_Suggestion,
            'Content_Suggestion' => $request->Content_Suggestion,
            'Date_First_Suggestion' => Carbon::today(),
            'Status_Suggestion' => 0,
            // 'Acceptance_First_Suggestion' => $newNumber,
        ]);

        return redirect()->route('suggestion.show', $suggestion->Id_Suggestion)
                         ->with('success', 'Saran berhasil ditambahkan.');
    }

    // detail saran
    public function show($id)
    {
        $page = "suggestion";

        $Id_User = session('Id_User');
        $user = User::find($Id_User);

        $suggestion = Suggestion::findOrFail($id);
        $contentPhotos = json_decode($suggestion->Content_Photos_Suggestion, true) ?? [];
        $improvementPhotos = json_decode($suggestion->Improvement_Photos_Suggestion, true) ?? [];

        return view('leaders.suggestions.detail', compact('page', 'user', 'suggestion', 'contentPhotos', 'improvementPhotos'));
    }

    // update per-field (inline via modal)
    public function updateField(Request $request, $id)
    {
        $suggestion = Suggestion::findOrFail($id);

        // ---------------- Validasi umum ----------------
        $request->validate([
            'field' => 'required|string',
            'value' => 'nullable',
        ]);

        $field = $request->field;

        // hanya field ini yang boleh diupdate
        $allowed = [
            'Team_Suggestion', 'Theme_Suggestion', 'Date_First_Suggestion',
            'Date_Last_Suggestion', 'Status_Suggestion', 'Content_Suggestion',
            'Improvement_Suggestion', 'Score_A_Suggestion',
            'Score_B_Suggestion', 'Comment_Suggestion', 'Id_User',
            'Acceptance_First_Suggestion', 'Acceptance_Last_Suggestion'
        ];

        if (!in_array($field, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Kolom tidak valid.'
            ]);
        }

        // ---------------- Simpan Data ----------------
        if ($field === 'Score_B_Suggestion') {
            $value = $request->input('value', []);
            $suggestion->Score_B_Suggestion = json_encode($value);
        } else {
            $suggestion->$field = $request->value;
        }

        $suggestion->save();

        // ---------------- Response ----------------
        $formatted = $suggestion->$field;

        if ($field === 'Score_A_Suggestion') {
            $formatted = $suggestion->score_a_formatted;
        } elseif ($field === 'Score_B_Suggestion') {
            $formatted = $suggestion->score_b_formatted;
        } elseif ($field === 'Id_User') {
            $formatted = $suggestion->user->Name_User; // ambil relasi User
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'field'   => $field,
            'value'   => $formatted
        ]);
    }

    public function destroy($Id_Suggestion)
    {
        $suggestion = Suggestion::findOrFail($Id_Suggestion);

        // Hapus foto permasalahan (Content_Photos_Suggestion)
        if (!empty($suggestion->Content_Photos_Suggestion)) {
            $contentPhotos = json_decode($suggestion->Content_Photos_Suggestion, true);
            if (is_array($contentPhotos)) {
                foreach ($contentPhotos as $photo) {
                    $path = public_path('uploads/contents/' . $photo);
                    if ($photo && file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
        }

        // Hapus foto perbaikan (Improvement_Photos_Suggestion)
        if (!empty($suggestion->Improvement_Photos_Suggestion)) {
            $improvementPhotos = json_decode($suggestion->Improvement_Photos_Suggestion, true);
            if (is_array($improvementPhotos)) {
                foreach ($improvementPhotos as $photo) {
                    $path = public_path('uploads/improvements/' . $photo);
                    if ($photo && file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
        }

        // Hapus record dari database
        $suggestion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully'
        ]);
    }

    public function export($id)
    {
        $suggestion = Suggestion::with(['user','member'])->findOrFail($id);

        // Load template Excel
        $spreadsheet = IOFactory::load(storage_path('app/templates/template_saran_perbaikan.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // Mapping cell sesuai request
        $sheet->setCellValue('K4', $suggestion->Date_First_Suggestion ?? '');
        $sheet->setCellValue('AD4', $suggestion->Date_Last_Suggestion ?? '');
        $sheet->setCellValue('C5', $suggestion->member->nik ?? '');
        $sheet->setCellValue('Q5', $suggestion->Team_Suggestion ?? '');
        $sheet->setCellValue('Y5', $suggestion->member->nama ?? '');
        $sheet->setCellValue('Q11', $suggestion->Theme_Suggestion ?? '');
        $sheet->setCellValue('B16', $suggestion->Content_Suggestion ?? '');
        $sheet->setCellValue('AG16', $suggestion->Improvement_Suggestion ?? '');
        $sheet->setCellValue('AF38', $suggestion->Comment_Suggestion ?? '');
        $sheet->setCellValue('BC39', $suggestion->user->Name_User ?? '');

        // Output file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Saran_Perbaikan_'.$suggestion->Id_Suggestion.'.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

}
