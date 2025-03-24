<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg livewire-modal">
    <!-- Header -->
    <div class="bg-secondary text-white px-6 py-4 rounded-t-lg">
        <h2 class="text-xl font-bold text-center">On-the-Job-Training Program Performance Evaluation Report</h2>
    </div>

    <div class="p-6">
        <!-- Student Information -->
        <div class="grid grid-cols-2 gap-6 mb-6 bg-gray-50 p-4 rounded-lg">
            <div>
                <p class="mb-2"><span class="font-semibold text-gray-600">Student:</span> {{ $deployment->student->first_name}} {{ $deployment->student->last_name}}</p>
                <p class="mb-2"><span class="font-semibold text-gray-600">Course:</span> {{ $deployment->student->section->course->course_name }}</p>
                <p class="mb-2"><span class="font-semibold text-gray-600">Company:</span> {{ $deployment->department->company->company_name }}</p>
                <p class="mb-2"><span class="font-semibold text-gray-600">Address:</span> {{ $deployment->department->company->address }}</p>
            </div>
            <div>
                <p class="mb-2"><span class="font-semibold text-gray-600">Required Hours:</span> {{ $deployment->custom_hours }}</p>
                <p class="mb-2"><span class="font-semibold text-gray-600">Completed Hours:</span> {{ App\Models\Attendance::getTotalApprovedHours($deployment->student_id) }}</p>
                <p class="mb-2"><span class="font-semibold text-gray-600">Training Period:</span> 
                    {{ $deployment->starting_date->format('M d, Y') }} - {{ $deployment->ending_date->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Evaluation Form -->
        <form wire:submit.prevent="save">
            <div class="bg-white rounded-lg overflow-hidden border">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-left">Criteria</th>
                            <th class="border-b px-6 py-3 text-center w-32">Max Rating (%)</th>
                            <th class="border-b px-6 py-3 text-center w-32">Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $criteria = [
                                'quality_work' => 'Quality of Work (thoroughness, accuracy, neatness, effectiveness)',
                                'completion_time' => 'Quality of Work (able to complete work in allotted time)',
                                'dependability' => 'Dependability, Reliability, and Resourcefulness',
                                'judgment' => 'Judgment (sound decisions, ability to evaluate factors)',
                                'cooperation' => 'Cooperation (teamwork, working well with others)',
                                'attendance' => 'Attendance (punctuality, regularity)',
                                'personality' => 'Personality (grooming, disposition)',
                                'safety' => 'Safety (awareness of safety practices)',
                            ];
                        @endphp

@foreach ($criteria as $key => $label)
<tr class="hover:bg-gray-50">
    <td class="border-b px-6 py-1">{{ $label }}</td>
    <td class="border-b px-6 py-1 text-center font-medium">
        {{ $maxRatings[$key] }}%
    </td>
    <td class="border-b px-6 py-1">
        @if($isEvaluated)
            <span class="block p-2 text-center">{{ $ratings[$key] }}</span>
        @else
            <div class="relative">
                <input 
                    type="number" 
                    inputmode="numeric"
                    pattern="[0-9]*"
                    class="w-full p-2 border rounded text-center focus:ring-primary focus:border-primary"
                    wire:model.live="ratings.{{ $key }}"
                    min="1"
                    max="{{ $maxRatings[$key] }}"
                    placeholder="1-{{ $maxRatings[$key] }}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                @error("ratings.$key")
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </td>
</tr>
@endforeach
</tbody>
<tfoot>
    <tr class="bg-gray-50 font-medium">
        <td class="border-t px-6 py-4">Total</td>
        <td class="border-t px-6 py-4 text-center">100%</td>
        <td class="border-t px-6 py-4 text-center">
            {{ $this->totalRating }}%
        </td>
    </tr>
</tfoot>
</table>

            <!-- Recommendation Section -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Recommendation
                    @if(!$isEvaluated)
                        <span class="text-xs text-gray-500 ml-1">(minimum 50 characters)</span>
                    @endif
                </label>
                @if($isEvaluated)
                    <div class="w-full p-3 bg-gray-50 rounded-lg">
                        {{ $recommendation }}
                    </div>
                @else
                    <textarea
                        wire:model="recommendation"
                        rows="4"
                        class="w-full p-3 border rounded-lg focus:ring-primary focus:border-primary resize-none"
                        placeholder="Enter your recommendation here..."
                    ></textarea>
                    @error('recommendation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <button 
                    type="button"
                    wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Close
                </button>
                @if($isEvaluated)
                    <button 
                        type="button"
                        wire:click="generatePdf"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark"
                    >
                        Generate PDF
                    </button>
                @else
                    <button 
                        type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark"
                    >
                        Submit Evaluation
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>