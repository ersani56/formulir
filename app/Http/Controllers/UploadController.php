<?php

namespace App\Http\Controllers;

use App\Models\ArsipPeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploadNotification;

class UploadController extends Controller
{
    public function showForm()
    {
        return view('upload-form');
    }

    public function checkFiles($nip)
    {
        $arsipPeg = ArsipPeg::where('nip', $nip)->first();

        $files = [];
        $hasFiles = false;

        if ($arsipPeg) {
            $fileTypes = [
                'drh_path' => 'DRH',
                'skcpns_path' => 'SKCPNS',
                'skpns_path' => 'SKPNS',
                'spmt_path' => 'SPMT'
            ];

            foreach ($fileTypes as $path => $label) {
                $exists = $arsipPeg->$path && Storage::disk('public')->exists($arsipPeg->$path);
                $files[] = [
                    'label' => $label,
                    'exists' => $exists,
                    'url' => $exists ? Storage::disk('public')->url($arsipPeg->$path) : ''
                ];

                if ($exists) {
                    $hasFiles = true;
                }
            }
        }

        return response()->json([
            'hasFiles' => $hasFiles,
            'files' => $files
        ]);
    }

    public function upload(Request $request)
    {
        $nip = $request->nip;
        $email = $request->email;

        // Validasi dasar
        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric|digits:18',
            'email' => 'required|email',
            'drh_file' => 'nullable|file|mimes:pdf|max:1024',
            'skcpns_file' => 'nullable|file|mimes:pdf|max:1024',
            'skpns_file' => 'nullable|file|mimes:pdf|max:1024',
            'spmt_file' => 'nullable|file|mimes:pdf|max:1024',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.digits' => 'NIP harus 18 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek apakah NIP sudah ada di database
        $existingArsipPeg = ArsipPeg::where('nip', $nip)->first();

        // Validasi email: hanya ditolak jika email sudah digunakan oleh NIP LAIN
        if ($existingArsipPeg) {
            // Jika NIP sudah ada (update data), cek apakah email digunakan oleh NIP lain
            $emailUsedByOtherNip = ArsipPeg::where('email', $email)
                                          ->where('nip', '!=', $nip)
                                          ->exists();
        } else {
            // Jika NIP baru (create data), cek apakah email sudah digunakan oleh siapa pun
            $emailUsedByOtherNip = ArsipPeg::where('email', $email)->exists();
        }

        if ($emailUsedByOtherNip) {
            // Cari NIP yang sudah menggunakan email ini
            $existingRecord = ArsipPeg::where('email', $email)->first();
            return back()->withErrors([
                'email' => 'Email ini sudah digunakan oleh pegawai dengan NIP: ' . $existingRecord->nip
            ])->withInput();
        }

        // Proses data
        if ($existingArsipPeg) {
            // Update existing record
            $arsipPeg = $existingArsipPeg;
            $arsipPeg->email = $email;
            $action = 'diperbarui';
            $isUpdate = true;
        } else {
            // Buat record baru
            $arsipPeg = new ArsipPeg();
            $arsipPeg->nip = $nip;
            $arsipPeg->email = $email;
            $action = 'disimpan';
            $isUpdate = false;
        }

        $uploadedFiles = [];
        $uploadedCount = 0;

        $fileMappings = [
            'drh_file' => 'drh_path',
            'skcpns_file' => 'skcpns_path',
            'skpns_file' => 'skpns_path',
            'spmt_file' => 'spmt_path',
        ];

        foreach ($fileMappings as $formField => $dbColumn) {
            if ($request->hasFile($formField)) {
                $file = $request->file($formField);
                $fileType = strtoupper(str_replace('_file', '', $formField));
                $expectedFileName = "{$fileType}_{$nip}.pdf";

                // Validasi nama file
                if ($file->getClientOriginalName() !== $expectedFileName) {
                    return back()->withErrors([
                        $formField => "Nama file harus: {$expectedFileName}"
                    ])->withInput();
                }

                // Hapus file lama jika ada
                if ($arsipPeg->{$dbColumn} && Storage::disk('public')->exists($arsipPeg->{$dbColumn})) {
                    Storage::disk('public')->delete($arsipPeg->{$dbColumn});
                }

                // Upload file baru
                $filePath = $file->storeAs(
                    'uploads/' . strtolower($fileType),
                    $expectedFileName,
                    'public'
                );

                $arsipPeg->{$dbColumn} = $filePath;
                $uploadedCount++;
                $uploadedFiles[] = $fileType;
            }
        }

        $arsipPeg->save();

        // Kirim email notifikasi
        try {
            Mail::to($email)->send(new DocumentUploadNotification(
                $nip,
                $email,
                $uploadedFiles,
                $isUpdate,
                $arsipPeg
            ));
            $emailSent = true;
        } catch (\Exception $e) {
            \Log::error('Email notification failed: ' . $e->getMessage());
            $emailSent = false;
        }

        if ($uploadedCount > 0) {
            $message = "{$uploadedCount} dokumen berhasil diunggah untuk NIP {$nip}. Data {$action}.";
            if ($emailSent) {
                $message .= " Notifikasi telah dikirim ke email.";
            } else {
                $message .= " (Gagal mengirim notifikasi email)";
            }
            return back()->with('success', $message);
        } else {
            return back()->with('warning', "Tidak ada file yang diunggah. Data NIP dan email {$action}.");
        }

        if ($uploadedCount > 0) {
            return back()->with('success', "{$uploadedCount} dokumen berhasil diunggah untuk NIP {$nip}. Data {$action}. Notifikasi telah dikirim ke email.");
        } else {
            return back()->with('warning', "Tidak ada file yang diunggah. Data NIP dan email {$action}.");
        }
    }
}
