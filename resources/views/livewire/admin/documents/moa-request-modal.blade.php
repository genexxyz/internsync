<div class="relative bg-white rounded-xl shadow-xl max-w-4xl mx-auto">
    <!-- Sticky Header -->
    <div class="sticky top-0 z-20 bg-white px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-900">MOA Request Details</h3>
            <button wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Content Container -->
    <div class="relative">
        <!-- Scrollable Content -->
        <div class="p-6 max-h-[calc(100vh-16rem)] overflow-y-auto">
        <!-- Status Timeline -->
        <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-3">Request Timeline</h4>
            
            @php
                $statuses = [
                    'requested' => [
                        'icon' => 'fa-clock',
                        'date' => $request->requested_at,
                        'label' => 'Requested',
                        'color' => 'blue',
                        'received_by' => null
                    ],
                    'for_pickup' => [
                        'icon' => 'fa-box',
                        'date' => $request->for_pickup_at,
                        'label' => 'For Pickup',
                        'color' => 'yellow',
                        'received_by' => null
                    ],
                    'picked_up' => [
                        'icon' => 'fa-truck',
                        'date' => $request->picked_up_at,
                        'label' => 'Picked Up',
                        'color' => 'purple',
                        'received_by' => $request->received_by_student
                    ],
                    'received_by_company' => [
                        'icon' => 'fa-check-circle',
                        'date' => $request->received_by_company_at,
                        'label' => 'Received',
                        'color' => 'green',
                        'received_by' => null
                    ]
                ];
        
                $currentFound = false;
            @endphp
        
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200"></div>
        
                <!-- Timeline Items -->
                <div class="relative flex justify-between">
                    @foreach($statuses as $status => $item)
                        @php
                            $isActive = $request->status === $status;
                            $isPast = $currentFound ? false : ($item['date'] != null);
                            $currentFound = $currentFound || $isActive;
                            
                            $dotClass = $isActive || $isPast
                                ? "bg-{$item['color']}-500"
                                : "bg-gray-200";
                            
                            $textClass = $isActive || $isPast
                                ? "text-{$item['color']}-500"
                                : "text-gray-400";
                        @endphp
        
                        <div class="flex flex-col items-center relative">
                            <!-- Dot -->
                            <div class="w-8 h-8 rounded-full {{ $dotClass }} flex items-center justify-center z-10">
                                <i class="fas {{ $item['icon'] }} text-white text-sm"></i>
                            </div>
                            
                            <!-- Label -->
                            <p class="mt-2 text-xs font-medium {{ $textClass }}">
                                {{ $item['label'] }}
                            </p>
                            
                            <!-- Date -->
                            @if($item['date'])
                                <p class="text-xs {{ $textClass }}">
                                    {{ $item['date']->format('M d, Y') }}
                                </p>
                            @endif
                            @if($item['received_by'])
                    <p class="text-xs {{ $textClass }} mt-1">
                        <i class="fas fa-user-check mr-1"></i>
                        {{ $item['received_by'] }}
                    </p>
                @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Company Details -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Company Information</h4>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Company:</span> {{ $request->company->company_name }}</p>
                    <p><span class="text-gray-600">Company Number:</span> {{ $request->company_number }}</p>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Student Information</h4>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Name:</span> {{ $request->student->first_name }} {{ $request->student->last_name }}</p>
                    <p><span class="text-gray-600">Course:</span> {{ $request->student->section->course->course_name }}</p>
                </div>
            </div>
        </div>

        

        <!-- Officers & Witness -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Company Officer</h4>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Name:</span> {{ $request->officer_name }}</p>
                    <p><span class="text-gray-600">Position:</span> {{ $request->officer_position }}</p>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-3">Company Witness</h4>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-600">Name:</span> {{ $request->witness_name }}</p>
                    <p><span class="text-gray-600">Position:</span> {{ $request->witness_position }}</p>
                </div>
            </div>
        </div>
        <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-3">Company Interns</h4>
            <div class="bg-gray-50 rounded-lg p-3">
                @if($companyStudents->count() > 0)
                    <div class="space-y-2">
                        @foreach($companyStudents as $student)
                            <div class="flex items-center gap-2 text-sm">
                                <span class="w-2 h-2 rounded-full {{ $student->id === $request->student_id ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                <span class="text-gray-600">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                    <span class="text-gray-400">
                                        ({{ $student->section->course->course_code }}-{{ $student->section->year_level }}{{ $student->section->class_section }})
                                    </span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No other students currently deployed in this company.</p>
                @endif
            </div>
        </div>
        <!-- Admin Remarks -->
        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-2">Admin Remarks</label>
            <textarea
                wire:model="adminRemarks"
                rows="3"
                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary"
                placeholder="Add remarks here..."></textarea>
        </div>
        @if($request->status === 'for_pickup')
        <div class="mb-6">
            <label class="block font-medium text-gray-700 mb-2">Received By Student</label>
            <x-text-input 
                wire:model="receivedByStudent"
                type="text"
                placeholder="Enter student's complete name"
                class="w-full"
            />
            @error('receivedByStudent') 
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
    @endif
    
    @if($request->status === 'picked_up')
        <div class="mb-6">
            <h4 class="font-medium text-gray-700 mb-3">Pickup Information</h4>
            <div class="space-y-2 text-sm">
                <p>
                    <span class="text-gray-600">Received By:</span> 
                    {{ $request->received_by_student }}
                </p>
                <p>
                    <span class="text-gray-600">Pickup Date:</span>
                    {{ $request->picked_up_at?->format('M d, Y g:i A') }}
                </p>
            </div>
        </div>
    @endif
        <!-- Action Buttons -->
        <div class="pt-4 border-t flex justify-end gap-3">
            <button 
                wire:click="$dispatch('closeModal')"
                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Close
            </button>
            
            @if($request->status === 'requested')
                <button 
                    wire:click="updateStatus('for_pickup')"
                    class="px-4 py-2 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                    Mark for Pickup
                </button>
            @elseif($request->status === 'for_pickup')
                <button 
                    wire:click="updateStatus('picked_up')"
                    class="px-4 py-2 text-white bg-purple-500 rounded-lg hover:bg-purple-600">
                    Confirm Pickup
                </button>
            @endif
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-gray-300 via-white/50 to-transparent pointer-events-none"></div>
    </div>
</div>