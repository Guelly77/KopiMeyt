<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Coffee Shop') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">

    <header class="border-b border-stone-200 bg-white">

        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            <a
                href="{{ route('home') }}"
                class="text-xl font-bold tracking-tight text-emerald-900"
            >
                ☕ Coffee Shop
            </a>
            

            <div class="flex items-center gap-4">

                @auth

                    @if (auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="text-sm font-semibold text-stone-700 hover:text-emerald-800"
                        >
                            Products
                        </a>
                    @endif

                    
                        <a 
                            href="{{ route('cart') }}"
                            class="font-medium text-gray-700 hover:text-emerald-800">
                                Cart
                        </a>
                        
                    <span class="hidden text-sm text-stone-500 sm:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-lg bg-stone-100 px-4 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-200"
                        >
                            Log Out
                        </button>
                    </form>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="text-sm font-semibold text-stone-700 hover:text-emerald-800"
                    >
                        Log In
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-lg bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900"
                    >
                        Register
                    </a>

                @endauth

            </div>

        </nav>

    </header>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-stone-200 bg-white px-6 py-6 text-center text-sm text-stone-500">
        © {{ date('Y') }} Coffee Shop. All rights reserved.
    </footer>

    @livewireScripts

</body>
</html>