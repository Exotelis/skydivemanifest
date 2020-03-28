@props(['href'])

<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#ffffff" style="border:0px none;background-color:#ffffff">
    <tr>
        <td valign="top" style="padding:0px">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="padding:0px">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td valign="top" align="center" style="padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px">
                                    <!--[if !true]><div style="display:none;"><![endif]--><!--[if !mso]><!-- -->
                                    <a href="{{ $href }}" target="_blank" style="display:inline-block;text-decoration:none" class="full-width">
                                        <span>
                                            <table cellpadding="0" cellspacing="0" border="0" bgcolor="#00c281" style="border:0px none;border-radius:30px;border-collapse:separate !important;background-color:#00c281" class="full-width">
                                                <tr>
                                                    <td align="center" style="padding-top:15px;padding-right:25px;padding-bottom:15px;padding-left:25px">
                                                        <span style="color:#ffffff !important;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;mso-line-height:exactly;line-height:16px;mso-text-raise:1px">
                                                            <font color="#ffffff">
                                                                {{ $slot }}
                                                            </font>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </span>
                                    </a>
                                    <!--[if !true]></div><![endif]--><!--<![endif]-->
                                    <!--[if mso]>
                                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#00c281" style="border:0px none;border-radius:30px;border-collapse:separate !important;background-color:#00c281">
                                        <tr>
                                            <td align="center" style="padding-top:15px;padding-right:25px;padding-bottom:15px;padding-left:25px">
                                                <a href="{{ $href }}" target="_blank" style="color:#ffffff !important;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:12px;mso-line-height:exactly;line-height:14px;mso-text-raise:1px;text-decoration:none;text-align:center;">
                                                    <span style="color:#ffffff !important;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:14px;mso-line-height:exactly;line-height:16px;mso-text-raise:1px">
                                                        <font color="#ffffff">
                                                            {{ $slot }}
                                                        </font>
                                                    </span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
