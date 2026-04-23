<div class="dt-responsive table-responsive">
    <table class="table table-striped table-bordered nowrap datatable" style="width:100%">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Order ID</th>
                <th>Nama Pelanggan</th>
                <th>Total Harga</th>
                <th>Status Bayar</th>
                <th>Status Pengiriman</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pesanans as $item)
                @php 
                    $matchStatus = $item->status == $status_filter;
                    $matchPengiriman = $pengiriman_filter == null || $item->status_pengiriman == $pengiriman_filter;
                @endphp
                
                @if($matchStatus && $matchPengiriman)
                    <tr>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td><strong>{{ $item->order_id }}</strong></td>
                        <td>{{ $item->user->name ?? 'User Dihapus' }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($item->status == 'UNPAID')
                                <span class="badge bg-warning">Belum Bayar</span>
                            @else
                                <span class="badge bg-success">LUNAS</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary text-white">{{ $item->status_pengiriman }}</span></td>
                        <td>
                            <a href="{{ route('pesanan.show', $item->id) }}" class="btn btn-sm btn-info">Detail / Proses</a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
