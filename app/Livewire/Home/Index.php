<?php

namespace App\Livewire\Home;

use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public string $category = 'All';

    public array $categories = [
        'All',
        'Coffee',
        'Non-Coffee',
        'Tea',
        'Pastry',
        'Food',
    ];

    public function selectCategory(string $category): void
    {
        if (in_array($category, $this->categories, true)) {
            $this->category = $category;
        }
    }


    public function addToCart(int $productId): void
    {
        if (! auth()->check() || auth()->user()->role !== 'customer') {
            $this->redirectRoute('login');
            return;
        }

        $product = Product::query()
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->findOrFail($productId);

        $cart = session()->get('cart', []);
        $key = (string) $product->id;
        $quantity = (int) ($cart[$key]['quantity'] ?? 0);

        if ($quantity >= $product->stock) {
            session()->flash('cart_error', 'There is not enough stock available.');
            return;
        }

        $cart[$key] = [
            'product_id' => $product->id,
            'quantity' => $quantity + 1,
        ];

        session()->put('cart', $cart);

        session()->flash('cart_message', $product->name . ' added to your cart!');
    }


    public function render()
    {
        $products = Product::query()
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->when(
                $this->category !== 'All',
                fn($query) => $query->where('category', $this->category)
            )
            ->latest()
            ->get();

        return view('livewire.home.index', [
            'products' => $products,
        ])->layout('layouts.app', [
            'title' => 'Coffee Shop | Freshly Brewed Happiness',
        ]);
    }
}
