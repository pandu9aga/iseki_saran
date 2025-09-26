<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
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

        // ---------------- Khusus Acceptance ----------------
        if ($field === 'Acceptance_First_Suggestion') {
            if ($suggestion->Acceptance_First_Suggestion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor penerimaan awal sudah ada.'
                ]);
            }
            $next = (Suggestion::max('Acceptance_First_Suggestion') ?? 0) + 1;
            $suggestion->Acceptance_First_Suggestion = $next;
            $suggestion->save();

            return response()->json([
                'success' => true,
                'message' => 'Nomor penerimaan awal berhasil ditetapkan.',
                'field'   => $field,
                'value'   => $next
            ]);
        }

        if ($field === 'Acceptance_Last_Suggestion') {
            if ($suggestion->Acceptance_Last_Suggestion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor penerimaan akhir sudah ada.'
                ]);
            }
            $next = (Suggestion::max('Acceptance_Last_Suggestion') ?? 0) + 1;
            $suggestion->Acceptance_Last_Suggestion = $next;
            $suggestion->save();

            return response()->json([
                'success' => true,
                'message' => 'Nomor penerimaan akhir berhasil ditetapkan.',
                'field'   => $field,
                'value'   => $next
            ]);
        }

        // ---------------- Simpan Data umum ----------------
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
        $spreadsheet = IOFactory::load(storage_path('app/templates/saran_perbaikan.xlsx'));

        foreach ($spreadsheet->getDefinedNames() as $definedName) {
            $spreadsheet->removeDefinedName($definedName->getName());
        }

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

        // === Lingkaran outline pink berdasarkan Theme_Suggestion ===
        $positions = [
            'keselamatan' => 'C8',
            'kualitas'    => 'E8',
            'cost'        => 'G8',
            'waktu'       => 'I8',
            'lingkungan'  => 'K8',
            'moral'       => 'M8',
            'fasilitas'       => 'W15',
            'mould jig'       => 'AA15',
            'set up'       => 'AG15',
            'material'       => 'AK15',
            'metode'       => 'AO15',
            'informasi'       => 'AS15',
        ];

        $theme = strtolower(trim($suggestion->Theme_Suggestion ?? ''));
        $targetCell = null;
        foreach ($positions as $keyword => $cell) {
            if (stripos($theme, $keyword) !== false) {
                $targetCell = $cell;
                break;
            }
        }

        $tmpFiles = []; // simpan semua temp file supaya bisa dihapus nanti

        if ($targetCell && function_exists('imagecreatetruecolor')) {
            $size = 80;
            $thickness = 10;
            $img = imagecreatetruecolor($size, $size);
            imagesavealpha($img, true);
            $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparent);

            $pink = imagecolorallocate($img, 255, 0, 151);
            imagesetthickness($img, $thickness);
            $margin = $thickness + 6;
            imageellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $pink);

            $tmpFile = sys_get_temp_dir() . '/circle_theme_' . $suggestion->Id_Suggestion . '.png';
            imagepng($img, $tmpFile);
            imagedestroy($img);
            $tmpFiles[] = $tmpFile;

            $drawing = new Drawing();
            $drawing->setName('ThemeCircle');
            $drawing->setPath($tmpFile);
            $drawing->setCoordinates($targetCell);
            $specialCells = ['C8','E8','G8','I8','K8','M8'];
            if (in_array($targetCell, $specialCells)) {
                $drawing->setOffsetX(12);
                $drawing->setOffsetY(-3);
            } else {
                $drawing->setOffsetX(-3);
                $drawing->setOffsetY(0);
            }
            $drawing->setWidth(36);
            $drawing->setHeight(36);
            $drawing->setWorksheet($sheet);
        }

        // === Lingkaran oranye di Status (AL5 untuk 0, AN5 untuk 1) ===
        $statusCell = null;
        if ($suggestion->Status_Suggestion == 0) {
            $statusCell = 'AL5';
        } elseif ($suggestion->Status_Suggestion == 1) {
            $statusCell = 'AN5';
        }

        if ($statusCell && function_exists('imagecreatetruecolor')) {
            $size = 80;
            $thickness = 10;
            $img = imagecreatetruecolor($size, $size);
            imagesavealpha($img, true);
            $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparent);

            $orange = imagecolorallocate($img, 212, 109, 0); // oranye
            imagesetthickness($img, $thickness);
            $margin = $thickness + 6;
            imageellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $orange);

            $tmpFile = sys_get_temp_dir() . '/circle_status_' . $suggestion->Id_Suggestion . '.png';
            imagepng($img, $tmpFile);
            imagedestroy($img);
            $tmpFiles[] = $tmpFile;

            $drawing = new Drawing();
            $drawing->setName('StatusCircle');
            $drawing->setPath($tmpFile);
            $drawing->setCoordinates($statusCell);
            $drawing->setOffsetX(-2);
            $drawing->setOffsetY(5);
            $drawing->setWidth(64);
            $drawing->setHeight(64);
            $drawing->setWorksheet($sheet);
        }

        // === Lingkaran outline hitam berdasarkan Score_A_Suggestion ===
        $scoreMap = [
            0  => 'E37',
            1  => 'F37',
            2  => 'G37',
            3  => 'H37',
            4  => 'I37',
            5  => 'J37',
            6  => 'K37',
            7  => 'L37',
            8  => 'M37',
            9  => 'O37',
            10 => 'Q37',
            11 => 'S37',
            12 => 'U37',
            13 => 'W37',
            14 => 'Y37',
            15 => 'AA37',
        ];

        if (!is_null($suggestion->Score_A_Suggestion) && isset($scoreMap[$suggestion->Score_A_Suggestion])) {
            $scoreCell = $scoreMap[$suggestion->Score_A_Suggestion];

            if (function_exists('imagecreatetruecolor')) {
                $size = 80;
                $thickness = 10;
                $img = imagecreatetruecolor($size, $size);
                imagesavealpha($img, true);
                $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
                imagefill($img, 0, 0, $transparent);

                $black = imagecolorallocate($img, 0, 0, 0);
                imagesetthickness($img, $thickness);
                $margin = $thickness + 6;
                imageellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $black);

                $tmpFile = sys_get_temp_dir() . '/circle_score_' . $suggestion->Id_Suggestion . '.png';
                imagepng($img, $tmpFile);
                imagedestroy($img);
                $tmpFiles[] = $tmpFile;

                $drawing = new Drawing();
                $drawing->setName('ScoreCircle');
                $drawing->setPath($tmpFile);
                $drawing->setCoordinates($scoreCell);
                // offset beda untuk 0–7 dan 8–15
                if ($suggestion->Score_A_Suggestion <= 7) {
                    $drawing->setOffsetX(0);
                } else {
                    $drawing->setOffsetX(15);
                }
                $drawing->setOffsetY(-2);
                $drawing->setWidth(32);
                $drawing->setHeight(32);
                $drawing->setWorksheet($sheet);
            }
        }

        // === Lingkaran outline hitam berdasarkan Score_B_Suggestion (JSON) ===
        if (!empty($suggestion->Score_B_Suggestion)) {
            $scoreB = json_decode($suggestion->Score_B_Suggestion, true);

            if (is_array($scoreB)) {
                $mappingB = [
                    'kreatifitas' => [
                        0 => 'Y42', 1 => 'Z42', 2 => 'AA42',
                        3 => 'AB42', 4 => 'AC42', 5 => 'AD42',
                    ],
                    'ide' => [
                        0 => 'Y43', 1 => 'Z43', 2 => 'AA43',
                        3 => 'AB43', 4 => 'AC43', 5 => 'AD43',
                    ],
                    'usaha' => [
                        0 => 'Y44', 1 => 'Z44', 2 => 'AA44',
                        3 => 'AB44', 4 => 'AC44', 5 => 'AD44',
                    ],
                ];

                $total = 0;
                foreach ($mappingB as $key => $map) {
                    if (isset($scoreB[$key])) {
                        $val = (int) $scoreB[$key];
                        $total += $val;

                        if (isset($map[$val]) && function_exists('imagecreatetruecolor')) {
                            $cell = $map[$val];
                            $size = 80;
                            $thickness = 10;
                            $img = imagecreatetruecolor($size, $size);
                            imagesavealpha($img, true);
                            $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
                            imagefill($img, 0, 0, $transparent);

                            $black = imagecolorallocate($img, 0, 0, 0);
                            imagesetthickness($img, $thickness);
                            $margin = $thickness + 6;
                            imageellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $black);

                            $tmpFile = sys_get_temp_dir() . '/circle_scoreB_' . $key . '_' . $suggestion->Id_Suggestion . '.png';
                            imagepng($img, $tmpFile);
                            imagedestroy($img);
                            $tmpFiles[] = $tmpFile;

                            $drawing = new Drawing();
                            $drawing->setName('ScoreB_' . $key);
                            $drawing->setPath($tmpFile);
                            $drawing->setCoordinates($cell);
                            $drawing->setOffsetX(0);
                            $drawing->setOffsetY(-2);
                            $drawing->setWidth(32);
                            $drawing->setHeight(32);
                            $drawing->setWorksheet($sheet);
                        }
                    }
                }

                // Tulis total ke AA45
                $sheet->setCellValue('AA45', $total);
            }
        }

        // === Insert gambar Content & Improvement ===
        if (!empty($suggestion->Content_Photos_Suggestion)) {
            $contentPhotos = json_decode($suggestion->Content_Photos_Suggestion, true);

            if (is_array($contentPhotos)) {
                foreach ($contentPhotos as $i => $photoName) {
                    $filePath = public_path('uploads/contents/'.$photoName);
                    if (!empty($photoName) && file_exists($filePath)) {
                        $cell = $i == 0 ? 'B19' : ($i == 1 ? 'B24' : null);
                        if ($cell) {
                            $drawing = new Drawing();
                            $drawing->setName('ContentPhoto'.($i+1));
                            $drawing->setPath($filePath);
                            $drawing->setCoordinates($cell);
                            $drawing->setOffsetX(200);
                            $drawing->setOffsetY(50);
                            $drawing->setWidthAndHeight(600, 360); // otomatis scale
                            $drawing->setWorksheet($sheet);
                        }
                    }
                }
            }
        }

        if (!empty($suggestion->Improvement_Photos_Suggestion)) {
            $improvePhotos = json_decode($suggestion->Improvement_Photos_Suggestion, true);

            if (is_array($improvePhotos)) {
                foreach ($improvePhotos as $i => $photoName) {
                    $filePath = public_path('uploads/improvements/'.$photoName);
                    if (!empty($photoName) && file_exists($filePath)) {
                        $cell = $i == 0 ? 'AG19' : ($i == 1 ? 'AG24' : null);
                        if ($cell) {
                            $drawing = new Drawing();
                            $drawing->setName('ImprovementPhoto'.($i+1));
                            $drawing->setPath($filePath);
                            $drawing->setCoordinates($cell);
                            $drawing->setOffsetX(200);
                            $drawing->setOffsetY(50);
                            $drawing->setWidthAndHeight(600, 360); // otomatis scale
                            $drawing->setWorksheet($sheet);
                        }
                    }
                }
            }
        }

        // === Mapping Acceptance_First_Suggestion ===
        if (!empty($suggestion->Acceptance_First_Suggestion)) {
            // isi AR3, AT3, AV3
            $sheet->setCellValue('AR3', 6);
            $sheet->setCellValue('AT3', 2);
            $sheet->setCellValue('AV3', 0);

            // format jadi 5 digit (misal: 00001, 00025, dst)
            $accFirst = str_pad($suggestion->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT);

            // isi digit ke cell
            $sheet->setCellValue('AX3', substr($accFirst, 0, 1));
            $sheet->setCellValue('AZ3', substr($accFirst, 1, 1));
            $sheet->setCellValue('BB3', substr($accFirst, 2, 1));
            $sheet->setCellValue('BD3', substr($accFirst, 3, 1));
            $sheet->setCellValue('BF3', substr($accFirst, 4, 1));
        }

        // Output file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'Saran_Perbaikan_'.$suggestion->Id_Suggestion.'.xlsx';

        return response()->streamDownload(function() use ($writer, $tmpFiles) {
            $writer->save('php://output');
            foreach ($tmpFiles as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

}
