<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arsip Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">📊 Laporan Arsip Pegawai</h1>
                    <p class="text-gray-600">BKPSDM - Tulang Bawang Barat</p>
                </div>
                <a href="/upload-arsip" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    ← Kembali ke Upload
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-blue-600" id="totalPegawai">0</div>
                <div class="text-gray-600">Total Pegawai</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-green-600" id="totalWithFiles">0</div>
                <div class="text-gray-600">Pegawai dengan File</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-purple-600" id="totalFiles">0</div>
                <div class="text-gray-600">Total File</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-orange-600" id="completionRate">0%</div>
                <div class="text-gray-600">Tingkat Kelengkapan</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Distribusi Jenis Dokumen</h3>
                <canvas id="documentChart" height="250"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Upload 10 Terakhir</h3>
                <div id="recentUploads" class="space-y-3">
                    <!-- Recent uploads will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Export Form -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4">📈 Export Laporan</h3>
            <form action="{{ route('generate.report') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format Export</label>
                        <select name="export_format" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                        📥 Download Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load statistics
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
        });

        function loadStatistics() {
                fetch('/api/statistics')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Statistics data:', data); // Debug log

                        // Update statistics
                        document.getElementById('totalPegawai').textContent = data.total_pegawai;
                        document.getElementById('totalWithFiles').textContent = data.total_with_files;

                        const totalFiles = data.file_stats.drh + data.file_stats.skcpns + data.file_stats.skpns + data.file_stats.spmt;
                        document.getElementById('totalFiles').textContent = totalFiles;

                        const completionRate = data.total_pegawai > 0 ?
                            Math.round((data.total_with_files / data.total_pegawai) * 100) : 0;
                        document.getElementById('completionRate').textContent = completionRate + '%';

                        // Update chart
                        updateChart(data.file_stats);

                        // Update recent uploads
                        updateRecentUploads(data.recent_uploads);
                    })
                    .catch(error => {
                        console.error('Error loading statistics:', error);
                        // Fallback values
                        document.getElementById('totalPegawai').textContent = 'Error';
                        document.getElementById('totalWithFiles').textContent = 'Error';
                        document.getElementById('totalFiles').textContent = 'Error';
                        document.getElementById('completionRate').textContent = 'Error';
                    });
            }

            function updateRecentUploads(uploads) {
                const container = document.getElementById('recentUploads');
                container.innerHTML = '';

                if (!uploads || uploads.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-500 py-4">Tidak ada data upload terbaru</div>';
                    return;
                }

                uploads.forEach(upload => {
                    const item = document.createElement('div');
                    item.className = 'flex justify-between items-center p-3 border border-gray-200 rounded-lg';

                    // Hitung jumlah file yang ada
                    const fileCount = [
                        upload.drh_path,
                        upload.skcpns_path,
                        upload.skpns_path,
                        upload.spmt_path
                    ].filter(path => path !== null && path !== '').length;

                    // Format tanggal
                    const uploadDate = new Date(upload.updated_at);
                    const formattedDate = uploadDate.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    item.innerHTML = `
                        <div class="flex-1">
                            <div class="font-medium text-sm">${upload.nip}</div>
                            <div class="text-xs text-gray-500 mt-1">${fileCount} file(s) uploaded</div>
                        </div>
                        <div class="text-xs text-gray-500 text-right">
                            ${formattedDate}
                        </div>
                    `;

                    container.appendChild(item);
                });
            }

            function updateChart(fileStats) {
                const ctx = document.getElementById('documentChart');

                // Destroy existing chart if any
                if (window.documentChartInstance) {
                    window.documentChartInstance.destroy();
                }

                window.documentChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['DRH', 'SKCPNS', 'SKPNS', 'SPMT'],
                        datasets: [{
                            label: 'Jumlah File',
                            data: [
                                fileStats.drh || 0,
                                fileStats.skcpns || 0,
                                fileStats.skpns || 0,
                                fileStats.spmt || 0
                            ],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(139, 92, 246)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Report page loaded'); // Debug log
                loadStatistics();

                // Auto set date range to last 30 days
                const endDate = new Date().toISOString().split('T')[0];
                const startDate = new Date();
                startDate.setDate(startDate.getDate() - 30);

                const startDateInput = document.querySelector('input[name="start_date"]');
                const endDateInput = document.querySelector('input[name="end_date"]');

                if (startDateInput) startDateInput.value = startDate.toISOString().split('T')[0];
                if (endDateInput) endDateInput.value = endDate;
            });
    </script>
</body>
</html>
