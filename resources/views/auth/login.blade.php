<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BKK SMKN 1 Garut</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl shadow-blue-100 border border-blue-50">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Selamat Datang</h1>
            <p class="text-slate-500 mt-2">Masuk ke akun BKK kamu</p>
        </div>

        <form action="{{ route('login.prosess') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-blue-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="nama@email.com" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-blue-100 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                Masuk Sekarang
            </button>
        </form>
        
        <p class="text-center text-sm text-slate-500 mt-8">
            Belum punya akun? <a href="#" class="text-blue-600 font-bold">Hubungi Admin</a>
        </p>
    </div>
</body>
</html>