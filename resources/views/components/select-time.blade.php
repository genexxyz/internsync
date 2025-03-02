@props([
    'selected' => null, // Currently selected time (in 'h:i A' format)
    'name' => '', // Name of the time field
    'class' => '', // Custom class for the time picker
])

<div x-data="{
    hour: null,
    minute: null,
    period: null,
    selectedTime: @entangle($attributes->wire('model')).live,
    init() {
        if (this.selectedTime) {
            this.setTime(this.selectedTime);
        }
    },
    setTime(time) {
        const [h, m, p] = time.split(/[: ]/);
        this.hour = parseInt(h);
        this.minute = m;
        this.period = p;
    },
    formatTime() {
        if (this.hour !== null && this.minute !== null && this.period !== null) {
            return `${this.hour}:${this.minute} ${this.period}`;
        }
        return '';
    },
    selectTime() {
        if (this.hour !== null && this.minute !== null && this.period !== null) {
            this.selectedTime = `${this.hour}:${this.minute} ${this.period}`;
        }
    }
}" x-init="init()" class="relative">
    <input 
        type="text" 
        :value="selectedTime || 'Select Time'" 
        @click="isOpen = !isOpen"
        class="block mt-1 w-full text-gray-600 border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm {{ $class }}" 
        readonly
    />

    <!-- Time Picker Dropdown -->
    <div x-show="isOpen" 
         x-cloak
         @click.away="isOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
         x-transition>
        <div class="bg-white rounded-lg shadow-lg p-4 border w-96">
            <div class="flex items-center justify-between mb-4">
                <!-- Profile Heading -->
                <h2 class="text-xl font-bold text-center">SELECT TIME</h2>
            
                <!-- Close Button -->
                <button @click="isOpen = false"
                    class="bg-primary-600 rounded-md hover:bg-primary-700 p-2 focus:outline-none">
                    <i class="fa fa-xmark font-bold text-xl text-gray-500"></i>
                </button>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <!-- Hour -->
                <div>
                    <select x-model="hour" class="w-full mt-1 px-2 py-1 border rounded-md">
                        <option value="">hour</option>
                        <template x-for="h in Array.from({ length: 12 }, (_, i) => i + 1)" :key="h">
                            <option :value="h" x-text="h"></option>
                        </template>
                    </select>
                </div>
                <!-- Minute -->
                <div>
                    <select x-model="minute" class="w-full mt-1 px-2 py-1 border rounded-md">
                        <option value="">minute</option>
                        <template x-for="m in Array.from({ length: 60 }, (_, i) => String(i).padStart(2, '0'))" :key="m">
                            <option :value="m" x-text="m"></option>
                        </template>
                    </select>
                </div>
                <!-- Period -->
                <div>
                    <select x-model="period" class="w-full mt-1 px-2 py-1 border rounded-md">
                        <option value="">AM/PM</option>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button @click="selectTime()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    SET
                </button>
            </div>
        </div>
    </div>
</div>
