<div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Orders Management
                </h1>

                <p class="mt-2 text-gray-600">
                    Manage customer orders and preparation instructions.
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50">
                Manage Products
            </a>
        </div>

        {{-- Success Message --}}
        @if (session()->has('order_message'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                {{ session('order_message') }}
            </div>
        @endif

        {{-- Status Filter --}}
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-5">
            <label for="statusFilter"
                class="mb-2 block text-sm font-semibold text-gray-700">
                Filter Orders by Status
            </label>

            <select
                id="statusFilter"
                wire:model.live="statusFilter"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-emerald-700 focus:outline-none focus:ring-1 focus:ring-emerald-700 sm:max-w-xs">

                <option value="all">All Orders</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="ready">Ready</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        {{-- Orders List --}}
        <div class="space-y-5">

            @forelse ($orders as $order)

                <article wire:key="order-{{ $order->id }}"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                    {{-- Order Header --}}
                    <div class="flex flex-col gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Order Number
                            </p>

                            <h2 class="mt-1 text-lg font-bold text-emerald-800">
                                {{ $order->order_number }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y · h:i A') }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">

                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'preparing' => 'bg-blue-100 text-blue-800',
                                    'ready' => 'bg-emerald-100 text-emerald-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];

                                $statusClass = $statusClasses[$order->status]
                                    ?? 'bg-gray-100 text-gray-800';
                            @endphp

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($order->status) }}
                            </span>

                            <span class="text-lg font-bold text-gray-900">
                                ₱{{ number_format((float) $order->total, 2) }}
                            </span>

                        </div>
                    </div>

                    {{-- Order Details --}}
                    <div class="p-5">

                        <div class="grid gap-4 sm:grid-cols-2">

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Customer
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $order->user?->name ?? 'Deleted Customer' }}
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $order->user?->email ?? 'No email available' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Items Ordered
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ $order->items->sum('quantity') }}
                                    {{ $order->items->sum('quantity') === 1 ? 'item' : 'items' }}
                                </p>
                            </div>

                        </div>

                        {{-- Expand Order --}}
                        <button
                            type="button"
                            wire:click="toggleOrder({{ $order->id }})"
                            class="mt-5 text-sm font-semibold text-emerald-800 hover:text-emerald-950">

                            {{ $expandedOrderId === $order->id
                                ? 'Hide Order Details ↑'
                                : 'View Order Details ↓' }}

                        </button>

                        @if ($expandedOrderId === $order->id)

                            <div class="mt-5 border-t border-gray-200 pt-5">

                                {{-- Ordered Products --}}
                                <h3 class="font-bold text-gray-900">
                                    Ordered Products
                                </h3>

                                <div class="mt-4 overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">

                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                                    Product
                                                </th>

                                                <th class="px-4 py-3 text-center font-semibold text-gray-600">
                                                    Quantity
                                                </th>

                                                <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                    Unit Price
                                                </th>

                                                <th class="px-4 py-3 text-right font-semibold text-gray-600">
                                                    Subtotal
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-200">

                                            @foreach ($order->items as $item)

                                                <tr wire:key="order-item-{{ $item->id }}">

                                                    <td class="px-4 py-3 font-medium text-gray-900">
                                                        {{ $item->product_name }}
                                                    </td>

                                                    <td class="px-4 py-3 text-center text-gray-700">
                                                        {{ $item->quantity }}
                                                    </td>

                                                    <td class="px-4 py-3 text-right text-gray-700">
                                                        ₱{{ number_format((float) $item->unit_price, 2) }}
                                                    </td>

                                                    <td class="px-4 py-3 text-right font-medium text-gray-900">
                                                        ₱{{ number_format((float) $item->line_total, 2) }}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                {{-- Private Preparation Notes --}}
                                <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4">

                                    <h3 class="font-bold text-amber-900">
                                        Preparation Notes
                                    </h3>

                                    @if (filled($order->notes))
                                        <p class="mt-2 whitespace-pre-line text-sm text-amber-900">{{ $order->notes }}</p>
                                    @else
                                        <p class="mt-2 text-sm text-amber-800">
                                            No special preparation instructions.
                                        </p>
                                    @endif

                                </div>

                                {{-- Update Status --}}
                                <div class="mt-6 rounded-lg bg-gray-50 p-4">

                                    <h3 class="font-bold text-gray-900">
                                        Update Order Status
                                    </h3>

                                    @php
                                        $availableStatuses = [
                                            'pending' => ['preparing', 'cancelled'],
                                            'preparing' => ['ready', 'cancelled'],
                                            'ready' => ['completed'],
                                            'completed' => [],
                                            'cancelled' => [],
                                        ];

                                        $nextStatuses = $availableStatuses[$order->status] ?? [];
                                    @endphp

                                    @if (count($nextStatuses) > 0)

                                        <div class="mt-3 flex flex-col gap-3 sm:flex-row">

                                            <select
                                                wire:model="statusUpdates.{{ $order->id }}"
                                                aria-label="Select new order status"
                                                class="rounded-lg border border-gray-300 px-4 py-3 focus:border-emerald-700 focus:outline-none sm:flex-1">

                                                <option value="{{ $order->status }}">
                                                    Keep current status
                                                </option>

                                                @foreach ($nextStatuses as $nextStatus)

                                                    <option value="{{ $nextStatus }}">
                                                        {{ ucfirst($nextStatus) }}
                                                    </option>

                                                @endforeach

                                            </select>

                                            <button
                                                type="button"
                                                wire:click="updateStatus({{ $order->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="updateStatus({{ $order->id }})"
                                                class="rounded-lg bg-emerald-800 px-5 py-3 font-semibold text-white hover:bg-emerald-900 disabled:opacity-60">

                                                Save Status

                                            </button>

                                        </div>

                                        @error('statusUpdates.' . $order->id)
                                            <p class="mt-2 text-sm text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    @else

                                        <p class="mt-2 text-sm text-gray-600">
                                            This order is {{ $order->status }} and cannot be advanced further.
                                        </p>

                                    @endif

                                </div>

                            </div>

                        @endif

                    </div>

                </article>

            @empty

                <div class="rounded-xl border border-gray-200 bg-white p-10 text-center">

                    <h2 class="text-xl font-bold text-gray-900">
                        No Orders Found
                    </h2>

                    <p class="mt-2 text-gray-600">
                        There are currently no orders matching this filter.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

    </div>
</div>
