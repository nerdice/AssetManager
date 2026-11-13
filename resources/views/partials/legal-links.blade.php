@php
    $legalLinkLocale = request()->route('locale') ?? app()->getLocale();
    $legalLinkLocale = in_array($legalLinkLocale, ['en', 'de', 'hu'], true) ? $legalLinkLocale : 'en';
    $legalLinkLabels = [
        'en' => ['privacy' => 'Privacy Notice', 'notice' => 'Legal Notice', 'terms' => 'Terms of Use'],
        'de' => ['privacy' => 'Datenschutzerklärung', 'notice' => 'Impressum', 'terms' => 'Nutzungsbedingungen'],
        'hu' => ['privacy' => 'Adatkezelési tájékoztató', 'notice' => 'Impresszum', 'terms' => 'Felhasználási feltételek'],
    ];
@endphp
<span class="d-inline-flex flex-wrap justify-content-center gap-3 small">
    @foreach ($legalLinkLabels[$legalLinkLocale] as $legalDocument => $legalLabel)
        <a class="link-secondary" href="{{ route('legal.show', ['document' => $legalDocument, 'locale' => $legalLinkLocale]) }}">{{ $legalLabel }}</a>
    @endforeach
</span>
