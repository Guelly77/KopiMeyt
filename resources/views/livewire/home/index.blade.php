<div class="flex flex-1 flex-col">

    {{-- ============ HERO + WELCOME SECTIONS ============ --}}
    <div class="flex min-h-screen flex-col">

        {{-- Hero Section --}}
        <section class="relative flex flex-1 items-center overflow-hidden bg-[#173f32] text-white rounded-t-2xl">
            <div class="mx-auto grid w-full max-w-7xl items-center gap-10 px-6 py-12 md:grid-cols-2 md:px-10 md:py-14 lg:py-16">

                {{-- Hero Text --}}
                <div class="relative z-10">
                    <p class="mb-5 text-sm font-semibold uppercase tracking-[0.3em] text-amber-300">
                        Your daily coffee ritual
                    </p>

                    <h1 class="max-w-xl text-5xl font-bold leading-tight tracking-tight md:text-6xl lg:text-7xl">
                        A little coffee.
                        <span class="text-amber-300">A lot of happiness.</span>
                    </h1>

                    <p class="mt-6 max-w-lg text-lg leading-8 text-stone-200">
                        Discover your new favorite cup, freshly prepared with care
                        and served with a little warmth in every sip.
                    </p>

                    <a
                        href="#menu"
                        class="mt-8 inline-flex rounded-full bg-amber-300 px-7 py-3.5 font-bold text-[#173f32] transition hover:bg-amber-200 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-300"
                    >
                        Explore Our Menu
                        <span class="ml-2" aria-hidden="true">→</span>
                    </a>
                </div>

                {{-- Hero Artwork --}}
                <div class="flex items-center justify-center py-4">
                    <div class="relative flex h-64 w-64 items-center justify-center rounded-full bg-[#285746] sm:h-72 sm:w-72 md:h-80 md:w-80 lg:h-96 lg:w-96">
                        <div class="absolute h-52 w-52 rounded-full border border-amber-300/30 sm:h-60 sm:w-60 md:h-64 md:w-64 lg:h-80 lg:w-80"></div>

                        <div class="text-center">
                            <div class="text-8xl md:text-9xl" aria-hidden="true">
                                ☕
                            </div>

                            <p class="mt-4 text-sm uppercase tracking-[0.25em] text-amber-200">
                                Brewed with love
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Welcome / Feature Section --}}
        <section class="shrink-0 bg-[#faf8f4] px-6 py-8 md:px-10 md:py-9">
            <div class="mx-auto grid max-w-7xl gap-8 sm:grid-cols-3">

                {{-- Feature 1 --}}
                <div>
                    <span class="text-3xl" aria-hidden="true">☕</span>

                    <h2 class="mt-3 text-lg font-bold text-stone-900">
                        Freshly Prepared
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-600">
                        Drinks made to order, with attention to every cup.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div>
                    <span class="text-3xl" aria-hidden="true">🌿</span>

                    <h2 class="mt-3 text-lg font-bold text-stone-900">
                        Quality Ingredients
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-600">
                        Thoughtfully selected ingredients for a better coffee break.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div>
                    <span class="text-3xl" aria-hidden="true">♡</span>

                    <h2 class="mt-3 text-lg font-bold text-stone-900">
                        Made for Your Day
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-stone-600">
                        A cozy little stop for your favorite everyday moments.
                    </p>
                </div>

            </div>
        </section>

    </div>

    {{-- ============ PRODUCT MENU ============ --}}
    <section id="menu" class="scroll-mt-8 bg-white py-16">
        <div class="mx-auto max-w-7xl px-6 md:px-10">

            <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-800">
                        Made fresh for you
                    </p>

                    <h2 class="mt-3 text-4xl font-bold tracking-tight text-stone-900">
                        Explore Our Menu
                    </h2>

                    <p class="mt-3 max-w-xl text-stone-600">
                        From your morning coffee to an afternoon treat,
                        find something you'll love.
                    </p>
                </div>

                <p class="text-sm text-stone-500">
                    {{ $products->count() }}
                    {{ Str::plural('product', $products->count()) }} available
                </p>
            </div>

            {{-- Category Filters --}}
            <div class="mt-8 flex flex-wrap gap-3">
                @foreach ($categories as $item)
                    <button
                        type="button"
                        wire:click="selectCategory('{{ $item }}')"
                        @class([
                            'rounded-full px-5 py-2.5 text-sm font-semibold transition',
                            'bg-[#173f32] text-white' => $category === $item,
                            'bg-stone-100 text-stone-700 hover:bg-stone-200' => $category !== $item,
                        ])
                    >
                        {{ $item }}
                    </button>
                @endforeach
            </div>

            {{-- Product Cards --}}
            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                @forelse ($products as $product)
                    <article
                        wire:key="product-{{ $product->id }}"
                        class="group overflow-hidden rounded-2xl border border-stone-200 bg-white transition hover:-translate-y-1 hover:shadow-lg"
                    >

                        <div class="relative flex h-56 items-center justify-center overflow-hidden bg-[#f2eee6]">

                            @if ($product->image)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            @else
                                <div class="text-7xl" aria-hidden="true">
                                    {{ match ($product->category) {
                                        'Coffee' => '☕',
                                        'Non-Coffee' => '🥤',
                                        'Tea' => '🍵',
                                        'Pastry' => '🥐',
                                        'Food' => '🥪',
                                        default => '🍽️',
                                    } }}
                                </div>
                            @endif

                            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-stone-700">
                                {{ $product->category }}
                            </span>

                        </div>

                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold text-stone-900">
                                    {{ $product->name }}
                                </h3>

                                <p class="shrink-0 font-bold text-emerald-900">
                                    ₱{{ number_format((float) $product->price, 2) }}
                                </p>
                            </div>

                            <p class="mt-2 min-h-12 text-sm leading-6 text-stone-600">
                                {{ $product->description ?: 'A delicious choice for your next coffee break.' }}
                            </p>

                            <div class="mt-5 border-t border-stone-100 pt-4">
                                @auth
                                    @if (auth()->user()->role === 'customer')
                                        <button
                                            type="button"
                                            wire:click="addToCart({{ $product->id }})"
                                            class="mt-4 w-full rounded-lg bg-emerald-800 px-4 py-3 font-semibold text-white hover:bg-emerald-900"
                                        >
                                            Add to Cart
                                        </button>
                                    @endif
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="mt-4 block w-full rounded-lg bg-emerald-800 px-4 py-3 text-center font-semibold text-white hover:bg-emerald-900"
                                    >
                                        Log In to Order
                                    </a>
                                @endauth
                            </div>
                        </div>

                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-stone-300 px-6 py-16 text-center">

                        <div class="text-5xl" aria-hidden="true">☕</div>

                        <h3 class="mt-4 text-xl font-bold text-stone-900">
                            Nothing brewing just yet
                        </h3>

                        <p class="mt-2 text-stone-600">
                            @if ($category === 'All')
                                Our menu is being prepared. Please check back soon.
                            @else
                                There are no available products in this category.
                            @endif
                        </p>

                        @if ($category !== 'All')
                            <button
                                type="button"
                                wire:click="selectCategory('All')"
                                class="mt-5 font-semibold text-emerald-800 hover:underline"
                            >
                                View all products
                            </button>
                        @endif

                    </div>
                @endforelse

            </div>

        </div>
    </section>

    {{-- ============ FOOTER CTA ============ --}}
    <section class="bg-[#eee8dc] px-6 py-16 text-center">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-900">
            Your next favorite is waiting
        </p>

        <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-bold text-stone-900 md:text-4xl">
            Make today a little more delicious.
        </h2>

        <p class="mt-4 text-stone-600">
            Create an account and get ready to order your favorites.
        </p>

        @guest
            <a
                href="{{ route('register') }}"
                class="mt-7 inline-block rounded-full bg-[#173f32] px-7 py-3.5 font-semibold text-white transition hover:bg-[#245744]"
            >
                Create Your Account
            </a>
        @endguest
    </section>

</div>
