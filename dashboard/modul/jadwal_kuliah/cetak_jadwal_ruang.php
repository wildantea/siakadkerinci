<?php
session_start();
include "../../inc/config.php";
//include "../../inc/function.php";


session_check();

?>
<html>
<head>
<title>Cetak Surat Keterangan</title>
<style>body {
  background:#ffffff;
}
page {
  padding: 10px;
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
  /*box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
}
page[size="A4"] {
  width: 21cm;
  height: 29.7cm;
}
page[size="A4"][layout="landscape"] {
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
	padding: 0px;
   font-family: Tahoma;
   font-size: 11px;
}

h1, h2, h3, h4, h5, h6 {
   padding: 2px 0px;
   margin: 0px;
}

h1 {
   font-size: 15pt;
   margin: 0;
}
h1+p {
   margin: 0;
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
   margin: 0px 0px 0px 0px;
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
  margin: 0;
}
h1+p {
   margin: 0;
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
<link rel="stylesheet" type="text/css" href="../styles/a_sia_cetak.css" />
<link rel="stylesheet" type="text/css" media="print" href="../styles/a_sia_cetak_media_print.css" />
</head>
<?php

 $ruang = de($_POST['ruang']);
 $sem = $_POST['sem'];
 $jur = $_POST['jur'];
 
 $qqq = $db->query("SELECT js.`jns_semester`, sf.`tahun`, sf.`tahun`+1 FROM kelas k 
JOIN semester s ON s.id_semester=k.sem_id 
JOIN semester_ref sf ON sf.`id_semester`=s.`id_semester`
JOIN jenis_semester js ON js.`id_jns_semester`=sf.`id_jns_semester`
WHERE k.sem_id='$sem' ");
 foreach ($qqq as $kkk) {
 } 



 
 $idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
 foreach ($idt as $identitas) {
   # code...
 }
 $idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
 foreach ($idt2 as $identitas2) {
   # code...
 }

?>
<body onLoad="//window.print();">
<page size="A4">
      <div class="page-landscape">
      <div class="page-break">
   <table>
      <tbody>
        <tr>
           <td>
             <img src="../../assets/login/img/logokerinci.png" width="100" height="100">
           </td>
           <td>
               <h3><br><?= $identitas->header ?></h3>
              <h4>FAKULTAS SAINS DAN TEKNOLOGI</h4>
  				    <hr>
  				    <?= $identitas2->alamat ?>
           </td>
        </tr>
     </tbody>
   </table>

      <!--table width="100%">
         <tr>
            <td width="5%"><img src="../images/logo.jpg" /></td>
            <td width="95%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
         </tr>
      </table-->
      <br>

      <h3 align="center">JADWAL KELAS KULIAH</h3>
      <h4 align="center">Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>-<?= $kkk->tahun+1 ?></h4><br>

      


   
      <table class="tabel-common" width="100%">
         <tbody>
           <tr>
                  <th style="width:25px" class='center' valign="center" rowspan='2'>No</th>
                  <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                  <th class='center' valign="center" rowspan='2'>Ruang</th>
                  <th class='center' valign="center" colspan='7'>Hari</th>
               </tr>
                <tr>
                  <th>Senin</th>
                  <th>Selasa</th>
                  <th>Rabu</th>
                  <th>Kamis</th>
                  <th>Jumat</th>
                  <th>Sabtu</th>
                  <th>Minggu</th>
                </tr>
         <?php
         $i=1;
            $q=$db->query("SELECT jd.jadwal_id, d.nama_dosen, d.gelar_depan,d.gelar_belakang, r.nm_ruang, r.`ruang_id`, jd.jam_mulai,jd.jam_selesai,jd.hari, matkul.kode_mk, j.nama_jur, kelas.kls_nama,kelas.kode_paralel,
                      kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                      kelas.kelas_id FROM kelas INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul
                      JOIN kurikulum k ON k.kur_id=matkul.kur_id
                      JOIN jurusan j ON j.kode_jur=k.kode_jur
                     JOIN jadwal_kuliah jd ON jd.kelas_id=kelas.kelas_id  
                     LEFT JOIN ruang_ref r ON r.ruang_id=jd.ruang_id
                     JOIN dosen_kelas dk ON dk.id_kelas=kelas.kelas_id
                     JOIN dosen d ON d.nip=dk.id_dosen 
                     WHERE j.kode_jur='$jur' AND kelas.sem_id='$sem' AND r.`ruang_id`='$ruang' 
                     ORDER BY jd.hari desc");
            
            $no=1;
            foreach ($q as $isi) {
        ?><tr id="line_<?=$isi->kode_mk;?>">
          <td align="center"><?=$i;?></td>
         
          <td><?=$isi->kls_nama." - ".$isi->nama_mk;?></td>
          
               <td><?=$isi->nm_ruang;?></td> 
          <td align="center"><?php if(strtolower($isi->hari)=='senin') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='selasa') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='rabu') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='kamis') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='jumat') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='sabtu') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>
          <td align="center"><?php if(strtolower($isi->hari)=='minggu') echo "".substr($isi->jam_mulai, 0,5)." - ".substr($isi->jam_selesai,0,5); ?></td>

         
          
        
        </tr>
        <?php
      $i++;
      }
      ?>
        </tbody>
    </table>
 
</div>
</div>
</page>

</body>
</html>