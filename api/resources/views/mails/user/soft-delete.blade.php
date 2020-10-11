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
            {{ __('mails.soft_delete_user_1', ['days' => $days]) }}
        </p>
    </x-mails.text>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.soft_delete_user_2', ['days' => $days]) }}
        </p>
    </x-mails.text>

    <x-mails.button :href="$recoverUrl">
        {{ __('mails.button_recover_account') }}
    </x-mails.button>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.soft_delete_user_3') }}
        </p>
    </x-mails.text>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_confirm_mail'),'url' => $recoverUrl]) }}
        </p>
    </x-mails.button_fallback>
@endsection
