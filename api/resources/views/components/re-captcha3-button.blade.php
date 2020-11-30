@if (! \is_null(config('setting.google.recaptcha3.public')))
    @once
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            function onReCaptcha3Submit(token) {
                let form = document.getElementById('{{ $formID }}');

                // Create new element
                let input = document.createElement('input');

                input.name = 'recaptcha_token';
                input.type = 'hidden';
                input.value = token;

                // Append child
                form.appendChild(input);

                // Submit form
                form.submit();
            }
        </script>
    @endpush
    @endonce

    <button {{ $attributes->merge(['class' => 'g-recaptcha']) }}
            data-sitekey="{{ config('setting.google.recaptcha3.public') }}"
            data-callback='onReCaptcha3Submit'
            data-action='submit'>{{ $slot }}</button>
@else
    <button {{ $attributes->merge(['class' => 'g-recaptcha no-key-provided']) }} type="submit">{{ $slot }}</button>
@endif
