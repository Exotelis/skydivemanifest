@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }}!

{{ __('mails.aircraft_maintenance_1', ['hours' => trans_choice('messages.hours', $hours), 'id' => $id, 'minutes' => trans_choice('messages.minutes', $minutes), 'registration' => $registration, ]) }}
@endsection
