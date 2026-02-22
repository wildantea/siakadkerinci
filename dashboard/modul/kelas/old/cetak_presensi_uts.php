<?php 
session_start();
include "../../inc/config.php";

session_check();


?>
<html>
<head>
<title>Cetak Surat Keterangan</title>
<style>body 
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
$kelas2 = de($_POST['kelas2']);
$q = $db->query("select ds.`gelar_depan`,ds.`nama_dosen`,ds.`gelar_belakang`,rf.`nm_ruang`,jk.`hari`,jk.`jam_mulai`,jk.`jam_selesai`, matkul.kode_mk, f.nama_resmi, j.nama_jur, jj.`jenjang`,kelas.kls_nama,kelas.sem_id, matkul.`sks_tm`,kelas.kode_paralel,
                                                              kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
                                                              kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
                                                              join kurikulum k on k.kur_id=matkul.kur_id
                                                              JOIN jadwal_kuliah jk ON jk.`kelas_id`= kelas.`kelas_id`
                                                              join jurusan j on j.kode_jur=k.kode_jur 
                                                              JOIN fakultas f ON f.`kode_fak`=j.`fak_kode`
                                                              JOIN jenjang_pendidikan jj ON jj.`id_jenjang`=j.`id_jenjang`
                                                              JOIN ruang_ref rf ON rf.`ruang_id`=jk.`ruang_id`
                                                              JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id`
                                                              JOIN dosen ds ON ds.`nip`=dk.`id_dosen`
                                                              where kelas.kelas_id = '$kelas2' ");

foreach ($q as $kel) {
  $matkul = $kel->nama_mk;
  $kls = $kel->kls_nama;
  $jur = $kel->nama_jur;
  $sem = $kel->sem_id;
}
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

 $qp= $db->query("select count(k.id_krs_detail) as jml from krs_detail k join kelas kl on k.id_kelas=kl.kelas_id
                                                 where k.disetujui='1' and kl.kelas_id='$kelas2' group by kl.kelas_id  ");
                                    foreach ($qp as $kp) {
                            
                                    }
?>
</html>
<!-- saved from url=(0191)https://siakad.fst.uinsgd.ac.id/gtakademik/peserta_kelas/presensi_ujian_cetak.php?w=bd1c68ccb9mrlum28002&x=14b5dpsoru5bf48uupur79ef0&y=14b5dlpqou5bf48uupur79ef0&z=14b5d5bf48pum3$79ef0&jenis=T -->
<html class="gr__siakad_fst_uinsgd_ac_id"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>CETAK PRESENSI UJIAN SEMUA KELAS</title>
<link rel="stylesheet" type="text/css" href="./CETAK PRESENSI UJIAN SEMUA KELAS_files/a_sia_cetak.css">
<link rel="stylesheet" type="text/css" media="print" href="./CETAK PRESENSI UJIAN SEMUA KELAS_files/a_sia_cetak_media_print.css">
<style rel="stylesheet" type="text/css" title="Default" media="all">
#separator {
  border: 0px solid #fff;
  background-color: #fff;
  padding: 2px;
  border-right: 1px dashed #ccc;
}
#separatora {
  border: 0px solid #fff;
  background-color: #fff;
  /*border-right: 1px dashed #fff;*/
}
#noborder td {
  border: 0px;
}
.tabel-common .nama {
  width: 150px;
  overflow: hidden;
}
html, body {
  margin: 1;
  padding: 0;
  height: 100%; /* Required */
}
#container-page {
  width: 600px;
  background: #DDD;
  position: relative;
  min-height: 100%; /* For Modern Browsers */
  height: auto !important; /* For Modern Browsers */
  height: 100%; /* For IE */
}
#container-content {
  padding-bottom: 40px;
}
#container-foot {
  width: 100%;
  background: #CCC;
  position: absolute;
  bottom: 0 !important;
  bottom: -1px; /* For Certain IE widths */
  height: 40px;
}
</style>
</head>
<body onLoad="window.print();">
<div class="page-portrait">

<div class="page-break">

  <table width="100%" class="tabel-common">


  <tbody><tr id="noborder">
    <td colspan="8" id="separator" width="*" style="border-right: 1px dashed #ccc;">
   <table>
      <tbody><tr>
         <td>
             <img src="../../assets/login/img/logokerinci.png" width="100" height="100">
           </td><td>
            <h1><?= $identitas->header ?></h1>
        
                  <h3>FAKULTAS <?= $kel->nama_resmi ?></h3>

        <hr>
        <?= $identitas2->alamat ?>
         </td>
      </tr>
   </tbody></table>

      <!--table width="100%">
        <tr>
          <td width="7%"><img src="../images/logo.jpg" /></td>
          <td width="93%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
        </tr>
      </table-->

      <br>

      <h2 align="center">DAFTAR PESERTA UJIAN<br>DAN NILAI UJIAN  TENGAH SEMESTER</h2>
       <h4 align="center">Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>/<?= $kkk->tahun+1 ?></h4><br>

      <table class="tabel-info">
      <tbody><tr>
        <td>Nama Kelas</td>
        <td>:</td>
        <td><?= $kls ?></td>
      </tr>
      <tr>
        <td>Kode Matakuliah</td>
        <td>:</td>
        <td><?= $kel->kode_mk ?></td>
      </tr>
      <tr>
        <td>Matakuliah</td>
        <td>:</td>
        <td><?= $matkul ?></td>
      </tr>
      <tr>
        <td>Jumlah Peserta</td>
        <td>:</td>
        <td><?= $kp->jml ?></td>
      </tr>
      <tr>
        <td>Dosen</td>
        <td>:</td>
        <td><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></td>
      </tr>
      <tr>
        <td>Hari, Tanggal</td>
        <td>:</td>
        <td><?= $kel->hari ?></td>
      </tr>
      <tr>
        <td>Ruang</td>
        <td>:</td>
        <td><?= $kel->nm_ruang ?></td>
      </tr>
      </tbody></table>
    </td>
    <td>&nbsp;</td>
    <td colspan="4" width="180">
      <br><br><br><br><br><h3 align="center">DAFTAR NILAI UJIAN  TENGAH SEMESTER</h3>
     <center><b>Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>/<?= $kkk->tahun+1 ?></b></center><br><br>

      <table class="tabel-info">
      <tbody><tr>
        <td>Kelas</td>
        <td>:</td>
        <td><?= $kls ?></td>
      </tr>
      <tr>
        <td>Prodi Studi</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3"><?= $jur ?> - <?= $kel->jenjang ?> Reguler</td>
      </tr>
      <tr>
        <td>Kode MK</td>
        <td>:</td>
        <td><?= $kel->kode_mk ?></td>
      </tr>
      <tr>
        <td>Matakuliah</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3"><?= $matkul ?></td>
      </tr>
      </tbody></table>

    </td>
  </tr>
  <tr>
    <th>NO</th>
    <th>NIM</th>
    <th width="150">NAMA</th>
    <th>PRODI</th>
    <th nowrap="nowrap">HADIR<br> (%)</th>
    <th>NILAI</th>
    <th>TANDA<br> TANGAN</th>
    <th id="separator" width="1"></th>
    <th id="separatora" width="1"></th>
    <th>NO</th>
    <th>NIM</th>
    <th>NILAI</th>
  </tr>

    <?php
            $q=$db->query("SELECT m.nim, m.nama, m.jur_kode FROM krs_detail k 
                            JOIN krs kr ON k.id_krs = kr.krs_id
                            JOIN kelas kl ON k.id_kelas=kl.kelas_id 
                            JOIN mahasiswa m ON kr.mhs_id = m.nim
                            WHERE k.disetujui='1' AND kl.kelas_id='$kelas2'
                            ORDER BY m.`nim` ASC");
           

           
            $no=1;
            foreach ($q as $kr) {
             echo "
                  <tr>
                  </tr>
                  <tr>
                      <td style='text-align:center; vertical-align:middle;' height='34' >$no</td>
                      <td style='text-align:center; vertical-align:middle;'>$kr->nim</td>
                      <td style='vertical-align:middle;' width='250'>$kr->nama</td>
                      <td style='text-align:center; vertical-align:middle;'>$kr->jur_kode</td>
                      <td style='text-align:center'></td>
                      <td style='text-align:center'></td>
                      <td style='text-align:center'></td>
                      <td id='separator' width='1''></td>
                      <td id='separator' width='1'></td>
                      <td style='text-align:center; vertical-align:middle;' height='34' >$no</td>
                      <td style='text-align:center; vertical-align:middle;'>$kr->nim</td>
                      <td style='text-align:center'></td>
                  </tr>";
              $no++;
            }
         ?>
<tr id="noborder">
    <td colspan="8" id="separator" style="border-right: 1px dashed #ccc;"><br><b>Mengetahui,</b><br></td>
    <td colspan="4"></td>
  </tr>
  <tr>
    <th>NO</th>
    <th colspan="4">Nama Dosen</th>
    <th colspan="2">Tanda Tangan</th>
    <th id="separator" width="1"></th>
    <th id="separatora" width="1"></th>
    <th>No</th>
    <th colspan="2">Tanda Tangan</th>
  </tr>
  <tr height="30">
    <td align="right">1</td>
    <td colspan="4"><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></td>
    <td colspan="2"></td>
    <td id="separator" width="1"></td>
    <td id="separatora" width="1"></td>
    <td align="right">1</td>
    <td colspan="2"></td>
  </tr>


  <tr id="noborder">
    <td colspan="8" id="separator" style="border-right: 1px dashed #ccc;">&nbsp;</td>
    <td border="0">&nbsp;</td>
    <td colspan="3"></td>
  </tr>
  <tr>
    <th>NO</th>
    <th colspan="4">Nama Pengawas</th>
    <th colspan="2">Tanda Tangan</th>
    <th id="separator" width="1"></th>
    <th id="separatora" width="1"></th>
    <th>No</th>
    <th colspan="2">Tanda Tangan</th>
  </tr>

  <tr height="30">
    <td align="right">1</td>
    <td colspan="4">&nbsp;</td>
    <td colspan="2"></td>
    <td id="separator" width="1"></td>
    <td id="separatora" width="1"></td>
    <td align="right">1</td>
    <td colspan="2"></td>
  </tr>
  <tr height="30">
    <td align="right">2</td>
    <td colspan="4">&nbsp;</td>
    <td colspan="2"></td>
    <td id="separator" width="1"></td>
    <td id="separatora" width="1"></td>
    <td align="right">2</td>
    <td colspan="2"></td>
  </tr>



  </tbody></table>

  

 
</div>



</div>


</body></html>