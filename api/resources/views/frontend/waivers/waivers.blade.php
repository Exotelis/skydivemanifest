@extends('frontend.utility_skeleton')

@section('title', __('messages.waivers') . ' - ' . appName())
@section('header', __('messages.waivers'))

@section('content')

    @if (session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if (count($waivers) === 0)
        {{ __('messages.waivers_no_active') }}
    @else
        <ul>
            @foreach ($waivers as $waiver)
                <li>
                    <a href="{{ route(Route::currentRouteName(), [$language, $waiver['id']]) }}">
                        {{ $waiver['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
