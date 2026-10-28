<div>
    <div class="min-h-screen bg-stone-100 p-6 md:p-10">

        <div class="mx-auto max-w-4xl">

            {{-- Header --}}
            <div class="mb-8">

                <a
                    href="{{ route('admin.products.index') }}"
                    class="text-sm font-semibold text-emerald-800 hover:text-emerald-950"
                >
                    ← Back to Products
                </a>

                <h1 class="mt-4 text-3xl font-bold text-stone-900">
                    Add New Product
                </h1>

                <p class="mt-2 text-stone-500">
                    Add a new drink, pastry, or food item to your menu.
                </p>

            </div>

            {{-- Product Form --}}
            <form
                wire:submit="save"
                class="space-y-8 rounded-2xl border border-stone-200 bg-white p-6 shadow-sm md:p-8"
            >

                {{-- Product Information --}}
                <section>

                    <h2 class="text-lg font-bold text-stone-900">
                        Product Information
                    </h2>

                    <p class="mt-1 text-sm text-stone-500">
                        Enter the basic details of your product.
                    </p>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">

                        {{-- Name --}}
                        <div class="md:col-span-2">

                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Product Name *
                            </label>

                            <input
                                id="name"
                                type="text"
                                wire:model="name"
                                placeholder="e.g. Spanish Latte"
                                maxlength="255"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Category --}}
                        <div>

                            <label
                                for="category"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Category *
                            </label>

                            <select
                                id="category"
                                wire:model="category"
                                required
                                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >
                                <option value="Coffee">Coffee</option>
                                <option value="Non-Coffee">Non-Coffee</option>
                                <option value="Tea">Tea</option>
                                <option value="Pastry">Pastry</option>
                                <option value="Food">Food</option>
                                <option value="Other">Other</option>
                            </select>

                            @error('category')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Price --}}
                        <div>

                            <label
                                for="price"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Price (₱) *
                            </label>

                            <input
                                id="price"
                                type="number"
                                wire:model="price"
                                placeholder="150.00"
                                min="0.01"
                                max="99999999.99"
                                step="0.01"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >

                            @error('price')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Stock --}}
                        <div>

                            <label
                                for="stock"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Initial Stock *
                            </label>

                            <input
                                id="stock"
                                type="number"
                                wire:model="stock"
                                min="0"
                                max="1000000"
                                step="1"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >

                            @error('stock')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Availability --}}
                        <div>

                            <label
                                for="is_available"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Availability *
                            </label>

                            <select
                                id="is_available"
                                wire:model="is_available"
                                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >
                                <option value="1">Available</option>
                                <option value="0">Unavailable</option>
                            </select>

                            @error('is_available')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">

                            <label
                                for="description"
                                class="mb-2 block text-sm font-semibold text-stone-700"
                            >
                                Description
                            </label>

                            <textarea
                                id="description"
                                wire:model="description"
                                rows="4"
                                maxlength="2000"
                                placeholder="Describe your drink, its ingredients, or what makes it special..."
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            ></textarea>

                            @error('description')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </section>

                {{-- Product Image --}}
                <section class="border-t border-stone-200 pt-8">

                    <h2 class="text-lg font-bold text-stone-900">
                        Product Image
                    </h2>

                    <p class="mt-1 text-sm text-stone-500">
                        Upload a photo to display on your menu.
                    </p>

                    <div class="mt-5">

                        <label
                            for="image"
                            class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 p-8 text-center transition hover:border-emerald-600 hover:bg-emerald-50"
                        >

                            <span class="text-4xl">📷</span>

                            <span class="mt-3 font-semibold text-stone-800">
                                Choose a product image
                            </span>

                            <span class="mt-2 text-sm text-stone-500">
                                JPG, JPEG, PNG, or WebP. Maximum 2 MB.
                            </span>

                            <input
                                id="image"
                                type="file"
                                wire:model="image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="sr-only"
                            >

                        </label>

                        @error('image')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        {{-- Upload Progress --}}
                        <div wire:loading wire:target="image" class="mt-3 text-sm text-emerald-800">
                            Uploading image...
                        </div>

                        {{-- Image Preview --}}
                        @if ($image)
                            <div class="mt-5">

                                <p class="mb-3 text-sm font-semibold text-stone-700">
                                    Image Preview
                                </p>

                                <img
                                    src="{{ $image->temporaryUrl() }}"
                                    alt="Product image preview"
                                    class="h-56 w-full rounded-xl border border-stone-200 object-cover sm:w-80"
                                >

                                <p class="mt-2 text-sm text-stone-500">
                                    {{ $image->getClientOriginalName() }}
                                </p>

                            </div>
                        @endif

                    </div>

                </section>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-stone-200 pt-6 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-xl border border-stone-300 px-6 py-3 text-center font-semibold text-stone-700 transition hover:bg-stone-100"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="rounded-xl bg-emerald-800 px-6 py-3 font-semibold text-white transition hover:bg-emerald-900 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="save">
                            Save Product
                        </span>

                        <span wire:loading wire:target="save">
                            Saving Product...
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
