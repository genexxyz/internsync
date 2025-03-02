<div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Send Test Email</h2>
    
    <form wire:submit.prevent="sendTestEmail" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Recipient Email
            </label>
            <input 
                type="email" 
                id="email"
                wire:model="email" 
                placeholder="Enter email address"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                required
            >
            @error('email')
                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
            @enderror
        </div>

        <button 
            type="submit" 
            wire:loading.attr="disabled"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
            {{ $sending ? 'opacity-50 cursor-not-allowed' : '' }}"
        >
            <span wire:loading.remove>Send Test Email</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>

    @if($status)
        <div class="mt-4 p-3 rounded-md 
            {{ str_contains($status, 'successfully') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $status }}
        </div>
    @endif
</div>