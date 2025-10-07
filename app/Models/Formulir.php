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

    public function setIjazahAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['ijazah'] = json_encode($decoded ?: []);
        } else {
            $this->attributes['ijazah'] = json_encode($value ?: []);
        }
    }

    public function setTranskripNilaiAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $this->attributes['transkrip_nilai'] = json_encode($decoded ?: []);
        } else {
            $this->attributes['transkrip_nilai'] = json_encode($value ?: []);
        }
    }
}
