<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Unggah Dokumen Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- PDF.js untuk preview PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
    .preview-container {
        transition: all 0.3s ease;
    }

    .file-input:valid + .preview-actions {
        display: block;
    }

    /* Style untuk file inputs yang sudah ada file */
    input[type="file"]:not(:placeholder-shown) {
        border-color: #10B981;
        background-color: #F0FDF4;
    }
</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-4xl">
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nip') border-red-500 @enderror"
                    placeholder="Masukkan 18 digit NIP"
                    maxlength="18"
                    required
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                @error('nip')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">NIP harus 18 digit angka dan akan digunakan untuk validasi nama file.</p>
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="Masukkan alamat email"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Gunakan Email aktif dan masih digunakan, <span class="font-semibold text-red-600">Email akan didaftarkan di ASN Digital jika email di SIASN Kosong, duplikat atau salah format</span></p>
            </div>

            <!-- File Upload Fields -->
            <div id="fileFields" class="space-y-6" style="{{ old('nip') && strlen(old('nip')) === 18 ? '' : 'display: none;' }}">
                @foreach([
                    'drh_file' => 'DRH',
                    'skcpns_file' => 'SKCPNS',
                    'skpns_file' => 'SKPNS',
                    'spmt_file' => 'SPMT'
                ] as $field => $label)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Unggah {{ $label }}
                            <span class="text-blue-600">(Format: <span id="filename_{{ $field }}">{{ $label }}_[NIP].pdf</span>)</span>
                        </label>
                        <input
                            type="file"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error($field) border-red-500 @enderror"
                            accept=".pdf"
                            onchange="previewFile(this, 'preview-{{ $field }}')"
                        >
                        @error($field)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Preview Container -->
                        <div id="preview-{{ $field }}" class="mt-3 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                            <div class="border border-gray-300 rounded p-2 bg-gray-50">
                                <canvas id="canvas-{{ $field }}" class="max-w-full mx-auto"></canvas>
                            </div>
                            <button type="button" onclick="closePreview('preview-{{ $field }}')" class="mt-2 text-red-600 text-sm hover:text-red-800">
                                ✕ Tutup Preview
                            </button>
                        </div>

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

        <!-- Download Section -->
        <div id="downloadSection" class="mt-8 border-t border-gray-200 pt-6" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📥 Download Dokumen yang Sudah Diupload</h3>
            <div id="downloadLinks" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Download links akan diisi oleh JavaScript -->
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Ersani. All rights reserved.</p>
        </div>
    </div>

    <!-- Modal untuk Preview PDF -->
    <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 max-w-4xl max-h-screen overflow-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Preview PDF</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    ✕
                </button>
            </div>
            <canvas id="pdfCanvas" class="max-w-full"></canvas>
            <div class="flex justify-between mt-4">
                <button onclick="prevPage()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Sebelumnya</button>
                <span id="pageInfo" class="px-4 py-2">Halaman 1</span>
                <button onclick="nextPage()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Berikutnya</button>
            </div>
        </div>
    </div>

   <script>
    // PDF Preview Functionality
    let currentPdf = null;
    let currentPage = 1;
    let pdfDoc = null;

    function previewFile(input, previewId) {
        const file = input.files[0];
        if (file && file.type === 'application/pdf') {
            const previewContainer = document.getElementById(previewId);
            const canvas = document.getElementById('canvas-' + input.id);

            previewContainer.classList.remove('hidden');

            const fileReader = new FileReader();
            fileReader.onload = function() {
                const typedarray = new Uint8Array(this.result);

                pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                    pdfDoc = pdf;
                    renderPage(pdf, 1, canvas);
                });
            };
            fileReader.readAsArrayBuffer(file);
        }
    }

    function renderPage(pdf, pageNumber, canvas) {
        pdf.getPage(pageNumber).then(function(page) {
            const viewport = page.getViewport({ scale: 0.8 });
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    }

    function closePreview(previewId) {
        // Hanya menyembunyikan preview, TIDAK reset input file
        document.getElementById(previewId).classList.add('hidden');

        // JANGAN reset input file agar data tidak hilang
        // const input = document.querySelector('input[onchange*="' + previewId + '"]');
        // input.value = ''; // ← INI YANG DICOMMENT ATAU DIHAPUS
    }

    // Enhanced NIP handling dengan auto-check existing files
    document.getElementById('nip').addEventListener('input', function() {
        const nip = this.value;
        const fileFields = document.getElementById('fileFields');
        const submitBtn = document.getElementById('submitBtn');
        const downloadSection = document.getElementById('downloadSection');
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

            // Check existing files and show download links
            checkExistingFiles(nip);
        } else {
            fileFields.style.display = 'none';
            submitBtn.disabled = true;
            downloadSection.style.display = 'none';
        }
    });

    // Check existing files function
    function checkExistingFiles(nip) {
        fetch(`/check-files/${nip}`)
            .then(response => response.json())
            .then(data => {
                const downloadSection = document.getElementById('downloadSection');
                const downloadLinks = document.getElementById('downloadLinks');

                if (data.hasFiles) {
                    downloadSection.style.display = 'block';
                    downloadLinks.innerHTML = '';

                    data.files.forEach(file => {
                        if (file.exists) {
                            const link = document.createElement('div');
                            link.className = 'flex items-center justify-between p-3 border border-gray-300 rounded-lg';
                            link.innerHTML = `
                                <span class="text-sm">${file.label}</span>
                                <div class="space-x-2">
                                    <button onclick="previewExistingFile('${file.url}')" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                        👁️ Preview
                                    </button>
                                    <a href="${file.url}" download class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                                        📥 Download
                                    </a>
                                </div>
                            `;
                            downloadLinks.appendChild(link);
                        }
                    });
                } else {
                    downloadSection.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error checking files:', error);
            });
    }

    // Preview existing PDF in modal
    function previewExistingFile(url) {
        const modal = document.getElementById('pdfModal');
        const canvas = document.getElementById('pdfCanvas');

        modal.classList.remove('hidden');
        currentPage = 1;

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdfDoc = pdf;
            renderModalPage(pdf, 1, canvas);
        });
    }

    function renderModalPage(pdf, pageNumber, canvas) {
        pdf.getPage(pageNumber).then(function(page) {
            const viewport = page.getViewport({ scale: 1.5 });
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);

            document.getElementById('pageInfo').textContent = `Halaman ${pageNumber} dari ${pdf.numPages}`;
        });
    }

    function closeModal() {
        document.getElementById('pdfModal').classList.add('hidden');
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            renderModalPage(pdfDoc, currentPage, document.getElementById('pdfCanvas'));
        }
    }

    function nextPage() {
        if (currentPage < pdfDoc.numPages) {
            currentPage++;
            renderModalPage(pdfDoc, currentPage, document.getElementById('pdfCanvas'));
        }
    }

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

            // Check existing files
            checkExistingFiles(nip);
        }

        // Auto-format NIP input
        nipInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Enable submit button jika email dan NIP valid
        document.getElementById('email').addEventListener('input', function() {
            const nip = document.getElementById('nip').value;
            const email = this.value;
            const submitBtn = document.getElementById('submitBtn');

            const nipValid = /^\d{18}$/.test(nip);
            const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            submitBtn.disabled = !(nipValid && emailValid);
        });

        // Tambahkan event listener untuk mempertahankan preview saat form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // Data akan tetap ada karena input file tidak di-reset
            console.log('Form submitted with files intact');
        });
    });

    // Function untuk clear file input jika benar-benar diperlukan
    function clearFileInput(fieldId) {
        document.getElementById(fieldId).value = '';
        const previewId = 'preview-' + fieldId;
        document.getElementById(previewId).classList.add('hidden');
    }
</script>
</body>
</html>
