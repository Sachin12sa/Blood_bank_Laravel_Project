<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wide shadow-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-4 focus:ring-red-500/50 active:shadow-lg active:scale-[0.98] transition-all duration-200 transform hover:shadow-2xl']) }}>
    {{ $slot }}
</button>

