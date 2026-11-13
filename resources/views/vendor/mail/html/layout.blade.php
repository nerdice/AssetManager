<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <style>
        :root {
            color-scheme: dark;
            supported-color-schemes: dark;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #121018;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table {
            mso-table-lspace: 0;
            mso-table-rspace: 0;
        }
        .inner-body {
            width: 570px;
            max-width: 570px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                padding-right: 16px !important;
                padding-left: 16px !important;
            }
            .inner-body, .footer {
                width: 100% !important;
            }
            .inner-body .content-cell {
                padding: 28px 22px !important;
            }
            .header {
                padding: 32px 12px 24px !important;
            }
            h1 {
                font-size: 23px !important;
            }
        }
        @media only screen and (max-width: 500px) {
            .action {
                width: 100% !important;
            }
            .button {
                box-sizing: border-box !important;
                width: 100% !important;
                text-align: center !important;
            }
        }
    </style>
    {!! $head ?? '' !!}
</head>
<body style="margin: 0; padding: 0; background-color: #121018; color: #eeeaf5;">
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" bgcolor="#121018" style="background-color: #121018;">
        <tr>
            <td height="4" bgcolor="#b99aff" style="height: 4px; background-color: #b99aff; font-size: 0; line-height: 0;">&nbsp;</td>
        </tr>
        <tr>
            <td class="email-container" align="center" style="padding: 0 24px;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color: #121018;">
                    {!! $header ?? '' !!}
                    <tr>
                        <td class="body" width="100%" align="center" style="background-color: #121018; border: 0;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" border="0" role="presentation" bgcolor="#1b1724" style="width: 570px; max-width: 570px; background-color: #1b1724; border: 1px solid #393044; border-top: 3px solid #b99aff; border-radius: 16px;">
                                <tr>
                                    <td class="content-cell" style="padding: 36px 30px;">
                                        {!! Illuminate\Mail\Markdown::parse($slot) !!}
                                        {!! $subcopy ?? '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {!! $footer ?? '' !!}
                </table>
            </td>
        </tr>
    </table>
</body>
</html>