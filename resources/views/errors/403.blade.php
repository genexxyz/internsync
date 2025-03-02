<x-guest-layout>
    <div class="flex flex-col text-center text-white bg-black/30">
        <h1 class="font-bold text-2xl">403 - Unauthorized</h1>
        <p>You do not have permission to access this page.</p>
        <a class="underline" href="{{ route('login') }}"><i class="fa fa-arrow-left"></i> Go back to login</a>
    </div>
</x-guest-layout>
