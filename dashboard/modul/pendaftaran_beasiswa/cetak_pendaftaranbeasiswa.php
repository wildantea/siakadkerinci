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
    //window.print();
  </script>
</head>
<?php
  $priode = "";
  $jenis = "";
  $beasiswa = "";

  if (isset($_POST['jenis'])) {

    if ($_POST['jenis']!='all') {
      $jenis = ' and bj.id_beasiswajns="'.$_POST['jenis'].'"';
    }

    if ($_POST['priode']!='all') {
      $priode = ' and s.id_semester="'.$dec->dec($_POST['priode']).'"';
    }

    if ($_POST['beasiswa']!='all') {
      $beasiswa = ' and b.id_beasiswa="'.$_POST['beasiswa'].'"';
    }
  }

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

  $idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
   foreach ($idt as $identitas) {
     # code...
   }
   $idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
   foreach ($idt2 as $identitas2) {
     # code...
   }
  $fak=$db->query("SELECT fk.nama_resmi, j.`nama_jur` FROM fakultas fk
  JOIN jurusan j ON j.`fak_kode`=fk.`kode_fak`
  WHERE kode_fak is not null $jurusan");
   foreach ($fak as $fk) {
     # code...
   }

  $fakultas_query = $db->query("SELECT *,d.gelar_depan, d.nama_dosen, d.gelar_belakang FROM mahasiswa m
                              JOIN jurusan j ON m.jur_kode=j.kode_jur
                              JOIN fakultas f ON j.fak_kode=f.kode_fak
                              JOIN dosen d WHERE d.id_dosen=f.dekan $fakultas $jurusan");
  foreach ($fakultas_query as $fak_q) {
      $dekan=$fak_q->gelar_depan." ".$fak_q->nama_dosen." ".$fak_q->gelar_belakang;
  }
?>
<body onLoad="//window.print();">
  <div class="page-break">
    <table>
      <tbody><tr>
       <td><img src="../../assets/login/img/logokerinci.png" width="100" height="100"></td>
      <!--halaman <?= $i ?>-->
      <td>
            <h3><br><?= $identitas->header ?></h3>
        <hr>
        <div style="font-size:10"><?= $identitas2->alamat ?></div>
         </td>
          </tr>
   </tbody></table>
    <br>
      <h2 align="center" style="margin-bottom: 10px;">DAFTAR PENDAFTARAN BEASISWA</h2>
      <h4 align="left">Fakultas &ensp;&ensp;&ensp;&emsp;: <?= $fk->nama_resmi ?></h4>
      <h4 align="left">Program Studi  : <?= $fk->nama_jur ?> S1 - Reguler</h4>
    <br>
    <div class="nobreak">
      <table class="tabel-common" width="100%">
        <tbody>
          <tr>
            <th width="10" scope="col">No.</th>
            <th width="35" scope="col">Nim</th>
            <th width="35" scope="col">Nama</th>
            <th width="35" scope="col">Beasiswa</th>
          </tr>         
<?php
    
  $q=$db->query("select count(bm.id_beasiswamhs) as n, bm.nim_beasiswamhs,m.nama,b.nama_beasiswa,bm.stt_beasiswamhs,bm.id_beasiswamhs from beasiswa_mhs bm 
    inner join mahasiswa m on bm.nim_beasiswamhs=m.nim
    inner join fakultas f on bm.kode_fak=f.kode_fak
    inner join jurusan j on bm.kode_jur=j.kode_jur
    inner join beasiswa_jenis bj on bm.id_beasiswajns=bj.id_beasiswajns 
    inner join beasiswa b on bm.id_beasiswa=b.id_beasiswa 
    inner join semester_ref s on b.priode_beasiswa=s.id_semester
    where bm.id_beasiswamhs is not null $jenis $beasiswa $priode $fakultas $jurusan");

  $no=1;
  foreach ($q as $k) {
    if($k->n == 0) {
      echo "
      <tr>
        <td align='center' colspan='4'><strong>Data Kosong / Tidak Ditemukan</strong></td>
      </tr>";
    }else {
      echo "
        <tr>
          <td>$no</td>
          <td>$k->nim_beasiswamhs</td>
          <td>$k->nama</td>
          <td>$k->nama_beasiswa</td>
        </tr>";
      $no++;
    }
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
      <h4 align="right" style="margin-right: 38px;">Dekan Fakultas</h4>
      <h4 align="right" style="margin-top: 50px;"><?=$dekan?></h4>
    </div>
  </div>
</body>
</html>
