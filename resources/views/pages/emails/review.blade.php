<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
    <style>
        .myButton {
            background-color: #44c767;
            border-radius: 4px;
            border: 1px solid #18ab29;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 17px;
            padding: 6px 32px;
            text-decoration: none;
            text-shadow: 0px 1px 0px #2f6627;
        }

        .myButton:hover {
            background-color: #5cbf2a;
        }

        .myButton:active {
            position: relative;
            top: 1px;
        }

    </style>
</head>
<body>
    <table style="width: 80%; margin: auto; border:1px solid #095d2d; border-radius: 5px;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="text-align: center;border-top: 5px solid #095d2d;">
                    <img width="200px" height="60px" src="{{ env('APP_URL') }}/public/assets/images/logo.png">
                    <p><span style="color:#095d2d; font-size:16px;"><strong>{{ config('app.name') }}</strong></span></p>
                </td>
            </tr>
            <tr>
                <td style="border-top: 2px solid #fdd700;font-size: 12px;">
                    <p>{{ $details['msg'] }}, silahkan klik link dibawah ini. </p>
                    <p style="text-align:center;">
                    <a style="background-color: #095d2d;
                        border-radius: 4px;border: 1px solid #18ab29;display: inline-block;cursor: pointer;color: #ffffff;font-size: 17px;padding: 6px 32px;text-decoration: none;text-shadow: 0px 1px 0px #2f6627;" href="{{ env('APP_URL') }}">Klik Disini</a></p>
                    <p>Abaikan pesan ini jika sudah selesai terimakasih atas perhatiannya.</p>
                </td>
            </tr>
            <tr>
                <td style="background-color: #095d2d; padding: 5px; color:#ffffff !important; font-size: 10px;">
                    <p style="font-weight:bold;">{{ config('data.client_name') }}</p>
                    <p>{{ config('data.address') }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
