<div class="min-h-screen flex justify-center items-center sm:pt-0">
    

    <div class="w-3/4 px-4 sm:px-6 py-8">
        <div class="bg-white shadow-xl overflow-hidden sm:rounded-xl">
            <!-- Header -->
            <div class="px-6 py-4 bg-primary/5 border-b border-primary/10">
                <h2 class="text-2xl font-bold text-gray-900">Supervisor Registration</h2>
                <p class="mt-1 text-sm text-gray-600">Complete your account setup to get started</p>
            </div>

            <div class="p-6">
                
                <form wire:submit.prevent="register" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-1 gap-x-8 gap-y-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            
<!-- Company Info Display -->

    <div class="items-center p-4 bg-primary/5 rounded-xl border border-primary/10">
        <h3 class="font-medium text-gray-900 flex items-center gap-2">
            <i class="fas fa-building text-primary"></i>
            Company Information
        </h3>
        <div class="mt-3 text-sm text-gray-600 space-y-2">
            <p class="flex items-center justify-between">
                <span class="font-medium">Company:</span> 
                <span class="text-gray-800">{{ $acceptanceLetter->company_name }}</span>
            </p>
            <p class="flex items-center justify-between">
                <span class="font-medium">Department:</span>
                <span class="text-gray-800">{{ $acceptanceLetter->department_name }}</span>
            </p>
        </div>
    </div>
    
                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- First Name -->
            <div class="mt-4">

                <x-text-input wire:model="first_name" icon="fa fa-font" id="first_name" class="block mt-1 w-full"
                    type="text" name="first_name" :value="old('first_name')" required autofocus
                    autocomplete="given-name" placeholder="First Name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Middle Name (Optional) -->
            <div class="mt-4">
                <x-text-input wire:model="middle_name" icon="fa fa-font" id="middle_name" class="block mt-1 w-full"
                    type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name"
                    placeholder="Middle Name (If Applicable)" />
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="mt-4">
                <x-text-input wire:model="last_name" icon="fa fa-font" id="last_name" class="block mt-1 w-full"
                    type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name"
                    placeholder="Last Name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Suffix (Optional) -->
            <div class="mt-4">
                <x-text-input wire:model="suffix" icon="fa fa-font" id="suffix" class="block mt-1 w-full" type="text"
                    name="suffix" :value="old('suffix')" autocomplete="honorific-suffix"
                    placeholder="Suffix (If Applicable)" />
                <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
            </div>
                            </div>

                            <!-- Contact & Position -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="mt-4">
                                    <x-text-input wire:model.lazy="contact" icon="fa fa-phone" id="contact" class="block mt-1 w-full"
                                        type="text" name="contact" :value="old('contact')" required placeholder="Contact No." maxlength="11" />
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-text-input wire:model="position" icon="fa fa-font" id="position" class="block mt-1 w-full"
                                        type="text" name="position" :value="old('position')" required
                                        placeholder="Position" />
                                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Email -->
                            <div>
                                
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <x-text-input 
                                        wire:model="email" 
                                        id="email" 
                                        type="email" 
                                        class="block w-full" 
                                        required 
                                        placeholder="Email"
                                    />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                @if($email !== $acceptanceLetter->email)
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Email must match the one provided in the acceptance letter.
                                    </p>
                                @endif
                            </div>
                            <!-- Password Section -->
                            <div class="space-y-4">
                                <div class="mt-4">
                                    <x-password-input wire:model="password" icon="fa fa-lock" id="password" name="password"
                                        placeholder="Password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                        
                        
                                <div class="mt-4">
                                    <x-password-input wire:model="password_confirmation" icon="fa fa-check" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm Password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <!-- ID Upload -->
                            <div>
                                <x-input-label for="document" value="Employee ID" />
                                <div class="mt-2">
                                    <input type="file" wire:model="document" id="document" x-ref="photo" class="hidden"
                                        @change="handleFileUpload($event)" accept=".jpg,.jpeg,.png">
                    
                                    <div class="flex items-center gap-4">
                                        <button type="button" @click="$refs.photo.click()"
                                            class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-400 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                            <i class="fa fa-upload mr-2"></i>
                                            Upload File
                                        </button>
                                        @if($document)
                                            <span class="text-sm text-gray-600">
                                                File selected: {{ $document->getClientOriginalName() }}
                                            </span>
                                        @endif
                                    </div>
                    
                                    
                    
                                    <p class="mt-2 text-sm text-gray-500">
                                        Accepted file types: PNG, JPEG, JPG. Maximum file size: 2MB.
                                    </p>
                                    <x-input-error :messages="$errors->get('document')" class="mt-2" />
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="w-full flex justify-end mt-4" x-data="{ showTerms: false }">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" type="checkbox" wire:model="acceptTerms"
                                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                            </div>
                            <div class="ml-3">
                                <label for="terms" class="text-sm text-gray-600">
                                    I accept the
                                    <button type="button" @click="showTerms = true"
                                        class="text-primary hover:underline focus:outline-none">
                                        Terms and Conditions
                                    </button>
                                    .
                                </label>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('acceptTerms')" class="mt-2" />
            
                        <!-- Terms Modal -->
                        <div x-show="showTerms" class="fixed inset-0 z-50 overflow-y-auto"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
            
                            <!-- Background overlay -->
                            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            
                            <!-- Modal content -->
                            <div class="relative min-h-screen flex items-center justify-center p-4">
                                <div class="relative bg-white rounded-xl max-w-3xl w-full shadow-2xl p-6 overflow-y-auto max-h-[85vh]"
                                    @click.away="showTerms = false">
            
                                    <!-- Close button -->
                                    <button @click="showTerms = false"
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
            
                                    <!-- Modal header -->
                                    <div class="text-center mb-8">
                                        <h2 class="text-2xl font-bold text-gray-800">Terms & Conditions</h2>
                                        {{-- <p class="text-sm text-gray-500 mt-1">Last Updated: Feb 1, 2025</p> --}}
                                    </div>
            
                                    <div class="space-y-6 px-2">
                                        <!-- Account Section -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                                <i class="fas fa-user-circle text-primary"></i>
                                                Account Usage
                                            </h3>
                                            <ul class="list-disc pl-5 text-sm space-y-2 text-gray-600">
                                                <li>Provide accurate information and keep it updated</li>
                                                <li>Keep your login details private and secure</li>
                                                <li>Don't share accounts or submit false information</li>
                                            </ul>
                                        </div>
            
                                        <!-- Privacy Section -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                                <i class="fas fa-shield-alt text-primary"></i>
                                                Data Privacy (RA 10173)
                                            </h3>
                                            <ul class="list-disc pl-5 text-sm space-y-2 text-gray-600">
                                                <li>We collect: name, contact, education details, training records</li>
                                                <li>Your rights: access, correct, delete your information</li>
                                                <li>We protect your data according to Philippine law</li>
                                            </ul>
                                        </div>
            
                                        <!-- Platform Usage -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                                <i class="fas fa-desktop text-primary"></i>
                                                Platform Usage
                                            </h3>
                                            <div class="grid md:grid-cols-2 gap-6 text-sm">
                                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                                    <p class="font-medium text-primary mb-2 flex items-center gap-2">
                                                        <i class="fas fa-check-circle"></i>
                                                        Allowed:
                                                    </p>
                                                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                                                        <li>Track OJT progress</li>
                                                        <li>Submit reports</li>
                                                        <li>Contact supervisors</li>
                                                    </ul>
                                                </div>
                                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                                    <p class="font-medium text-red-500 mb-2 flex items-center gap-2">
                                                        <i class="fas fa-times-circle"></i>
                                                        Prohibited:
                                                    </p>
                                                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                                                        <li>Share login details</li>
                                                        <li>Submit false data</li>
                                                        <li>Harass others</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
            
                                        <!-- Contact -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h3 class="font-semibold text-lg text-gray-800 flex items-center gap-2 mb-3">
                                                <i class="fas fa-envelope text-primary"></i>
                                                Contact Us
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                For questions or concerns, reach us at:
                                                <a href="mailto:internsync01@gmail.com"
                                                    class="text-primary hover:underline">internsync01@gmail.com</a>
                                            </p>
                                        </div>
                                    </div>
            
                                    <!-- Modal footer -->
                                    <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                                        <button @click="showTerms = false"
                                            class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            I Understand
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="border-t pt-6 flex justify-end">
                        <x-primary-button class="w-auto justify-center py-3 text-base">
                            <span wire:loading.remove>Complete Registration</span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Processing...
                            </span>
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>