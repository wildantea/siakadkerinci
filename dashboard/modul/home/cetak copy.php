<?php
session_start();
include "../../inc/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Added for mobile responsiveness -->
  <title>Rekap Nilai</title>
  <style type="text/css">
    body {
      font-family: 'Times New Roman', Times, serif;
      background-color: #f0f0f0; /* Light gray background like Word workspace */
      margin: 0;
      padding: 10px; /* Reduced padding for mobile */
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }
    .paper {
      width: 210mm; /* A4 width for desktop */
      min-height: 297mm; /* A4 height for desktop */
      background: #fff; /* White paper background */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for paper effect */
      border: 1px solid #d3d3d3; /* Light border */
      padding: 25mm; /* Standard Word margin (~2.54cm) */
      box-sizing: border-box;
    }
    .content {
      max-width: 100%;
      margin: 0;
    }
    .header {
      margin-bottom: 20px;
    }
    .header table {
      width: 100%;
      border-collapse: collapse;
    }
    .header td {
      vertical-align: top;
      padding: 0;
    }
    .header img {
      width: 100px;
      height: 100px;
      margin-right: 10px;
    }
    .header h1 {
      font-size: 16px;
      margin: 0;
      line-height: 1.2;
      text-align: left;
      text-transform: uppercase;
    }
    .header p {
      font-size: 12px;
      margin: 5px 0 0;
      text-align: left;
      line-height: 1.4;
    }
    .header-table {
      width: 100%;
      font-size: 14px;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .header-table td {
      padding: 5px 0;
    }
    .header-table .title {
      font-size: 20px;
      text-align: center;
      border-bottom: 1px solid #000;
      padding-bottom: 10px;
      font-weight: bold;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      margin-top: 30px;
    }
    .data-table th, .data-table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
      vertical-align: middle;
    }
    .data-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    .data-table td {
      font-size: 11px;
    }
    /* Mobile-specific styles */
    @media (max-width: 767px) {
      .paper {
        width: 100%; /* Full width for mobile */
        min-height: auto; /* Remove fixed height */
        padding: 15px; /* Reduced padding */
      }
      .header img {
        width: 80px; /* Smaller logo */
        height: 80px;
        display: block; /* Center logo */
        margin: 0 auto 10px;
      }
      .header table {
        display: block; /* Stack header content */
      }
      .header td {
        display: block;
        text-align: center; /* Center text for mobile */
      }
      .header h1 {
        font-size: 14px; /* Slightly smaller for mobile */
      }
      .header p {
        font-size: 11px; /* Smaller text */
      }
      .header-table {
        font-size: 13px; /* Slightly smaller font */
      }
      .header-table td {
        padding: 3px 0; /* Reduced padding */
      }
      .header-table .title {
        font-size: 18px; /* Smaller title */
      }
      .data-table {
        display: block;
        overflow-x: auto; /* Horizontal scrolling for table */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        font-size: 11px;
      }
      .data-table th, .data-table td {
        padding: 6px; /* Reduced padding */
        white-space: nowrap; /* Prevent text wrapping */
      }
    }
    @media (max-width: 576px) {
      .header h1 {
        font-size: 12px; /* Even smaller for very small screens */
      }
      .header p {
        font-size: 10px;
      }
      .header-table {
        font-size: 12px;
      }
      .data-table th, .data-table td {
        font-size: 10px; /* Smaller font for very small screens */
        padding: 4px;
      }
    }
    /* Print styles remain unchanged */
    @media print {
      body {
        background: #fff;
        padding: 0;
        margin: 0;
      }
      .paper {
        width: 100%;
        min-height: auto;
        box-shadow: none;
        border: none;
        padding: 25mm;
      }
      .content {
        max-width: 100%;
      }
      .data-table {
        page-break-inside: auto;
      }
      .data-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
    }
  </style>
  <script type="text/javascript">
  /*  window.onload = function() {
      window.print();
    };*/
  </script>
</head>
<body>
  <div class="paper">
    <section class="content">
      <div class="header">
        <table>
          <tr>
            <td style="vertical-align: top;">
              <img src="logo.png" alt="Logo" width="100" height="100">
            </td>
            <td>
              <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA<br>
              INSTITUT AGAMA ISLAM NEGERI (IAIN)<br>
              KERINCI - JAMBI</h1>
              <p>Jl. Kapten Muradi, Kecamatan Pesisir Bukit, Kota Sungai Penuh<br>
              Telepon (0748) 21065; Faksimili (0748) 22114; Kode Pos 37112<br>
              Website: www.iainkerinci.ac.id; email: info@iainkerinci.ac.id</p>
            </td>
          </tr>
        </table>
      </div>
      <div>
        <?php
        $id_dosen = $db->fetch_single_row("dosen","nip",$_SESSION['username']);
        $periode = $db->fetch_custom_single("select left(priode,4) as periode,dpl,dpl2,lokasi_kkn.id_lokasi,nama_lokasi from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester 
        join jenis_semester j on sr.id_jns_semester=j.id_jns_semester 
        inner join lokasi_kkn on jm.id_priode=lokasi_kkn.id_periode
        where dpl='$id_dosen->id_dosen' or dpl2='$id_dosen->id_dosen'
        order by sr.id_semester desc limit 1
        ");
        $dosen_pembimbing = $db->fetch_custom_single("select group_concat(dosen SEPARATOR '#') as pembimbing from view_dosen where id_dosen in($periode->dpl,$periode->dpl2)");
        ?>
        <table class="header-table">
          <tr>
            <td colspan="2" class="title">DAFTAR PESERTA KUKERTA</td>
          </tr>
          <tr>
            <td style="width: 150px">Periode Kukerta</td>
            <td>: Kukerta <?=$periode->periode;?></td>
          </tr>
          <tr>
            <td style="width: 150px">Lokasi Kukerta</td>
            <td>: <?=$periode->nama_lokasi;?></td>
          </tr>
          <?php
          $pemb = explode("#", $dosen_pembimbing->pembimbing);
          $i = count($pemb) > 1 ? 1 : '';
          foreach ($pemb as $pb) {
            ?>
            <tr>
              <td>DPL <?=$i;?></td>
              <td>: <?=$pb;?></td>
            </tr>
            <?php
            if (count($pemb) > 1) $i++;
          }
          ?>
        </table>
      </div>
      <div>
        <input type="hidden" name="jur" value="923b8cabc00$e5ab9">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 5%">No</th>
              <th style="width: 15%">NIM</th>
              <th style="width: 25%">Nama</th>
              <th style="width: 25%">Fakultas</th>
              <th style="width: 25%">Program Studi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $id_lokasi = $dec->dec($_GET['id']);
            $data_peserta = $db->query("select lk.nama_lokasi,mahasiswa.jk, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from kkn 
            inner join mahasiswa on kkn.nim=mahasiswa.nim 
            inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
            inner join jurusan on kkn.kode_jur=jurusan.kode_jur 
            left join priode_kkn on priode_kkn.id_priode=kkn.id_priode
            left join lokasi_kkn lk on lk.id_lokasi=kkn.id_lokasi
            where lk.id_lokasi=?
            ", array('id_lokasi' => $id_lokasi));
            $no = 1;
            foreach ($data_peserta as $peserta) {
              ?>
              <tr>
                <td><?=$no;?></td>
                <td><?=$peserta->nim;?></td>
                <td><?=$peserta->nama;?></td>
                <td><?=$peserta->nama_resmi;?></td>
                <td><?=$peserta->nama_jur;?></td>
              </tr>
              <?php
              $no++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</body>
</html>