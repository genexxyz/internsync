<div class="mt-6 p-4 sm:p-6 bg-gray-50 rounded-md">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Endorsement Letter Requests</h2>
    
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
            <select 
                wire:model.live="statusFilter" 
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
            >
                <option value="">All Status</option>
                <option value="requested">Requested</option>
                <option value="for_pickup">For Pickup</option>
                <option value="picked_up">Picked Up</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('requested_at')">
                        Requested Date
                        @if($sortField === 'requested_at')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                        @endif
                    </th>
                    <th class="py-3 px-6 text-left">Student</th>
                    <th class="py-3 px-6 text-left">Course</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($requests as $request)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6">
                            {{ $request->requested_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $request->student->first_name }} {{ $request->student->last_name }}
                        </td>
                        <td class="py-3 px-6">
                            {{ $request->student->section->course->course_code }}
                        </td>
                        <td class="py-3 px-6">
                            @php
                                $statusClasses = [
                                    'requested' => 'bg-blue-100 text-blue-800',
                                    'for_pickup' => 'bg-yellow-100 text-yellow-800',
                                    'picked_up' => 'bg-green-100 text-green-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClasses[$request->status] }}">
                                {{ str_replace('_', ' ', ucfirst($request->status)) }}
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex justify-center items-center gap-2">
                                @if($request->status === 'requested')
                                    <button 
                                        wire:click="updateStatus({{ $request->id }}, 'for_pickup')"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-yellow-600 transition-colors">
                                        Mark for Pickup
                                    </button>
                                @elseif($request->status === 'for_pickup')
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="text" 
                                            wire:model="receivedBy.{{ $request->id }}" 
                                            placeholder="Received by"
                                            class="rounded-lg border-gray-300 text-sm"
                                        >
                                        <button 
                                            wire:click="updateStatus({{ $request->id }}, 'picked_up')"
                                            class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600 transition-colors">
                                            Confirm Pickup
                                        </button>
                                    </div>
                                    @error("receivedBy.{$request->id}") 
                                        <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                    @enderror
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">
                            No endorsement letter requests found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>