<div {{ $attributes->merge(['class' => 'min-h-[60vh] flex flex-col items-center justify-center p-6']) }}>
    <div class="text-center">
        <!-- Construction Icon -->
        <div class="mb-6 text-primary">
            <i class="fas fa-hammer text-6xl"></i>
        </div>

        <!-- Main Message -->
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
            {{ $message }}
        </h2>

        <!-- Sub Message -->
        <p class="text-gray-600 mb-8">
            {{ $submessage }}
        </p>

        <!-- Animation -->
        <div class="flex justify-center gap-2 text-primary">
            <i class="fas fa-cog text-2xl"></i>
            <i class="fas fa-cog text-3xl"></i>
            <i class="fas fa-cog text-2xl"></i>
        </div>
    </div>
</div>