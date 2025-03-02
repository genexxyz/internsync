<div class="w-full sm:h-52 md:h-64 lg:h-80 flex items-center px-3">
    <canvas id="verificationChart" class="w-full h-full"></canvas>
</div>

<script>
    // Declare the variable globally without re-declaring it
    if (typeof verificationChart === 'undefined') {
        var verificationChart = null; // Use var to avoid block-scoping issues
    }
    document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('livewire:navigated', function () {
        const canvasElement = document.getElementById('verificationChart');
        if (!canvasElement) {
            
            return; // Exit if the canvas is not found
        }

        const ctx = canvasElement.getContext('2d');

        // Destroy the existing chart instance if it exists
        if (verificationChart) {
            verificationChart.destroy();
        }

        // Create a new chart instance
        verificationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Instructors', 'Supervisors', 'Students'],
                datasets: [{
                    label: 'Verified',
                    data: [
                        {{ $instructorsVerified }},
                        {{ $supervisorsVerified }},
                        {{ $studentsVerified }}
                    ],
                    backgroundColor: 'rgba(129,199,132,1)',
                }, {
                    label: 'Pending Verification',
                    data: [
                        {{ $instructorsPending }},
                        {{ $supervisorsPending }},
                        {{ $studentsPending }}
                    ],
                    backgroundColor: 'rgba(246,104,94,1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
});
</script>
