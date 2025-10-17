<?php

namespace App\Http\Controllers;

use App\Models\ArsipPeg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function showForm()
    {
        return view('upload-form');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric|digits:18',
            'drh_file' => 'nullable|file|mimes:pdf|max:1024',
            'skcpns_file' => 'nullable|file|mimes:pdf|max:1024',
            'skpns_file' => 'nullable|file|mimes:pdf|max:1024',
            'spmt_file' => 'nullable|file|mimes:pdf|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $nip = $request->nip;
        $arsipPeg = ArsipPeg::firstOrCreate(['nip' => $nip]);
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
            }
        }

        if ($uploadedCount > 0) {
            $arsipPeg->save();
            return back()->with('success', "{$uploadedCount} dokumen berhasil diunggah untuk NIP {$nip}.");
        } else {
            return back()->with('warning', 'Tidak ada file yang diunggah.');
        }
    }
}
