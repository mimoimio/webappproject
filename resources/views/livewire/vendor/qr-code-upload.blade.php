<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Vendor Profile & Payment QR Code
            </h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Business Name *
                    </label>
                    <input type="text" wire:model="businessName" class="w-full border rounded px-3 py-2"
                        placeholder="Enter your business name">
                    @error('businessName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea wire:model="description" rows="3" class="w-full border rounded px-3 py-2"
                        placeholder="Describe your business..."></textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Payment QR Code
                    </label>

                    @if ($vendor && $vendor->qr_code_path)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current QR Code:</p>
                            <img src="{{ Storage::url($vendor->qr_code_path) }}" alt="Current QR Code"
                                class="max-w-xs border rounded">
                        </div>
                    @endif

                    <input type="file" wire:model="qrCode" accept="image/*" class="w-full border rounded px-3 py-2">
                    @error('qrCode')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    @if ($qrCode)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Preview:</p>
                            <img src="{{ $qrCode->temporaryUrl() }}" class="max-w-xs">
                        </div>
                    @endif
                </div>

                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Save Profile</span>
                    <span wire:loading>Saving...</span>
                </button>
            </form>
        </div>
    </div>
</div>
