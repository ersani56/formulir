<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Formulir Unggah Dokumen Pegawai</h1>

        <form wire:submit="upload">
            {{ $this->form }}

            <div class="flex justify-start pt-6">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    {{ !($nip && strlen($nip) === 18) ? 'disabled' : '' }}>
                    Unggah/Perbarui Dokumen
                </button>
            </div>
        </form>
    </div>
</div>
