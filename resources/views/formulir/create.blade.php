<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Isian PPPK Paruh Waktu Tahun 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">FORMULIR PPPK PARUH WAKTU TAHUN 2025</h1>
                <p class="text-gray-600">BKPSDM Kab. Tulang Bawang Barat</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.
                </div>
            @endif

            <form action="{{ route('formulir.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg overflow-hidden">
                @csrf

                <!-- Data Pribadi Section -->
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                        Data Pribadi
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                                oninput="this.value = this.value.toUpperCase()"
                                required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                                NIK <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                maxlength="16" pattern="[0-9]{16}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                            @error('nik')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelompok Jabatan -->
                        <div>
                            <label for="kelompok_jabatan" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelompok Jabatan <span class="text-red-500">*</span>
                            </label>
                            <select id="kelompok_jabatan" name="kelompok_jabatan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="">Pilih Kelompok Jabatan</option>
                                <option value="Tenaga Teknis" {{ old('kelompok_jabatan') == 'Tenaga Teknis' ? 'selected' : '' }}>Tenaga Teknis</option>
                                <option value="Tenaga Guru" {{ old('kelompok_jabatan') == 'Tenaga Guru' ? 'selected' : '' }}>Tenaga Guru</option>
                                <option value="Tenaga Kesehatan" {{ old('kelompok_jabatan') == 'Tenaga Kesehatan' ? 'selected' : '' }}>Tenaga Kesehatan</option>
                            </select>
                            @error('kelompok_jabatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="no_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                No WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                            @error('no_whatsapp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Upload Dokumen Section -->
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-file-upload mr-2 text-green-500"></i>
                        Upload Dokumen
                    </h2>
                    <p class="text-sm text-gray-600 mb-6">
                        <i class="fas fa-info-circle mr-1"></i>
                        Maksimal ukuran file: 1MB per file. Format: PDF
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKCK -->
                        <div>
                            <label for="skck" class="block text-sm font-medium text-gray-700 mb-2">
                                SKCK <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="skck" name="skck"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('skck')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SUKET Sehat -->
                        <div>
                            <label for="suket_sehat" class="block text-sm font-medium text-gray-700 mb-2">
                                SUKET Sehat <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="suket_sehat" name="suket_sehat"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('suket_sehat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ijazah (Multiple) -->
                        <div>
                            <label for="ijazah" class="block text-sm font-medium text-gray-700 mb-2">
                                Ijazah <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500">(Dapat upload multiple file)</span>
                            </label>
                            <input type="file" id="ijazah" name="ijazah[]" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('ijazah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('ijazah.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transkrip Nilai (Multiple) -->
                        <div>
                            <label for="transkrip_nilai" class="block text-sm font-medium text-gray-700 mb-2">
                                Transkrip Nilai <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500">(Dapat upload multiple file)</span>
                            </label>
                            <input type="file" id="transkrip_nilai" name="transkrip_nilai[]" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('transkrip_nilai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('transkrip_nilai.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Surat Pernyataan -->
                        <div>
                            <label for="surat_pernyataan" class="block text-sm font-medium text-gray-700 mb-2">
                                Surat Pernyataan 5 Point <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="surat_pernyataan" name="surat_pernyataan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('surat_pernyataan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pas Foto -->
                        <div>
                            <label for="pas_foto" class="block text-sm font-medium text-gray-700 mb-2">
                                Pas Foto Latar Merah <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="pas_foto" name="pas_foto"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('pas_foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto KTP -->
                        <div>
                            <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto KTP <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="foto_ktp" name="foto_ktp"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700"
                                accept=".pdf" required>
                            @error('foto_ktp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <div class="flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            <span class="text-red-500">*</span> Menandakan field wajib diisi
                        </p>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Formulir
                        </button>
                    </div>
                </div>
            </form>

            <!-- Footer Info -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Formulir ini akan disimpan ke dalam database dan dapat dikelola melalui admin panel.</p>
            </div>
        </div>
    </div>

    <script>
        // Real-time validation and feedback
        document.addEventListener('DOMContentLoaded', function() {
            // File size validation
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const files = e.target.files;
                    const maxSize = 1024 * 1024; // 1MB in bytes

                    for (let file of files) {
                        if (file.size > maxSize) {
                            alert(`File ${file.name} terlalu besar. Maksimal ukuran file adalah 1MB.`);
                            e.target.value = '';
                            break;
                        }
                        if (file.type !== 'application/pdf') {
                            alert(`File ${file.name} harus dalam format PDF.`);
                            e.target.value = '';
                            break;
                        }
                    }
                });
            });

            // NIK validation
            const nikInput = document.getElementById('nik');
            nikInput.addEventListener('input', function(e) {
                if (e.target.value.length > 16) {
                    e.target.value = e.target.value.slice(0, 16);
                }
            });

            // Form submission confirmation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Harap isi semua field yang wajib diisi.');
                }
            });
        });
    </script>
</body>
</html>
