@extends('mails.mail')

@section('content')
    <x-mails.header_container>
        <x-mails.h2>
            {{ __('mails.hello') }}
        </x-mails.h2>
    </x-mails.header_container>

    <x-mails.text>
        <p style="padding: 0; margin: 0;text-align: center;">
            {{ __('mails.aircraft_maintenance_1', ['hours' => trans_choice('messages.hours', $hours), 'id' => $id, 'minutes' => trans_choice('messages.minutes', $minutes), 'registration' => $registration, ]) }}
        </p>
    </x-mails.text>
@endsection
