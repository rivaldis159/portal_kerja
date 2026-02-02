<!DOCTYPE html>
<html lang="id" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Kerja BPS Dairi</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="h-full bg-slate-50 relative overflow-hidden flex items-center justify-center">

    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[800px] h-[800px] rounded-full bg-blue-600/10 blur-[100px] mix-blend-multiply shadow-2xl animate-pulse"></div>
        <div class="absolute top-[10%] -right-[10%] w-[600px] h-[600px] rounded-full bg-indigo-500/10 blur-[100px] mix-blend-multiply shadow-2xl"></div>
        <div class="absolute -bottom-[30%] left-[20%] w-[700px] h-[700px] rounded-full bg-teal-400/10 blur-[100px] mix-blend-multiply shadow-2xl"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-sm mx-auto p-4 sm:p-0">
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white/50 p-8 sm:p-10">
            <!-- Header section -->
            <div class="text-center mb-10">
                <div class="inline-flex justify-center items-center w-20 h-20 rounded-2xl bg-white shadow-sm border border-slate-100 mb-6 p-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-full w-auto object-contain">
                </div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Portal Kerja</h2>
                <p class="text-xs font-semibold text-slate-400 mt-2 uppercase tracking-[0.2em] px-2 py-1 rounded inline-block bg-slate-50 border border-slate-100">BPS Kabupaten Dairi</p>
                <p class="text-slate-500 text-sm mt-4 leading-relaxed">Selamat datang, silakan masuk untuk mengakses Portal Kerja</p>
            </div>

            <!-- Error Notification -->
            @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-100 flex gap-3 items-start animate-fade-in-down">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-red-600 font-medium">{{ $errors->first() }}</p>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.action') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5 ml-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all sm:text-sm bg-slate-50/50 focus:bg-white"
                            placeholder="nama@bps.go.id">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5 ml-1">Password</label>
                    <div class="relative x-data='{ show: false }'">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all sm:text-sm bg-slate-50/50 focus:bg-white"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/20 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        Masuk Dashboard
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Dairi
        </p>
    </div>

</body>
</html>