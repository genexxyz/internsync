<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <!-- Profile Information -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
                <livewire:components.profile-image-upload />
                <!-- Read-only Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructor ID</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $instructor->instructor_id }}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $instructor->user->email }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $instructor->full_name }}
                        </div>
                    </div>
                </div>

                <!-- Editable Information -->
                <form wire:submit="updateProfile" class="space-y-6">
                    <!-- Contact Number -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <div class="mt-1">
                            <input type="text" wire:model="contact" id="contact"
                                class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    

                    <div class="flex justify-end space-x-4">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary cursor-pointer">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
            <!-- E-Signature Upload -->
            <div class="mt-10 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900">E-Signature</h2>
                    <button
                        type="button"
                        wire:click="$set('showSignatureModal', true)"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                        @if($instructor->signature_path)
                            <i class="fas fa-pen mr-2"></i>
                            Update Signature
                        @else
                            <i class="fas fa-signature mr-2"></i>
                            Add Signature
                        @endif
                    </button>
                </div>
            
                <!-- Replace the existing signature display section -->
@if($instructor->signature_path)
<div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
    <div class="flex items-center justify-between mb-2">
        <div>
            <span class="text-sm text-gray-600">Current Signature</span>
            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-file-signature mr-1"></i>
                Used for official documents
            </span>
        </div>
        <span class="text-xs text-gray-500">Last updated: {{ Carbon\Carbon::parse($instructor->updated_at)->diffForHumans() }}</span>
    </div>
    
    <div 
        x-data="{ show: false }" 
        class="relative"
    >
        <div 
            class="relative flex justify-center rounded-lg border bg-white p-3"
            :class="{ 'blur-xl': !show }"
        >
            <img 
                src="{{ Storage::url($instructor->signature_path) }}" 
                alt="Current signature" 
                class="max-h-16"
            >
        </div>
        
        <div class="flex items-center justify-end space-x-2 mt-2">
            <button
                type="button"
                x-show="!show"
                @click="show = true"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
            >
                <i class="fas fa-eye mr-2"></i>
                Show Signature
            </button>
            <button
                type="button"
                x-show="show"
                @click="show = false"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
            >
                <i class="fas fa-eye-slash mr-2"></i>
                Hide Signature
            </button>
            <button
                type="button"
                wire:click="deleteSignature"
                wire:confirm="Are you sure you want to delete your signature?"
                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
                <i class="fas fa-trash-alt mr-2"></i>
                Delete
            </button>
        </div>

        <!-- Security Notice -->
        <div class="mt-3 flex items-start space-x-2 text-xs text-gray-500">
            <i class="fas fa-shield-alt mt-0.5"></i>
            <p>For security purposes, your signature is hidden by default. This signature will be used for signing official documents and acceptance letters.</p>
        </div>
    </div>
</div>
@endif
            </div>
            @if($showSignatureModal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Manage Signature
                                </h3>
                                <button 
                                    type="button" 
                                    wire:click="$set('showSignatureModal', false)"
                                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none"
                                >
                                    <span class="sr-only">Close</span>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-4">
                                <livewire:components.signature-pad />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


            <div class="mt-10 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>
                <form wire:submit="updatePassword" class="space-y-4 max-w-md">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model="current_password" id="current_password"
                                class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
            
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model="new_password" id="new_password"
                                class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
            
                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation"
                                class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('new_password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
            
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
            <!-- Danger Zone -->
            <div class="mt-10 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h2>
                <button type="button" wire:click="$set('showDeleteModal', true)"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    @if($showDeleteModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Account
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete your account? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="deleteAccount"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" wire:click="$set('showDeleteModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>