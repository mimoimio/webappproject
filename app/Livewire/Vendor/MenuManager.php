<?php

namespace App\Livewire\Vendor;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MenuManager extends Component
{
    public $menus;
    public $name;
    public $description;
    public $price;
    public $isAvailable = true;
    public $editingId = null;

    public function mount()
    {
        $this->loadMenus();
    }

    public function loadMenus()
    {
        $vendor = Auth::user()->vendor;
        if ($vendor) {
            $this->menus = Menu::where('vendor_id', $vendor->id)->get();
        } else {
            $this->menus = collect();
        }
    }

    public function save()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            session()->flash('error', 'Please complete your vendor profile first.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($this->editingId) {
            // Update existing menu
            $menu = Menu::findOrFail($this->editingId);
            $menu->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'is_available' => $this->isAvailable,
            ]);
            session()->flash('message', 'Menu updated successfully!');
        } else {
            // Create new menu
            Menu::create([
                'vendor_id' => $vendor->id,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'is_available' => $this->isAvailable,
            ]);
            session()->flash('message', 'Menu added successfully!');
        }

        $this->reset(['name', 'description', 'price', 'isAvailable', 'editingId']);
        $this->loadMenus();
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->editingId = $menu->id;
        $this->name = $menu->name;
        $this->description = $menu->description;
        $this->price = $menu->price;
        $this->isAvailable = $menu->is_available;
    }

    public function cancelEdit()
    {
        $this->reset(['name', 'description', 'price', 'isAvailable', 'editingId']);
    }

    public function delete($id)
    {
        Menu::findOrFail($id)->delete();
        session()->flash('message', 'Menu deleted successfully!');
        $this->loadMenus();
    }

    public function toggleAvailability($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_available' => !$menu->is_available]);
        $this->loadMenus();
    }

    public function render()
    {
        return view('livewire.vendor.menu-manager')->layout('layouts.app');
    }
}
