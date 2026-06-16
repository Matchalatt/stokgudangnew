<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 0; color: #666; font-size: 13px; }
        .meta-info { margin-bottom: 15px; width: 100%; }
        .meta-info td { vertical-align: top; }
        .report-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .report-table th { background-color: #f2f2f2; text-align: left; font-weight: bold; border: 1px solid #ddd; padding: 8px; text-transform: uppercase; font-size: 11px; }
        .report-table td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: Courier, monospace; font-size: 11px; }
        .badge-danger { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Riwayat Barang Keluar (Outbound)</h2>
        <p>Sistem Manajemen Inventaris</p>
    </div>

    <table class="meta-info">
        <tr>
            <td>
                <strong>Periode Laporan:</strong> 
                {{ $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($endDate)->format('d M Y') : 'Semua Riwayat Data' }}
            </td>
            <td class="text-right">
                <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }}
            </td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5px;">No</th>
                <th style="width: 85px;" class="text-center">Tanggal Keluar</th>
                <th style="width: 110px;">Referensi / Plat</th>
                <th>Nama Barang</th>
                <th class="text-right" style="width: 90px;">Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outbounds as $index => $outbound)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($outbound->tanggal_fisik)->format('d M Y') }}</td>
                    <td class="font-mono">{{ $outbound->reference ?? '-' }}</td>
                    <td><strong>{{ $outbound->item->nama ?? 'Barang Tidak Ditemukan' }}</strong></td>
                    <td class="text-right"><span class="badge-danger">-{{ $outbound->qty_base }}</span> <small>{{ $outbound->item->base_unit ?? '' }}</small></td>
                    <td>{{ $outbound->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #777;">Tidak ada riwayat transaksi barang keluar dalam periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>