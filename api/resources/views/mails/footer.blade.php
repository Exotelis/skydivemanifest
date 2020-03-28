<x-mails.spacer/>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td valign="top" style="padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px">
            <div style="text-align:left;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;color:#ffffff;line-height:22px;mso-line-height:exactly;mso-text-raise:4px">

                <p style="padding: 0; margin: 0;text-align: center;">

                    <strong>
                        {{ __('mails.greeting', ['appname' => appName()]) }}
                    </strong>

                </p>

                @if (! is_null(appSupportMail()) && ! empty(appSupportMail()))
                <p style="padding: 0; margin: 0;text-align: center;">

                    <a href="mailto:{{ appSupportMail() }}" target="_blank" style="color: #00C281 !important; text-decoration: underline !important;">
                        <font style=" color:#00C281;"><strong>{{ appSupportMail() }}</strong></font>
                    </a>

                </p>
                @endif

            </div>
        </td>
    </tr>
</table>
