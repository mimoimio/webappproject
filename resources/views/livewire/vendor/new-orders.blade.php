<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">New Orders - Payment Verification
                </h2>
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

            @if ($orders->count() > 0)
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="border dark:border-gray-700 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Customer: {{ $order->customer->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-indigo-600">
                                        RM{{ number_format($order->total_price, 2) }}</p>
                                    <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                        Pending Verification
                                    </span>
                                </div>
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
                                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-1">Customer Notes:</h4>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $order->customer_notes }}</p>
                                </div>
                            @endif

                            <button wire:click="viewOrder({{ $order->id }})"
                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                View Payment Proof & Verify
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No new orders awaiting verification.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Proof Modal -->
    @if ($selectedOrder)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        Order #{{ $selectedOrder->id }} - Payment Verification
                    </h3>
                    <button wire:click="closeModal" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Order Details:</h4>
                    <p class="text-gray-600 dark:text-gray-400">Customer: {{ $selectedOrder->customer->name }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Email: {{ $selectedOrder->customer->email }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Total:
                        RM{{ number_format($selectedOrder->total_price, 2) }}</p>
                </div>

                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Items:</h4>
                    <ul class="space-y-1">
                        @foreach ($selectedOrder->items as $item)
                            <li class="text-gray-600 dark:text-gray-400">
                                {{ $item->menu->name }} x{{ $item->quantity }} -
                                RM{{ number_format($item->price * $item->quantity, 2) }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if ($selectedOrder->payment_proof_path)
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Payment Proof:</h4>
                        <img src="{{ Storage::url($selectedOrder->payment_proof_path) }}" alt="Payment Proof"
                            class="max-w-full border rounded">
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Vendor Notes (Optional)
                    </label>
                    <textarea wire:model="vendorNotes" rows="3" class="w-full border rounded px-3 py-2"
                        placeholder="Add notes for this order..."></textarea>
                </div>

                <div class="flex space-x-2">
                    <button wire:click="approveOrder({{ $selectedOrder->id }})"
                        class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Approve & Start Preparing
                    </button>
                    <button wire:click="rejectOrder({{ $selectedOrder->id }})"
                        onclick="return confirm('Are you sure you want to reject this order?')"
                        class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Reject Order
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
