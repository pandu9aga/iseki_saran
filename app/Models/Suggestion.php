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
        'Acceptance_Suggestion'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'Id_Member', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_User', 'Id_User');
    }

}
