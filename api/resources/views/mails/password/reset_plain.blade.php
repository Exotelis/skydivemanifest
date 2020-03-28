@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.password_reset_request') }}

{{ $resetUrl }}

{{ __('mails.not_me') }}
@endsection
