@php
    use App\Helpers\CaptchaHelper;
    $formType = $formType ?? 'general';
@endphp

@if(CaptchaHelper::isEnabled($formType))
    <div class="captcha-container mb-3">
        {!! CaptchaHelper::render($formType) !!}
        @error('g-recaptcha-response')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('h-captcha-response')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        @error('cf-turnstile-response')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    @push('scripts')
        {!! CaptchaHelper::getScripts($formType) !!}
    @endpush
@endif
