@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.recover_account_1') }}

{{ __('mails.further_questions') }}
@endsection
