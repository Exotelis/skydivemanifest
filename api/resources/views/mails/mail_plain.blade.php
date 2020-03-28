@yield('content')


{{ __('mails.greeting', ['appname' => appName()]) }}
@if (! is_null(appSupportMail()) && ! empty(appSupportMail()))
{{ appSupportMail() }}
@endif
@if(file_exists(resource_path('views'.DIRECTORY_SEPARATOR.'mails'.DIRECTORY_SEPARATOR.'imprint_plain.blade.php')))

--
@include('mails.imprint_plain')
@else

--
{{ appName() }}
@endif
