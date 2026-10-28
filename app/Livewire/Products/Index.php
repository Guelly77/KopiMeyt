<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::findOrFail($id);

        // We'll add administrator authorization here
        // before enabling this action in the application.

        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($product->image);
        }

        $product->delete();

        session()->flash('success', 'Product deleted successfully.');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.products.index', [
            'products' => $products,
        ]);
    }
}
