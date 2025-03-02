<div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-gray-600 text-center mb-4">
        Email Verification
    </h1>

    <p class="text-gray-600 text-center mb-6">
        A verification code has been sent to <strong>{{ $email }}</strong>.
        Please enter the code below to verify your email.
    </p>

    <!-- OTP Input Form -->
    <form wire:submit.prevent="verifyOtp">
        <div class="mb-4">
            <x-input-label for="otp" :value="__('Verification Code')" />
            <x-text-input 
                id="otp"
                wire:model.lazy="otp"
                type="text"
                class="block mt-1 w-full"
                placeholder="Enter the 6-digit code"
                maxlength="6"
                icon="fa fa-lock"
            />
            @error('otp')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full text-center">
                Verify Email
            </x-primary-button>
        </div>
    </form>

    <!-- Resend OTP -->
    <div class="mt-4 text-center">
        <button 
            wire:click="resendOtp" 
            class="text-primary hover:underline focus:outline-none"
        >
            Resend OTP
        </button>
        <p class="text-gray-500 text-sm mt-2">
            Didn't receive the email? Click above to resend.
        </p>
    </div>

    <div class="mt-4">
        <a wire:navigate class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}"><i class="fa fa-arrow-left"></i>
                {{ __('Return to login') }}
            </a>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('status'))
        <div class="mt-6 p-4 bg-green-100 text-green-700 rounded">
            {{ session('status') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="mt-6 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>
