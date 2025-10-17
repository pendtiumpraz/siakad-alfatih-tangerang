<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 islamic-button w-full text-sm uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150']) }} style="background-color: #4A7C59; color: white; font-weight: 600;">
    {{ $slot }}
</button>
