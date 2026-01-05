<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">My Orders</h2>
                <button wire:click="$refresh"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </button>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($orders->count() > 0)
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="border dark:border-gray-700 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        Order #{{ $order->id }} - {{ $order->vendor->business_name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold {{ $this->getStatusBadgeClass($order->status) }}">
                                    {{ $this->getStatusLabel($order->status) }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Items:</h4>
                                <ul class="space-y-1">
                                    @foreach ($order->items as $item)
                                        <li class="text-gray-600 dark:text-gray-400">
                                            {{ $item->menu->name }} x{{ $item->quantity }} -
                                            RM{{ number_format($item->price * $item->quantity, 2) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if ($order->customer_notes)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Your Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->customer_notes }}</p>
                                </div>
                            @endif

                            @if ($order->vendor_notes)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Vendor Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->vendor_notes }}</p>
                                </div>
                            @endif

                            <div class="flex justify-between items-center border-t dark:border-gray-700 pt-4">
                                <span class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                    Total: RM{{ number_format($order->total_price, 2) }}
                                </span>

                                @if ($order->payment_proof_path)
                                    <a href="{{ Storage::url($order->payment_proof_path) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-800 text-sm">
                                        View Payment Proof
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('customer.vendors') }}"
                        class="inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Browse Vendors
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
