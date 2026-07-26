<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Donat Menak</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        cocoa: {
                            950: '#150a06',
                            900: '#23120b',
                            800: '#321a10',
                            700: '#48271a',
                            600: '#633726',
                        },
                        gold: {
                            50:  '#fefce8',
                            100: '#fef9c3',
                            200: '#fef08a',
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #fefce8;
            color: #23120b;
            background-image: 
                radial-gradient(circle at 10% 15%, rgba(250, 204, 21, 0.45) 0%, transparent 40%),
                radial-gradient(circle at 90% 85%, rgba(253, 224, 71, 0.5) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(254, 240, 138, 0.6) 0%, transparent 60%);
        }
        .yellow-card {
            background: rgba(254, 249, 195, 0.94);
            backdrop-filter: blur(16px);
            border: 2px solid #facc15;
            box-shadow: 0 8px 25px -6px rgba(202, 138, 4, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="yellow-card rounded-3xl p-8 max-w-md w-full space-y-8">
        <div class="text-center space-y-4">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-white border-2 border-cocoa-900 shadow-lg flex items-center justify-center p-1 overflow-hidden">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.src='https://ui-avatars.com/api/?name=DM&background=facc15&color=23120b'">
            </div>
            
            <div>
                <h1 class="text-3xl font-display font-black text-cocoa-950 uppercase tracking-tight">DONAT MENAK</h1>
                <p class="text-sm text-cocoa-800 font-bold">Logistics & Resource Planning</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-xs font-bold text-center">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-black text-cocoa-950 mb-1">Alamat Email / Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-envelope text-gold-600"></i>
                    </div>
                    <input type="email" name="email" id="email" required placeholder="admin.pusat@donatmenak.com" value="{{ old('email') }}"
                        class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold focus:border-amber-600 focus:outline-none transition">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-black text-cocoa-950 mb-1">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gold-600"></i>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border-2 border-gold-400 text-cocoa-950 font-bold focus:border-amber-600 focus:outline-none transition">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-cocoa-900 hover:bg-cocoa-950 text-gold-300 font-black text-sm tracking-wide shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2 border-2 border-gold-400">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Sistem
            </button>
        </form>
        
        <div class="text-center text-xs text-cocoa-800 font-semibold border-t-2 border-gold-400/50 pt-4">
            Gunakan Akun Role yang tersedia:<br>
            <span class="text-emerald-700">admin.pusat@donatmenak.com</span> | <span class="text-amber-700">kasir.cibiru@...</span>
        </div>
    </div>

</body>
</html>
