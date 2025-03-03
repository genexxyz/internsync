<div class="relative">
    <!-- Close button -->
    <button wire:click="$dispatch('closeModal')" class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow-lg z-10">
        <svg class="w-6 h-6 text-gray-600 hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <!-- PDF Viewer -->
    <div class="w-full h-[90vh]">
        <iframe
            src="{{ $pdfUrl }}#toolbar=0"
            class="w-full h-full"
            frameborder="0"
        ></iframe>
    </div>
</div>