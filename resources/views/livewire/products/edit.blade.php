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
                    Edit Product
                </h1>

                <p class="mt-2 text-stone-500">
                    Update the details of {{ $product->name }}.
                </p>

            </div>

            <form
                wire:submit="update"
                class="space-y-8 rounded-2xl border border-stone-200 bg-white p-6 shadow-sm md:p-8"
            >

                {{-- Product Information --}}
                <section>

                    <h2 class="text-lg font-bold text-stone-900">
                        Product Information
                    </h2>

                    <div class="mt-6 grid gap-6 md:grid-cols-2">

                        {{-- Name --}}
                        <div class="md:col-span-2">

                            <label for="name" class="mb-2 block text-sm font-semibold text-stone-700">
                                Product Name *
                            </label>

                            <input
                                id="name"
                                type="text"
                                wire:model="name"
                                maxlength="255"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-100"
                            >

                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Category --}}
                        <div>

                            <label for="category" class="mb-2 block text-sm font-semibold text-stone-700">
                                Category *
                            </label>

                            <select
                                id="category"
                                wire:model="category"
                                required
                                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 outline-none focus:border-emerald-700"
                            >
                                <option value="Coffee">Coffee</option>
                                <option value="Non-Coffee">Non-Coffee</option>
                                <option value="Tea">Tea</option>
                                <option value="Pastry">Pastry</option>
                                <option value="Food">Food</option>
                                <option value="Other">Other</option>
                            </select>

                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Price --}}
                        <div>

                            <label for="price" class="mb-2 block text-sm font-semibold text-stone-700">
                                Price (₱) *
                            </label>

                            <input
                                id="price"
                                type="number"
                                wire:model="price"
                                min="0.01"
                                max="99999999.99"
                                step="0.01"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                            >

                            @error('price')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Stock --}}
                        <div>

                            <label for="stock" class="mb-2 block text-sm font-semibold text-stone-700">
                                Stock *
                            </label>

                            <input
                                id="stock"
                                type="number"
                                wire:model="stock"
                                min="0"
                                max="1000000"
                                step="1"
                                required
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                            >

                            @error('stock')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Availability --}}
                        <div>

                            <label for="is_available" class="mb-2 block text-sm font-semibold text-stone-700">
                                Availability *
                            </label>

                            <select
                                id="is_available"
                                wire:model="is_available"
                                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 outline-none focus:border-emerald-700"
                            >
                                <option value="1">Available</option>
                                <option value="0">Unavailable</option>
                            </select>

                            @error('is_available')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">

                            <label for="description" class="mb-2 block text-sm font-semibold text-stone-700">
                                Description
                            </label>

                            <textarea
                                id="description"
                                wire:model="description"
                                rows="4"
                                maxlength="2000"
                                class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                                placeholder="Describe your product..."
                            ></textarea>

                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                </section>

                {{-- Product Image --}}
                <section class="border-t border-stone-200 pt-8">

                    <h2 class="text-lg font-bold text-stone-900">
                        Product Image
                    </h2>

                    {{-- Current Image --}}
                    @if ($product->image)
                        <div class="mt-5">

                            <p class="mb-3 text-sm font-semibold text-stone-700">
                                Current Image
                            </p>

                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="h-56 w-full rounded-xl border border-stone-200 object-cover sm:w-80"
                            >

                        </div>
                    @endif

                    {{-- Upload New Image --}}
                    <div class="mt-6">

                        <label for="image" class="mb-2 block text-sm font-semibold text-stone-700">
                            Upload a New Image
                        </label>

                        <input
                            id="image"
                            type="file"
                            wire:model="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-xl border border-stone-300 p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-100 file:px-4 file:py-2 file:font-semibold file:text-emerald-900"
                        >

                        <p class="mt-2 text-sm text-stone-500">
                            JPG, JPEG, PNG, or WebP. Maximum 2 MB.
                            Leave this empty to keep the current image.
                        </p>

                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div wire:loading wire:target="image" class="mt-3 text-sm text-emerald-800">
                            Uploading image...
                        </div>

                        {{-- New Image Preview --}}
                        @if ($image)
                            <div class="mt-5">

                                <p class="mb-3 text-sm font-semibold text-stone-700">
                                    New Image Preview
                                </p>

                                <img
                                    src="{{ $image->temporaryUrl() }}"
                                    alt="New product image preview"
                                    class="h-56 w-full rounded-xl border border-stone-200 object-cover sm:w-80"
                                >

                            </div>
                        @endif

                    </div>

                </section>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-stone-200 pt-6 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-xl border border-stone-300 px-6 py-3 text-center font-semibold text-stone-700 hover:bg-stone-100"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="update"
                        class="rounded-xl bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="update">
                            Update Product
                        </span>

                        <span wire:loading wire:target="update">
                            Updating Product...
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
