<?php

namespace App\Livewire\Vendor;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewOrders extends Component
{
    public $selectedOrder = null;

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['customer', 'items.menu'])
            ->findOrFail($orderId);
    }

    public function closeModal()
    {
        $this->selectedOrder = null;
    }

    public function approveOrder($orderId, $vendorNotes = null)
    {
        $order = Order::findOrFail($orderId);

        $order->update([
            'status' => 'preparing',
            'vendor_notes' => $vendorNotes,
        ]);

        session()->flash('message', 'Order approved and moved to preparing!');
        $this->selectedOrder = null;
    }

    public function rejectOrder($orderId, $vendorNotes = null)
    {
        $order = Order::findOrFail($orderId);

        // For prototype, we'll just add a note. In production, you might want a separate status
        $order->update([
            'vendor_notes' => $vendorNotes ?: 'Order rejected by vendor',
        ]);

        session()->flash('message', 'Order rejected.');
        $this->selectedOrder = null;
    }

    public function render()
    {
        $vendor = Auth::user()->vendor;
        $orders = collect();

        if ($vendor) {
            $orders = Order::where('vendor_id', $vendor->id)
                ->where('status', 'pending_payment')
                ->with(['customer', 'items.menu'])
                ->latest()
                ->get();
        }

        return view('livewire.vendor.new-orders', [
            'orders' => $orders,
        ])->layout('layouts.app');
    }
}
