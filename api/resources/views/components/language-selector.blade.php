@if (Arr::has(Route::current()->parameters(), 'language'))
    @push('scripts')
        <script>
            function changeLanguage(url) {
                window.location.href = url;
            }
        </script>
    @endpush

    <label class="mr-2">{{ __('messages.language') }}:</label>
    <select onchange="changeLanguage(this.value)">
        @foreach (validLocales() as $locale)
            @php
                $params = Route::current()->parameters();
                Arr::set($params, 'language', $locale);
            @endphp

            <option value="{{ route(Route::currentRouteName(), $params) }}" @if ($locale === $language) selected @endif>
                {{ $locale }}
            </option>
        @endforeach
    </select>
@endif
