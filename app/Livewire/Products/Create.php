<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $description = '';

    public string $category = 'Coffee';

    public string $price = '';

    public string $stock = '0';

    public bool $is_available = true;

    public $image = null;

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

    public function save(): void
    {
        $validated = $this->validate();

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store(
                'products',
                'public'
            );
        }

        try {
            Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'is_available' => $validated['is_available'],
                'image' => $imagePath,
            ]);
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        session()->flash(
            'success',
            'Product created successfully.'
        );

        $this->redirectRoute('admin.products.index');
    }

    public function render()
    {
        return view('livewire.products.create');
    }
}
