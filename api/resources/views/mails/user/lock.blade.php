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
            {{ __('mails.lock_account_1', ['expire' => $expire, 'timezone' => $timezone]) }}
        </p>
    </x-mails.text>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.lock_account_2') }}
        </p>
    </x-mails.text>

    <x-mails.button :href="$forgotUrl">
        {{ __('mails.button_forgot_password') }}
    </x-mails.button>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            <em>{{ __('mails.lock_account_3') }}</em>
        </p>
    </x-mails.text>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_forgot_password'),'url' => $forgotUrl]) }}
        </p>
    </x-mails.button_fallback>
@endsection
