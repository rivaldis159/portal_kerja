<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pegawai - Portal Kerja BPS Dairi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-slate-800 bg-slate-50/50 relative">

    <!-- Ambient Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -right-[10%] w-[600px] h-[600px] rounded-full bg-blue-100/40 blur-[80px] mix-blend-multiply"></div>
        <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-indigo-100/40 blur-[80px] mix-blend-multiply"></div>
    </div>

    <nav class="bg-white/70 backdrop-blur-xl sticky top-0 z-40 border-b border-slate-200/60 supports-[backdrop-filter]:bg-white/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('portal.index') }}" class="group flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/50 transition">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 transition text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition">Kembali ke Portal</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/30">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Profil Saya</h1>
                <p class="text-slate-500 mt-1">Kelola informasi pribadi dan data kepegawaian Anda.</p>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                 class="mb-8 rounded-xl bg-green-50 border border-green-100 p-4 flex justify-between items-start shadow-sm ring-1 ring-green-500/10">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-bold text-green-900 text-sm">Berhasil Disimpan</h4>
                        <p class="text-green-700 text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <form action="{{ route('portal.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Sidebar: Basic Info -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-6 sm:p-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 p-1 shadow-xl shadow-blue-500/20 mb-6">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center overflow-hidden border-4 border-white">
                                    <span class="text-4xl font-black text-slate-800">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            
                            <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                            <span class="mt-2 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider rounded-full border border-blue-100">
                                {{ $user->employeeDetail->jabatan ?? 'Pegawai' }}
                            </span>
                        </div>

                        <div class="mt-8 space-y-5">
                             <div>
                                <label class="label">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-field bg-slate-50 focus:bg-white text-center font-semibold" required>
                            </div>
                            <div>
                                <label class="label">Email Kantor</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input-field bg-slate-50 focus:bg-white text-center" required>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-100">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 text-center">Ubah Password</h3>
                            <div class="space-y-4">
                                <div>
                                    <input type="password" name="password" class="input-field text-sm" placeholder="Password Baru (Opsional)">
                                </div>
                                <div>
                                    <input type="password" name="password_confirmation" class="input-field text-sm" placeholder="Ulangi Password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content: Forms -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Card: Kepegawaian -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center gap-3">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800">Data Kepegawaian</h3>
                        </div>
                        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="label">NIP Baru (18 Digit)</label>
                                <input type="text" name="nip" value="{{ old('nip', $user->employeeDetail->nip ?? '') }}" class="input-field font-mono text-slate-700 tracking-wide" maxlength="18" placeholder="19xxxxxxxxxxxxxxxx">
                            </div>
                            <div>
                                <label class="label">NIP Lama (9 Digit)</label>
                                <input type="text" name="nip_lama" value="{{ old('nip_lama', $user->employeeDetail->nip_lama ?? '') }}" class="input-field font-mono text-slate-700 tracking-wide" maxlength="9" placeholder="3400xxxxx">
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">Pangkat / Golongan Ruang</label>
                                <div class="relative">
                                    <select name="pangkat_golongan" class="input-field appearance-none cursor-pointer">
                                        <option value="">-- Pilih Pangkat --</option>
                                        @foreach(\App\Models\EmployeeDetail::getPangkatOptions() as $pangkat)
                                            <option value="{{ $pangkat }}" {{ (old('pangkat_golongan', $user->employeeDetail->pangkat_golongan ?? '') == $pangkat) ? 'selected' : '' }}>
                                                {{ $pangkat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="label">TMT Pangkat Terakhir</label>
                                <input type="date" name="tmt_pangkat" value="{{ old('tmt_pangkat', $user->employeeDetail->tmt_pangkat ?? '') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Masa Kerja</label>
                                <div class="flex gap-3">
                                    <div class="flex-1 relative">
                                        <input type="number" name="masa_kerja_tahun" value="{{ $user->employeeDetail->masa_kerja_tahun ?? 0 }}" class="input-field pr-10 bg-slate-100 text-slate-500 cursor-not-allowed" readonly title="Dihitung otomatis dari NIP">
                                        <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">THN</span>
                                    </div>
                                    <div class="flex-1 relative">
                                        <input type="number" name="masa_kerja_bulan" value="{{ $user->employeeDetail->masa_kerja_bulan ?? 0 }}" class="input-field pr-10 bg-slate-100 text-slate-500 cursor-not-allowed" readonly title="Dihitung otomatis dari NIP">
                                        <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400">BLN</span>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic">* Dihitung otomatis dari NIP</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">Jabatan</label>
                                <div class="relative">
                                    <select name="jabatan" class="input-field appearance-none cursor-pointer">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach(\App\Models\EmployeeDetail::getJabatanOptions() as $jabatan)
                                            <option value="{{ $jabatan }}" {{ (old('jabatan', $user->employeeDetail->jabatan ?? '') == $jabatan) ? 'selected' : '' }}>
                                                {{ $jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Personal & Education -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800">Pendidikan & Data Pribadi</h3>
                        </div>
                        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label class="label">Strata Pendidikan</label>
                                <div class="relative">
                                    <select name="pendidikan_strata" class="input-field appearance-none cursor-pointer">
                                        <option value="">- Pilih -</option>
                                        @foreach(['SD', 'SMP', 'SMA/SMK', 'D-I', 'D-II', 'D-III', 'D-IV', 'S-1', 'S-2', 'S-3'] as $strata)
                                            <option value="{{ $strata }}" {{ (old('pendidikan_strata', $user->employeeDetail->pendidikan_strata ?? '') == $strata) ? 'selected' : '' }}>{{ $strata }}</option>
                                        @endforeach
                                    </select>
                                     <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
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
                                <label class="label">NIK (KTP)</label>
                                <input type="text" name="nik" value="{{ old('nik', $user->employeeDetail->nik ?? '') }}" class="input-field font-mono text-slate-700 tracking-wide">
                            </div>

                            <div>
                                <label class="label">Status Perkawinan</label>
                                <div class="relative">
                                    <select name="status_perkawinan" class="input-field appearance-none cursor-pointer">
                                        <option value="">- Pilih -</option>
                                        @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                                            <option value="{{ $s }}" {{ (old('status_perkawinan', $user->employeeDetail->status_perkawinan ?? '') == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                     <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="label">Nama Suami/Istri</label>
                                <input type="text" name="nama_pasangan" value="{{ old('nama_pasangan', $user->employeeDetail->nama_pasangan ?? '') }}" class="input-field">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="label">Alamat Tempat Tinggal</label>
                                <textarea name="alamat_tinggal" rows="3" class="input-field resize-none">{{ old('alamat_tinggal', $user->employeeDetail->alamat_tinggal ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Finance -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                         <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 text-yellow-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800">Data Keuangan</h3>
                        </div>
                        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="label">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $user->employeeDetail->bank_name ?? 'BRI') }}" class="input-field">
                            </div>
                            <div>
                                <label class="label">Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $user->employeeDetail->nomor_rekening ?? '') }}" class="input-field font-mono font-bold text-slate-800 tracking-wide">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center sm:text-left border-t border-slate-200/50 mt-12 bg-white/30 backdrop-blur-sm">
        <p class="text-slate-400 text-sm font-medium">&copy; {{ date('Y') }} Portal Kerja BPS Kabupaten Dairi</p>
    </footer>

    <style type="text/tailwindcss">
        @layer components {
            .label { @apply block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide ml-0.5; }
            .input-field { @apply block w-full rounded-lg border-slate-200 bg-white text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all py-2.5 px-3 sm:text-sm placeholder:text-slate-300; }
        }
    </style>
</body>
</html>