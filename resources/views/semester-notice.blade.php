<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times text-red-500 text-5xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Academic Period Mismatch
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Your account is not associated with the current academic period.
                        Please contact your administrator for assistance.
                    </p>
                    @if($currentAcademic)
                        <p class="text-sm text-gray-500 mb-6">
                            Current Academic Period: {{ $currentAcademic->academic_year }} - {{ $currentAcademic->semester }}
                        </p>
                    @endif
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('profile.edit') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-gray-800 hover:bg-gray-200">
                            View Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>