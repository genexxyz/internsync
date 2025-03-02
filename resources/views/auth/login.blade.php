<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <div class="sm:w-full container mx-auto lg:w-4/6 ">
        <!-- Flex Container -->
        <div class="flex flex-col sm:flex-row rounded-lg overflow-hidden shadow-lg bg-white">
            <!-- Left Section -->
            <div
                class="flex-1 bg-primary text-white p-6 flex flex-col justify-center lg:items-center lg:text-center sm:items-start sm:text-left">
                <div>
                    <h1 class="text-4xl font-bold leading-tight ">OJT</h1>
                    <h1 class="text-4xl font-bold leading-tight ">MONITORING</h1>
                    <h1 class="text-4xl font-bold leading-tight ">SYSTEM</h1>
                </div>
                <div class="mt-6">
                    <p class="italic">A PLACE TO STAY ON TRACK</p>
                </div>
            </div>
            <!-- Right Section -->
            <div class="flex-1 p-6">
                <div class="text-2xl text-primary mb-5 flex justify-center">
                    <p>Login</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-text-input icon="fa fa-user" id="email" class="block mt-1 w-full" type="email"
                            name="email" :value="old('email')" autofocus autocomplete="username"
                            placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    {{-- <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input icon="fa fa-lock" id="password" class="block mt-1 w-full" type="password" name="password" 
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div> --}}
                    <div>
                        <x-password-input icon="fa fa-lock" id="password" name="password" placeholder="Password"
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>
                    <div class="flex justify-center">
                        <x-primary-button class="px-40 py-2">

                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>


                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif


                    </div>
                </form>
                <hr class="mt-5">
                <div class="flex justify-center mt-5 text-sm">
                    <p>Don't have an account yet? <a href="{{ route('register') }}"
                            class="text-primary hover:text-accent font-bold">Register</a></p>

                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="flex flex-col items-center text-sm mt-5 text-accent">
            <p><i class="fa fa-location-dot"></i> {{ $settings->school_address ?? 'School Address' }}</p>
            <p><i class="fa fa-envelope"></i> {{ $settings->system_email ?? 'School Email' }} | <i
                    class="fa fa-phone"></i> {{ $settings->system_contact ?? 'School Contact' }}</p>
            <p></p>
        </div>
    </div>
</x-guest-layout>
