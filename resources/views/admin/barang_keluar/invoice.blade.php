<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $barangKeluar->nomor_keluar }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header .company {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .header .invoice-title {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }

        .company h2 {
            margin: 0 0 4px 0;
            font-size: 18px;
        }

        .company p {
            margin: 0;
            color: #555;
            font-size: 11px;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            color: #444;
        }

        .invoice-title p {
            margin: 2px 0;
            font-size: 11px;
        }

        .info-box {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .info-box .box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-box h4 {
            margin: 0 0 6px 0;
            font-size: 11px;
            text-transform: uppercase;
            color: #888;
        }

        .info-box p {
            margin: 0;
            line-height: 1.5;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.items th {
            background: #f5f5f5;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border-bottom: 2px solid #ddd;
        }

        table.items td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }

        table.items .text-end {
            text-align: right;
        }

        table.items .text-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .footer {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .footer .note {
            display: table-cell;
            width: 60%;
            font-size: 10px;
            color: #777;
            vertical-align: bottom;
        }

        .footer .signature {
            display: table-cell;
            width: 40%;
            text-align: center;
            vertical-align: top;
        }

        .signature p {
            margin: 0;
        }

        .signature .space {
            height: 60px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company">
            <h2>Sinar Bangkit</h2>
            <p>Alamat perusahaan, Kota, Kode Pos</p>
            <p>Telp: (0231) 000-0000 &middot; Email: info@perusahaan.com</p>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p><strong>No:</strong> {{ $barangKeluar->nomor_keluar }}</p>
            <p><strong>Tanggal:</strong> {{ $barangKeluar->tanggal->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="info-box">
        <div class="box">
            <h4>Kepada Yth.</h4>
            <p><strong>{{ $barangKeluar->nama_penerima ?? ($barangKeluar->tujuan ?? '-') }}</strong></p>
            @if ($barangKeluar->tujuan)
                <p>{{ $barangKeluar->tujuan }}</p>
            @endif
            @if ($barangKeluar->alamat_penerima)
                <p>{{ $barangKeluar->alamat_penerima }}</p>
            @endif
            @if ($barangKeluar->no_telepon_penerima)
                <p>Telp: {{ $barangKeluar->no_telepon_penerima }}</p>
            @endif
        </div>
        <div class="box">
            <h4>Detail Transaksi</h4>
            <p>Dicatat oleh: {{ $barangKeluar->user?->name ?? '-' }}</p>
            @if ($barangKeluar->keterangan)
                <p>Keterangan: {{ $barangKeluar->keterangan }}</p>
            @endif
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th style="width:35%">Nama Barang</th>
                <th style="width:15%">Satuan</th>
                <th class="text-center" style="width:10%">Qty</th>
                <th class="text-end" style="width:15%">Harga Satuan</th>
                <th class="text-end" style="width:20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangKeluar->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $detail->barang->satuan?->nama_satuan ?? '-' }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-end">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($barangKeluar->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="note">
            <p>Invoice ini dibuat secara otomatis oleh sistem dan sah tanpa tanda tangan basah.</p>
        </div>
        <div class="signature">
            <p>Hormat kami,</p>
            <div class="space"></div>
            <p>( {{ $barangKeluar->user?->name ?? '.....................' }} )</p>
        </div>
    </div>

</body>

</html>
