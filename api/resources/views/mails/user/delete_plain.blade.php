@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.delete_user_1', ['email' => $email]) }}

{{ __('mails.delete_user_2') }}

{{ __('mails.blue_skies') }}
@endsection
