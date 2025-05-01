<div class="mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg">
        <!-- Profile Header -->
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Settings</h3>
            <p class="mt-1 text-sm text-gray-500">Update your profile information and email address.</p>
        </div>
        <div class="px-6">
            <livewire:components.profile-image-upload />
        </div>
        
        <!-- Profile Form -->
        <form wire:submit="updateProfile" class="px-4 py-5 sm:p-6 space-y-6">
            

            <!-- Name Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" wire:model="firstName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    @error('firstName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                    <input type="text" wire:model="middleName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    @error('middleName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" wire:model="lastName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    @error('lastName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Suffix</label>
                    <input type="text" wire:model="suffix" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    @error('suffix') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Add after the main profile form -->
<div class="mt-6 space-y-6">
    <!-- Email Change Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Email Address</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your email address</p>
                </div>
                <button 
                    type="button"
                    wire:click="toggleEmailChange"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    {{ $showEmailChange ? 'Cancel' : 'Change Email' }}
                </button>
            </div>

            @if($showEmailChange)
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Email Address</label>
                    <div class="mt-1 flex space-x-4">
                        <input 
                            type="email" 
                            wire:model="newEmail"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                            placeholder="Enter new email"
                        >
                        <button 
                            type="button"
                            wire:click="initiateEmailChange"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark"
                            @if($showOtpInput) disabled @endif
                        >
                            Send OTP
                        </button>
                    </div>
                    @error('newEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                @if($showOtpInput)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Enter Verification Code</label>
                    <div class="mt-1 flex space-x-4">
                        <input 
                            type="text" 
                            wire:model="otp"
                            maxlength="6"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-center text-2xl tracking-widest"
                            placeholder="000000"
                        >
                        <button 
                            type="button"
                            wire:click="verifyOTP"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark"
                        >
                            Verify
                        </button>
                    </div>
                    @error('otp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Password Change Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Password</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your password</p>
                </div>
                <button 
                    type="button"
                    wire:click="togglePasswordChange"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                >
                    {{ $showPasswordChange ? 'Cancel' : 'Change Password' }}
                </button>
            </div>

            @if($showPasswordChange)
            <form wire:submit="updatePassword" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input 
                        type="password" 
                        wire:model="currentPassword"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                    >
                    @error('currentPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input 
                        type="password" 
                        wire:model="newPassword"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                    >
                    @error('newPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input 
                        type="password" 
                        wire:model="confirmPassword"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"
                    >
                    @error('confirmPassword') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark"
                    >
                        Update Password
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
</div>