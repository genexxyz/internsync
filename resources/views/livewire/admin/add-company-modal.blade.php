<!-- Modal -->
<div class="px-6 py-3 w-full text-gray-600">

    <div class="flex items-center justify-between mb-4">
        <!-- Profile Heading -->
        <h2 class="text-xl font-bold flex-1 text-center">Add New Company</h2>

        <!-- Close Button -->
        <button wire:click="$dispatch('closeModal')"
            class="bg-primary-600 rounded-md hover:bg-primary-700 p-2 focus:outline-none">
            <i class="fa fa-xmark font-bold text-xl text-gray-500"></i>
        </button>
    </div>

    <hr class="mb-4">

    <!-- Form Start -->
    <form wire:submit.prevent="saveCompany" class="space-y-4">

        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input icon="fa fa-building" id="company_name" class="block mt-1 w-full" type="text"
                wire:model="company_name" name="company_name" placeholder="Business Inc." />
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>
        <!-- Course Name -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input icon="fa fa-location-dot" id="address" class="block mt-1 w-full" type="text"
                wire:model="address" name="address" placeholder="" />
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="contact_person" :value="__('Contact Person')" />
            <x-text-input icon="fa fa-user" id="contact_person" class="block mt-1 w-full" type="text"
                wire:model="contact_person" name="contact_person" placeholder="" />
            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="contact_email" :value="__('Email')" />
            <x-text-input icon="fa fa-envelope" id="contact_email" class="block mt-1 w-full" type="email"
                wire:model="contact_email" name="contact_email" placeholder="example@email.com" />
            <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
        </div>

        

        <!-- Form Buttons -->
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2 bg-blue-500 text-white rounded shadow-md hover:bg-blue-600 focus:outline-none">
                Save
            </button>
        </div>
    </form>
    <!-- Form End -->
</div>
