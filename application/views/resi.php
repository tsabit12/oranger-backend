<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
table, td, th {
  border: 1px solid black;
}

table {
  border-collapse: collapse;
  width: 100%;
}
p {
  font-size: 10px;
}

</style>
</head>
<body>
    <?php
    function rupiah($angka)
    {
        $hasil_rupiah = "" . number_format($angka, 0, '.', '.');
        return $hasil_rupiah;
    }
    ?>
    <table>
        <tr>
          <th style="width:66.66666%;text-align:left;font-size:10pt;height: -10px;padding-left:10px;" colspan="3"><?php echo $record['productname'];?></th>
        </tr>
        <tr>
          <td style="width:66.66666%;text-align:center;" colspan="2"><img style="width:50%; height: 50px;" src="<?php echo base_url()?>/barcode?extid=<?php echo $record['extid'];?>" alt=""></td>
          <th style="width:33.33333%;text-align:center;">
            <img style="width:75%; height: 75px;" src="https://sampoerna.posindonesia.co.id/images/093e67893f52a7b3f51b5577e82b0b5c_1.jpeg" alt="" />
          </th>
        </tr>
        <tr>
          <td colspan="3" style="padding-left:10px;">Ref.Pengirim : 0</td>
        </tr>
        <tr>
          <td colspan="3" style="padding-left:10px;">-</td>
        </tr>
        <tr>
          <td style="padding-left:10px;">
            <p>
            Dari:<br>
            <?php echo $record['sendername'];?><br>
            <?php echo $record['senderaddr'];?><br>
            <?php echo $record['sendercity']." ".$record['senderposcode'];?><br>
            <?php echo $record['senderprovinci']?><br>
            Indonesia<br>
            Telp: <?php echo $record['senderphone'];?>
            </p>
          </td>
          <td style="width:66.66666%;text-align:left;padding-left:10px;" colspan="2"><p><b>
            Kepada:<br>
            <?php echo $record['receivername'];?><br>
            <?php echo $record['receiveraddr']?><br>
            <?php echo $record['receivercity']." ".$record['receiverposcode'];?><br>
            <?php echo $record['receiverprovinci'];?><br>
            Indonesia<br>
            Telp: <?php echo $record['receiverphone'];?>
            </b></p>
          </td>
        </tr>
        <tr>
          <td colspan="3" style="padding-left:10px;">Berat : <?php echo rupiah($record['berat']);?> Gr</td>
        </tr>
        <tr>
          <td style="width:33.33333%;text-align:left;font-size:15pt;padding-left:10px;">SI - -</td>
          <td style="width:33.33333%;text-align:center;" rowspan="2">
            <img style="width:80%" src="<?php echo base_url()?>/barcode/qr?extid=<?php echo $record['extid'];?>" alt="">
          </td>
          <td style="width:33.33333%;text-align:center;font-size:15pt;">NON COD</td>
        </tr>
        <tr>
          <td style="width:33.33333%;text-align:left;padding-left:10px;"><p>Tanggal Transaksi :
            <br><?php echo date('d F Y', strtotime($record['created_at']));?></p>
          </td>
          <td style="width:33.33333%;text-align:center;">Rp.0</td>
        </tr>
    </table>
    <br>
    <table>
      <tr>
        <th style="width:33.33333%;text-align:center;font-size:7pt;">
          <br>
          BUKTI PENGIRIMAN
          <br>
          <p style="text-align:center;font-size:7pt;">
            
          <br>
          Tgl Posting : <?php echo date('d F Y', strtotime($record['created_at']));?>
          <br>
          Wkt Posting : <?php echo date('h:i:s', strtotime($record['created_at']));?></p><br>
        </th>
        <td style="text-align:left;font-size:8pt;padding-left: 10px;" colspan="2">
          Berat : [AW] <?php echo rupiah($record['berat']);?> GR [VW] 0,0 GR
          <br>Bea Kirim : Rp. <?php echo rupiah($record['totalfee']);?> <br>
        </td>
      </tr>
      <tr>
        <td style="width:33.33333%;text-align:left;font-size:8pt;padding-left: 10px;">
            Jenis kiriman : <?php echo $record['productid']."-".$record['productname'];?><br>
            Estimasi Antaran : 48 JAM
        </td>
        <td style="width:33.33333%;text-align:left;font-size:8pt;padding: 10px;" colspan="2">
          Pernyataan Pengirim<br>
            1. Setuju dengan ketentuan dan syarat pengiriman<br>
            yang ditetapkan PT.Pos Indonesia(Persero)<br>
            2. Isi Kiriman : <?php echo $record['isikiriman']; ?><br>
            3. Nilai pertanggungan isi kiriman<br>
            Rp. <?php echo rupiah($record['valuegoods']); ?><br>
            </td>
      </tr>
      
    </table>
</body>
</html>