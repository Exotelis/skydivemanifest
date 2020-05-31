@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.email_verified') }}

{{ __('mails.button_to_application') }}: {{ $frontend }}
@endsection

