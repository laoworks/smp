@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-2xl border-slate-300 shadow-sm focus:border-[oklch(45.7%_0.24_277.023)] focus:ring-[oklch(45.7%_0.24_277.023)]']) }}>
