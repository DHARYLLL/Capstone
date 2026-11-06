{{-- filepath: resources/views/layouts/staff.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Portal | DARIV Waterproofing</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            primary: '#D8210B',
                            'primary-dark': '#b51a08',
                            'primary-light': '#fef2f2',
                            coral: '#EE4E56',
                            gold: '#F2D742',
                            charcoal: '#263049',
                            navy: '#263049',
                            'bg-base': '#f8fafc',
                            'bg-card': '#ffffff',
                            border: '#e2e8f0',
                        }
                    }
                },
            },
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/axios@1.11.0/dist/axios.min.js"></script>
</head>
<body class="bg-[#f8fafc] font-sans text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        @include('partials.staff-sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('partials.staff-header')

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @yield('scripts')
</body>
</html>
