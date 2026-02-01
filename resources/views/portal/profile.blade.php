<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Portal Kerja BPS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full text-gray-800 bg-gray-50/50">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('portal.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition group font-medium">
                <div class="bg-gray-100 p-1.5 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span>Kembali ke Dashboard</span>
            </a>
            <div class="flex items-center gap-3">
                 <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $user->employeeDetail->jabatan ?? 'PEGAWAI' }}
                 </span>
            </div>
        </div>
    </nav>

    <div class="relative bg-gradient-to-br from-blue-700 to-indigo-800 pb-32">
        <div class="absolute inset-0 bg-white/5" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 24px 24px; opacity: 0.1;"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
            <h1 class="text-3xl font-bold text-white tracking-tight">Profil Pegawai</h1>
            <p class="text-blue-100 mt-2 text-lg">Kelola data pribadi dan informasi kepegawaian Anda.</p>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 pb-20 relative z-20">
        
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-md flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold text-green-800">Berhasil Disimpan!</p>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <form action="{{ route('portal.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div class="p-8 text-center bg-white">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg ring-4 ring-white mb-4">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            
                            @if($user->employeeDetail?->nip)
                            <div class="mt-4 inline-flex items-center px-3 py-1 bg-gray-100 rounded text-xs text-gray-600 font-mono">
                                NIP: {{ $user->employeeDetail->nip }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Pengaturan Akun</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Login</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 text-gray-500 cursor-not-allowed shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3" readonly>
                                <p class="text-xs text-gray-400 mt-1">*Email tidak dapat diubah sembarangan.</p>
                            </div>
                            
                            <hr class="border-gray-100">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" name="password" placeholder="Isi jika ingin ganti..." 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ulangi Password</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password..." 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                             <div class="bg-blue-100 p-2 rounded text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                             </div>
                             <h3 class="text-lg font-bold text-gray-800">Data Kepegawaian</h3>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap (Tanpa Gelar)</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP (18 Digit)</label>
                                <input type="number" name="nip" value="{{ old('nip', $user->employeeDetail->nip ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3 font-mono" placeholder="Contoh: 199...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK (KTP)</label>
                                <input type="number" name="nik" value="{{ old('nik', $user->employeeDetail->nik ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3 font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                <input type="text" name="jabatan" value="{{ old('jabatan', $user->employeeDetail->jabatan ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3" placeholder="Statistisi...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat/Golongan</label>
                                <input type="text" name="pangkat_golongan" value="{{ old('pangkat_golongan', $user->employeeDetail->pangkat_golongan ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3" placeholder="III/a">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja</label>
                                <input type="text" name="masa_kerja" value="{{ old('masa_kerja', $user->employeeDetail->masa_kerja ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user->employeeDetail->pendidikan_terakhir ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                            <div class="bg-green-100 p-2 rounded text-green-600">
                               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Data Pribadi & Administrasi</h3>
                       </div>

                       <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->employeeDetail->tempat_lahir ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->employeeDetail->tanggal_lahir ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                <textarea name="alamat_tinggal" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">{{ old('alamat_tinggal', $user->employeeDetail->alamat_tinggal ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Perkawinan</label>
                                <select name="status_perkawinan" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                                    <option value="">- Pilih -</option>
                                    @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                                        <option value="{{ $s }}" {{ (old('status_perkawinan', $user->employeeDetail->status_perkawinan ?? '') == $s) ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pasangan</label>
                                <input type="text" name="nama_pasangan" value="{{ old('nama_pasangan', $user->employeeDetail->nama_pasangan ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>

                            <div class="sm:col-span-2 border-t border-gray-100 my-2"></div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $user->employeeDetail->bank_name ?? 'BRI') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening', $user->employeeDetail->nomor_rekening ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3 font-mono font-bold">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Kantor (BPS)</label>
                                <input type="email" name="email_kantor" value="{{ old('email_kantor', $user->employeeDetail->email_kantor ?? '') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                            </div>
                       </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform transition hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            SIMPAN SEMUA DATA
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <footer class="text-center py-6 text-gray-400 text-sm">
        &copy; {{ date('Y') }} Portal Kerja BPS Kabupaten Dairi.
    </footer>

</body>
</html>