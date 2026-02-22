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
  padding: 0px;
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
 $k = $_POST['k']; 
  $jur = $_POST['jur']; 
  $sem = $_POST['sem'];

  $idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
 foreach ($idt as $identitas) {
   # code...
 }
 $idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
 foreach ($idt2 as $identitas2) {
   # code...
 }
 $fak=$db->query("SELECT fk.nama_resmi, jr.`nama_jur` FROM fakultas fk
JOIN jurusan jr ON jr.`fak_kode`=fk.`kode_fak`
WHERE jr.`kode_jur`='$jur'");
 foreach ($fak as $fk) {
   # code...
 }
 $qqq = $db->query("SELECT js.`jns_semester`, sf.`tahun`, sf.`tahun`+1 FROM kelas k 
JOIN semester s ON s.id_semester=k.sem_id 
JOIN semester_ref sf ON sf.`id_semester`=s.`id_semester`
JOIN jenis_semester js ON js.`id_jns_semester`=sf.`id_jns_semester`
WHERE k.sem_id='$sem' ");
 foreach ($qqq as $kkk) {
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
                  <h4>FAKULTAS <?= $fk->nama_resmi ?></h4>

        <hr>
        <div style="font-size:10"><?= $identitas2->alamat ?></div>
         </td>
          </tr>
   </tbody></table>
   <h2 align="center">DAFTAR NAMA DOSEN YANG BELUM INPUT NILAI</h2>
    <h4 align="center">Program Studi : <?= $fk->nama_jur ?> S1 - Reguler</h4>
            <h4 align="center">Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>/<?= $kkk->tahun+1 ?></h4>
      <br>
      <div class="nobreak">
      <table class="tabel-common" width="100%">
         <tbody><tr>
            <th width="15" scope="col">No.</th>
            <th width="300" scope="col">Dosen Pengampu</th>
            <th width="300" scope="col">Matakuliah</th>
            
         </tr>

           
<?php

      
          $q=$db->query("SELECT matkul.kode_mk, d.`gelar_depan`, d.`nama_dosen`, d.`gelar_belakang`, j.nama_jur, kelas.`sem_id`, kelas.kls_nama,kelas.kode_paralel, kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id,  
(SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf='')
                                                AND k.batal=0 GROUP BY k.id_kelas) AS belum,
(SELECT COUNT(id_krs_detail) FROM krs_detail k 
WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf!='') AND k.batal=0 GROUP BY k.id_kelas) AS sdh 
FROM kelas INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id 
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN dosen_kelas dk ON dk.`id_kelas` = kelas.`kelas_id`
JOIN dosen d ON d.`nip` = dk.`id_dosen`
                                                  where j.kode_jur='$jur' and kelas.sem_id='$sem' and (SELECT COUNT(id_krs_detail) FROM krs_detail k WHERE k.id_kelas=kelas.kelas_id AND (nilai_huruf!='')
                                                AND k.batal=0 GROUP BY k.id_kelas) IS NULL");
          $no=1;
          foreach ($q as $k) {
            
            echo "<tr>
                     <td>$no</td>
                     <td>$k->gelar_depan $k->nama_dosen $k->gelar_belakang</td>
                     <td>$k->kode_mk - $k->nama_mk</td>
                     
                 </tr>";
                 $no++;
          }
         ?>
      <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
         </tr> -->
        </tbody></table>
      <br>
   </div>

  </div>

  
</body>
</html>