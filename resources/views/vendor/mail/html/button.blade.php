@props(['url', 'color' => 'primary', 'align' => 'center'])
@php
    $background = $color === 'error' ? '#ff879c' : '#b99aff';
@endphp
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 30px auto;">
    <tr>
        <td align="{{ $align }}">
            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center" bgcolor="{{ $background }}" style="background-color: {{ $background }}; border-radius: 9px;">
                        <a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener noreferrer" style="display: inline-block; background-color: {{ $background }}; border: 0; border-radius: 9px; padding: 15px 26px; color: #20142f !important; -webkit-text-fill-color: #20142f; font-size: 15px; font-weight: 700; line-height: 1.4; text-decoration: none !important; text-align: center;">
                            <span style="color: #20142f !important; -webkit-text-fill-color: #20142f;">{{ $slot }}</span>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>