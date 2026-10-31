<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Coffee Shop') }}</title>

    {{-- Fonts: Bebas Neue (display), Yellowtail (script accent), Inter (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=Yellowtail&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="flex min-h-screen flex-col bg-cream font-sans text-forest-950 antialiased">

    {{-- ============ HEADER ============ --}}
    <header class="sticky top-0 z-50 border-b border-white/10 bg-forest-900/95 backdrop-blur">

        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-4">

            <a
                href="{{ route('home') }}"
                class="flex shrink-0 items-center rounded-md focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-gold-400"
            >
                <img
                    alt="KopiMeyt Logo"
                    class="h-10 w-auto object-contain"
                    src="{{ asset('KopiMeyt_Transparent.png') }}"
                />
            </a>

            <div class="flex items-center gap-3 sm:gap-5">

                @auth

                    @if (auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="font-display text-lg tracking-wider text-cream transition hover:text-gold-400"
                        >
                            Products
                        </a>

                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="font-display text-lg tracking-wider text-cream transition hover:text-gold-400"
                        >
                            Orders
                        </a>
                    @endif

                    <a
                        href="{{ route('cart') }}"
                        class="flex h-11 w-11 items-center justify-center rounded-full bg-gold-400 text-forest-900 transition hover:bg-gold-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold-400"
                        aria-label="Cart"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                            <path d="M6 7h12l1 13H5L6 7Z" />
                            <path d="M9 7a3 3 0 0 1 6 0" />
                        </svg>
                    </a>

                    <span class="hidden text-sm text-cream/70 md:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-full border border-cream/30 px-5 py-2.5 font-display text-lg leading-none tracking-wider text-cream transition hover:border-gold-400 hover:text-gold-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold-400"
                        >
                            Log Out
                        </button>
                    </form>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="font-display text-lg tracking-wider text-cream transition hover:text-gold-400"
                    >
                        Log In
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-full bg-gold-400 px-6 py-2.5 font-display text-lg leading-none tracking-wider text-forest-900 transition hover:bg-gold-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold-400"
                    >
                        Register
                    </a>

                @endauth

            </div>

        </nav>

    </header>

    {{-- ============ MAIN ============ --}}
    <main class="flex-1 min-h-screen bg-cream px-6 py-8 sm:px-8 lg:px-12">
        {{ $slot }}
    </main>
    
    {{-- ============ FOOTER ============ --}}
    <div class="scallop" aria-hidden="true"></div>

    <footer class="bg-gold-400 px-6 pb-8 pt-2 text-center text-forest-900">
        <p class="font-display text-3xl tracking-wider">KopiMeyt</p>
        <p class="mt-1 text-sm font-medium text-forest-900/80">
            © {{ date('Y') }} Coffee Shop. All rights reserved.
        </p>
    </footer>

    @livewireScripts

</body>
</html>