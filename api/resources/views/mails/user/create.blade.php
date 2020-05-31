@extends('mails.mail')

@section('content')
    <x-mails.header_container>
        <x-mails.h2>
            {{ __('mails.welcome') }}
        </x-mails.h2>
        <x-mails.h3>
            {{ $firstname }}!
        </x-mails.h3>
    </x-mails.header_container>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.create_user_1') }}
        </p>
    </x-mails.text>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.create_user_2', ['days' => $delete]) }}
        </p>
    </x-mails.text>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.create_user_3') }}
        </p>
    </x-mails.text>

    @if( !is_null($password))
        <x-mails.text>
            <p style="padding: 0; margin: 0;text-align: center;">
                {{ __('mails.create_user_password') }} <strong>{{ $password }}</strong>
            </p>
        </x-mails.text>
    @endif

    <x-mails.button :href="$frontend">
        {{ __('mails.button_get_started') }}
    </x-mails.button>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            <em>{{ __('mails.create_user_4') }}</em>
        </p>
    </x-mails.text>

    <x-mails.hr/>

    <x-mails.button_fallback>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.button_fallback', ['button' => __('mails.button_get_started'),'url' => $frontend]) }}
        </p>
    </x-mails.button_fallback>
@endsection
