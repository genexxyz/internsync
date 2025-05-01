<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Acceptance Letters Management</h2>
    <button 
        onclick="Livewire.dispatch('openModal', { component: 'admin.letter-template-manager' })"
        class="inline-flex items-center px-4 py-2.5 rounded-lg bg-primary text-white hover:bg-accent transition-colors gap-2 shadow-sm"
    >
        <i class="fas fa-file-alt mr-2"></i>
        Manage Templates
    </button>
    </div>
    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search students..."
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
        </div>

        <div>
            <select 
                wire:model.live="courseFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select 
                wire:model.live="sectionFilter"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Sections</option>
                @if($courseFilter)
                    @foreach($courses->find($courseFilter)->sections as $section)
                        <option value="{{ $section->id }}">{{ $section->course->year_level }}{{ $section->class_section }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div>
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="for_review">For Review</option>
                <option value="waiting_for_supervisor">Waiting for Supervisor</option>
                <option value="deployed">Deployed</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('created_at')">
                        Date
                        @if($sortField === 'created_at')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left">Student</th>
                    <th class="py-3 px-6 text-left">Course</th>
                    <th class="py-3 px-6 text-left">Section</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    {{-- <th class="py-3 px-6 text-center">Actions</th> --}}
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($students as $student)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6">
                            {{ $student->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->section->course->course_code }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $student->section->course->year_level }}{{ $student->section->class_section }}
                        </td>
                        <td class="py-3 px-6">
                            @php
                                $status = 'pending';
                                if ($student->acceptance_letter) {
                                    if ($student->deployment?->company_id && $student->deployment?->supervisor_id) {
                                        $status = 'deployed';
                                    } elseif ($student->deployment?->company_id) {
                                        $status = 'waiting_for_supervisor';
                                    } else {
                                        $status = 'for_review';
                                    }
                                }
                        
                                $statusClasses = [
                                    'pending' => 'bg-gray-100 text-gray-800',
                                    'for_review' => 'bg-yellow-100 text-yellow-800',
                                    'waiting_for_supervisor' => 'bg-blue-100 text-blue-800',
                                    'deployed' => 'bg-green-100 text-green-800'
                                ];
                                
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'for_review' => 'For Review',
                                    'waiting_for_supervisor' => 'Waiting for Supervisor',
                                    'deployed' => 'Deployed'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$status] }}">
                                {{ $statusLabels[$status] }}
                            </span>
                        </td>
                        {{-- <td class="py-3 px-6 text-center">
                            @if($student->acceptance_letter && $student->acceptance_letter->signed_path)
                        <!-- If letter is signed, show view button -->
                        <button @click="window.dispatchEvent(new CustomEvent('open-pdf-viewer', {
                            detail: { url: '{{ Storage::url($student->acceptance_letter->signed_path) }}' }
                        }))" 
                        class="w-full py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fa fa-eye text-sm"></i>
                            <span class="text-sm font-medium">View Signed Letter</span>
                        </button>
                        @endif
                            
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">
                            No students found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $students->links() }}
    </div>
    <div x-data="{
        showPdfViewer: false,
        pdfUrl: '',
        init() {
            window.addEventListener('open-pdf-viewer', (e) => {
                this.pdfUrl = e.detail.url;
                this.showPdfViewer = true;
            });
        }
    }" x-show="showPdfViewer" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    
            <!-- Backdrop overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
            <!-- Modal container -->
            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full">
                    <!-- Close button -->
                    <div class="absolute top-4 right-4">
                        <button @click="showPdfViewer = false"
                            class="py-2 px-4 bg-gray-100 hover:bg-gray-300 rounded-full transition-colors duration-200">
                            <i class="fa fa-times text-gray-500"></i>
                        </button>
                    </div>
    
                    <!-- PDF iframe container -->
                    <div class="p-1">
                        <iframe :src="pdfUrl" class="w-full h-[85vh] rounded-lg" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
</div>