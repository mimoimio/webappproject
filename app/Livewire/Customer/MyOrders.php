<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;

class MyOrders extends Component
{
    public function render()
    {
        $orders = Order::where('customer_id', auth()->id())
            ->with(['vendor', 'items.menu'])
            ->latest()
            ->get();

        return view('livewire.customer.my-orders', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'pending_payment' => 'bg-yellow-100 text-yellow-800',
            'preparing' => 'bg-blue-100 text-blue-800',
            'done' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel($status)
    {
        return match ($status) {
            'pending_payment' => 'Payment Verification Pending',
            'preparing' => 'Order Confirmed - Preparing',
            'done' => 'Ready for Pickup',
            default => $status,
        };
    }
}
