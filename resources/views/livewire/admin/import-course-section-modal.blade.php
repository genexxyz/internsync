<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">Import Courses & Sections</h2>
        <p class="mt-1 text-sm text-gray-600">
            Upload course and section data using CSV file format.
        </p>
    </div>

    <!-- Academic Year -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
        <select 
            wire:model="academic_id"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
        >
            <option value="">Select Academic Year</option>
            @foreach($academics as $academic)
                <option value="{{ $academic->id }}">
                    {{ $academic->academic_year }} - {{ $academic->semester }}
                </option>
            @endforeach
        </select>
        @error('academic_id') 
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- File Upload -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <label class="text-sm font-medium text-gray-700">CSV File</label>
            <button
                wire:click="downloadTemplate"
                type="button"
                class="text-sm text-primary hover:text-accent"
            >
                <i class="fas fa-download mr-1"></i>
                Download Template
            </button>
        </div>
        
        <input 
            wire:model="file" 
            type="file" 
            accept=".csv"
            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-accent"
        />
        @error('file') 
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Error Messages -->
    @error('import')
        <div class="mb-6 p-3 rounded bg-red-50 text-red-600 text-sm">
            {{ $message }}
        </div>
    @enderror

    @if(count($importErrors) > 0)
        <div class="mb-6 p-3 rounded bg-red-50 text-red-600 text-sm">
            <p class="font-medium mb-2">Import Errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($importErrors as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end gap-3">
        <button
            type="button"
            wire:click="$dispatch('closeModal')"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50"
        >
            Cancel
        </button>
        <button
            type="button"
            wire:click="import"
            wire:loading.attr="disabled"
            class="px-4 py-2 bg-primary text-white rounded-md text-sm hover:bg-accent disabled:opacity-50"
        >
            <span wire:loading.remove>Import Courses</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Processing...
            </span>
        </button>
    </div>
</div>