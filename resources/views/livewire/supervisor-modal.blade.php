<div class="bg-white rounded-lg w-full max-w-7xl shadow-lg px-12 py-3 text-gray-800">
    <div class="flex items-center justify-between mb-2">
        <!-- Profile Heading -->
        <div class="flex-1 text-center">
            <h2 class="text-xl font-bold">Profile</h2>
        </div>
        <!-- Close Button -->
        <div>
            <button wire:click="$dispatch('closeModal')" class="bg-primary-600 rounded-md hover:bg-primary-700 p-2">
                <i class="fa fa-xmark font-bold text-xl text-gray-500"></i>
            </button>
        </div>
    </div>

    <hr>
    <div class="flex flex-col lg:flex-row p-4 sm:flex-col sm:items-center xs:flex-col xs:items-center mb-4">
        <!-- Profile Image -->
        <img src="/images/default_avatar.jpg" class="w-32 h-32 rounded-full mx-auto lg:mx-0" alt="avatar">

        <div class="lg:ml-4 sm:text-center xs:text-center mt-4 lg:mt-0">
            <p class="text-2xl font-semibold">
                {{ $supervisor->first_name ?? '' }}
                {{ $supervisor->middle_name ?? '' }}
                {{ $supervisor->last_name ?? '' }}
                {{ $supervisor->suffix ?? '' }}
                @if ($supervisor->user->is_verified)
                    <i class="fa fa-circle-check text-green-500" tooltip="verified"></i>
                @endif
            </p>
            <p><i class="fa fa-user text-gray-400"></i> Supervisor</p>
            <p><i class="fa fa-building text-gray-400"></i>
                Company Name
            </p>
        </div>
    </div>

    <hr>

    <div class="flex flex-col lg:flex-row sm:flex-col sm:items-center xs:flex-col xs:items-center p-4">
        <div class="lg:w-1/2 sm:w-full xs:w-full text-center mb-4 lg:mb-0">
            <p class="font-semibold">Contact Information</p>
            <p><i class="fa fa-envelope text-gray-400"></i> {{ $supervisor->user->email ?? '' }}</p>
            <p><i class="fa fa-phone text-gray-400"></i> {{ $supervisor->contact ?? '' }}</p>
        </div>

        {{-- <div class="lg:w-1/2 sm:w-full xs:w-full text-center">
            <p class="font-semibold">Deployment Details</p>
            <ul class="flex flex-col items-center">
                @if ($student->deployment)
                    
                @else
                <button wire:click="deleteInstructor" class="bg-blue-500 px-2 py-1 rounded uppercase text-white font-semibold hover:bg-blue-600">
                    Assign
                </button>
                @endif
            </ul>
        </div> --}}
    </div>

    <div>
        <p>Supporting Document</p>
        <i class="fa fa-id-card"></i>
    </div>
    <hr>
    <div class="flex flex-row justify-center items-center my-4 gap-4">
        @if (!$supervisor->user->is_verified)
            <button wire:click="verifySupervisor"
                class="bg-green-500 px-3 py-2 rounded uppercase text-white font-semibold hover:bg-green-700">
                Verify
            </button>
        @endif
        <button wire:click="deleteSupervisor" class="bg-red-500 px-3 py-2 rounded uppercase text-white font-semibold hover:bg-red-700">
            Delete
        </button>
    </div>
</div>
