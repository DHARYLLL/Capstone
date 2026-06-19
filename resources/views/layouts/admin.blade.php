{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\layouts\admin.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Project RED AI</title>

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
                },
            },
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="bg-[#F9F8F6] font-sans text-gray-800 antialiased">
    <div class="flex min-h-screen overflow-hidden">
        @include('partials.admin-sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            @include('partials.admin-header')

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>