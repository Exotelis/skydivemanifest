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
            {{ __('mails.email_verified') }}
        </p>
    </x-mails.text>

    <x-mails.button :href="$frontend">
        {{ __('mails.button_to_application') }}
    </x-mails.button>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_to_application'),'url' => $frontend]) }}
        </p>
    </x-mails.button_fallback>
@endsection
