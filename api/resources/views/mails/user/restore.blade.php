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
            {{ __('mails.recover_account_1') }}
        </p>
    </x-mails.text>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.further_questions') }}
        </p>
    </x-mails.text>
@endsection
