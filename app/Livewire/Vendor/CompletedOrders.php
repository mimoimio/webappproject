<?php

namespace App\Livewire\Vendor;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CompletedOrders extends Component
{
    public function render()
    {
        $vendor = Auth::user()->vendor;
        $orders = collect();

        if ($vendor) {
            $orders = Order::where('vendor_id', $vendor->id)
                ->where('status', 'done')
                ->with(['customer', 'items.menu'])
                ->latest()
                ->get();
        }

        return view('livewire.vendor.completed-orders', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }
}
