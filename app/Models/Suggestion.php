<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $table = 'suggestions'; // Nama tabel
    protected $primaryKey = 'Id_Suggestion'; // Nama primary key

    public $timestamps = false; // Jika tabel tidak memiliki created_at dan updated_at

    protected $fillable = [
        'Id_Member',
        'Team_Suggestion',
        'Theme_Suggestion',
        'Date_First_Suggestion',
        'Date_Last_Suggestion',
        'Status_Suggestion',
        'Content_Suggestion',
        'Improvement_Suggestion',
        'Content_Photos_Suggestion',
        'Improvement_Photos_Suggestion',
        'Score_A_Suggestion',
        'Score_B_Suggestion',
        'Comment_Suggestion',
        'Id_User',
        'Acceptance_First_Suggestion',
        'Acceptance_Last_Suggestion'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'Id_Member', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_User', 'Id_User');
    }

    public function getAcceptanceFirstSuggestionFormattedAttribute()
    {
        return $this->Acceptance_First_Suggestion !== null
            ? str_pad($this->Acceptance_First_Suggestion, 5, '0', STR_PAD_LEFT)
            : null; // atau '' kalau mau kosong
    }

    public function getAcceptanceLastSuggestionFormattedAttribute()
    {
        return $this->Acceptance_Last_Suggestion !== null
            ? str_pad($this->Acceptance_Last_Suggestion, 5, '0', STR_PAD_LEFT)
            : null; // atau '' kalau mau kosong
    }

    public function getScoreAFormattedAttribute()
    {
        if ($this->Score_A_Suggestion === null) {
            return null; // biar kosong kalau null
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

        $val = $this->Score_A_Suggestion;
        $converted = $map[$val] ?? 0;

        return "{$val} = Rp {$converted} rb/tahun";
    }

    public function getScoreBFormattedAttribute()
    {
        if (!$this->Score_B_Suggestion) {
            return null; // kosong kalau null
        }

        $scores = json_decode($this->Score_B_Suggestion, true);
        if (!is_array($scores)) {
            return null;
        }

        $result = [];
        $total = 0;

        if (isset($scores['kreatifitas'])) {
            $result['Kreatifitas'] = $scores['kreatifitas'];
            $total += $scores['kreatifitas'];
        }
        if (isset($scores['ide'])) {
            $result['Ide'] = $scores['ide'];
            $total += $scores['ide'];
        }
        if (isset($scores['usaha'])) {
            $result['Usaha'] = $scores['usaha'];
            $total += $scores['usaha'];
        }

        $result['Total'] = $total;

        return $result;
    }

    public function getTotalScoreAttribute()
    {
        $scoreA = $this->Score_A_Suggestion ?? 0; // Ambil Skor A
        $scoreBTotal = $this->score_b_formatted['Total'] ?? 0; // Ambil Total Skor B
        return $scoreA + $scoreBTotal;
    }
}
