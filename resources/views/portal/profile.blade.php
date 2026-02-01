<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pegawai - Portal Kerja BPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="h-full text-gray-800 bg-gray-50/50">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('portal.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition font-medium">
                <span>&larr; Kembali ke Dashboard</span>
            </a>
            <div class="font-bold text-blue-700">MANAJEMEN DATA PEGAWAI</div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-6 bg-green-100 border-l-4 border-green-500 p-4 rounded shadow flex justify-between">
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                <button @click="show = false">&times;</button>
            </div>
        @endif

        <form action="{{ route('portal.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-xl shadow p-6 text-center border border-gray-100">
                        <div class="w-24 h-24 mx-auto bg-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h2 class="text-lg font-bold">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                        
                        <div class="text-left space-y-3 mt-6">
                            <div>
                                <label class="label">Nama Lengkap (Tanpa Gelar)</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-field" required>
                            </div>
                            <div>
                                <label class="label">Email Kantor (Login)</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field" required>
                            </div>
                             <div>
                                <label class="label">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="input-field" placeholder="Isi jika ingin ganti">
                            </div>
                             <div>
                                <label class="label">Ulangi Password</label>
                                <input type="password" name="password_confirmation" class="input-field">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="card">
                        <div class="card-header bg-blue-50 text-blue-800">
                            <h3 class="font-bold">Data Kepegawaian</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div>
                                <label class="label">NIP Baru (18 Digit)</label>
                                <input type="text" name="nip" value="{{ old('nip', $user->employeeDetail->nip ?? '') }}" class="input-field font-mono" maxlength="18" placeholder="19xxxxxxxxxxxxxxxx">
                            </div>
                            <div>
                                <label class="label">NIP Lama (9 Digit)</label>
                                <input type="text" name="nip_lama" value="{{ old('nip_lama', $user->employeeDetail->nip_lama ?? '') }}" class="input-field font-mono" maxlength="9" placeholder="3400xxxxx">
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">Pangkat / Golongan Ruang</label>
                                <select name="pangkat_golongan" class="input-field">
                                    <option value="">-- Pilih Pangkat --</option>
                                    @foreach(\App\Models\EmployeeDetail::getPangkatOptions() as $pangkat)
                                        <option value="{{ $pangkat }}" {{ (old('pangkat_golongan', $user->employeeDetail->pangkat_golongan ?? '') == $pangkat) ? 'selected' : '' }}>
                                            {{ $pangkat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="label">TMT Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat" value="{{ old('tmt_pangkat', $user->employeeDetail->tmt_pangkat ?? '') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Masa Kerja (MKG)</label>
                                <div class="flex gap-2">
                                    <div class="flex-1 relative">
                                        <input type="number" name="masa_kerja_tahun" value="{{ old('masa_kerja_tahun', $user->employeeDetail->masa_kerja_tahun ?? 0) }}" class="input-field pr-8">
                                        <span class="absolute right-3 top-2.5 text-xs text-gray-500">Thn</span>
                                    </div>
                                    <div class="flex-1 relative">
                                        <input type="number" name="masa_kerja_bulan" value="{{ old('masa_kerja_bulan', $user->employeeDetail->masa_kerja_bulan ?? 0) }}" class="input-field pr-8">
                                        <span class="absolute right-3 top-2.5 text-xs text-gray-500">Bln</span>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">Jabatan</label>
                                <select name="jabatan" class="input-field">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach(\App\Models\EmployeeDetail::getJabatanOptions() as $jabatan)
                                        <option value="{{ $jabatan }}" {{ (old('jabatan', $user->employeeDetail->jabatan ?? '') == $jabatan) ? 'selected' : '' }}>
                                            {{ $jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-green-50 text-green-800">
                            <h3 class="font-bold">Pendidikan & Data Pribadi</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            <div>
                                <label class="label">Strata Pendidikan</label>
                                <select name="pendidikan_strata" class="input-field">
                                    <option value="">- Pilih -</option>
                                    @foreach(['SD', 'SMP', 'SMA/SMK', 'D-I', 'D-II', 'D-III', 'D-IV', 'S-1', 'S-2', 'S-3'] as $strata)
                                        <option value="{{ $strata }}" {{ (old('pendidikan_strata', $user->employeeDetail->pendidikan_strata ?? '') == $strata) ? 'selected' : '' }}>{{ $strata }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">Jurusan</label>
                                <input type="text" name="pendidikan_jurusan" value="{{ old('pendidikan_jurusan', $user->employeeDetail->pendidikan_jurusan ?? '') }}" class="input-field" placeholder="Contoh: Manajemen, Statistika" list="jurusan_list">
                                <datalist id="jurusan_list">
                                    <option value="Statistika">
                                    <option value="Matematika">
                                    <option value="Teknik Informatika">
                                    <option value="Manajemen">
                                    <option value="Akuntansi">
                                    <option value="Ekonomi Pembangunan">
                                </datalist>
                            </div>

                            <div>
                                <label class="label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->employeeDetail->tempat_lahir ?? '') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->employeeDetail->tanggal_lahir ?? '') }}" class="input-field">
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" name="nik" value="{{ old('nik', $user->employeeDetail->nik ?? '') }}" class="input-field font-mono">
                            </div>
                            <div>
                                <label class="label">Status Perkawinan</label>
                                <select name="status_perkawinan" class="input-field">
                                    <option value="">- Pilih -</option>
                                    @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                                        <option value="{{ $s }}" {{ (old('status_perkawinan', $user->employeeDetail->status_perkawinan ?? '') == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">Nama Suami/Istri</label>
                                <input type="text" name="nama_pasangan" value="{{ old('nama_pasangan', $user->employeeDetail->nama_pasangan ?? '') }}" class="input-field">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="label">Alamat Tempat Tinggal</label>
                                <textarea name="alamat_tinggal" rows="2" class="input-field">{{ old('alamat_tinggal', $user->employeeDetail->alamat_tinggal ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                         <div class="card-header bg-yellow-50 text-yellow-800">
                            <h3 class="font-bold">Data Rekening</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="label">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $user->employeeDetail->bank_name ?? 'BRI') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $user->employeeDetail->nomor_rekening ?? '') }}" class="input-field font-mono font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <footer class="text-center py-8 text-gray-400 text-sm">
        &copy; {{ date('Y') }} Portal Kerja BPS Kabupaten Dairi
    </footer>

    <style type="text/tailwindcss">
        @layer components {
            .card { @apply bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden; }
            .card-header { @apply px-6 py-3 border-b border-gray-100; }
            .label { @apply block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide; }
            .input-field { @apply w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3 transition; }
        }
    </style>
</body>
</html>