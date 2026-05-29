<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-2xl font-semibold text-xs text-white uppercase tracking-widest bg-[oklch(45.7%_0.24_277.023)] hover:opacity-95 focus:bg-[oklch(45.7%_0.24_277.023)] active:bg-[oklch(31%_0.18_277.023)] focus:outline-none focus:ring-2 focus:ring-[oklch(45.7%_0.24_277.023)] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
