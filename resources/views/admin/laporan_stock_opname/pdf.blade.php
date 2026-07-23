<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stock Opname</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #222;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
        }

        .sub {
            color: #666;
            font-size: 11px;
            margin-bottom: 16px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }

        .summary-grid .row {
            display: table-row;
        }

        .summary-grid .cell {
            display: table-cell;
            width: 25%;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .summary-grid .label {
            font-size: 9px;
            color: #777;
            text-transform: uppercase;
        }

        .summary-grid .value {
            font-size: 13px;
            font-weight: bold;
            margin-top: 2px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table.items th {
            background: #f0f0f0;
            padding: 6px;
            text-align: left;
            font-size: 10px;
            border-bottom: 1px solid #ccc;
        }

        table.items td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        table.items .text-end {
            text-align: right;
        }

        h3 {
            font-size: 13px;
            margin: 18px 0 6px 0;
            border-bottom: 2px solid #333;
            padding-bottom: 4px;
        }
    </style>
</head>

<body>

    <h1>Laporan Stock Opname</h1>
    <div class="sub">Periode: {{ $start->format('d/m/Y') }} — {{ $end->format('d/m/Y') }} &middot; Dicetak:
        {{ now()->format('d/m/Y H:i') }}</div>

    <div class="summary-grid">
        <div class="row">
            <div class="cell">
                <div class="label">Total Pendapatan</div>
                <div class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="cell">
                <div class="label">Modal Pembelian</div>
                <div class="value">Rp {{ number_format($totalModalPembelian, 0, ',', '.') }}</div>
            </div>
            <div class="cell">
                <div class="label">HPP (Estimasi)</div>
                <div class="value">Rp {{ number_format($totalHpp, 0, ',', '.') }}</div>
            </div>
            <div class="cell">
                <div class="label">Laba Kotor (Estimasi)</div>
                <div class="value">Rp {{ number_format($labaKotor, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <div class="label">Transaksi Masuk</div>
                <div class="value">{{ $jumlahTransaksiMasuk }}</div>
            </div>
            <div class="cell">
                <div class="label">Transaksi Keluar</div>
                <div class="value">{{ $jumlahTransaksiKeluar }}</div>
            </div>
            <div class="cell">
                <div class="label">Qty Barang Masuk</div>
                <div class="value">{{ number_format($totalQtyMasuk, 0, ',', '.') }}</div>
            </div>
            <div class="cell">
                <div class="label">Qty Barang Keluar</div>
                <div class="value">{{ number_format($totalQtyKeluar, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="row">
            <div class="cell">
                <div class="label">Nilai Stok Saat Ini</div>
                <div class="value">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</div>
            </div>
            <div class="cell">
                <div class="label">Barang Stok Kritis</div>
                <div class="value">{{ $jumlahBarangKritis }} item</div>
            </div>
            <div class="cell">
                <div class="label">Selisih Opname (+/-)</div>
                <div class="value">+{{ $selisihPlus }} / {{ $selisihMinus }}</div>
            </div>
            <div class="cell">
                <div class="label">Jumlah Opname</div>
                <div class="value">{{ $jumlahOpname }} kali</div>
            </div>
        </div>
    </div>

    <h3>5 Barang Terlaris</h3>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th class="text-end">Qty Terjual</th>
                <th class="text-end">Total Omzet</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($barangTerlaris as $i => $bt)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $bt->nama_barang }}</td>
                    <td class="text-end">{{ $bt->total_qty }}</td>
                    <td class="text-end">Rp {{ number_format($bt->total_omzet, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Detail Stock Opname</h3>
    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th class="text-end">Stok Sistem</th>
                <th class="text-end">Stok Fisik</th>
                <th class="text-end">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detailOpname as $i => $so)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $so->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $so->barang?->nama_barang ?? '-' }}</td>
                    <td class="text-end">{{ $so->stok_sistem }}</td>
                    <td class="text-end">{{ $so->stok_fisik }}</td>
                    <td class="text-end">{{ $so->selisih > 0 ? '+' . $so->selisih : $so->selisih }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
