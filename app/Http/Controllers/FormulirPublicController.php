<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormulirPublicController extends Controller
{
    public function create()
    {
        return view('formulir.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:formulirs,nik',
            'kelompok_jabatan' => 'required|in:Tenaga Teknis,Tenaga Guru,Tenaga Kesehatan',
            'skck' => 'required|file|mimes:pdf|max:1024',
            'suket_sehat' => 'required|file|mimes:pdf|max:1024',
            'ijazah' => 'required|array|min:1',
            'ijazah.*' => 'file|mimes:pdf|max:1024',
            'transkrip_nilai' => 'required|array|min:1',
            'transkrip_nilai.*' => 'file|mimes:pdf|max:1024',
            'surat_pernyataan' => 'required|file|mimes:pdf|max:1024',
            'pas_foto' => 'required|file|mimes:pdf|max:1024',
            'foto_ktp' => 'required|file|mimes:pdf|max:1024',
            'email' => 'required|email|unique:formulirs,email',
            'no_whatsapp' => 'required|numeric',
        ], [
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            '*.max' => 'Ukuran file tidak boleh lebih dari 1MB.',
            '*.mimes' => 'File harus dalam format PDF.',
            'ijazah.required' => 'Minimal upload 1 file ijazah.',
            'transkrip_nilai.required' => 'Minimal upload 1 file transkrip nilai.',
        ]);

        try {
            // Upload files and store paths
            $formulirData = [
                'nama' => Str::upper($validated['nama']),
                'nik' => $validated['nik'],
                'kelompok_jabatan' => $validated['kelompok_jabatan'],
                'email' => $validated['email'],
                'no_whatsapp' => $validated['no_whatsapp'],
            ];

            // Handle single file uploads
            $fileFields = [
                'skck' => 'skck',
                'suket_sehat' => 'suket_sehat',
                'surat_pernyataan' => 'surat_pernyataan',
                'pas_foto' => 'pas_foto',
                'foto_ktp' => 'foto_ktp'
            ];

            foreach ($fileFields as $field => $directory) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store("formulir/{$directory}", 'public');
                    $formulirData[$field] = $path;
                }
            }

            // Handle multiple file uploads
            $multipleFileFields = [
                'ijazah' => 'ijazah',
                'transkrip_nilai' => 'transkrip_nilai'
            ];

            foreach ($multipleFileFields as $field => $directory) {
                if ($request->hasFile($field)) {
                    $paths = [];
                    foreach ($request->file($field) as $file) {
                        $paths[] = $file->store("formulir/{$directory}", 'public');
                    }
                    $formulirData[$field] = json_encode($paths);
                }
            }

            // Create record
            Formulir::create($formulirData);

            return redirect()->back()->with('success', 'Formulir berhasil dikirim! Data Anda telah disimpan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
