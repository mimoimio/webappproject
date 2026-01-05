<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Checkout</h2>

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Order Summary -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Order Summary</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Vendor: <strong>{{ $vendor->business_name }}</strong>
                </p>

                <div class="space-y-2">
                    @foreach ($cart as $item)
                        <div class="flex justify-between items-center border-b dark:border-gray-700 pb-2">
                            <div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $item['name'] }}</span>
                                <span class="text-gray-600 dark:text-gray-400"> x{{ $item['quantity'] }}</span>
                            </div>
                            <span class="text-gray-800 dark:text-gray-200">
                                RM{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div
                    class="flex justify-between items-center text-xl font-bold mt-4 pt-4 border-t dark:border-gray-700">
                    <span class="text-gray-800 dark:text-gray-200">Total:</span>
                    <span class="text-indigo-600">RM{{ number_format($totalPrice, 2) }}</span>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Payment</h3>

                @if ($vendor->qr_code_path)
                    <div class="mb-4 text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-2">Scan QR Code to Pay:</p>
                        <img src="{{ Storage::url($vendor->qr_code_path) }}" alt="Payment QR Code"
                            class="mx-auto max-w-xs border rounded">
                    </div>
                @else
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Please contact the vendor for payment instructions.
                    </p>
                @endif

                <form wire:submit.prevent="placeOrder">
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Upload Payment Proof *
                        </label>
                        <input type="file" wire:model="paymentProof" accept="image/*"
                            class="w-full border rounded px-3 py-2">
                        @error('paymentProof')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                        @if ($paymentProof)
                            <div class="mt-2">
                                <img src="{{ $paymentProof->temporaryUrl() }}" class="max-w-xs">
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                            Notes (Optional)
                        </label>
                        <textarea wire:model="customerNotes" rows="3" class="w-full border rounded px-3 py-2"
                            placeholder="Add any special instructions..."></textarea>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('customer.vendor.menu', $vendorId) }}"
                            class="text-gray-600 hover:text-gray-800">
                            ← Back to Menu
                        </a>
                        <button type="submit"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Place Order</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
