<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public array $cart = [];

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    public function addToCart(int $productId): void
    {
        $product = Product::query()
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->findOrFail($productId);

        $cart = session()->get('cart', []);
        $key = (string) $product->id;

        $currentQuantity = (int) ($cart[$key]['quantity'] ?? 0);

        if ($currentQuantity >= $product->stock) {
            $this->addError('cart', 'There is not enough stock for this quantity.');
            return;
        }

        $cart[$key] = [
            'product_id' => $product->id,
            'quantity' => $currentQuantity + 1,
        ];

        session()->put('cart', $cart);

        $this->cart = $cart;

        $this->resetErrorBag('cart');

        session()->flash('cart_message', $product->name . ' added to your cart!');
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        $key = (string) $productId;

        if (! isset($cart[$key])) {
            return;
        }

        if ($quantity < 1) {
            $this->removeItem($productId);
            return;
        }

        $product = Product::findOrFail($productId);

        if (! $product->is_available || $quantity > $product->stock) {
            $this->addError('cart', 'The requested quantity exceeds available stock.');
            return;
        }

        $cart[$key]['quantity'] = $quantity;

        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->resetErrorBag('cart');
    }

    public function removeItem(int $productId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[(string) $productId]);

        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->resetErrorBag('cart');
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->cart = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $items = Product::query()
            ->whereIn('id', collect($this->cart)->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $cartItems = collect($this->cart)
            ->map(function ($item) use ($items) {
                $product = $items->get($item['product_id']);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => (int) $item['quantity'],
                    'subtotal' => (float) $product->price * (int) $item['quantity'],
                ];
            })
            ->filter()
            ->values();

        return view('livewire.cart.index', [
            'cartItems' => $cartItems,
            'total' => $cartItems->sum('subtotal'),
        ])->layout('layouts.app', [
            'title' => 'Your Cart | Coffee Shop',
        ]);
    }
}
