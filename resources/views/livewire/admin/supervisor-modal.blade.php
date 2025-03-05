<!-- filepath: /opt/lampp/htdocs/internsync/resources/views/livewire/admin/supervisor-modal.blade.php -->
<div class="bg-white rounded-lg w-full max-w-7xl shadow-lg">
    <!-- Header -->
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Supervisor Profile</h2>
            <button wire:click="$dispatch('closeModal')"
                class="p-2 rounded-full hover:bg-gray-200 transition-colors duration-200">
                <i class="fa fa-xmark text-gray-500 text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Profile Section -->
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 mb-6">
            <img src="/images/default_avatar.jpg"
                class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm" alt="Profile Picture">

            <div class="flex-1 text-center lg:text-left">
                <div class="flex items-center justify-center lg:justify-start gap-2 mb-2">
                    <h3 class="text-2xl font-bold text-gray-800">
                        {{ $supervisor->first_name }}
                        {{ $supervisor->last_name }}
                    </h3>
                    @if ($supervisor->user->is_verified ?? false)
                        <i class="fa fa-circle-check text-green-500 text-xl" title="Verified"></i>
                    @endif
                </div>

                <p class="text-gray-600 mb-2">
                    <i class="fa fa-building text-gray-400 mr-2"></i>
                    {{ $supervisor->department->department_name ?? 'No Department' }}
                </p>

                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full">
                    <i class="fa fa-user-tie text-sm"></i>
                    <span class="font-medium">Supervisor</span>
                </div>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Contact Information</h4>
                <div class="space-y-2">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-envelope w-5"></i>
                        <span>{{ $supervisor->user->email ?? '' }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-phone w-5"></i>
                        <span>{{ $supervisor->contact ?? '' }}</span>
                    </p>
                </div>
            </div>

            <!-- Company Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-4">Company Information</h4>
                <div class="space-y-2">
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-building w-5"></i>
                        <span>{{ $supervisor->department->company->company_name ?? 'No Company' }}</span>
                    </p>
                    <p class="flex items-center gap-2 text-gray-600">
                        <i class="fa fa-map-marker-alt w-5"></i>
                        <span>{{ $supervisor->department->company->address ?? 'No Address' }}</span>
                    </p>
                </div>
            </div>
        </div>
<!-- Supporting Documents -->
<div class="mb-6">
    <h4 class="font-semibold text-gray-800 mb-4">Supporting Documents</h4>
    @if($supervisor->supporting_doc)
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa fa-file-image text-red-500 text-xl"></i>
                    <span class="text-gray-600">{{ basename($supervisor->supporting_doc) }}</span>
                </div>
                <button 
            x-data
            @click="$dispatch('open-preview', { url: '{{ Storage::url($supervisor->supporting_doc) }}' })"
            class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200"
        >
            <i class="fa fa-eye mr-2"></i>
            View Document
        </button>
            </div>
        </div>
    @else
        <p class="text-gray-500">No supporting documents available</p>
    @endif
</di
        <!-- Interns List -->
        @if($supervisor->deployments && $supervisor->deployments->count() > 0)
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Current Interns</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year, Course & Section</th>
                                    
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($supervisor->deployments as $deployment)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $deployment->student->first_name }} {{ $deployment->student->last_name }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ $deployment->student->yearSection->course->course_code ?? 'N/A' }}
                                            {{ $deployment->student->yearSection->year_level ?? 'N/A' }}{{ $deployment->student->yearSection->class_section ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $deployment->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                                   ($deployment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($deployment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-4">Interns</h4>
                <div class="bg-gray-50 rounded-lg p-4 text-center text-gray-500">
                    No interns assigned to this supervisor
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex justify-end gap-3">
            @if (!$supervisor->user->is_verified)
                <button wire:click="verifySupervisor"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fa fa-check mr-2"></i>
                    Verify Supervisor
                </button>
            @endif
            <button wire:click="deleteSupervisor"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                <i class="fa fa-trash mr-2"></i>
                Delete Supervisor
            </button>
        </div>
    </div>



    <!-- Document Preview Modal -->
    <div x-data="{ 
        showPreview: false,
        previewUrl: '',
        init() {
            window.addEventListener('open-preview', (e) => {
                this.previewUrl = e.detail.url;
                this.showPreview = true;
            });
        }
    }" x-show="showPreview" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
        <!-- Modal container -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full">
                <!-- Close button -->
                <div class="absolute top-4 right-4 z-10">
                    <button @click="showPreview = false"
                        class="p-2 bg-white hover:bg-gray-100 rounded-full transition-colors duration-200 shadow-md">
                        <i class="fa fa-times text-gray-500"></i>
                    </button>
                </div>
                
                <!-- Image viewer -->
                <div class="p-1">
                    <div class="flex items-center justify-center bg-gray-100 w-full h-[85vh] rounded-lg overflow-auto">
                        <img :src="previewUrl" class="max-w-full max-h-full object-contain" alt="Document preview">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>