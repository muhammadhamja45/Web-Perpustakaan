<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Additional Styles -->
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
                overflow: hidden;
            }

            .gradient-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 25%, #ec4899 50%, #f59e0b 75%, #10b981 100%);
                opacity: 0.9;
                animation: gradientShift 8s ease-in-out infinite;
            }

            @keyframes gradientShift {
                0%, 100% { transform: translateX(-100%) rotate(0deg); }
                50% { transform: translateX(100%) rotate(180deg); }
            }

            .glassmorphism {
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }

            .floating-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
            }

            .floating-shapes::before,
            .floating-shapes::after {
                content: '';
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                animation: float 6s ease-in-out infinite;
            }

            .floating-shapes::before {
                width: 100px;
                height: 100px;
                top: 20%;
                left: 10%;
                animation-delay: -2s;
            }

            .floating-shapes::after {
                width: 150px;
                height: 150px;
                bottom: 20%;
                right: 10%;
                animation-delay: -4s;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }

            .smk-pattern {
                background-image:
                    radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                    radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
                background-size: 50px 50px;
                background-position: 0 0, 25px 25px;
            }
        </style>
    </head>
    <body class="font-inter antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - SMK-Friendly Background -->
            <div class="hidden lg:flex lg:w-1/2 gradient-bg relative overflow-hidden">
                <!-- Animated Background -->
                <div class="floating-shapes"></div>
                <div class="smk-pattern absolute inset-0"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center items-center text-white p-12">
                    <!-- SMK Logo/Icon -->
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-3xl flex items-center justify-center backdrop-blur-sm border border-white border-opacity-30 shadow-2xl">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Welcome Text -->
                    <div class="text-center mb-8">
                        <h1 class="text-5xl font-bold mb-4">🎓 SMK Digital</h1>
                        <p class="text-xl opacity-90 mb-2">Sistem Perpustakaan Modern</p>
                        <p class="text-lg opacity-75">untuk Generasi Digital</p>
                        <div class="w-20 h-1 bg-white mx-auto rounded-full mt-6 opacity-80"></div>
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-1 gap-4 mb-8 max-w-sm">
                        <div class="flex items-center space-x-3 bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                                <span class="text-white text-lg">📚</span>
                            </div>
                            <span class="text-white font-medium">Koleksi Buku Digital</span>
                        </div>
                        <div class="flex items-center space-x-3 bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-blue-400 rounded-lg flex items-center justify-center">
                                <span class="text-white text-lg">🔐</span>
                            </div>
                            <span class="text-white font-medium">Keamanan Terjamin</span>
                        </div>
                        <div class="flex items-center space-x-3 bg-white bg-opacity-10 rounded-2xl p-4 backdrop-blur-sm">
                            <div class="w-8 h-8 bg-purple-400 rounded-lg flex items-center justify-center">
                                <span class="text-white text-lg">⚡</span>
                            </div>
                            <span class="text-white font-medium">Akses Cepat 24/7</span>
                        </div>
                    </div>

                    <!-- Quote -->
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="bg-white bg-opacity-10 rounded-2xl p-6 backdrop-blur-sm border border-white border-opacity-20">
                            <blockquote class="text-lg italic text-center">
                                <span class="text-2xl">"</span>
                                Membaca adalah jendela dunia, teknologi adalah kuncinya
                                <span class="text-2xl">"</span>
                            </blockquote>
                            <p class="text-center mt-2 text-sm opacity-80">- SMK Digital Library</p>
                        </div>
                    </div>
                </div>

                <!-- Modern Decorative Elements -->
                <div class="absolute top-6 right-6 w-16 h-16 border-2 border-white opacity-20 rounded-2xl rotate-12"></div>
                <div class="absolute bottom-32 left-6 w-12 h-12 border-2 border-white opacity-20 rounded-xl -rotate-12"></div>
                <div class="absolute top-1/3 right-20 w-8 h-8 bg-white opacity-20 rounded-full"></div>
                <div class="absolute bottom-1/3 left-20 w-6 h-6 bg-white opacity-20 rounded-full"></div>
            </div>

            <!-- Right Side - Form -->
            <div class="flex-1 flex items-center justify-center p-8 bg-gradient-to-br from-gray-50 to-blue-50 relative">
                <!-- Background Pattern for Mobile/Right Side -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-purple-100"></div>
                    <div class="smk-pattern absolute inset-0"></div>
                </div>

                <div class="w-full max-w-md relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Mobile Background Enhancement -->
        <div class="lg:hidden fixed inset-0 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 opacity-10 pointer-events-none"></div>
    </body>
</html>
