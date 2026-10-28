<div>
    @if ($completedOrderNumber)
        {{-- Order Confirmation --}}
        <div class="mx-auto max-w-2xl px-4 py-12">
            <div class="rounded-2xl border border-emerald-200 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                    <span class="text-3xl text-emerald-700">✓</span>
                </div>

            <h1 class="mt-5 text-2xl font-bold text-gray-900">
                Order Placed Successfully!
            </h1>

            <p class="mt-3 text-gray-600">
                Thank you for ordering from our coffee shop.
            </p>

            <div class="mt-6 rounded-lg bg-gray-50 p-4">
                <p class="text-sm text-gray-500">Your Order Number</p>

                <p class="mt-1 text-xl font-bold text-emerald-800">
                    {{ $completedOrderNumber }}
                </p>
            </div>

            <p class="mt-4 text-sm text-gray-600">
                Your order has been recorded with a pending status.
            </p>

            <a href="{{ route('home') }}"
               class="mt-6 inline-block rounded-lg bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900">
                Return to Menu
            </a>
        </div>
    </div>
@else
    {{-- Checkout Page --}}
    <div class="mx-auto max-w-4xl px-4 py-12">
        <a href="{{ route('cart') }}"
           class="text-sm font-medium text-emerald-700 hover:text-emerald-900">
            &larr; Back to Cart
        </a>

        <h1 class="mt-4 text-3xl font-bold text-gray-900">
            Checkout
        </h1>

        <p class="mt-2 text-gray-600">
            Review your order and add any preparation instructions.
        </p>

        {{-- General checkout errors --}}
        @error('cart')
            <div class="mt-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                {{ $message }}
                <a href="{{ route('cart') }}" class="ml-1 font-semibold underline">
                    Review Cart
                </a>
            </div>
        @enderror

        <div class="mt-8 grid gap-8 md:grid-cols-2">
            {{-- Order Summary --}}
            <section class="h-fit rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-xl font-bold text-gray-900">
                    Order Summary
                </h2>

                @if ($cartItems->isEmpty())
                    <p class="mt-5 text-gray-600">
                        Your cart is empty.
                    </p>

                    <a href="{{ route('home') }}"
                       class="mt-4 inline-block font-semibold text-emerald-800 hover:underline">
                        Browse Menu
                    </a>
                @else
                    <div class="mt-5 space-y-4">
                        @foreach ($cartItems as $item)
                            <div wire:key="checkout-item-{{ $item['product']->id }}"
                                 class="flex justify-between gap-4">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $item['product']->name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Quantity: {{ $item['quantity'] }}
                                    </p>
                                </div>

                                <p class="font-medium text-gray-900">
                                    ₱{{ number_format($item['subtotal'], 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-between border-t border-gray-200 pt-4 text-lg font-bold">
                        <span>Total</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                @endif
            </section>

            {{-- Preparation Notes --}}
            <section class="h-fit rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-xl font-bold text-gray-900">
                    Preparation Notes
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    Optional private instructions for the barista.
                </p>

                <label for="notes"
                       class="mt-5 block text-sm font-medium text-gray-700">
                    Special instructions
                </label>

                <textarea
                    id="notes"
                    wire:model="notes"
                    rows="5"
                    maxlength="1000"
                    placeholder="Example: Less sugar, no ice, oat milk..."
                    class="mt-2 w-full rounded-lg border border-gray-300 p-3 focus:border-emerald-700 focus:outline-none focus:ring-1 focus:ring-emerald-700"
                ></textarea>

                @error('notes')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <p class="mt-2 text-xs text-gray-500">
                    Maximum 1,000 characters. Keep public feedback separate from these notes.
                </p>

                @if ($cartItems->isNotEmpty())
                    <button
                        type="button"
                        wire:click="placeOrder"
                        wire:loading.attr="disabled"
                        wire:target="placeOrder"
                        class="mt-6 w-full rounded-lg bg-emerald-800 px-4 py-3 font-semibold text-white hover:bg-emerald-900 disabled:cursor-not-allowed disabled:opacity-60">

                        <span wire:loading.remove wire:target="placeOrder">
                            Place Order
                        </span>

                        <span wire:loading wire:target="placeOrder">
                            Processing Order...
                        </span>
                    </button>
                @endif

                <p class="mt-3 text-xs text-gray-500">
                    Your order will be saved with a pending status. This checkout does not collect online payments.
                </p>
            </section>
        </div>
    </div>
@endif

</div>