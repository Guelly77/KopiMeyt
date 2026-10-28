<div>

    <div class="mx-auto max-w-5xl px-4 py-12">
        <div class="mb-8">
            <a href="{{ route('home') }}"
            class="text-sm font-medium text-emerald-700 hover:text-emerald-900">
                &larr; Continue shopping
            </a>

            <h1 class="mt-4 text-3xl font-bold text-gray-900">
                Your Shopping Cart
            </h1>

            <p class="mt-2 text-gray-600">
                Review your selections before checkout.
            </p>
        </div>

        @if (session()->has('cart_message'))
            <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-emerald-800">
                {{ session('cart_message') }}
            </div>
        @endif

        @error('cart')
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-red-700">
                {{ $message }}
            </div>
        @enderror

        @if ($cartItems->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center">
                <h2 class="text-xl font-semibold text-gray-800">
                    Your cart is empty
                </h2>

                <p class="mt-2 text-gray-600">
                    Find something delicious on our menu.
                </p>

                <a href="{{ route('home') }}"
                class="mt-6 inline-block rounded-lg bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900">
                    Browse Menu
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    @foreach ($cartItems as $item)
                        <div wire:key="cart-item-{{ $item['product']->id }}"
                            class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex-1">
                                <h2 class="font-semibold text-gray-900">
                                    {{ $item['product']->name }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $item['product']->category }}
                                </p>

                                <p class="mt-2 font-medium text-emerald-800">
                                    ₱{{ number_format((float) $item['product']->price, 2) }}
                                    each
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    wire:click="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] - 1 }})"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-100"
                                    aria-label="Decrease quantity">
                                    −
                                </button>

                                <span class="min-w-6 text-center font-semibold">
                                    {{ $item['quantity'] }}
                                </span>

                                <button
                                    type="button"
                                    wire:click="updateQuantity({{ $item['product']->id }}, {{ $item['quantity'] + 1 }})"
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-100"
                                    aria-label="Increase quantity">
                                    +
                                </button>
                            </div>

                            <div class="text-left sm:text-right">
                                <p class="font-bold text-gray-900">
                                    ₱{{ number_format($item['subtotal'], 2) }}
                                </p>

                                <button
                                    type="button"
                                    wire:click="removeItem({{ $item['product']->id }})"
                                    wire:confirm="Remove this item from your cart?"
                                    class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <button
                        type="button"
                        wire:click="clearCart"
                        wire:confirm="Are you sure you want to clear your cart?"
                        class="text-sm font-medium text-red-600 hover:text-red-800">
                        Clear Cart
                    </button>
                </div>

                <aside class="h-fit rounded-xl border border-gray-200 bg-white p-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        Order Summary
                    </h2>

                    <div class="mt-5 flex justify-between border-b border-gray-200 pb-4 text-gray-600">
                        <span>Items</span>
                        <span>{{ $cartItems->sum('quantity') }}</span>
                    </div>

                    <div class="mt-4 flex justify-between text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>

                    @auth
                        @if (auth()->user()->role === 'customer')
                            <a href="{{ route('checkout') }}"
                            class="mt-6 block rounded-lg bg-emerald-800 px-4 py-3 text-center font-semibold text-white hover:bg-emerald-900">
                                Proceed to Checkout
                            </a>
                        @else
                            <p class="mt-5 text-sm text-gray-500">
                                Customer checkout is available to customer accounts.
                            </p>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                        class="mt-6 block rounded-lg bg-emerald-800 px-4 py-3 text-center font-semibold text-white hover:bg-emerald-900">
                            Log In to Checkout
                        </a>
                    @endauth
                </aside>
            </div>
        @endif
    </div>

</div>
