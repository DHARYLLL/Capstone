{{-- filepath: c:\Users\dhary\Desktop\Capstone\capstone1\resources\views\layouts\landing.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project RED AI</title>

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
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body class="bg-[#FCFBF8] font-sans text-gray-900 antialiased">
    @include('partials.navbar')

    <main class="min-h-screen pt-20">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.chatbot')
</body>
</html>