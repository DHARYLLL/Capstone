{{-- filepath: resources/views/layouts/manager.blade.php --}}
{{-- Dedicated layout for branch managers — sidebar is scoped to their assigned unit only --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Manager Portal') | Project RED AI</title>

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
                        brand: {
                            primary: '#7c3aed',
                            'primary-dark': '#6d28d9',
                            'primary-light': '#f5f3ff',
                            charcoal: '#1e293b',
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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-[#f8fafc] font-sans text-gray-800 antialiased">
<div class="flex min-h-screen overflow-hidden">

    {{-- Manager sidebar — injected from the child view via @section('manager-sidebar') --}}
    @yield('manager-sidebar')

    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

        {{-- Header --}}
        <header class="sticky top-0 z-20 border-b border-gray-200 bg-[#f8fafc]/95 backdrop-blur">
            <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
                        @yield('breadcrumbs', 'Manager / Dashboard')
                    </div>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-gray-800">
                        @yield('page_title', 'Manager Dashboard')
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        @yield('page_description', 'Your scoped workspace for this business unit.')
                    </p>
                </div>
                <div class="flex items-center gap-3 rounded-2xl bg-base-100 px-4 py-3 shadow-sm">
                    <div class="text-right leading-tight">
                        <div class="font-bold text-gray-800">Branch Manager</div>
                        <div class="text-xs text-gray-500">@yield('unit-type', 'Business Unit')</div>
                    </div>
                    <div class="avatar">
                        <div class="h-11 w-11 rounded-full ring-2 ring-[#1e293b] ring-offset-2 ring-offset-base-100">
                            <img src="https://i.pravatar.cc/100?img=47" alt="Manager avatar">
                        </div>
                    </div>
                </div>
            </div>
        </header>

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
