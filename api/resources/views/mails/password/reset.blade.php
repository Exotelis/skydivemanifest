@extends('mails.mail')

@section('content')
    <x-mails.header_container>
        <x-mails.h2>
            {{ __('mails.hello') }}
        </x-mails.h2>
        <x-mails.h3>
            {{ $firstname }}!
        </x-mails.h3>
    </x-mails.header_container>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.password_reset_request') }}
        </p>
    </x-mails.text>

    <x-mails.button :href="$resetUrl">
        {{ __('mails.button_reset_password') }}
    </x-mails.button>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            <em>{{ __('mails.not_me') }}</em>
        </p>
    </x-mails.text>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_reset_password'),'url' => $resetUrl]) }}
        </p>
    </x-mails.button_fallback>
@endsection
