<div class="w-full max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 p-8">
    

    <!-- Draw Section -->
    <div class="mb-8" wire:ignore>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Draw Signature</h3>
            <span class="text-xs text-gray-500">Use mouse or touch to sign</span>
        </div>  
                <div 
        x-data="{
            signaturePadInstance: null,
            signatureData: '',
            isEmpty: true,
            
            init() {
            this.$nextTick(() => {
                const canvas = this.$refs.signatureCanvas;
                if (canvas) {
                    this.signaturePadInstance = new SignaturePad(canvas, {
    backgroundColor: 'rgba(255, 255, 255, 0)', // Make background transparent
    penColor: 'rgb(0, 0, 0)',
    minWidth: 0.5,
    maxWidth: 2.5,
    onBegin: () => {
        this.isEmpty = false;
    },
    onEnd: () => {
        this.isEmpty = this.signaturePadInstance.isEmpty();
    }
});
                    
                    // Handle window resize
                    window.addEventListener('resize', () => this.resizeCanvas());
                    this.resizeCanvas();
                }
            });
        },
            
            resizeCanvas() {
                const canvas = this.$refs.signatureCanvas;
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                this.signaturePadInstance?.clear();
            },
            
            clear() {
                if (this.signaturePadInstance) {
                    this.signaturePadInstance.clear();
                    this.signatureData = '';
                    this.isEmpty = true;
                }
            },
            
            // Replace the existing save() function with this updated version:
save() {
    if (!this.signaturePadInstance) return;
    
    // Check if signature pad is empty
    const isEmpty = this.signaturePadInstance.isEmpty();
    if (isEmpty) {
        $wire.dispatch('alert', {
            type: 'error',
            text: 'Please provide your signature before saving.'
        });
        return;
    }
    
    // Get the canvas and create a temporary canvas for cropping
    const canvas = this.$refs.signatureCanvas;
    const context = canvas.getContext('2d');
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const data = imageData.data;
    
    // Find the bounds of the signature
    let bounds = {
        top: null,
        left: null,
        right: null,
        bottom: null
    };
    
    // Scan for signature bounds
    for (let i = 0; i < data.length; i += 4) {
        if (data[i + 3] !== 0) { // If pixel is not transparent
            const x = (i / 4) % canvas.width;
            const y = Math.floor((i / 4) / canvas.width);
            
            if (bounds.top === null) bounds.top = y;
            if (bounds.left === null || x < bounds.left) bounds.left = x;
            if (bounds.right === null || x > bounds.right) bounds.right = x;
            bounds.bottom = y;
        }
    }
    
    // Add padding around signature
    const padding = 20;
    bounds.top = Math.max(0, bounds.top - padding);
    bounds.left = Math.max(0, bounds.left - padding);
    bounds.right = Math.min(canvas.width, bounds.right + padding);
    bounds.bottom = Math.min(canvas.height, bounds.bottom + padding);
    
    // Create temporary canvas for cropped image
    const tempCanvas = document.createElement('canvas');
    const tempContext = tempCanvas.getContext('2d');
    
    // Set dimensions of cropped image
    const width = bounds.right - bounds.left;
    const height = bounds.bottom - bounds.top;
    tempCanvas.width = width;
    tempCanvas.height = height;
    
    // Copy the signature to the temporary canvas
    tempContext.drawImage(
        canvas,
        bounds.left, bounds.top, width, height,
        0, 0, width, height
    );
    
    // Convert to data URL with transparent background
    this.signatureData = tempCanvas.toDataURL('image/png');
    $wire.saveSignature(this.signatureData);
}
        }"
    >
    <div class="relative">
        <canvas 
            x-ref="signatureCanvas"
            class="rounded-lg w-full cursor-crosshair"
            style="height: 200px; touch-action: none; background: repeating-linear-gradient(45deg, rgba(0, 0, 0, 0.02) 0px, rgba(0, 0, 0, 0.02) 2px, transparent 2px, transparent 4px);"
        ></canvas>
        
        <div 
            x-show="isEmpty"
            class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none"
        >
            <i class="fas fa-signature text-2xl text-gray-300 mb-2"></i>
            <span class="text-sm text-gray-400">Sign here</span>
        </div>
    </div>

    <div class="flex space-x-3 mt-4">
        <button 
            type="button"
            x-on:click="save()"
            class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200"
        >
            <i class="fas fa-save mr-2"></i>
            Save Signature
        </button>
        
        <button 
            type="button"
            x-on:click="clear()"
            class="inline-flex justify-center items-center px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200"
        >
            <i class="fas fa-eraser mr-2"></i>
            Clear
        </button>
    </div>
</div>
</div>

<!-- Divider -->
<div class="relative my-8">
<div class="absolute inset-0 flex items-center">
    <div class="w-full border-t border-gray-200"></div>
</div>
<div class="relative flex justify-center text-sm">
    <span class="px-4 bg-white text-gray-500 font-medium">OR</span>
</div>
</div>

<!-- Upload Section -->
<div>
<div class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-semibold text-gray-900">Upload Signature</h3>
    <span class="text-xs text-gray-500">PNG format only</span>
</div>

<div class="flex items-center justify-center w-full">
    <label class="flex flex-col w-full h-40 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer hover:border-primary transition-colors duration-200">
        <div class="flex flex-col items-center justify-center pt-6 pb-4">
            <div class="p-3 mb-2 rounded-full bg-gray-50">
                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
            </div>
            <p class="mb-2 text-sm text-gray-700">
                <span class="font-semibold">Click to upload</span>
            </p>
            <p class="text-xs text-gray-500">PNG files only (MAX. 1MB)</p>
        </div>
        <input 
            type="file" 
            wire:model.live="uploadedSignature" 
            class="hidden" 
            accept="image/png"
        />
    </label>
</div>

@error('uploadedSignature') 
    <p class="mt-2 text-sm text-red-600 flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ $message }}
    </p>
@enderror

@if($uploadedSignature)
    <button 
        type="button"
        wire:click="saveUploadedSignature"
        class="mt-4 w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200"
    >
        <i class="fas fa-save mr-2"></i>
        Save Uploaded Signature
    </button>
@endif
</div>
</div>