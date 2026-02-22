<?php 
  session_start();
  include "../../inc/config.php";
  session_check();
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style type="text/css">body 
page {
  padding: 50px;
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[size="A4"] {  
  width: 21cm;
  height: 29.7cm; 
}
page[size="A4"][layout="portrait"] {
  width: 29.7cm;
  height: 29.7cm;  
}
page[size="A3"] {
  width: 29.7cm;
  height: 42cm;
}
page[size="A3"][layout="portrait"] {
  width: 42cm;
  height: 29.7cm;  
}
page[size="A5"] {
  width: 14.8cm;
  height: 21cm;
}
page[size="A5"][layout="portrait"] {
  width: 21cm;
  height: 14.8cm;  
}
@media print {
  body, page {
    margin: 0;
    box-shadow: 0;
  }
}



body {
  padding: 10px;
   font-family: Tahoma;
   font-size: 11px;
}

h1, h2, h3, h4, h5, h6 {
   padding: 2px 0px;
   margin: 0px;
}

h1 {
   font-size: 15pt;
}

h2 {
   font-size: 13pt;
}

h3 {
   font-size: 11pt;
}

h4 {
   font-size: 9pt;
}

hr {
   clear: both;
}

img {
   margin: 2px;
}

.center {
   text-align: center;
}

div.page-portrait {
   visibility: visible;
   font-family: Tahoma;
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 17cm;
}

div.page-landscape {
   visibility: visible;
   font-family: Tahoma;
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 25.5cm;
}

table {
   border-collapse: collapse;
}

.box {
   border: 1px solid #ccc;
   padding: 4px;
}

table tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 0px 2px 0px 2px;
}

table tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
}

.tabel-common tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 0px 2px 0px 2px;
   border: 1px solid #ccc;
   vertical-align: top;
}

.tabel-common .nama {
   width: 250px;
   overflow: hidden;
}

.tabel-common tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
   border: 1px solid #ccc;
}

.tabel-info tr td, th {
   font-family: Tahoma;
   font-size: 11px;
   padding: 2px;
   font-weight: bold;
}

div.nobreak .hidden {
   visibility: hidden;
   display: none;
}

div.page-break .hidden {
   visibility: visible;
   margin: 10px 0px 10px 0px;
}

.page-break {
   clear: both;
}

.link {
  clear:both;
  visibility: visible;
}

body {
   font-family: Tahoma;
   font-size: 11px;
}

h1, h2, h3, h4, h5, h6 {
   padding: 2px 0px;
   margin: 0px;
}

h1 {
   font-size: 15pt;
}

h2 {
   font-size: 13pt;
}

h3 {
   font-size: 11pt;
}

h4 {
   font-size: 9pt;
}

hr {
   clear: both;
}

img {
   margin: 2px;
}

@page size-A4 {size:  21.0cm 29.7cm; margin: 1.5cm;}
@page rotate-landscape {size: landscape; }

div.page-portrait {
   visibility: visible;
   font-family: Tahoma;
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 17cm;
}

div.page-landscape {
   visibility: visible;
   font-family: Tahoma;
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 25.5cm;
}

div.page-break {
   visibility: visible;
   page-break-after: always;
}

div.nobreak {
   visibility: visible;
}

table {
   border-collapse: collapse;
}

.box {
   border: 1px solid #000;
   padding: 4px;
}

table tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 0px 2px 0px 2px;
}

table tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #eee;
   padding: 2px;
}

.tabel-common tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 0px 2px 0px 2px;
   border: 1px solid #000;
   vertical-align: top;
}

.tabel-common tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #eee;
   padding: 2px;
   border: 1px solid #000;
}

.tabel-common .nama {
   width: 250px;
   overflow: hidden;
}

.hidden {
   visibility: hidden;
}

div.nobreak .hidden {
   display: none;
}

div.page-break .hidden {
   display: none;
}

.tabel-info tr td, th {
   font-family: Tahoma;
   font-size: 11px;
   padding: 2px;
   font-weight: bold;
}

.page-break {
   clear: both;
}

.link {
  clear:both;
  visibility: hidden;
  display: none;
}


@media print {
  .page-break { display: block; page-break-before: always; }
}
  </style>
  <script type="text/javascript">
    window.print();
  </script>
</head>
<?php
  $fakultas="";
  $jurusan="";

  if (isset($_POST['fakultas_filter'])) {

    if ($_POST['fakultas_filter']!='all') {
      $fakultas = ' and f.kode_fak="'.$_POST['fakultas_filter'].'"';
    }
  }

  if(isset($_POST['jurusan_filter'])) {
    if ($_POST['jurusan_filter']!='all') {
      $jurusan = ' and j.kode_jur="'.$_POST['jurusan_filter'].'"';
    }
  }

  $qq=$db->query("select ta.nim,m.nama,pem_1.nama_dosen,pem_2.nama_dosen,f.nama_resmi,j.nama_jur,ta.id_ta from tugas_akhir ta 
    inner join mahasiswa m on ta.nim=m.nim
    inner join dosen pem_1 on pem_1.id_dosen=ta.pembimbing_1
    inner join dosen pem_2 on pem_2.id_dosen=ta.pembimbing_2
    inner join fakultas f on ta.kode_fak=f.kode_fak 
    inner join jurusan j where ta.kode_jurusan=j.kode_jur $fakultas $jurusan group by ta.id_ta");
foreach ($qq as $kk) {
  # code...
}
?>
<body onLoad="//window.print();">
  <div class="page-break">
    <table>
      <tbody>
      <tr>
        <td>
          <img src="http://simak.uinsgd.ac.id/gtakademik/images/logo.jpg" width="100" height="100" style="margin-top: 65px;">
        </td>
        <!--halaman <?= $i ?>-->
        <td width="100%">
          <h1 align="center" style="text-align: justify-all;">UNIVERSITAS ISLAM NEGERI (UIN) SUNAN GUNUNG DJATI BANDUNG</h1>
        </td>
      </tr>
      </tbody>
    </table>
    <br>
      <h2 align="center" style="margin-bottom: 10px;">DAFTAR BIMBINGAN TUGAS AKHIR</h2>
      <h4 align="left">Fakultas &ensp;&ensp;&ensp;&emsp;: <?= $kk->nama_resmi ?></h4>
      <h4 align="left">Program Studi  : <?= $kk->nama_jur ?> S1 - Reguler</h4>
    <br>
    <div class="nobreak">
      <table class="tabel-common" width="100%">
        <tbody>
          <tr>
            <th width="10" scope="col">No.</th>
            <th width="35" scope="col">Nim</th>
            <th width="35" scope="col">Nama</th>
            <th width="35" scope="col">Pembingin 1</th>
            <th width="35" scope="col">Pembingin 2</th>
            <th width="35" scope="col">Fakultas</th>
            <th width="35" scope="col">Jurusan</th>
          </tr>         
<?php
    
  $q=$db->query("select ta.nim,m.nama,pem_1.nama_dosen as pem1,pem_2.nama_dosen as pem2,f.nama_resmi,j.nama_jur,ta.id_ta from tugas_akhir ta 
    inner join mahasiswa m on ta.nim=m.nim
    inner join dosen pem_1 on pem_1.id_dosen=ta.pembimbing_1
    inner join dosen pem_2 on pem_2.id_dosen=ta.pembimbing_2
    inner join fakultas f on ta.kode_fak=f.kode_fak 
    inner join jurusan j where ta.kode_jurusan=j.kode_jur $fakultas $jurusan group by ta.id_ta");


  $no=1;
  foreach ($q as $k) {

    echo "
      <tr>
        <td>$no</td>
        <td>$k->nim</td>
        <td>$k->nama</td>
        <td>$k->pem1</td>
        <td>$k->pem2</td>
        <td>$k->nama_resmi</td>
        <td>$k->nama_jur</td>
      </tr>";
    $no++;
  }
?>
      <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
         </tr> -->
        </tbody>
      </table>
      <br>
      <h4 align="right" style="margin-right: 32px;"><?= tgl_indo(date('Y-m-d')); ?></h4>
      <h4 align="right" style="margin-right: 38px;">Ketua Jurusan</h4>
      <h4 align="right" style="margin-top: 50px;">Muhamad Irfan ST,. M.Kom</h4>
    </div>
  </div>
</body>
</html>
