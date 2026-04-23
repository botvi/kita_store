<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan - Toko KOJAR</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            margin: 0; 
            padding: 40px; 
            color: #111; 
            font-size: 13px;
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #111; 
            padding-bottom: 20px; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0 0 5px 0; 
            font-size: 28px; 
            font-weight: 800;
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .header p { 
            margin: 3px 0; 
            color: #444; 
            font-size: 14px;
        }
        .sub-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .sub-header h3 {
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
        }
        table th, table td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        table th { 
            background-color: #f8f9fa; 
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            color: #555;
        }
        table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row td { 
            font-weight: bold; 
            background-color: #f8f9fa; 
            font-size: 14px;
        }
        .signature { 
            margin-top: 50px; 
            text-align: right; 
        }
        .signature-box { 
            display: inline-block; 
            text-align: left; 
            margin-right: 20px; 
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>Toko KOJAR</h1>
        <p>Kuansing, Riau</p>
        <p>Telp / WA: +62877 36977344</p>
    </div>

    <div class="sub-header">
        <h3>Laporan Penjualan (Selesai/Lunas)</h3>
        <p>Periode: <strong>{{ $start_date->format('d M Y') }}</strong> s/d <strong>{{ $end_date->format('d M Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Order ID</th>
                <th width="20%">Nama Pelanggan</th>
                <th width="30%">Rincian Pembelian</th>
                <th class="text-right" width="15%">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $e => $pesanan)
            <tr>
                <td class="text-center">{{ $e + 1 }}</td>
                <td>{{ $pesanan->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $pesanan->order_id }}</td>
                <td>{{ $pesanan->user->name ?? 'User Dihapus' }}</td>
                <td>
                    @php
                        $details = is_string($pesanan->produk_id) ? json_decode($pesanan->produk_id, true) : $pesanan->produk_id;
                    @endphp
                    @if(is_array($details))
                        <ul style="margin:0; padding-left:15px; color:#444;">
                        @foreach($details as $d)
                            <li>{{ $d['nama_produk'] }} (Qty: {{ $d['qty'] }})</li>
                        @endforeach
                        </ul>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada rekap transaksi yang lunas di periode ini.</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN BULANAN (PERIODE TERPILIH)</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-box">
            <p style="margin-bottom: 70px;">Kuansing, {{ \Carbon\Carbon::now()->format('d M Y') }}<br>Hormat Kami,<br><strong>Admin Toko KOJAR</strong></p>
            <p style="border-top: 1px solid #111; padding-top: 5px; text-align: center;">Tanda Tangan & Nama Terang</p>
        </div>
    </div>

</body>
</html>
