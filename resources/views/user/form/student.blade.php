<x-form-layout>

    <div class="w-full container mx-autoflex justify-center rounded-lg">


        <div class="bg-white rounded px-10 py-5">
            <div class="mb-5 flex flex-col justify-center">
                <p class="text-2xl">Additional Informations</p>
                <p class="text-sm">Enter your details below:</p>
            </div>

            <div class="font-semi-bold text-xl text-gray-600">
                <p>Name: {{ Auth::user()->roleInfo->first_name }} {{ Auth::user()->roleInfo->middle_name ?? '' }}
                    {{ Auth::user()->roleInfo->last_name }} {{ Auth::user()->roleInfo->suffix ?? '' }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('user.form.student.submit') }}">
                @csrf

                <div class="mt-4">
                    <x-input-label for="year_section_id" :value="__('Course, Year & Section')" />
                
                    <livewire:searchable-dropdown 
                        :options="$sections->map(function($section) {
                            return [
                                'value' => $section->course ? $section->course->id : null,
                                'label' => $section->course ? $section->course->course_code . ' ' . $section->course->year_level . $section->class_section : 'No Course'
                            ];
                        })->toArray()" 
                        name="year_section_id" 
                        placeholder="BSIS 4A"
                        multiple="" 
                    />
                </div>
                
                <div class="mt-4">
                    <x-input-label for="birthday" :value="__('Birthday')" />
                    <x-text-input icon="fa fa-calendar-days" id="birthday" class="block mt-1 w-full" type="text"
                        name="birthday" :value="old('birthday')" required autofocus placeholder="MM/DD/YYYY" />
                </div>


                <div class="my-4">
                    <p class="text-gray-600">Address</p>
                    @livewire('address-selector')
                </div>


                
                

                <!-- Register Button -->
                <div class="flex items-center justify-end mt-7">
                    <a wire:navigate
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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



</x-form-layout>
