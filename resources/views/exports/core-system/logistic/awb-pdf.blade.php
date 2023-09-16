<html>
<head>
  <title>{{ $awb->awb_no }}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: open-sans, sans-serif; font-size:0.25cm">
    <table style="width: 100%;">
      <tr>
        <td style="width: 15%; vertical-align: middle;">
          <img src="data:image/svg+xml;base64,{{ $logo }}" width="65" height="20"/>
        </td>
        <td style="width: 25%; text-align: left;">
          <div style="margin-bottom: 0.04cm;">eAWB</div>
          <div style="margin-bottom: 0.04cm;">Banana Xpress</div>
          <div style="margin-bottom: 0.04cm;">CS Hotline : -</div>
          <div style="margin-bottom: 0.04cm;font-size:0.2cm">{{ config('app.url') }}</div>
        </td>
        <td style="width: 20%; text-align: center;">
            <div style="margin-bottom: 0.04cm; font-weight: bold">Pieces/Jlm. Satuan</div>
            <div style="margin-bottom: 0.08cm;">{{ $awb->package_koli }}</div>
            <div style="margin-bottom: 0.04cm; font-weight: bold">Weight/Berat</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->package_weight }}</div>
        </td>
        <td style="width: 20%; text-align: center;">
            <div style="margin-bottom: 0.04cm;font-weight: bold">Shipment/Kiriman</div>
            <div style="margin-bottom: 0.08cm;">{{ $awb->shipmentType->name }}</div>
            <div style="margin-bottom: 0.04cm;font-weight: bold">Payment/Pembayaran</div>
            <div style="margin-bottom: 0.04cm;">-</div>
        </td>
        <td style="width: 20%; text-align: center;">
            <div style="margin-bottom: 0.04cm;font-weight: bold">Service/Layanan</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->service_id }}</div>
        </td>
      </tr>
    </table>
    <hr>

    <table style="width: 100%;">
        <tr>
          <td style="width: 40%; text-align: left;">
            <div style="margin-bottom: 0.04cm;">Pengirim : {{ $awb->origin_contact_name }}</div>
            <div style="margin-bottom: 0.04cm;">TGL PU : {{ $awb->created_at->toDatestring() }}</div>
          </td>
          <td style="width: 20%; text-align: center;font-weight: bold">
            <div style="margin-bottom: 0.04cm;">ORI : {{ $awb->originCity->code }}</div>
          </td>
          <td style="width: 20%; text-align: center;font-weight: bold">
            <div style="margin-bottom: 0.04cm;">DEST : {{ $awb->destinationCity->code }}</div>
          </td>
          <td style="width: 20%; text-align: center; font-weight: bold">
              <div style="margin-bottom: 0.04cm;">-</div>
          </td>
          <td style="width: 20%; text-align: center; font-weight: bold">
              <div style="margin-bottom: 0.05cm;">{{ $awb->destinationCity->name }}</div>
          </td>
        </tr>
      </table>
      <hr>

      <table style="width: 100%;">
        <tr>
          <td style="width: 40%; text-align: center; font-weight:bold">
            <div style="margin-bottom: 0.04cm;">TANDA TERIMA</div>
            <div style="margin-bottom: 0.04cm;"><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($awb->awb_no, 'C39+', 1, 33) }}" alt="barcode"\/></div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->awb_no }}</div>
          </td>
          <td style="width: 30%; text-align: center;font-weight: bold">
            <div style="margin-bottom: 0.04cm;">No Referensi :</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->ref_no }}</div>
          </td>
          <td style="width: 30%; text-align: center;font-weight: bold">
            <div style="margin-bottom: 0.04cm;">Banana XPress</div>
          </td>
        </tr>
      </table>
      <hr>
      <table style="width: 100%;">
        <tr>
          <td style="width: 50%; text-align: left;vertical-align: top;">
            <div style="margin-bottom: 0.04cm;">TUJUAN: {{ $awb->destination_contact_name }}</div>
            <div style="margin-bottom: 0.04cm;">TELEPON: {{ $awb->destination_contact_phone }}</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->destination_address_for_print }}</div>
            <br>
            <div style="margin-bottom: 0.04cm;">Attn : {{ $awb->destination_contact_name }}</div>
          </td>
          <td style="width: 50%; text-align: left;vertical-align: top;">
            <div style="margin-bottom: 0.04cm;">ALAMAT ALTERNATIF:</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->destination_alt_address_for_print }}</div>
            <div style="margin-bottom: 0.04cm;">TELEPON: {{ $awb->destination_alt_phone }}</div>
            <br>
            <div style="margin-bottom: 0.04cm;font-weight:bold;">Deskripsi:</div>
            <div style="margin-bottom: 0.04cm;">{{ $awb->package_desc_for_print }}</div>
          </td>
        </tr>
      </table>
</body>
</html>
