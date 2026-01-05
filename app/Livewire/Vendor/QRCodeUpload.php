<?php

namespace App\Livewire\Vendor;

use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class QRCodeUpload extends Component
{
    use WithFileUploads;

    public $vendor;
    public $qrCode;
    public $businessName;
    public $description;

    public function mount()
    {
        $this->vendor = Auth::user()->vendor;

        if ($this->vendor) {
            $this->businessName = $this->vendor->business_name;
            $this->description = $this->vendor->description;
        }
    }

    public function save()
    {
        $this->validate([
            'businessName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'qrCode' => 'nullable|image|max:2048',
        ]);

        if (!$this->vendor) {
            // Create vendor profile
            $this->vendor = Vendor::create([
                'user_id' => Auth::id(),
                'business_name' => $this->businessName,
                'description' => $this->description,
            ]);
        } else {
            // Update vendor profile
            $this->vendor->update([
                'business_name' => $this->businessName,
                'description' => $this->description,
            ]);
        }

        if ($this->qrCode) {
            // Delete old QR code if exists
            if ($this->vendor->qr_code_path) {
                \Storage::disk('public')->delete($this->vendor->qr_code_path);
            }

            // Save new QR code
            $path = $this->qrCode->store("vendor_qr_codes/{$this->vendor->id}", 'public');
            $this->vendor->update(['qr_code_path' => $path]);
        }

        session()->flash('message', 'Profile updated successfully!');
        $this->qrCode = null;
    }

    public function render()
    {
        return view('livewire.vendor.qr-code-upload')->layout('layouts.app');
    }
}
