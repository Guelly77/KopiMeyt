<?php

namespace App\Livewire\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Index extends Component
{
    public string $notes = '';

    public ?string $completedOrderNumber = null;

    public function mount(): void
    {
        if (
            auth()->guest() ||
            auth()->user()->role !== 'customer'
        ) {
            $this->redirectRoute('login');
            return;
        }

        if (empty(session()->get('cart', []))) {
            $this->redirectRoute('cart');
        }
    }

    public function placeOrder(): void
    {
        // Confirm the customer is still authenticated and authorized.
        if (
            auth()->guest() ||
            auth()->user()->role !== 'customer'
        ) {
            $this->redirectRoute('login');
            return;
        }

        // Validate the preparation notes.
        $this->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = session()->get('cart', []);

        if (! is_array($cart) || empty($cart)) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty. Please add products first.',
            ]);
        }

        // Validate the structure of every cart entry.
        $quantities = [];

        foreach ($cart as $item) {
            if (
                ! is_array($item) ||
                ! isset($item['product_id'], $item['quantity']) ||
                ! is_numeric($item['product_id']) ||
                ! is_numeric($item['quantity']) ||
                (int) $item['product_id'] < 1 ||
                (int) $item['quantity'] < 1 ||
                (int) $item['product_id'] != $item['product_id'] ||
                (int) $item['quantity'] != $item['quantity']
            ) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart contains invalid items. Please review your cart.',
                ]);
            }

            $productId = (int) $item['product_id'];

            // Prevent duplicate entries for the same product.
            $quantities[$productId] =
                ($quantities[$productId] ?? 0) + (int) $item['quantity'];
        }

        try {
            $order = DB::transaction(function () use ($quantities) {
                $productIds = array_keys($quantities);

                // Lock the product rows so concurrent checkouts cannot
                // oversell the same available stock.
                $products = Product::query()
                    ->whereIn('id', $productIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($products->count() !== count($productIds)) {
                    throw ValidationException::withMessages([
                        'cart' => 'One or more products no longer exist. Please review your cart.',
                    ]);
                }

                $totalCents = 0;
                $orderLines = [];

                foreach ($quantities as $productId => $quantity) {
                    $product = $products->get($productId);

                    if (
                        ! $product->is_available ||
                        $product->stock < $quantity
                    ) {
                        throw ValidationException::withMessages([
                            'cart' => "Insufficient stock for {$product->name}. Please update your cart.",
                        ]);
                    }

                    // Calculate in cents to avoid accumulating
                    // floating-point rounding errors.
                    $unitPriceCents = (int) round(
                        (float) $product->price * 100
                    );

                    $lineTotalCents = $unitPriceCents * $quantity;

                    $totalCents += $lineTotalCents;

                    $orderLines[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'unit_price' => $unitPriceCents / 100,
                        'line_total' => $lineTotalCents / 100,
                    ];
                }

                // Save the order using the authenticated customer.
                $order = Order::create([
                    'order_number' => 'CS-' .
                        now()->format('Ymd') . '-' .
                        Str::upper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'total' => $totalCents / 100,
                    'status' => 'pending',
                    'notes' => trim($this->notes) !== ''
                        ? trim($this->notes)
                        : null,
                ]);

                // Save order items and deduct inventory.
                foreach ($orderLines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $line['product']->id,
                        'product_name' => $line['product']->name,
                        'unit_price' => $line['unit_price'],
                        'quantity' => $line['quantity'],
                        'line_total' => $line['line_total'],
                    ]);

                    $line['product']->decrement(
                        'stock',
                        $line['quantity']
                    );
                }

                return $order;
            }, 3);
        } catch (ValidationException $exception) {
            // Preserve Laravel's validation errors for Livewire.
            throw $exception;
        }

        // Clear the cart only after the database transaction succeeds.
        session()->forget('cart');

        $this->notes = '';
        $this->completedOrderNumber = $order->order_number;
    }

    public function render()
    {
        $cart = session()->get('cart', []);

        $productIds = collect($cart)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $cartItems = collect($cart)
            ->map(function ($item) use ($products) {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => (int) $item['quantity'],
                    'subtotal' => round(
                        (float) $product->price * (int) $item['quantity'],
                        2
                    ),
                ];
            })
            ->filter()
            ->values();

        return view('livewire.checkout.index', [
            'cartItems' => $cartItems,
            'total' => $cartItems->sum('subtotal'),
        ])->layout('layouts.app', [
            'title' => 'Checkout | Coffee Shop',
        ]);
    }
}
