<html moznomarginboxes mozdisallowselectionprint>

<head>
  <title>
    Nota Pembelian
  </title>
  <style type="text/css">
    html {
      font-family: "Verdana";
    }

    .content {
      width: 80mm;
      font-size: 10px;
      padding: 20px;
    }

    .content .title {
      text-align: center;
    }

    .content .head-desc {
      margin-top: 10px;
      display: table;
      width: 100%;
    }

    .content .head-desc>div {
      display: table-cell;
    }

    .content .head-desc .user {
      text-align: right;
    }

    .content .nota {
      text-align: center;
      margin-top: 5px;
      margin-bottom: 5px;
    }

    .content .separate {
      margin-top: 10px;
      margin-bottom: 15px;
      border-top: 1px dashed #000;
    }

    .content .transaction-table {
      width: 100%;
      font-size: 10px;
    }

    .content .transaction-table .name {
      /*//width: 185px;*/
    }

    .content .transaction-table .qty {
      /*//text-align: center;*/
      /*width: 65px;*/
    }

    .content .transaction-table .sell-price {
      /*//text-align: right;*/
      width: 65px;
      text-align: right;
    }

    .content .transaction-table .final-price {
      text-align: right;
    }

    .content .transaction-table tr td {
      vertical-align: top;
    }

    .content .transaction-table .price-tr td {
      padding-top: 7px;
      padding-bottom: 7px;
    }

    .content .transaction-table .discount-tr td {
      padding-top: 7px;
      padding-bottom: 7px;
    }

    .content .transaction-table .separate-line {
      height: 1px;
      border-top: 1px dashed #000;
    }

    .content .thanks {
      margin-top: 25px;
      text-align: center;
    }

    .content .azost {
      margin-top: 5px;
      text-align: center;
      font-size: 10px;
    }

    @media print {
      @page {
        width: 80mm;
        margin: 0mm;
      }
    }

  </style>
  <script>
    window.print();
    window.onafterprint = function() {
      setTimeout(function() {
        window.close();
      }, 1000);
    }

  </script>
</head>

<body>
  <div class="content">
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

    <div class="separate-line" style="border-top: 1px dashed #000;height: 1px;margin-bottom: 5px"></div>
    <table class="transaction-table" cellspacing="0" cellpadding="0">
      <tr>
        <td>TANGGAL</td>
        <td>:</td>
        <td>{{ $transaksi->created_at }}</td>
      </tr>
      <tr>
        <td>FAKTUR</td>
        <td>:</td>
        <td>{{ $transaksi->faktur }}</td>
      </tr>
      <tr>
        <td>KASIR</td>
        <td>:</td>
        <td>{{ $transaksi->kasir->name ?? '' }}</td>
      </tr>
      <tr>
        <td>PEMBELI</td>
        <td>:</td>
        <td>{{ $transaksi->pelanggan->nama ?? 'Umum' }}</td>
      </tr>
    </table>

    <div class="transaction">
      <table class="transaction-table" cellspacing="0" cellpadding="0">
        <tr class="price-tr">
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
        </tr>
        <tr>
          <td style="text-align: left">PRODUK (HARGA)</td>
          <td style="text-align: center">QTY</td>
          <td style="text-align: right" colspan="5">SUB TOTAL</td>
        </tr>
        <tr class="price-tr">
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
          <td colspan="3">
            <div class="separate-line" style="border-top: 1px dashed #000;"></div>
          </td>
        </tr>
        @foreach ($transaksi->details()->get() as $item)
        <tr>
          <td class='name'>{{ $item->produk->nama }} ({{ $item->produk->harga_jual }})</td>
          <td class='qty' style='text-align: center'>{{ $item->kuantitas }}</td>
          <td class='final-price' style='text-align: right' colspan="5">{{ formatPrice($item->sub_total) }}</td>
        </tr>
        @endforeach
        <tr class="price-tr">
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
        </tr>
        <tr>
          <td colspan="3" class="final-price">
            TOTAL
          </td>
          <td colspan="3" class="final-price">
            :
          </td>
          <td class="final-price">
            {{ formatPrice($transaksi->total) }}
          </td>
        </tr>
        <tr>
          <td colspan="3" class="final-price">
            DISKON
          </td>
          <td colspan="3" class="final-price">
            :
          </td>
          <td class="final-price">
            {{ formatPrice($transaksi->diskon) }}
          </td>
        </tr>
        <tr>
          <td colspan="3" class="final-price">
            GRAND TOTAL
          </td>
          <td colspan="3" class="final-price">
            :
          </td>
          <td class="final-price">
            {{ formatPrice($transaksi->grand_total) }}
          </td>
        </tr>

        <tr class="discount-tr">
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
          <td colspan="3">
            <div class="separate-line"></div>
          </td>
        </tr>

        <tr>
          <td colspan="3" class="final-price">
            TUNAI
          </td>
          <td colspan="3" class="final-price">
            :
          </td>
          <td class="final-price">
            {{ formatPrice($transaksi->uang_tunai) }}
          </td>
        </tr>
        <tr>
          <td colspan="3" class="final-price">
            KEMBALI
          </td>
          <td colspan="3" class="final-price">
            :
          </td>
          <td class="final-price">
            {{ formatPrice($transaksi->uang_kembali) }}
          </td>
        </tr>
      </table>
    </div>
    <div class="thanks">
      =====================================
    </div>
    <div class="azost" style="margin-top: 5px">
      {{ $toko->keterangan }} <br>
      TERIMA KASIH<br>
      ATAS KUNJUNGAN ANDA
    </div>
  </div>
</body>

</html>
