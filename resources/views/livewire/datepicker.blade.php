<div x-data="{
    selectedDate: @entangle('selectedDate').live,
    isOpen: false,
    currentMonth: new Date(),
    minDate: new Date('{{ $minDate }}'),
    maxDate: new Date('{{ $maxDate }}'),
    init() {
        this.selectedDate = '{{ $initialDate }}' || null;
        this.generateDates();
    },
    generateDates() {
    const year = this.currentMonth.getFullYear();
    const month = this.currentMonth.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startOffset = firstDay.getDay();

    let dates = [];
    for (let i = 0; i < startOffset; i++) {
        dates.push(null); // Placeholder for blank days
    }
    for (let i = 1; i <= daysInMonth; i++) {
        let date = new Date(year, month, i);
        if (this.isDateInRange(date)) {
            dates.push(date);
        } else {
            dates.push(null); // Explicitly add `null` for out-of-range dates
        }
    }
    return dates.filter(date => date === null || date instanceof Date); // Ensure all items are null or valid Dates
}
,
    isDateInRange(date) {
        // Check if the date is within the minDate and maxDate range
        return date >= this.minDate && date <= this.maxDate;
    },
    formatDate(date) {
    if (!(date instanceof Date) || isNaN(date)) return ''; // Check if valid Date
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}
,
    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    },
    isSelected(date) {
        if (!date || !this.selectedDate) return false;
        return date.toDateString() === new Date(this.selectedDate).toDateString();
    },
    previousMonth() {
        this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() - 1);
        this.generateDates();
    },
    nextMonth() {
        this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + 1);
        this.generateDates();
    }
}" class="relative">
<!-- Input Field -->
<input type="text" 
       wire:model.live="selectedDate"
       x-model="formatDate(selectedDate)"
       @click="isOpen = !isOpen"
       readonly
       class="w-full pl-9 sm:text-sm text-gray-600  border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm cursor-pointer"
       placeholder="{{ $placeholder }}"><i class="fa fa-calendar-day absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>

<!-- Calendar Dropdown -->
<div x-show="isOpen" 
     x-cloak
     @click.away="isOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
     x-transition>
    <div class="bg-white rounded-lg shadow-lg p-4 border w-96">
        <!-- Calendar Header -->
        <div class="flex justify-between items-center mb-4">
            <button @click="previousMonth()" class="p-1 hover:bg-gray-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div x-text="currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })" 
                 class="text-lg font-semibold"></div>
            <button @click="nextMonth()" class="p-1 hover:bg-gray-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-1">
            <!-- Day Headers -->
            <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']">
                <div class="text-gray-600 text-center text-sm py-1" x-text="day"></div>
            </template>

            <!-- Calendar Dates -->
            <template x-for="date in generateDates()">
                <div class="text-center py-1">
                    <button x-show="date"
                            @click="
                                const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
                                selectedDate = localDate.toISOString().split('T')[0];
                                isOpen = false;
                            "
                            :class="{
                                'w-8 h-8 rounded-full focus:outline-none': true,
                                'bg-primary text-white': isSelected(date),
                                'bg-gray-100': isToday(date) && !isSelected(date),
                                'hover:bg-gray-100': !isSelected(date),
                                'opacity-50 cursor-not-allowed': !isDateInRange(date)
                            }"
                            x-text="date?.getDate()">
                    </button>
                </div>
            </template>
            
        </div>

        <!-- Close Button -->
        <div class="mt-4 text-center">
            <button @click="isOpen = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Close
            </button>
        </div>
    </div>
</div>
</div>

@push('styles')
<style>
[x-cloak] { display: none !important; }
</style>
@endpush
