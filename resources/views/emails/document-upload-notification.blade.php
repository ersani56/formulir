<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi Upload Dokumen</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .file-list {
            background: white;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-uploaded {
            background: #d1fae5;
            color: #065f46;
        }
        .status-missing {
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e1e1;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📄 Notifikasi Dokumen Pegawai</h1>
        <p>BKPSDM - Badan Kepegawaian dan Pengembangan Sumber Daya Manusia</p>
    </div>

    <div class="content">
        <h2>Halo,</h2>
        <p>Berikut adalah ringkasan upload dokumen pegawai yang telah dilakukan:</p>

        <div class="info-box">
            <h3>📋 Informasi Pegawai</h3>
            <p><strong>NIP:</strong> {{ $nip }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Waktu Upload:</strong> {{ $uploadTime }}</p>
            <p><strong>Status:</strong> {{ $isUpdate ? 'Update Data' : 'Data Baru' }}</p>
        </div>

        <div class="file-list">
            <h3>📁 Status Dokumen</h3>

            <div class="file-item">
                <span>DRH (Daftar Riwayat Hidup)</span>
                <span class="status-badge {{ $arsipPeg->drh_path ? 'status-uploaded' : 'status-missing' }}">
                    {{ $arsipPeg->drh_path ? '✅ Terupload' : '❌ Belum' }}
                </span>
            </div>

            <div class="file-item">
                <span>SKCPNS (SK Calon PNS)</span>
                <span class="status-badge {{ $arsipPeg->skcpns_path ? 'status-uploaded' : 'status-missing' }}">
                    {{ $arsipPeg->skcpns_path ? '✅ Terupload' : '❌ Belum' }}
                </span>
            </div>

            <div class="file-item">
                <span>SKPNS (SK PNS)</span>
                <span class="status-badge {{ $arsipPeg->skpns_path ? 'status-uploaded' : 'status-missing' }}">
                    {{ $arsipPeg->skpns_path ? '✅ Terupload' : '❌ Belum' }}
                </span>
            </div>

            <div class="file-item">
                <span>SPMT (Surat Perintah Melaksanakan Tugas)</span>
                <span class="status-badge {{ $arsipPeg->spmt_path ? 'status-uploaded' : 'status-missing' }}">
                    {{ $arsipPeg->spmt_path ? '✅ Terupload' : '❌ Belum' }}
                </span>
            </div>
        </div>

        @if(count($uploadedFiles) > 0)
        <div class="info-box">
            <h3>🔄 File yang Baru Diupload</h3>
            <ul>
                @foreach($uploadedFiles as $file)
                    <li>{{ $file }}.pdf</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="info-box">
            <h3>ℹ️ Informasi</h3>
            <p>Dokumen yang sudah diupload dapat diakses melalui sistem BKPSDM.</p>
            <p>Jika ada pertanyaan atau kendala, silakan hubungi administrator sistem.</p>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} BKPSDM - Badan Kepegawaian dan Pengembangan Sumber Daya Manusia</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>
</html>
