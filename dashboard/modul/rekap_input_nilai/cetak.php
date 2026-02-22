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
 $jur_filter = ' and vnk.kode_jur="'.$_POST['jur_filter'].'"';
 $sem_filter = ' and vnk.sem_id="'.$_POST['sem_filter'].'"';

$prodi = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$_POST['jur_filter']);
  if ($_POST['status_nilai']==1) {
    //sudah dinilai
    $status_nilai = "and (fungsi_belum_dinilai(vnk.kelas_id) is null and fungsi_sudah_dinliai(vnk.kelas_id) is not null)";
    $judul = "SUDAH LENGKAP";
  } elseif ($_POST['status_nilai']==2) {
    //belum dinilai
     $status_nilai = "and (fungsi_belum_dinilai(vnk.kelas_id) is not null and fungsi_sudah_dinliai(vnk.kelas_id) is null)";
     $judul = "BELUM";
  } elseif ($_POST['status_nilai']==3) {
    //belum lengkap nilai
    $status_nilai = "and (fungsi_belum_dinilai(vnk.kelas_id) is not null and fungsi_sudah_dinliai(vnk.kelas_id) is not null)";
    $judul = "BELUM LENGKAP";
  }

$periode = $db->fetch_custom_single("SELECT * from view_semester
WHERE id_semester='".$_POST['sem_filter']."'");

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

  <div class="page-break">
     <table>
      <tbody><tr>
       <td> <img src="<?=base_url();?>upload/logo/<?=$db->fetch_single_row('identitas_logo','id_logo',1)->logo;?>" width="100" height="100"></td>
      <!--halaman <?= $i ?>-->
      <td>
            <h3><br><?= $identitas->header ?></h3>

        <hr>
        <div style="font-size:10"><?= $identitas2->alamat ?></div>
         </td>
          </tr>
   </tbody></table>
   <h3 align="center">DAFTAR NAMA DOSEN YANG <?=$judul;?> INPUT NILAI</h3>
    <h4 align="center">Program Studi : <?= $prodi->jurusan;?></h4>
            <h4 align="center">Semester : <?= $periode->tahun_akademik ?></h4>
      <br>
      <div class="nobreak">
      <table class="tabel-common" width="100%">
         <tbody><tr>
            <th width="15" scope="col">No.</th>
            <th width="300" scope="col">Nama Dosen</th>
            <th width="300" scope="col">Matakuliah</th>
            <th width="100" scope="col">Kelas</th>            
         </tr>

           
<?php
      
          $q=$db->query("select vd.nama_dosen, vnk.nm_matkul,vnk.nama_kelas,fungsi_get_jml_krs(vnk.kelas_id) as peserta,
    fungsi_belum_dinilai(vnk.kelas_id) as belum,
  fungsi_sudah_dinliai(vnk.kelas_id) as sudah,
  vnk.jurusan,
  vnk.kelas_id
   from view_nama_kelas vnk
   inner join view_dosen_kelas_single vd
   on vnk.kelas_id=vd.id_kelas
     where vnk.kelas_id is not null $sem_filter $jur_filter $status_nilai ");
          echo $db->getErrorMessage();
          $no=1;
          foreach ($q as $k) {
            
            echo "<tr>
                     <td>$no</td>
                     <td>$k->nama_dosen</td>
                     <td>$k->nm_matkul</td>
                     <td>$k->nama_kelas</td>
                 </tr>";
                 $no++;
          }
         ?>
        </tbody></table>
      <br>
   </div>

  </div>

  
</body>
</html>