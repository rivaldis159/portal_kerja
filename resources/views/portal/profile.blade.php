<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Portal Kerja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('portal') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition group">
                <div class="p-1.5 rounded-lg group-hover:bg-blue-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="font-medium text-sm">Kembali ke Dashboard</span>
            </a>
            <div class="flex items-center gap-2">
                <img src="/images/logo.png" alt="Logo" class="h-8 w-auto grayscale opacity-50">
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-10 sm:px-6">
        
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="mb-6 flex items-center p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm" role="alert">
                <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="font-medium">Berhasil!</span> &nbsp; {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative">
                <div class="absolute inset-0 bg-white/10 pattern-dots"></div> </div>

            <div class="px-6 sm:px-10 pb-10">
                <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-12 mb-8 gap-6">
                    
                    <div class="relative group">
                        <div class="h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-white p-1.5 shadow-lg">
                            <div class="h-full w-full rounded-full bg-slate-800 flex items-center justify-center text-white text-4xl font-bold overflow-hidden relative">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-not-allowed">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center sm:text-left flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">Kelola informasi profil dan keamanan akun Anda.</p>
                    </div>
                </div>

                <form action="{{ route('portal.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        
                        <div class="lg:col-span-2 space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 pb-2 border-b border-gray-100">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Informasi Pribadi
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all py-2.5 px-4 text-sm">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                                        class="w-full rounded-xl border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all py-2.5 px-4 text-sm">
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 pb-2 border-b border-gray-100">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Keamanan
                            </h3>

                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 mb-4">
                                <p class="text-xs text-yellow-700 leading-relaxed">
                                    <strong>Catatan:</strong> Kosongkan kolom password di bawah ini jika Anda tidak ingin mengubah password saat ini.
                                </p>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all py-2.5 px-4 text-sm">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Ulangi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all py-2.5 px-4 text-sm">
                            </div>
                        </div>

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-400">
                            Terakhir login: {{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() ?? 'Belum pernah' }}
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-600/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

</body>
</html>