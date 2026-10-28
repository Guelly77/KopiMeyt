<div>
    <div class="min-h-screen bg-gray-100 p-8">

        <div class="mx-auto max-w-7xl">

            <div class="mb-8 flex items-center justify-between">

                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Product Management
                    </h1>

                    <p class="mt-2 text-gray-500">
                        Manage your coffee shop products.
                    </p>
                </div>

                <a
                    href="{{ route('admin.products.create') }}"
                    class="rounded-lg bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800"
                >
                    + Add Product
                </a>

            </div>

            @if (session()->has('success'))
                <div class="mb-5 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl bg-white p-6 shadow-sm">

                <div class="mb-6">

                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search products..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-emerald-600 focus:outline-none"
                    >

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead>
                            <tr class="border-b bg-gray-50 text-sm text-gray-600">
                                <th class="p-4">Product</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Price</th>
                                <th class="p-4">Stock</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($products as $product)

                                <tr wire:key="product-{{ $product->id }}"
                                    class="border-b hover:bg-gray-50">

                                    <td class="p-4 font-medium text-gray-800">
                                        {{ $product->name }}
                                    </td>

                                    <td class="p-4 text-gray-600">
                                        {{ $product->category }}
                                    </td>

                                    <td class="p-4">
                                        ₱{{ number_format((float) $product->price, 2) }}
                                    </td>

                                    <td class="p-4">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="p-4">

                                        @if ($product->is_available)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Available
                                            </span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Unavailable
                                            </span>
                                        @endif

                                    </td>

                                    <td class="space-x-3 whitespace-nowrap p-4">

                                        <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="font-medium text-blue-600 hover:underline"
                                        >
                                            Edit
                                        </a>

                                        <button
                                            type="button"
                                            wire:click="deleteProduct({{ $product->id }})"
                                            wire:confirm="Are you sure you want to delete this product?"
                                            class="font-medium text-red-600 hover:underline"
                                        >
                                            Delete
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-500">
                                        No products found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>

            </div>

        </div>

    </div>
</div>
