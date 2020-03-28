@extends('mails.mail_plain')

@section('content')
{{ __('mails.welcome') }} {{ $firstname }}!

{{ __('mails.create_user_1') }}

{{ __('mails.create_user_2', ['days' => $delete]) }}

{{ __('mails.create_user_3') }}
@if( !is_null($password))

{{ __('mails.create_user_password') }} {{ $password }}
@endif

{{ __('mails.button_get_started') }}: {{ $frontend }}

{{ __('mails.create_user_4') }}
@endsection
