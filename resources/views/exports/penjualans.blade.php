<div class="title" style="padding-bottom: 13px">
  <div style="text-align: center;text-transform: uppercase;font-size: 15px">
    {{ $toko->nama }}
  </div>
  <div style="text-align: center">
    Alamat: {{ $toko->alamat }}
  </div>
  <div style="text-align: center">
    Telp: {{ $toko->no_telepon }}
  </div>
</div>
<table style="width: 100%">
  <thead>
    <tr style="vertical-align: middle;">
      <th scope="col" style="background-color: #e6e6e7; vertical-align: middle;">Tanggal</th>
      <th scope="col" style="background-color: #e6e6e7; vertical-align: middle;">Faktur</th>
      <th scope="col" style="background-color: #e6e6e7; vertical-align: middle;">Kasir</th>
      <th scope="col" style="background-color: #e6e6e7; vertical-align: middle;">Pelanggan</th>
      <th scope="col" style="background-color: #e6e6e7; vertical-align: middle;">Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach($penjualans as $penjualan)
    <tr>
      <td style="vertical-align: middle;">{{ $penjualan->created_at }}</td>
      <td style="vertical-align: middle;">{{ $penjualan->faktur }}</td>
      <td style="vertical-align: middle;">{{ $penjualan->kasir->name ?? '' }}</td>
      <td style="vertical-align: middle;">{{ $penjualan->pelanggan->nama ?? 'Umum' }}</td>
      <td class="text-end" style="vertical-align: middle;">{{ formatPrice($penjualan->grand_total) }}</td>
    </tr>
    @endforeach
    <tr>
      <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7; vertical-align: middle;">TOTAL</td>
      <td class="text-end fw-bold" style="background-color: #e6e6e7; vertical-align: middle;">{{ formatPrice($total) }}</td>
    </tr>
  </tbody>
</table>
