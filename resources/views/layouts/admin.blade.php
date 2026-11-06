<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Project RED AI')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        // RED AI — dark, precise, confident
                        ink: {
                            950: '#0A0D11',
                            900: '#0E1117',
                            850: '#12161D',
                            800: '#161A22',
                            700: '#1C212B',
                            600: '#232934',
                            500: '#2B313D',
                        },
                        surface: {
                            0: '#14181F',
                            1: '#1A1F29',
                            2: '#202632',
                        },
                        line: {
                            0: '#232934',
                            1: '#2B313D',
                            2: '#343B49',
                        },
                        text: {
                            primary: '#F0EDE8',
                            secondary: '#9AA0AB',
                            muted: '#6B7280',
                            faint: '#4B5260',
                        },
                        brand: {
                            primary: '#E8312B',
                            'primary-dark': '#C4211C',
                            'primary-soft': '#FDECEA',
                            coral: '#FF5A4D',
                            gold: '#F5A623',
                            'gold-soft': '#FDECD2',
                            charcoal: '#263049',
                            navy: '#263049',
                        },
                        accent: {
                            red: '#E8312B',
                            gold: '#F5A623',
                            emerald: '#34D399',
                            amber: '#FBBF24',
                            sky: '#60A5FA',
                            rose: '#FB7185',
                        },
                    },
                    boxShadow: {
                        xs: '0 1px 2px 0 rgba(0,0,0,0.4)',
                        sm: '0 2px 6px -2px rgba(0,0,0,0.5)',
                        md: '0 6px 16px -6px rgba(0,0,0,0.6)',
                        lg: '0 12px 28px -10px rgba(0,0,0,0.7)',
                    },
                    borderWidth: {
                        DEFAULT: '1px',
                    },
                },
            },
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-ink-950 font-sans text-text-primary antialiased selection:bg-brand-primary selection:text-white">

    <div class="drawer md:drawer-open">
        <input id="admin-mobile-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex h-screen flex-col overflow-hidden bg-ink-950">
            @include('partials.admin-header')

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <div class="drawer-side z-40">
            <label for="admin-mobile-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            @hasSection('sidebar')
                @yield('sidebar')
            @else
                @include('partials.admin-sidebar')
            @endif
        </div>
    </div>
    @yield('scripts')
</body>
</html>