<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Checkout extends Component
{
    use WithFileUploads;

    public $cart;
    public $vendorId;
    public $vendor;
    public $paymentProof;
    public $customerNotes;

    public function mount()
    {
        $this->cart = session('cart', []);
        $this->vendorId = session('vendor_id');

        if (empty($this->cart) || !$this->vendorId) {
            session()->flash('error', 'No items in cart');
            return redirect()->route('customer.vendors');
        }

        $this->vendor = Vendor::findOrFail($this->vendorId);
    }

    public function placeOrder()
    {
        $this->validate([
            'paymentProof' => 'required|image|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            // Calculate total
            $totalPrice = collect($this->cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // Create order
            $order = Order::create([
                'customer_id' => auth()->id(),
                'vendor_id' => $this->vendorId,
                'total_price' => $totalPrice,
                'status' => 'pending_payment',
                'customer_notes' => $this->customerNotes,
            ]);

            // Save payment proof
            $path = $this->paymentProof->store("payment_proofs/{$order->id}", 'public');
            $order->update(['payment_proof_path' => $path]);

            // Create order items
            foreach ($this->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget(['cart', 'vendor_id']);

            session()->flash('success', 'Order placed successfully!');
            return redirect()->route('customer.orders');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $totalPrice = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('livewire.customer.checkout', [
            'totalPrice' => $totalPrice,
        ])->layout('layouts.app');
    }
}
