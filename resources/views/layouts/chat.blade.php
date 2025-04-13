<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Chat App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.18.3/index.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'whatsapp': {
                            'light': '#DCF8C6',
                            'dark': '#128C7E',
                            'chat': '#E5DDD5'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .messages-container {
            height: calc(100vh - theme('spacing.32'));
        }
        .emoji-message {
            font-size: theme('fontSize.3xl');
        }
        #messageInput {
            resize: none;
        }
        .message-bubble {
            position: relative;
            max-width: 80%;
        }
        .message-bubble::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border: 8px solid transparent;
            top: 8px;
        }
        .message-sent::before {
            right: -15px;
            border-left-color: theme('colors.whatsapp.light');
        }
        .message-received::before {
            left: -15px;
            border-right-color: theme('colors.white');
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen relative">
        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 bg-whatsapp-dark z-10 flex items-center justify-between px-4 py-2">
            <button id="sidebarToggle" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-white font-semibold">Chat App</h1>
            <div class="w-6"></div>
        </div>

        <!-- Sidebar -->
        <div id="sidebar" class="fixed md:relative w-full md:w-80 bg-white border-r border-gray-200 flex flex-col h-full transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-20">
            <div class="flex-none p-4 border-b border-gray-200 mt-12 md:mt-0">
                <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 hover:bg-gray-50 rounded-lg p-2">
                    @if(Auth::user()->profile_picture)
                        <img class="h-10 w-10 object-cover rounded-full" src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="h-10 w-10 rounded-full bg-whatsapp-dark flex items-center justify-center text-white">
                            <span class="text-lg font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Lihat profil</p>
                    </div>
                </a>
            </div>
            @yield('sidebar')
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col bg-whatsapp-chat mt-12 md:mt-0">
            @yield('content')
        </div>
    </div>

    <emoji-picker id="emojiPicker" class="hidden fixed bottom-20 right-5"></emoji-picker>

    <script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.18.3/index.js" type="module"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>