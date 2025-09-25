<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'kelompok_jabatan',
        'skck',
        'suket_sehat',
        'ijazah',
        'transkrip_nilai',
        'surat_pernyataan',
        'pas_foto',
        'foto_ktp',
        'email',
        'no_whatsapp',
    ];

    protected $casts = [
        'ijazah' => 'array',
        'transkrip_nilai' => 'array',
    ];
}
