<x-layout>
    <x-slot:title>Edit Data Mitra</x-slot:title>

    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Data Mitra</h1>
            <a href="{{ route('sipetrik.mitras.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('sipetrik.mitras.update', $mitra) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Identitas & Posisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $mitra->nama) }}" required class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Sobat ID</label>
                        <input type="text" name="sobat_id" value="{{ old('sobat_id', $mitra->sobat_id) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Posisi</label>
                        <input type="text" name="posisi" value="{{ old('posisi', $mitra->posisi) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Status Seleksi</label>
                        <select name="status_seleksi" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">- Pilih Status -</option>
                            <option value="Diterima" {{ $mitra->status_seleksi == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="Cadangan" {{ $mitra->status_seleksi == 'Cadangan' ? 'selected' : '' }}>Cadangan</option>
                            <option value="Ditolak" {{ $mitra->status_seleksi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Alamat Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Alamat Lengkap</label>
                        <textarea name="alamat_detail" rows="2" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('alamat_detail', $mitra->alamat_detail) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Kecamatan</label>
                        <input type="text" name="alamat_kec" value="{{ old('alamat_kec', $mitra->alamat_kec) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Desa/Kelurahan</label>
                        <input type="text" name="alamat_desa" value="{{ old('alamat_desa', $mitra->alamat_desa) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nomor Telepon/WA</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $mitra->no_telp) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Email</label>
                        <input type="email" name="email" value="{{ old('email', $mitra->email) }}" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" onclick="if(confirm('Yakin ingin menghapus mitra ini?')) document.getElementById('delete-form').submit()" class="px-4 py-2 text-red-600 hover:text-red-800 border border-red-200 rounded-lg hover:bg-red-50">
                        Hapus Mitra
                    </button>
                    <div class="flex gap-3">
                        <a href="{{ route('sipetrik.mitras.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition shadow">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('sipetrik.mitras.destroy', $mitra) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-layout>
