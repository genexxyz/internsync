@props(['breadcrumbs' => []])

<nav class="text-sm text-gray-500">
    <ol class="flex items-center space-x-2">
        <!-- Home Breadcrumb -->
        <li>
            <a wire:navigate href="{{ route(Auth::user()->role . '.dashboard') }}" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-home"></i> Home
            </a>
        </li>

        <!-- Dynamic Breadcrumbs -->
        @foreach ($breadcrumbs as $breadcrumb)
            <li class="flex items-center">
                <i class="fa fa-chevron-right mx-2"></i>
                @if ($breadcrumb['url'])
                    <!-- Link Breadcrumb -->
                    <a wire:navigate href="{{ $breadcrumb['url'] }}" class="text-gray-400 hover:text-gray-600">
                        {{ $breadcrumb['label'] }}
                    </a>
                @else
                    <!-- Active Breadcrumb -->
                    <span class="text-gray-600 font-medium">{{ $breadcrumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
