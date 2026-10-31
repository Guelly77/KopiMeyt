<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public array $statusUpdates = [];

    public ?int $expandedOrderId = null;

    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() &&
                auth()->user()->role === 'admin',
            403,
            'You are not authorized to manage orders.'
        );
    }

    public function updatedStatusFilter(): void
    {
        $this->authorizeAdmin();

        $allowedFilters = [
            'all',
            'pending',
            'preparing',
            'ready',
            'completed',
            'cancelled',
        ];

        if (! in_array($this->statusFilter, $allowedFilters, true)) {
            $this->statusFilter = 'all';
        }

        $this->resetPage();
    }

    public function toggleOrder(int $orderId): void
    {
        $this->authorizeAdmin();

        $order = Order::findOrFail($orderId);

        $this->expandedOrderId =
            $this->expandedOrderId === $order->id
            ? null
            : $order->id;

        $this->statusUpdates[$order->id] = $order->status;
    }

    public function updateStatus(int $orderId): void
    {
        $this->authorizeAdmin();

        $order = Order::findOrFail($orderId);

        $key = (string) $orderId;

        $validated = $this->validate([
            "statusUpdates.{$key}" => [
                'required',
                'string',
                'in:pending,preparing,ready,completed,cancelled',
            ],
        ]);

        $newStatus = $validated['statusUpdates'][$key];

        if ($newStatus === $order->status) {
            return;
        }

        // Only permit valid status transitions.
        $transitions = [
            'pending' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        $allowedNextStatuses = $transitions[$order->status] ?? [];

        if (! in_array($newStatus, $allowedNextStatuses, true)) {
            throw ValidationException::withMessages([
                "statusUpdates.{$key}" =>
                "Cannot change an order from {$order->status} to {$newStatus}.",
            ]);
        }

        DB::transaction(function () use ($orderId, $newStatus, $allowedNextStatuses) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($orderId);

            // Recheck the transition against the latest database status.
            $transitions = [
                'pending' => ['preparing', 'cancelled'],
                'preparing' => ['ready', 'cancelled'],
                'ready' => ['completed'],
                'completed' => [],
                'cancelled' => [],
            ];

            $latestAllowedStatuses =
                $transitions[$lockedOrder->status] ?? [];

            if (! in_array($newStatus, $latestAllowedStatuses, true)) {
                throw ValidationException::withMessages([
                    "statusUpdates.{$orderId}" =>
                    'This order status has changed. Refresh the order and try again.',
                ]);
            }

            $lockedOrder->update([
                'status' => $newStatus,
            ]);
        });

        $this->statusUpdates[$key] = $newStatus;

        session()->flash(
            'order_message',
            "Order {$order->order_number} updated to " .
                ucfirst($newStatus) . '.'
        );
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->when(
                $this->statusFilter !== 'all',
                fn($query) => $query->where(
                    'status',
                    $this->statusFilter
                )
            )
            ->latest()
            ->paginate(10);

        return view('livewire.orders.index', [
            'orders' => $orders,
        ])->layout('layouts.app', [
            'title' => 'Orders Management | Coffee Shop',
        ]);
    }
}
