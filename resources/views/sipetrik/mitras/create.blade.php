<x-layout>
    <x-slot:title>Tambah Mitra Baru</x-slot:title>

    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Mitra Statistik</h1>
            <a href="{{ route('sipetrik.mitras.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('sipetrik.mitras.store') }}" method="POST">
                @csrf
                
                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Identitas & Posisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" required class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nama Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Sobat ID</label>
                        <input type="text" name="sobat_id" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="ID Sobat BPS (Jika ada)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Posisi</label>
                        <input type="text" name="posisi" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Contoh: Mitra Pendataan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Status Seleksi</label>
                        <select name="status_seleksi" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">- Pilih Status -</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Cadangan">Cadangan</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Alamat Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Alamat Lengkap</label>
                        <textarea name="alamat_detail" rows="2" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Jalan, RT/RW, Dusun"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Kecamatan</label>
                        <input type="text" name="alamat_kec" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Desa/Kelurahan</label>
                        <input type="text" name="alamat_desa" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200 border-b pb-2">Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nomor Telepon/WA</label>
                        <input type="text" name="no_telp" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Email</label>
                        <input type="email" name="email" class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('sipetrik.mitras.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition shadow">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
