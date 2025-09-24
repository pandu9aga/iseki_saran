<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Suggestion;
use Yajra\DataTables\Facades\DataTables;

class SuggestionController extends Controller
{
    public function getSuggestions(Request $request)
    {
        $Id_Member = $request->get('Id_Member');
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
            ])
            ->leftJoin($rifaDb.'.employees', $rifaDb.'.employees.id', '=', 'suggestions.Id_Member')
            ->where('suggestions.Id_Member', $Id_Member);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('Status_Suggestion', function ($row) {
                if ($row->Status_Suggestion == 1) {
                    return '<span class="badge bg-success">Sudah Selesai</span>';
                }
                return '<span class="badge bg-warning text-dark">Belum Selesai</span>';
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
                    <a href="'.route('suggestion.show', $row->Id_Suggestion).'" class="btn btn-sm btn-primary">
                        <span class="pc-micon"><i class="material-icons-two-tone text-white">edit</i></span>
                    </a>
                    <button class="btn btn-sm btn-danger delete-btn" 
                            data-id="'.$row->Id_Suggestion.'" 
                            data-name="'.$row->Content_Suggestion.'">
                        <span class="pc-micon"><i class="material-icons-two-tone text-white">delete</i></span>
                    </button>
                ';
            })
            ->rawColumns(['Status_Suggestion','action'])
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

        $Id_Member = session('Id_Member');
        $member = Member::find($Id_Member);

        $suggestion = Suggestion::findOrFail($id);
        $contentPhotos = json_decode($suggestion->Content_Photos_Suggestion, true) ?? [];
        $improvementPhotos = json_decode($suggestion->Improvement_Photos_Suggestion, true) ?? [];

        return view('members.suggestions.index', compact('page', 'member', 'suggestion', 'contentPhotos', 'improvementPhotos'));
    }

    // update per-field (inline via modal)
    public function updateField(Request $request, $id)
    {
        $suggestion = Suggestion::findOrFail($id);

        if ($request->field === 'Content_Photos_Suggestion') {
            $photos = json_decode($suggestion->Content_Photos_Suggestion, true) ?? [];
            $slot = $request->input('slot', 0);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/contents'), $name);

                // Hapus lama
                if (!empty($photos[$slot]) && file_exists(public_path('uploads/contents/'.$photos[$slot]))) {
                    unlink(public_path('uploads/contents/'.$photos[$slot]));
                }

                $photos[$slot] = $name;
            }

            $suggestion->Content_Photos_Suggestion = json_encode($photos);
            $suggestion->save();

            return response()->json(['success' => true, 'contentPhotos' => $photos]);
        }

        if ($request->field === 'Improvement_Photos_Suggestion') {
            $photos = json_decode($suggestion->Improvement_Photos_Suggestion, true) ?? [];
            $slot = $request->input('slot', 0);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/improvements'), $name);

                // Hapus lama
                if (!empty($photos[$slot]) && file_exists(public_path('uploads/improvements/'.$photos[$slot]))) {
                    unlink(public_path('uploads/improvements/'.$photos[$slot]));
                }

                $photos[$slot] = $name;
            }

            $suggestion->Improvement_Photos_Suggestion = json_encode($photos);
            $suggestion->save();

            return response()->json(['success' => true, 'improvementPhotos' => $photos]);
        }

        // Update field lain
        $request->validate([
            'field' => 'required|string',
            'value' => 'nullable',
        ]);

        $field = $request->field;

        if (!in_array($field, [
            'Team_Suggestion','Theme_Suggestion','Date_First_Suggestion',
            'Date_Last_Suggestion','Status_Suggestion','Content_Suggestion',
            'Content_Photos_Suggestion','Improvement_Suggestion',
            'Improvement_Photos_Suggestion','Score_A_Suggestion',
            'Score_B_Suggestion','Comment_Suggestion','Id_User',
            'Acceptance_First_Suggestion','Acceptance_Last_Suggestion'
        ])) {
            return response()->json(['success' => false, 'message' => 'Kolom tidak valid.']);
        }

        $suggestion->$field = $request->value;
        $suggestion->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($Id_Suggestion)
    {
        $suggestion = Suggestion::findOrFail($Id_Suggestion);
        $suggestion->delete();
        
        return response()->json(['success' => true, 'message' => 'Data delete successfully']);
    }
}
