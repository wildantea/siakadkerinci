<?php
session_start();
include "../../inc/config.php";
session_check_json();
$nim = $_GET['nim'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Bimbingan PA</title>
<style>
  body {
    font-family: "Arial", sans-serif;
    margin: 20px 40px;
    color: #000;
  }

  h2, h3 {
    text-align: center;
    margin: 5px 0;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }

  td, th {
    border: 1px solid #000;
    padding: 6px 8px;
    font-size: 13px;
    vertical-align: top;
  }

  .no-border td {
    border: none;
    padding: 2px 5px;
  }

  .header {
    margin-bottom: 20px;
  }

  .header table {
    border: 1px solid #000;
  }

  .header td {
    font-size: 13px;
  }

  .header .qr {
    text-align: center;
  }

  .section-title {
    font-weight: bold;
    text-align: center;
    background-color: #dce6f1;
    padding: 6px;
    border: 1px solid #000;
    margin-top: 15px;
  }

  .blue {
    background-color: #b8cce4;
    text-align: center;
    font-weight: bold;
  }

  .center {
    text-align: center;
  }

  @media print {
    body { margin: 0; }
    .no-print { display: none; }
  }
</style>
</head>
<body>

<h2>KARTU BIMBINGAN AKADEMIK</h2>
<?php
 $mhs_data = $db->fetch_custom_single("select * from view_simple_mhs where nim=?",array('nim' => $_GET['nim']));
 ?>
<div class="header">
  <table width="100%">
    <tr>
      <td width="20%">NIM</td>
      <td>: <?=$mhs_data->nim?></td>
      <td rowspan="6" class="qr" width="20%">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=K6420007" alt="QR Code">
      </td>
    </tr>
    <tr><td>Nama</td><td>: <?=$mhs_data->nama_fakultas?></td></tr>
    <tr><td>P A</td><td>: <?=$mhs_data->dosen_pa?></td></tr>
    <tr><td>Fakultas</td><td>: <?=$mhs_data->nama_fakultas?></td></tr>
    <tr><td>Program Studi</td><td>: <?=$mhs_data->nama_jurusan?></td></tr>
  <!--   <tr><td>Judul</td><td>: Konsultasi KRS</td></tr> -->
  </table>

  <table class="no-border" width="100%">
    <tr>
      <td width="50%">Tahun Akademik: 2021</td>
      <td width="50%">Semester: Genap</td>
    </tr>
  </table>
</div>

<div class="section-title">Konsultasi KRS</div>

<table>
  <tr class="blue">
    <th width="5%">No</th>
    <th width="15%">Judul</th>
    <th>Chat Mahasiswa</th>
    <th>Chat Pembimbing</th>
    <th width="10%">Paraf</th>
  </tr>

<?php
  $data = $db->query("SELECT 
  cm.category_id,
  CASE 
    WHEN cm.category_id = 1 THEN 'Konsultasi KRS'
    WHEN cm.category_id = 2 THEN 'Konsultasi Tengah Semester'
    WHEN cm.category_id = 3 THEN 'Konsultasi Pasca Perkuliahan'
    ELSE 'Lainnya'
  END AS kategori_konsultasi,

  cm.nim,
  cm.nip,

  -- semua pesan mahasiswa + waktu kirimnya
  GROUP_CONCAT(
    CASE WHEN cm.sender_role = 'mahasiswa' 
         THEN CONCAT(cm.message, ' (', DATE_FORMAT(cm.created_at, '%Y-%m-%d %H:%i:%s'), ')')
         ELSE NULL END 
    ORDER BY cm.created_at SEPARATOR '<br>'
  ) AS chat_mahasiswa,

  -- semua pesan dosen + waktu kirimnya
  GROUP_CONCAT(
    CASE WHEN cm.sender_role = 'dosen' 
         THEN CONCAT(cm.message, ' (', DATE_FORMAT(cm.created_at, '%Y-%m-%d %H:%i:%s'), ')')
         ELSE NULL END 
    ORDER BY cm.created_at SEPARATOR '<br>'
  ) AS chat_pembimbing

FROM chat_message cm
WHERE cm.nim = ?
GROUP BY cm.category_id
ORDER BY cm.category_id;

",array('nim' => $nim));
  $no = 1;
  foreach ($data as $dt) {
    ?>
<tr>
    <td class="center"><?=$no;?></td>
    <td><?=$dt->kategori_konsultasi;?></td>
    <td>
      <?=$dt->chat_mahasiswa;?><br>
    </td>
    <td>
      <?=$dt->chat_pembimbing;?><br>
    </td>
    <td></td>
  </tr>
    <?php
    $no++;
  }
?>
  
</table>

<br><br>
<table class="no-border" width="100%">
  <tr>
    <td width="60%"></td>
    <td class="center">
      <br><br>
      <b>Dosen Pembimbing Akademik</b><br><br><br>
      <u>tes</u><br>
      NIP. 23432
    </td>
  </tr>
</table>

<div class="no-print" style="text-align:center; margin-top:20px;">
  <button onclick="window.print()">🖨️ Cetak</button>
</div>

</body>
</html>
