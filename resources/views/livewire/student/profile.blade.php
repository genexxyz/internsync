<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <!-- Profile Information -->
            <div class="mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Profile Information</h2>
                
                <!-- Read-only Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $name }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $student->student_id }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $student->section->course->course_name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Section</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $student->section->year_level }}{{ $student->section->class_section }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $student->user->email }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <div class="mt-1 p-2 bg-gray-50 rounded-md">
                            {{ $student->address }}
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
                                class="shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Enter your contact number">
                        </div>
                        @error('contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Update Profile Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- E-Signature Section -->
            {{-- <div class="mt-10 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-900">E-Signature</h2>
                    <button
                        type="button"
                        wire:click="$set('showSignatureModal', true)"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                        @if($student->signature_path)
                            <i class="fas fa-pen mr-2"></i>
                            Update Signature
                        @else
                            <i class="fas fa-signature mr-2"></i>
                            Add Signature
                        @endif
                    </button>
                </div>

                @if($student->signature_path)
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="text-sm text-gray-600">Current Signature</span>
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-file-signature mr-1"></i>
                                Used for official documents
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">Last updated: {{ Carbon\Carbon::parse($student->updated_at)->diffForHumans() }}</span>
                    </div>
                    
                    <div x-data="{ show: false }" class="relative">
                        <div 
                            class="relative flex justify-center rounded-lg border bg-white p-3"
                            :class="{ 'blur-xl': !show }"
                        >
                            <img 
                                src="{{ Storage::url($student->signature_path) }}" 
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
                            <p>For security purposes, your signature is hidden by default. This signature will be used for signing official documents.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div> --}}

            <!-- Password Change Section -->
            <div class="mt-10 pt-6 border-t border-gray-200">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Change Password</h2>
                <form wire:submit="updatePassword" class="space-y-4 max-w-md">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" wire:model="current_password" id="current_password"
                            class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" wire:model="new_password" id="new_password"
                            class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" wire:model="new_password_confirmation" id="new_password_confirmation"
                            class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 rounded-md">
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
        </div>
    </div>

    <!-- Signature Modal -->
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
</div>