<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Unggah Dokumen Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Formulir Unggah Dokumen Pegawai</h1>
            <p class="text-gray-600 mt-2">BKPSDM - Badan Kepegawaian dan Pengembangan Sumber Daya Manusia</p>
        </div>

        <!-- Notifications -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                ⚠️ {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                ❌ Terdapat kesalahan:
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- NIP Input -->
            <div>
                <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                    NIP Pegawai *
                </label>
                <input
                    type="text"
                    id="nip"
                    name="nip"
                    value="{{ old('nip') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan 18 digit NIP"
                    maxlength="18"
                    required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                <p class="text-sm text-gray-500 mt-1">NIP harus 18 digit angka dan akan digunakan untuk validasi nama file.</p>
            </div>

            <!-- File Upload Fields -->
            <div id="fileFields" class="space-y-4" style="{{ old('nip') && strlen(old('nip')) === 18 ? '' : 'display: none;' }}">
                @foreach([
                    'drh_file' => 'DRH',
                    'skcpns_file' => 'SKCPNS',
                    'skpns_file' => 'SKPNS',
                    'spmt_file' => 'SPMT'
                ] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Unggah {{ $label }}
                            <span class="text-blue-600">(Format: <span id="filename_{{ $field }}">{{ $label }}_[NIP].pdf</span>)</span>
                        </label>
                        <input
                            type="file"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            accept=".pdf"
                        >
                        <p class="text-sm text-gray-500 mt-1">Maksimal 1MB, format PDF</p>
                    </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-6">
                <button
                    type="submit"
                    id="submitBtn"
                    class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed shadow-lg"
                    disabled
                >
                    📤 Unggah/Perbarui Dokumen
                </button>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} BKPSDM. All rights reserved.</p>
        </div>
    </div>

    <script>
        document.getElementById('nip').addEventListener('input', function() {
            const nip = this.value;
            const fileFields = document.getElementById('fileFields');
            const submitBtn = document.getElementById('submitBtn');
            const nipRegex = /^\d{18}$/;

            if (nipRegex.test(nip)) {
                fileFields.style.display = 'block';
                submitBtn.disabled = false;

                // Update filename preview
                document.querySelectorAll('[id^="filename_"]').forEach(element => {
                    const fieldName = element.id.replace('filename_', '');
                    const label = element.textContent.split('_')[0];
                    element.textContent = `${label}_${nip}.pdf`;
                });
            } else {
                fileFields.style.display = 'none';
                submitBtn.disabled = true;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const nipInput = document.getElementById('nip');
            const nip = nipInput.value;

            if (nip && nip.length === 18) {
                document.getElementById('fileFields').style.display = 'block';
                document.getElementById('submitBtn').disabled = false;

                // Update filename preview
                document.querySelectorAll('[id^="filename_"]').forEach(element => {
                    const fieldName = element.id.replace('filename_', '');
                    const label = element.textContent.split('_')[0];
                    element.textContent = `${label}_${nip}.pdf`;
                });
            }

            // Auto-format NIP input
            nipInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>
</html>
