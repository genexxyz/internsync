<x-guest-layout>
    <div class="sm:w-full container mx-auto lg:w-4/6 flex justify-center">


        <div class="bg-white rounded px-10 py-5">
            <div class="mb-5 flex flex-col justify-center">
                <p class="text-2xl">Register</p>
                <p class="text-sm">Enter your account details below:</p>
            </div>
            @livewire('auth.register')
        </div>
    </div>
</x-guest-layout>
