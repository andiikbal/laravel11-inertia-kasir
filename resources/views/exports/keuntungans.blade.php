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
    <tr style="background-color: #e6e6e7;">
      <th scope="col">Tanggal</th>
      <th scope="col">Faktur</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach($keuntungans as $keuntungan)
    <tr>
      <td>{{ $keuntungan->created_at }}</td>
      <td>{{ $keuntungan->transaksi->faktur }}</td>
      <td class="text-end">{{ formatPrice($keuntungan->total) }}</td>
    </tr>
    @endforeach
    <tr>
      <td colspan="2" class="text-end fw-bold" style="background-color: #e6e6e7;">TOTAL</td>
      <td class="text-end fw-bold" style="background-color: #e6e6e7;">{{ formatPrice($total) }}</td>
    </tr>
  </tbody>
</table>
