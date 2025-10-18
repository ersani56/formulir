<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arsip Pegawai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }
        .summary-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA ARSIP PEGAWAI</h1>
        <h2>BKPSDM - Badan Kepegawaian dan Pengembangan Sumber Daya Manusia</h2>
    </div>

    <div class="info">
        <p><strong>Periode:</strong>
            {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Semua Data' }}
            -
            {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Sekarang' }}
        </p>
        <p><strong>Dibuat pada:</strong> {{ $generatedAt }}</p>
        <p><strong>Total Data:</strong> {{ $data->count() }} records</p>
    </div>

    @if($data->count() > 0)
    <div class="summary-grid">
        <div class="summary-item">
            <span class="summary-number">{{ $data->count() }}</span>
            <span class="summary-label">Total Pegawai</span>
        </div>
        <div class="summary-item">
            <span class="summary-number">
                {{ $data->filter(function($item) {
                    return $item->drh_path || $item->skcpns_path || $item->skpns_path || $item->spmt_path;
                })->count() }}
            </span>
            <span class="summary-label">Pegawai dengan File</span>
        </div>
        <div class="summary-item">
            <span class="summary-number">
                {{ $data->sum(function($item) {
                    return ($item->drh_path ? 1 : 0) + ($item->skcpns_path ? 1 : 0) + ($item->skpns_path ? 1 : 0) + ($item->spmt_path ? 1 : 0);
                }) }}
            </span>
            <span class="summary-label">Total File</span>
        </div>
        <div class="summary-item">
            <span class="summary-number">
                {{ $data->count() > 0 ? round(($data->filter(function($item) {
                    return $item->drh_path || $item->skcpns_path || $item->skpns_path || $item->spmt_path;
                })->count() / $data->count()) * 100) : 0 }}%
            </span>
            <span class="summary-label">Tingkat Kelengkapan</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Email</th>
                <th>DRH</th>
                <th>SKCPNS</th>
                <th>SKPNS</th>
                <th>SPMT</th>
                <th>Tanggal Upload</th>
                <th>Terakhir Update</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nip }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    <span class="badge {{ $item->drh_path ? 'badge-success' : 'badge-danger' }}">
                        {{ $item->drh_path ? '✅' : '❌' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $item->skcpns_path ? 'badge-success' : 'badge-danger' }}">
                        {{ $item->skcpns_path ? '✅' : '❌' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $item->skpns_path ? 'badge-success' : 'badge-danger' }}">
                        {{ $item->skpns_path ? '✅' : '❌' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $item->spmt_path ? 'badge-success' : 'badge-danger' }}">
                        {{ $item->spmt_path ? '✅' : '❌' }}
                    </span>
                </td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Dokumen:</h3>
        <p>• DRH: {{ $data->where('drh_path')->count() }} file</p>
        <p>• SKCPNS: {{ $data->where('skcpns_path')->count() }} file</p>
        <p>• SKPNS: {{ $data->where('skpns_path')->count() }} file</p>
        <p>• SPMT: {{ $data->where('spmt_path')->count() }} file</p>
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>Tidak ada data untuk periode yang dipilih</h3>
        <p>Silakan pilih periode lain atau pastikan data sudah diupload.</p>
    </div>
    @endif

    <div class="footer">
        <p>Laporan dihasilkan secara otomatis oleh Sistem BKPSDM</p>
        <p>&copy; {{ date('Y') }} BKPSDM - Tulang Bawang Barat</p>
    </div>
</body>
</html>
