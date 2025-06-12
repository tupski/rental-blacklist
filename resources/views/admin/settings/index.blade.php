@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-cog mr-2"></i>
                    Pengaturan Situs
                </h1>
                <p class="text-blue-100 mt-1">Kelola pengaturan tampilan, SEO, dan konfigurasi situs</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 m-6 rounded">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                @foreach($settings as $group => $groupSettings)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">
                            @switch($group)
                                @case('general')
                                    <i class="fas fa-globe mr-2 text-blue-500"></i>
                                    Pengaturan Umum
                                    @break
                                @case('seo')
                                    <i class="fas fa-search mr-2 text-green-500"></i>
                                    SEO & Meta Tags
                                    @break
                                @case('appearance')
                                    <i class="fas fa-palette mr-2 text-purple-500"></i>
                                    Tampilan
                                    @break
                                @case('social')
                                    <i class="fas fa-share-alt mr-2 text-pink-500"></i>
                                    Media Sosial
                                    @break
                                @default
                                    {{ ucfirst($group) }}
                            @endswitch
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupSettings as $setting)
                                <div class="space-y-2">
                                    <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700">
                                        {{ $setting->label }}
                                        @if($setting->description)
                                            <span class="text-xs text-gray-500 block">{{ $setting->description }}</span>
                                        @endif
                                    </label>

                                    @switch($setting->type)
                                        @case('textarea')
                                            <textarea 
                                                name="settings[{{ $setting->key }}]" 
                                                id="setting_{{ $setting->key }}"
                                                rows="3"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >{{ $setting->value }}</textarea>
                                            @break

                                        @case('color')
                                            <div class="flex items-center space-x-2">
                                                <input 
                                                    type="color" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    id="setting_{{ $setting->key }}"
                                                    value="{{ $setting->value }}"
                                                    class="w-12 h-10 border border-gray-300 rounded cursor-pointer"
                                                >
                                                <input 
                                                    type="text" 
                                                    value="{{ $setting->value }}"
                                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    readonly
                                                >
                                            </div>
                                            @break

                                        @case('boolean')
                                            <div class="flex items-center">
                                                <input 
                                                    type="checkbox" 
                                                    name="settings[{{ $setting->key }}]" 
                                                    id="setting_{{ $setting->key }}"
                                                    value="1"
                                                    {{ $setting->value ? 'checked' : '' }}
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                >
                                                <label for="setting_{{ $setting->key }}" class="ml-2 text-sm text-gray-700">
                                                    Aktifkan
                                                </label>
                                            </div>
                                            @break

                                        @case('number')
                                            <input 
                                                type="number" 
                                                name="settings[{{ $setting->key }}]" 
                                                id="setting_{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                            @break

                                        @case('email')
                                            <input 
                                                type="email" 
                                                name="settings[{{ $setting->key }}]" 
                                                id="setting_{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                            @break

                                        @case('url')
                                            <input 
                                                type="url" 
                                                name="settings[{{ $setting->key }}]" 
                                                id="setting_{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                placeholder="https://..."
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                            @break

                                        @default
                                            <input 
                                                type="text" 
                                                name="settings[{{ $setting->key }}]" 
                                                id="setting_{{ $setting->key }}"
                                                value="{{ $setting->value }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update color input text when color picker changes
document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
    const textInput = colorInput.nextElementSibling;
    colorInput.addEventListener('change', function() {
        textInput.value = this.value;
    });
});
</script>
@endsection
