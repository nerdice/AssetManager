<x-mail::message>
# {{ $greeting ?: 'Hello!' }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach
@isset($actionText)
<x-mail::button :url="$actionUrl" :color="$level === 'error' ? 'error' : 'primary'">
{{ $actionText }}
</x-mail::button>
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach
@if (!empty($salutation))
{{ $salutation }}
@else
{{ config('app.name') }}
@endif

@isset($actionText)
<x-slot:subcopy>
<p style="color: #b7afc6; font-size: 12px; line-height: 1.7;">Button not working? Copy and paste this link into your browser:</p>
<p style="font-size: 12px; line-height: 1.7; word-break: break-all;">
<a href="{{ $actionUrl }}" style="color: #c4aaff !important; text-decoration: underline; word-break: break-all;"><span style="color: #c4aaff;">{{ $actionUrl }}</span></a>
</p>
</x-slot:subcopy>
@endisset
</x-mail::message>