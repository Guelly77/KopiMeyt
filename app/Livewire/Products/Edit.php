<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public string $name = '';

    public string $description = '';

    public string $category = 'Coffee';

    public string $price = '';

    public string $stock = '0';

    public bool $is_available = true;

    public $image = null;

    public function mount(Product $product): void
    {
        $this->product = $product;

        $this->name = $product->name;
        $this->description = $product->description ?? '';
        $this->category = $product->category;
        $this->price = (string) $product->price;
        $this->stock = (string) $product->stock;
        $this->is_available = $product->is_available;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'category' => [
                'required',
                Rule::in([
                    'Coffee',
                    'Non-Coffee',
                    'Tea',
                    'Pastry',
                    'Food',
                    'Other',
                ]),
            ],

            'price' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:99999999.99',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
                'max:1000000',
            ],

            'is_available' => [
                'required',
                'boolean',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function update(): void
    {
        $validated = $this->validate();

        $oldImage = $this->product->image;
        $newImagePath = null;

        if ($this->image) {
            $newImagePath = $this->image->store(
                'products',
                'public'
            );
        }

        try {
            $this->product->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'is_available' => $validated['is_available'],
                'image' => $newImagePath ?? $oldImage,
            ]);
        } catch (\Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        session()->flash(
            'success',
            'Product updated successfully.'
        );

        $this->redirectRoute('admin.products.index');
    }

    public function render()
    {
        return view('livewire.products.edit');
    }
}
