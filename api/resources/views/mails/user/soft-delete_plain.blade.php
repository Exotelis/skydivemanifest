@extends('mails.mail_plain')

@section('content')
{{ __('mails.hello') }} {{ $firstname }}!

{{ __('mails.soft_delete_user_1', ['days' => $days]) }}
{{ __('mails.soft_delete_user_2', ['days' => $days]) }}

{{ __('mails.button_recover_account') }}: {{ $recoverUrl }}

{{ __('mails.soft_delete_user_3') }}
@endsection
