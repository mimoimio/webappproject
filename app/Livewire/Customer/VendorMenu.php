<?php

namespace App\Livewire\Customer;

use App\Models\Menu;
use App\Models\Vendor;
use Livewire\Component;

class VendorMenu extends Component
{
    public $vendorId;
    public $vendor;
    public $cart = [];
    public $quantity = [];

    public function mount($vendorId)
    {
        $this->vendorId = $vendorId;
        $this->vendor = Vendor::findOrFail($vendorId);
    }

    public function addToCart($menuId)
    {
        $qty = $this->quantity[$menuId] ?? 1;

        logger()->info('Add to cart', [
            'menu_id' => $menuId,
            'quantity' => $qty,
            'current_cart' => $this->cart,
            'all_quantities' => $this->quantity
        ]);

        if (isset($this->cart[$menuId])) {
            $this->cart[$menuId]['quantity'] += $qty;
        } else {
            $menu = Menu::findOrFail($menuId);
            $this->cart[$menuId] = [
                'menu_id' => $menuId,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $qty,
            ];
        }

        // Reset quantity input
        $this->quantity[$menuId] = 1;
        session()->flash('message', 'Item added to cart!');
    }

    public function updateQuantity($menuId, $quantity)
    {
        if ($quantity > 0) {
            $this->cart[$menuId]['quantity'] = $quantity;
        } else {
            unset($this->cart[$menuId]);
        }
    }

    public function removeFromCart($menuId)
    {
        unset($this->cart[$menuId]);
    }

    public function getCartTotal()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function proceedToCheckout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        session(['cart' => $this->cart, 'vendor_id' => $this->vendorId]);
        return redirect()->route('customer.checkout');
    }

    public function render()
    {
        $menus = Menu::where('vendor_id', $this->vendorId)
            ->where('is_available', true)
            ->get();

        return view('livewire.customer.vendor-menu', [
            'menus' => $menus,
        ])->layout('layouts.app');
    }
}
