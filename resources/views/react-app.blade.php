<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - React App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles will be injected by Vite or your build process -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN for development -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- React App Styles -->
    <style>
        /* Custom styles for React app */
        body {
            font-family: 'Figtree', sans-serif;
        }

        /* Loading screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-spinner"></div>
    </div>

    <!-- React App Root -->
    <div id="root"></div>

    <!-- Pass Laravel data to React -->
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            user: @json(auth()->user()),
            apiUrl: '{{ config('app.url') }}/api/v1',
            appUrl: '{{ config('app.url') }}'
        };

        // Hide loading screen when React loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loadingScreen = document.getElementById('loading-screen');
                if (loadingScreen) {
                    loadingScreen.style.display = 'none';
                }
            }, 500);
        });
    </script>

    <!-- React and ReactDOM from CDN for development -->
    <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>

    <!-- Load React app -->
    <script type="module">
        // This would be replaced with your compiled React bundle
        console.log('React App Loading...');

        // For development, you can serve React from a separate dev server
        // In production, you would bundle the React app and serve it from Laravel

        // Example: Load React app from separate port in development
        if (window.location.hostname === 'localhost' && !window.location.port.includes('8000')) {
            // Development mode - React dev server
            window.location.href = 'http://localhost:3000';
        }
    </script>

    <!-- Development notice -->
    <div id="dev-notice" style="position: fixed; bottom: 20px; right: 20px; background: #3B82F6; color: white; padding: 12px 16px; border-radius: 8px; font-size: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        <strong>🚀 React App Mode</strong><br>
        <span style="font-size: 12px;">
            API tersedia di: <a href="/api/v1" class="underline">/api/v1</a><br>
            Laravel App: <a href="/dashboard" class="underline">/dashboard</a>
        </span>
        <button onclick="document.getElementById('dev-notice').style.display='none'" style="position: absolute; top: 4px; right: 8px; background: none; border: none; color: white; cursor: pointer; font-size: 16px;">×</button>
    </div>

    <!-- Fallback content if React doesn't load -->
    <noscript>
        <div style="text-align: center; padding: 50px;">
            <h1>React App</h1>
            <p>This page requires JavaScript to run the React application.</p>
            <p><a href="/dashboard">Go to Laravel Dashboard</a></p>
        </div>
    </noscript>
</body>
</html>