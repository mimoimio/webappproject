<?php

namespace App\Livewire\Customer;

use App\Models\Vendor;
use Livewire\Component;

class VendorList extends Component
{
    public function render()
    {
        $vendors = Vendor::with('user')->get();

        return view('livewire.customer.vendor-list', [
            'vendors' => $vendors,
        ])->layout('layouts.app');
    }
}
