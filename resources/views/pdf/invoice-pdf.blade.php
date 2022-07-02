<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice - #{{ $data->invoice }}</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .invoice td {
            text-align: center;
        }

        .information {
            /* background-color: #60A7A6; */
            background-color: #46546c;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }
    </style>

</head>

<body>

    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    <h3>{{ $data->klien->nama_lengkap }}</h3>
                    <pre>
{{ $data->klien->lokasi }}
{{ $data->klien->zip }} {{ $data->klien->kota }}
{{ $data->klien->negara }}
<br /><br />
Date: {{ $data->created_at }}
Identifier: #{{ $data->invoice }}
Type: {{ $data->kategoriPembayaran->value }}
Status: Paid
</pre>


                </td>
                <td align="center">
                    <!-- <img src="/path/to/logo.png" alt="Logo" width="64" class="logo"/> -->
                    Logo
                </td>
                <td align="right" style="width: 40%;">

                    <h3>CompanyName</h3>
                    <pre>
                    https://company.com

                    Street 26
                    123456 City
                    United Kingdom
                </pre>
                </td>
            </tr>

        </table>
    </div>


    <br />

    <div class="invoice">
        <h3>Invoice specification #{{ $data->invoice }}</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pembayaran</td>
                    <td>1</td>
                    <td>Rp. {{ number_format($data->nominal,2,',','.') }}</td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="1"></td>
                    <td>Total</td>
                    <td  class="gray">Rp. {{ number_format($data->nominal,2,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="information" style="position: absolute; bottom: 0;">
        <table width="100%">
            <tr>
                <td align="left" style="width: 50%;">
                    &copy; {{ date('Y') }} {{ config('app.url') }} - All rights reserved.
                </td>
                <td align="right" style="width: 50%;">
                    Company Slogan
                </td>
            </tr>

        </table>
    </div>
</body>

</html>