@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.lock_account_1', ['expire' => $expire, 'timezone' => $timezone]) }}

{{ __('mails.lock_account_2') }}

{{ __('mails.button_forgot_password') }}: {{ $forgotUrl }}

{{ __('mails.lock_account_3') }}
@endsection
