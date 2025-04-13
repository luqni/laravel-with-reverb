<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salam - Chat Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-green-500 glow" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
            </svg>
            <h1 class="text-4xl font-bold text-green-500 mt-4 glow">Salam</h1>
        </div>
        
        <div class="space-y-4">
            <p class="text-gray-300 text-xl mb-8">Segera login untuk mulai chat, jika belum punya akun silahkan register</p>
            
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 inline-block glow-button">Login</a>
                <a href="{{ route('register') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 inline-block">Register</a>
            </div>
        </div>
    </div>

    <style>
        .glow {
            filter: drop-shadow(0 0 10px rgba(34, 197, 94, 0.5));
        }
        .glow-button:hover {
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
        }
    </style>
</body>
</html>
