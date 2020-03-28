@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.verify_email_1') }}

{{ __('mails.button_confirm_mail') }}: {{ $confirmUrl }}
@endsection
