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
            {{ __('mails.verify_email_1') }}
        </p>
    </x-mails.text>

    <x-mails.button :href="$confirmUrl">
        {{ __('mails.button_confirm_mail') }}
    </x-mails.button>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_confirm_mail'),'url' => $confirmUrl]) }}
        </p>
    </x-mails.button_fallback>
@endsection
