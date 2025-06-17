<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Print Data Aset</title>

    <style>
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path("assets/fonts/PlusJakartaSans/Fonts/OTF/PlusJakartaSans-Regular.otf") }}') format('truetype');
        }
        @font-face {
            font-family: 'Plus Jakarta Sans SemiBold';
            font-style: medium;
            font-weight: 600;
            src: url('{{ public_path("assets/fonts/PlusJakartaSans/Fonts/OTF/PlusJakartaSans-SemiBold.otf") }}') format('truetype');
        }
        @page { 
            margin:0px;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Plus Jakarta Sans SemiBold', sans-serif;
        }

        .page-break {
            page-break-after: always;
        }
        
        table, th, td {
            /* border: 1px solid black; */
        }

        .tb_bordered {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .tb_bordered td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .qrcode {
            position: fixed;
            bottom: 35px;
            left: -20px;
        }
    </style>

    <style>
        .container {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-0.5 * var(--bs-gutter-x));
            margin-left: calc(-0.5 * var(--bs-gutter-x));
        }
        .col {
            flex: 1 0 0;
        }

        .col-3 {
            flex: 0 0 auto;
            max-width: 25%;
        }
        .col-6 {
            flex: 0 0 auto;
            max-width: 50%;
        }
        .col-9 {
            flex: 0 0 auto;
            max-width: 75%;
        }
        .col-12 {
            flex: 0 0 auto;
            max-width: 100%;
        }

        .row-cols-3 > * {
            flex: 0 0 auto;
            width: 33.33333333%;
        }
        .row-cols-6 > * {
            flex: 0 0 auto;
            width: 16.66666667%;
        }
    </style>
</head>
<body>
    @if ($size == 'big')
        @if (count($lists) > 18)
            @foreach (collect($lists)->chunk(16) as $items)
                <table style="width: 100%">
                    @foreach (collect($items)->chunk(2) as $item)
                        <tr>
                            @foreach ($item as $value)
                            <td style="width: 50%;" class="tb_bordered">
                                <div class="">
                                    <table style="width: 100%">
                                        <tr style="margin: 15px;">
                                            <td style="width: 20%; border: 0.6px solid black; vertical-align: top; text-align: center; padding: 0.5em;">
                                                <img src="{{ $logo }}" alt="logo" style="height: auto; width: 27px;">
                                            <br>
                                            <label style="font-size: 3pt;">&nbsp;<br></label>
                                            <img src="data:image/png;base64, {{ $value->qrcode }}" alt="" style="max-width: 60px;">
                                            <br>
                                            {{-- <label style="font-size: 8pt; font-weight: 600;">
                                                {{ $value->tahun_beli }}
                                            </label> --}}
                                            </td>
                                            <td style="width: 80%; border: 0px solid black; vertical-align: center; text-align: center; font-weight: 600; font-size: 10pt; padding: 0.5em;">
                                            <label>
                                                PEMERINTAH KABUPATEN KARANGANYAR
                                                <br>
                                                DINAS KOMUNIKASI DAN INFORMATIKA
                                                <br>
                                            </label>
                                            <label style="font-size: 5pt;">&nbsp;<br></label>
                                            <label style="">
                                                {{ $value->kode }} / {{ $value->tahun_beli }}
                                                <br>
                                                {{ $value->nama_barang }}
                                                <br>
                                            </label>
                                            <label style="font-size: 3pt;">&nbsp;<br></label>
                                            <label style="font-size: 7.5pt;">
                                                {{ !empty($value->merek_barang) ? ($value->merek_barang . ', ') : '' }}{{ !empty($value->type_barang) ? ($value->type_barang . ', ') : '' }}{{ !empty($value->lokasi) ? ($value->lokasi) : '' }}
                                                <br>
                                            </label>
                                        </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
                <div class="page-break"></div>
            @endforeach
        @else
            <table style="width: 100%">
                @foreach (collect($lists)->chunk(2) as $i => $items)
                    <tr>
                        @foreach ($items as $item)
                        <td style="width: 50%;" class="tb_bordered">
                            <div class="">
                                <table style="width: 100%">
                                    <tr style="margin: 15px;">
                                        <td style="width: 20%; border: 0px solid black; vertical-align: top; text-align: center; padding: 0.5em;">
                                            <img src="{{ $logo }}" alt="logo" style="height: auto; width: 27px;">
                                            <br>
                                            <label style="font-size: 3pt;">&nbsp;<br></label>
                                            <img src="data:image/png;base64, {{ $item->qrcode }}" alt="" style="max-width: 60px;">
                                            <br>
                                            {{-- <label style="font-size: 8pt; font-weight: 600;">
                                                {{ $item->tahun_beli }}
                                            </label> --}}
                                        </td>
                                        <td style="width: 80%; border: 0px solid black; vertical-align: center; text-align: center; font-weight: 600; font-size: 10pt; padding: 0.5em;">
                                            <label>
                                                PEMERINTAH KABUPATEN KARANGANYAR
                                                <br>
                                                DINAS KOMUNIKASI DAN INFORMATIKA
                                                <br>
                                            </label>
                                            <label style="font-size: 5pt;">&nbsp;<br></label>
                                            <label style="">
                                                {{ $item->kode }} / {{ $item->tahun_beli }}
                                                <br>
                                                {{ $item->nama_barang }}
                                                <br>
                                            </label>
                                            <label style="font-size: 3pt;">&nbsp;<br></label>
                                            <label style="font-size: 7.5pt;">
                                                {{ !empty($item->merek_barang) ? ($item->merek_barang . ', ') : '' }}{{ !empty($item->type_barang) ? ($item->type_barang . ', ') : '' }}{{ !empty($item->lokasi) ? ($item->lokasi) : '' }}
                                                <br>
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        @endforeach
                        @if(count($lists) == 1)
                        <td style="width: 50%;" class="tb_bordered">
                            &nbsp;
                        </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif

    @elseif ($size == 'small')

        @if (count($lists) > 12)
            @foreach (collect($lists)->chunk(39) as $items)
                <table style="width: 100%">
                    @foreach (collect($items)->chunk(3) as $item)
                        <tr>
                            @foreach ($item as $value)
                            <td style="width: {{ 100/3 }}%;" class="tb_bordered">
                                <div class="">
                                    <table style="width: 100%">
                                        <tr style="margin: 20px;">
                                            <td style="width: 20%; border-top: 0px solid black; border-left: 0px solid black; border-bottom: 0px solid black; border-right: 0px solid black; vertical-align: top; text-align: center; padding: 0.5em;">
                                                <img src="data:image/png;base64, {{ $value->qrcode }}" alt="" style="max-width: 55px;">
                                            </td>
                                            <td style="width: 80%; border: 0px solid black; vertical-align: center; text-align: center; font-weight: 600; font-size: 6pt; padding: 0.5em;">
                                                <label style="margin-bottom: 5px;">
                                                    PEMERINTAH KABUPATEN KARANGANYAR
                                                    <br>
                                                    DINAS KOMUNIKASI DAN INFORMATIKA
                                                </label>
                                                <label>
                                                    {{ $value->kode }}
                                                    <br>
                                                    {{ $value->nama_barang }}
                                                    <br>
                                                </label>
                                                <label style="font-size: 5pt;">
                                                    {{ !empty($value->merek_barang) ? ($value->merek_barang . ', ') : '' }}{{ !empty($value->type_barang) ? ($value->type_barang . ', ') : '' }}{{ !empty($value->lokasi) ? ($value->lokasi) : '' }}
                                                    <br>
                                                </label>
                                                <label style="font-size: 5.8pt; font-weight: 600;">
                                                    {{ $value->tahun_beli }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
                <div class="page-break"></div>
            @endforeach
        @else
            <table style="width: 100%">
                @foreach (collect($lists)->chunk(3) as $i => $items)
                    <tr>
                        @foreach ($items as $item)
                        <td style="width: {{ 100/3 }}%;" class="tb_bordered">
                            <div class="">
                                <table style="width: 100%">
                                    <tr style="margin: 20px;">
                                        <td style="width: 20%; border-top: 0px solid black; border-left: 0px solid black; border-bottom: 0px solid black; border-right: 0px solid black; vertical-align: top; text-align: center; padding: 0.5em;">
                                            <img src="data:image/png;base64, {{ $item->qrcode }}" alt="" style="max-width: 55px;">
                                        </td>
                                        <td style="width: 80%; border: 0px solid black; vertical-align: center; text-align: center; font-weight: 600; font-size: 6pt; padding: 0.5em;">
                                            <label style="margin-bottom: 5px;">
                                                PEMERINTAH KABUPATEN KARANGANYAR
                                                <br>
                                                DINAS KOMUNIKASI DAN INFORMATIKA
                                            </label>
                                            <label>
                                                {{ $item->kode }}
                                                <br>
                                                {{ $item->nama_barang }}
                                                <br>
                                            </label>
                                            <label style="font-size: 5pt;">
                                                {{ !empty($item->merek_barang) ? ($item->merek_barang . ', ') : '' }}{{ !empty($item->type_barang) ? ($item->type_barang . ', ') : '' }}{{ !empty($item->lokasi) ? ($item->lokasi) : '' }}
                                                <br>
                                            </label>
                                            <label style="font-size: 5.8pt; font-weight: 600;">
                                                {{ $item->tahun_beli }}
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        @endforeach
                        @if (count($lists) == 1)
                        <td style="width: {{ 100/3 }}%;" class="tb_bordered">
                            &nbsp;
                        </td>
                        <td style="width: {{ 100/3 }}%;" class="tb_bordered">
                            &nbsp;
                        </td>
                        @elseif (count($lists) == 2)
                        <td style="width: {{ 100/3 }}%;" class="tb_bordered">
                            &nbsp;
                        </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endif
    @endif
</body>
</html>