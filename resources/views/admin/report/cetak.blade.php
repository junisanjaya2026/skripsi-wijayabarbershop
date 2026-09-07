<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Laporan Order</title>

<style>
    * {
        box-sizing: border-box;
    }

    @page {
        size: A4;
        margin: 18mm 15mm 18mm 15mm;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #4746af;
        margin: 0;
        background: #fff;
    }

    /* =========================
       BUTTON AREA
    ========================= */

    .no-print {
        margin-bottom: 20px;
        display: flex;
        gap: 8px;
    }

    .btn {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 4px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-print {
        background: #4746af;
        color: #fff;
    }

    .btn-back {
        background: #eee;
        color: #4746af;
    }

    /* =========================
       REPORT HEADER
    ========================= */

    .report-header {
        text-align: center;
        border-bottom: 2px solid #4746af;
        padding-bottom: 12px;
        margin-bottom: 15px;
    }

    .report-header h1 {
        margin: 0;
        font-size: 19px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .report-header h2 {
        margin: 5px 0 0;
        font-size: 13px;
        font-weight: normal;
        color: #555;
    }

    /* =========================
       REPORT INFORMATION
    ========================= */

    .report-info {
        width: 100%;
        margin-bottom: 15px;
    }

    .report-info table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .report-info td {
        border: none;
        padding: 3px 0;
        vertical-align: top;
    }

    .report-info .label {
        width: 125px;
        color: #555;
    }

    .report-info .separator {
        width: 10px;
    }

    .report-info .value {
        font-weight: bold;
    }

    /* =========================
       PERIOD
    ========================= */

    .period-box {
        border: 1px solid #bbb;
        padding: 9px 12px;
        margin-bottom: 18px;
        background: #f8f8f8;
    }

    .period-box table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .period-box td {
        border: none;
        padding: 2px 0;
    }

    .period-label {
        width: 125px;
        font-weight: bold;
    }

    /* =========================
       SUMMARY
    ========================= */

    .summary {
        margin-bottom: 15px;
    }

    .summary table {
        width: 100%;
        border-collapse: collapse;
    }

    .summary td {
        border: 1px solid #ccc;
        padding: 8px 10px;
    }

    .summary-label {
        width: 60%;
        font-weight: bold;
        background: #f5f5f5;
    }

    .summary-value {
        text-align: right;
        font-weight: bold;
    }

    /* =========================
       MAIN TABLE
    ========================= */

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    .order-table th {
        background: #4746af;
        color: #fff;
        border: 1px solid #4746af;
        padding: 8px 7px;
        font-size: 10px;
        text-align: center;
        vertical-align: middle;
    }

    .order-table td {
        border: 1px solid #bbb;
        padding: 7px;
        vertical-align: middle;
    }

    .order-table tbody tr:nth-child(even) {
        background: #fafafa;
    }

    .order-table .center {
        text-align: center;
    }

    .order-table .right {
        text-align: right;
        white-space: nowrap;
    }

    .order-table tfoot th {
        background: #f0f0f0;
        color: #4746af;
        border: 1px solid #999;
        padding: 9px 7px;
    }

    /* =========================
       SIGNATURE
    ========================= */

    .signature-wrapper {
        width: 100%;
        margin-top: 40px;
    }

    .signature {
        width: 220px;
        margin-left: auto;
        text-align: center;
    }

    .signature .date {
        margin-bottom: 55px;
    }

    .signature .name {
        font-weight: bold;
        text-decoration: underline;
    }

    /* =========================
       FOOTER
    ========================= */

    .report-footer {
        margin-top: 25px;
        padding-top: 8px;
        border-top: 1px solid #ccc;
        font-size: 9px;
        color: #777;
        text-align: center;
    }

    /* =========================
       PRINT
    ========================= */

    @media print {

        body {
            font-size: 10.5px;
        }

        .no-print {
            display: none !important;
        }

        .order-table thead {
            display: table-header-group;
        }

        .order-table tr {
            page-break-inside: avoid;
        }

        .report-header {
            margin-top: 0;
        }

        .period-box {
            background: #fff;
        }

        .order-table th {
            background: #4746af !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .order-table tbody tr:nth-child(even) {
            background: #fafafa !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>


</head>

<body>


{{-- =========================
     BUTTON
========================== --}}

<div class="no-print">
    <button class="btn btn-print" onclick="window.print()">
        Cetak Laporan
    </button>

    <a href="{{ route('admin.report.index') }}" class="btn btn-back">
        Kembali
    </a>
</div>


{{-- =========================
     HEADER
========================== --}}

<div class="report-header">

    <h1>Laporan Order</h1>

    <h2>Wijaya Barbershop</h2>

</div>


{{-- =========================
     INFORMASI LAPORAN
========================== --}}

<div class="report-info">

    <table>

        <tr>
            <td class="label">
                Penanggung Jawab
            </td>

            <td class="separator">
                :
            </td>

            <td class="value">
                {{ auth()->user()->fullname ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Tanggal Cetak
            </td>

            <td class="separator">
                :
            </td>

            <td>
                {{ now()->format('d-m-Y H:i') }}
            </td>
        </tr>

    </table>

</div>


{{-- =========================
     PERIODE LAPORAN
========================== --}}

<div class="period-box">

    <table>

        <tr>

            <td class="period-label">
                Periode Laporan
            </td>

            <td>

                @if (request()->filled('start_date') && request()->filled('end_date'))

                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}

                    &nbsp; s/d &nbsp;

                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}

                @elseif (request()->filled('start_date'))

                    Mulai
                    {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}

                @elseif (request()->filled('end_date'))

                    Sampai
                    {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}

                @else

                    Semua Periode

                @endif

            </td>

        </tr>

        @if (request()->filled('payment_method'))

            <tr>

                <td class="period-label">
                    Metode Pembayaran
                </td>

                <td>
                    {{ ucfirst(request('payment_method')) }}
                </td>

            </tr>

        @endif

    </table>

</div>


{{-- =========================
     RINGKASAN
========================== --}}

<div class="summary">

    <table>

        <tr>

            <td class="summary-label">
                Jumlah Order Settlement
            </td>

            <td class="summary-value">
                {{ $orders->count() }} Order
            </td>

        </tr>

        <tr>

            <td class="summary-label">
                Total Pendapatan
            </td>

            <td class="summary-value">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </td>

        </tr>

    </table>

</div>


{{-- =========================
     DETAIL ORDER
========================== --}}

<table class="order-table">

    <thead>

        <tr>

            <th style="width: 35px;">
                No
            </th>

            <th style="width: 100px;">
                Kode Order
            </th>

            <th>
                Customer
            </th>

            <th style="width: 105px;">
                Tanggal
            </th>

            <th style="width: 90px;">
                Metode Bayar
            </th>

            <th style="width: 120px;">
                Total
            </th>

        </tr>

    </thead>


    <tbody>

        @forelse ($orders as $index => $order)

            <tr>

                <td class="center">
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $order->order_code ?? $order->id }}
                </td>

                <td>
                    {{ $order->user->fullname ?? '-' }}
                </td>

                <td class="center">
                    {{ $order->created_at->format('d-m-Y H:i') }}
                </td>

                <td class="center">
                    {{ ucfirst($order->payment_method) }}
                </td>

                <td class="right">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6" class="center">
                    Tidak ada data order pada periode yang dipilih.
                </td>

            </tr>

        @endforelse

    </tbody>


    <tfoot>

        <tr>

            <th colspan="5" style="text-align:right;">
                TOTAL KESELURUHAN
            </th>

            <th class="right">
                Rp {{ number_format($totalAmount, 0, ',', '.') }}
            </th>

        </tr>

    </tfoot>

</table>


{{-- =========================
     TANDA TANGAN
========================== --}}

<div class="signature-wrapper">

    <div class="signature">

        <div class="date">
            {{ now()->format('d F Y') }}
        </div>

        <div class="name">
            {{ auth()->user()->fullname ?? '-' }}
        </div>

        <div>
            Penanggung Jawab
        </div>

    </div>

</div>


{{-- =========================
     FOOTER
========================== --}}

<div class="report-footer">

    Laporan ini dibuat secara otomatis oleh sistem.

</div>


<script>
    window.onload = function () {
        window.print();
    };
</script>


</body>

</html>
