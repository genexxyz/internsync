<x-guest-layout>
    <div class="sm:w-full container mx-auto lg:w-4/6 flex justify-center">


        <div class="bg-white rounded px-10 py-5">
            <div class="mb-5 flex flex-col justify-center">
                <p class="text-2xl">Register</p>
                <p class="text-sm">Enter your account details below:</p>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- User Type -->
                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <x-select-input name="role" :options="[
                        'admin' => 'Admin',
                        'student' => 'Student',
                        'instructor' => 'Instructor',
                        'supervisor' => 'Supervisor/Client',
                    ]" :selected="old('role')" />
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />

                </div>

                <div class="mt-7 grid lg:grid-cols-2 gap-2 xs:grid-cols-1">
                    <!-- First Name -->
                    <div class="mt-4">
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input icon="fa fa-font" id="first_name" class="block mt-1 w-full" type="text"
                            name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" placeholder="Juan" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <!-- Middle Name (Optional) -->
                    <div class="mt-4">
                        <x-input-label for="middle_name" :value="__('Middle Name (Optional)')" />
                        <x-text-input icon="fa fa-font" id="middle_name" class="block mt-1 w-full" type="text"
                            name="middle_name" :value="old('middle_name')" autocomplete="additional-name" placeholder="Santos" />
                        <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                    </div>

                    <!-- Last Name -->
                    <div class="mt-4">
                        <x-input-label for="last_name" :value="__('Last Name')" />
                        <x-text-input icon="fa fa-font" id="last_name" class="block mt-1 w-full" type="text"
                            name="last_name" :value="old('last_name')" required autocomplete="family-name" placeholder="Dela Cruz" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <!-- Suffix (Optional) -->
                    <div class="mt-4">
                        <x-input-label for="suffix" :value="__('Suffix (Optional)')" />
                        <x-text-input icon="fa fa-font" id="suffix" class="block mt-1 w-full" type="text"
                            name="suffix" :value="old('suffix')" autocomplete="honorific-suffix" placeholder="Jr" />
                        <x-input-error :messages="$errors->get('suffix')" class="mt-2" />
                    </div>
                </div>
                <!-- Email Address -->
                <div class="mt-7">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input icon="fa fa-envelope" id="email" class="block mt-1 w-full" type="email"
                        name="email" :value="old('email')" required autocomplete="username" placeholder="example@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input icon="fa fa-lock" id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input icon="fa fa-check" id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Register Button -->
                <div class="flex items-center justify-end mt-7">
                    <a wire:navigate class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
