@extends('frontend.utility_skeleton')

@section('title', __('messages.waiver') . ' - ' . appName())
@section('header', __('messages.waiver'))

@once
@push('scripts')
    <script>
        function toggleForm (element) {
            let ticket = document.getElementById('ticket');

            let email = document.getElementById('email');
            let firstName = document.getElementById('firstName');
            let lastName = document.getElementById('lastName');

            ticket.disabled = !(element.checked);
            email.disabled = element.checked;
            firstName.disabled = element.checked;
            lastName.disabled = element.checked;
        }
    </script>
@endpush
@endonce

@section('content')

    <div class="flex flex-wrap align-items-center justify-content-between">
        <div>
            <a class="nowrap" href="{{ route(Route::currentRouteName(), [$language]) }}">
                {{ __('messages.back_to_overview') }}
            </a>
        </div>
        <div class="flex align-items-center">
            <x-language-selector :language="$language"/>
        </div>
    </div>

    <hr>

    <h2>{{ $waiver['title'] }}</h2>

    @if (\count($waiver['texts']) === 0)
        {{ __('messages.no_content') }}
    @else
        @foreach ($waiver['texts'] as $text)
            @if(! empty($text['title']))
                <h3>{{ $text['title'] }}</h3>
            @endif

            {!! $text['text'] !!}
        @endforeach

        <hr class="my-8">

        <form action="{{ route('e-waivers.sign', Route::current()->parameters() ) }}#e-waiver-form"
              method="post"
              id="e-waiver-form">
            @csrf

            @if (session('error'))
                <div class="alert danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert danger">
                    {{ __('error.form_error') }}
                </div>
            @endif

            <div class="mb-4">
                {{ __('messages.waiver_description') }}
            </div>

            <div class="form-group">
                <input id="confirm"
                       type="checkbox"
                       checked
                       onchange="toggleForm(this)">
                <label for="confirm">{{ __('messages.ticket_continue') }}?</label>
            </div>

            <div class="form-group">
                <label for="ticket">{{ __('messages.ticket_number') }}:</label>
                <input id="ticket"
                       name="ticket_number"
                       type="text"
                       value="{{ old('ticket_number') ?? Request::get('ticket') }}"
                       class="@error('ticket_number') has-error @enderror">
                @error('ticket_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('messages.email') }}:</label>
                <input id="email"
                       name="email"
                       type="text"
                       value="{{ old('email') }}"
                       class="@error('email') has-error @enderror"
                       disabled>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="flex flex-wrap form-row">
                <div class="form-group">
                    <label for="firstName">{{ __('messages.firstname') }}:</label>
                    <input id="firstName"
                           name="firstname"
                           type="text"
                           value="{{ old('firstname') }}"
                           class="@error('firstname') has-error @enderror"
                           disabled>
                    @error('firstname')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lastName">{{ __('messages.lastname') }}:</label>
                    <input id="lastName"
                           name="lastname"
                           type="text"
                           value="{{ old('lastname') }}"
                           class="@error('lastname') has-error @enderror"
                           disabled>
                    @error('lastname')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>{{ __('messages.signature') }}:</label>
                <x-signature-pad></x-signature-pad>
                @error('signature')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>


            <div class="mb-4">{{  __('messages.waiver_consent_text') }}</div>
            <x-re-captcha-3-button class="primary block" formID="e-waiver-form">
                {{ __('messages.waiver_accept') }}
            </x-re-captcha-3-button>
        </form>
    @endif

@endsection
