<button {{ $attributes->merge(['type' => 'submit', 'class' => 'px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-sm text-white uppercase text-center hover:bg-accent focus:bg-accent active:bg-accent focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
