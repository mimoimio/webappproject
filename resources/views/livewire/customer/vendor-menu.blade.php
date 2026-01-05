<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Vendor Info -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $vendor->business_name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $vendor->description }}</p>
                </div>
                <a href="{{ route('customer.vendors') }}" class="text-indigo-600 hover:text-indigo-800">
                    ← Back to Vendors
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Menu Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Menu</h3>
                        <button wire:click="$refresh"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Refresh
                        </button>
                    </div>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($menus->count() > 0)
                        <div class="space-y-4">
                            @foreach ($menus as $menu)
                                <div
                                    class="border dark:border-gray-700 rounded-lg p-4 flex justify-between items-center">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                            {{ $menu->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $menu->description }}</p>
                                        <p class="text-indigo-600 font-semibold mt-2">
                                            RM{{ number_format($menu->price, 2) }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <input type="number" wire:model="quantity.{{ $menu->id }}" min="1"
                                            value="1" class="w-16 border rounded px-2 py-1">
                                        <button wire:click="addToCart({{ $menu->id }})"
                                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No menu items available.</p>
                    @endif
                </div>
            </div>

            <!-- Cart -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 sticky top-6">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Cart</h3>

                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (count($cart) > 0)
                        <div class="space-y-3 mb-4">
                            @foreach ($cart as $menuId => $item)
                                <div class="border dark:border-gray-700 rounded p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $item['name'] }}</span>
                                        <button wire:click="removeFromCart({{ $menuId }})"
                                            class="text-red-500 hover:text-red-700 text-sm">
                                            Remove
                                        </button>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <input type="number"
                                            wire:change="updateQuantity({{ $menuId }}, $event.target.value)"
                                            value="{{ $item['quantity'] }}" min="1"
                                            class="w-16 border rounded px-2 py-1 text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            RM{{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t dark:border-gray-700 pt-4 mb-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-gray-800 dark:text-gray-200">Total:</span>
                                <span class="text-indigo-600">RM{{ number_format($this->getCartTotal(), 2) }}</span>
                            </div>
                        </div>

                        <button wire:click="proceedToCheckout"
                            class="w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded">
                            Proceed to Checkout
                        </button>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Your cart is empty</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
