<?php

namespace App\Livewire\Vendor;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PreparingOrders extends Component
{
    public function markAsDone($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => 'done']);

        session()->flash('message', 'Order marked as ready for pickup!');
    }

    public function render()
    {
        $vendor = Auth::user()->vendor;
        $orders = collect();

        if ($vendor) {
            $orders = Order::where('vendor_id', $vendor->id)
                ->where('status', 'preparing')
                ->with(['customer', 'items.menu'])
                ->latest()
                ->get();
        }

        return view('livewire.vendor.preparing-orders', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }
}
