<?php
session_start();
include "../../inc/config.php";

session_check();

?>
<html>
<head>
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
<link rel="stylesheet" type="text/css" href="../styles/a_sia_cetak.css" />
<link rel="stylesheet" type="text/css" media="print" href="../styles/a_sia_cetak_media_print.css" />
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
<?php

/*$matkul_filter = "";
$hari_filter = "";


$jur_filter = ' and k.`kode_jur`="'.$_POST['jur_filter'].'"';
$sem_filter = ' AND kelas.`sem_id`="'.$_POST['sem_filter'].'"';


if ($_POST['matkul_filter']!='all') {
  $matkul_filter = ' AND kelas.`id_matkul`="'.$_POST['matkul_filter'].'"';
}
if ($_POST['hari_filter']!='all') {
  $hari_filter = ' and jk.hari="'.$_POST['hari_filter'].'"';
}


$q = $db->query("SELECT ds.`nip`,ds.`gelar_depan`,ds.`nama_dosen`,ds.`gelar_belakang`,rf.`nm_ruang`,jk.`hari`,jk.`jam_mulai`,jk.`jam_selesai`, matkul.kode_mk, j.nama_jur,
jj.`jenjang`,kelas.kls_nama,kelas.sem_id, matkul.`sks_tm`,kelas.kode_paralel, kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id 
JOIN jadwal_kuliah jk ON jk.`kelas_id`= kelas.`kelas_id` 
JOIN jurusan j ON j.kode_jur=k.kode_jur
JOIN jenjang_pendidikan jj ON jj.`id_jenjang`=j.`id_jenjang` 
JOIN ruang_ref rf ON rf.`ruang_id`=jk.`ruang_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` 
JOIN dosen ds ON ds.`nip`=dk.`id_dosen`
WHERE kelas.kelas_id is not null $jur_filter $sem_filter $matkul_filter $hari_filter  ORDER BY nama_dosen ASC");
*/

$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vnk.kode_jur in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vnk.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);
$sem_filter = "and vnk.sem_id='".$semester_aktif->id_semester."'";
$matkul_filter = "";
$hari_filter = "";
$jenis_kelas = "";

if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and vnk.kode_jur="'.$_POST['jur_filter'].'"';
  }

  if ($_POST['sem_filter']!='all') {
    $sem_filter = ' and vnk.sem_id="'.$_POST['sem_filter'].'"';
  }

  if ($_POST['matkul_filter']!='all') {
    $matkul_filter = ' and vnk.id_matkul="'.$_POST['matkul_filter'].'"';
  }
  if ($_POST['hari_filter']!='all') {
    $hari_filter = ' and vj.hari="'.$_POST['hari_filter'].'"';
  }
  if ($_POST['jenis_kelas']!='all') {
    $jenis_kelas = ' and jenis_kelas.id="'.$_POST['jenis_kelas'].'"';
  }


}
$i=1;
// echo "SELECT DO.nip, vnk.kelas_id, je.jenjang, (mk.sks_tm+ mk.sks_prak+ mk.sks_prak_lap+ mk.sks_sim) AS sks_tm,
//  vnk.sem_id, j.nama_jur, vnk.kode_jur, vj.hari,vj.jam_mulai,vj.jam_selesai, sem_matkul,
//  nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,
//  vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,
//  vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,
//  fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
//  fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
//  from view_nama_kelas vnk
//  left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
//  inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
//  JOIN jurusan j ON j.kode_jur=vnk.kode_jur
//  JOIN matkul mk ON mk.kode_mk=vnk.kode_mk
//  JOIN jenjang_pendidikan  je ON je.id_jenjang=j.id_jenjang
//  LEFT JOIN dosen_kelas dk ON dk.id_kelas=vnk.kelas_id
//  LEFT JOIN dosen DO ON DO.nip=dk.id_dosen where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas group by kelas_id"; die();
$q=$db->query("SELECT DO.nip, vnk.kelas_id, je.jenjang, (mk.sks_tm+ mk.sks_prak+ mk.sks_prak_lap+ mk.sks_sim) AS sks_tm,
 vnk.sem_id, j.nama_jur, vnk.kode_jur, vj.hari,vj.jam_mulai,vj.jam_selesai, sem_matkul,
 nm_matkul,nama_kelas,vj.nm_ruang,vnk.id_matkul,
 vnk.nama_mk,vnk.nama_kelas,vj.waktu,vnk.peserta_max,vnk.jurusan,
 vnk.kelas_id,fungsi_get_jml_krs(vnk.kelas_id) as jml,
 fungsi_get_jml_krs_belum_disetujui(vnk.kelas_id) as belum_disetujui,
 fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,jenis_kelas.nama_jenis_kelas
 from view_nama_kelas vnk
 left join view_jadwal vj on vnk.kelas_id=vj.kelas_id
 inner join jenis_kelas on vnk.id_jenis_kelas=jenis_kelas.id
 JOIN jurusan j ON j.kode_jur=vnk.kode_jur
 JOIN matkul mk ON mk.kode_mk=vnk.kode_mk
 JOIN jenjang_pendidikan  je ON je.id_jenjang=j.id_jenjang
 LEFT JOIN dosen_kelas dk ON dk.id_kelas=vnk.kelas_id
 LEFT JOIN dosen DO ON DO.nip=dk.id_dosen where vnk.kelas_id is not null $sem_filter $jur_filter  $hari_filter $matkul_filter $jenis_kelas group by kelas_id");
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
<!-- <body onLoad="window.print();"> -->
  <body>
   <div class="page-landscape">
     <?php
     foreach ($q as $kel) {
      $qd = $db->query("select d.nip,d.gelar_depan,d.`nama_dosen`,d.gelar_belakang,dk.`dosen_ke` from dosen_kelas dk 
join dosen d on dk.`id_dosen`=d.nip where dk.`id_kelas`='$kel->kelas_id'");
      $matkul = $kel->nama_mk;
      $kls = $kel->nama_kelas;
      $jur = $kel->nama_jur;
      $sem = $kel->sem_id;
      ?>    
      <div class="page-break">      

       <table class="tabel-info" width="100%">
        <tr>
         <td rowspan = "6" width ="35%" valign="top">
           <table >
            <tr>
             <td>
              <img src="<?=base_url();?>upload/logo/<?=$db->fetch_single_row('identitas_logo','id_logo',1)->logo;?>" width="100" height="100">
            </td>
            <td>
              <h4><?= $identitas->header ?></h4>

              <hr />
              <div style="font-size:10"><?= $identitas2->alamat ?></div>
            </td>
          </tr>
        </table>


      </td>
      <td nowrap ="nowrap" colspan="6"><u>DAFTAR PRESENSI KULIAH&nbsp;</u></td>
    </tr>
    <tr>
     <td nowrap ="nowrap" >Mata Kuliah</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $matkul ?></td>
     <td nowrap ="nowrap" >Periode</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $periode->tahun_akademik ?></td>
   </tr>
   <tr>
     <td nowrap ="nowrap" >Kelas</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $kls ?></td>
     <td nowrap ="nowrap" >Program Studi</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $jur ?> - <?= $kel->jenjang ?> Reguler</td>
   </tr>
   <tr>

     <td nowrap ="nowrap" >Bobot SKS</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $kel->sks_tm ?>&nbsp;sks</td>
     <td nowrap ="nowrap" >Ruang</td>
     <td nowrap ="nowrap" >:</td>
     <td nowrap ="nowrap" ><?= $kel->nm_ruang ?></td>
   </tr>
   <tr>
     <td nowrap ="nowrap"  valign="top">Hari / Waktu</td>
     <td nowrap ="nowrap"  valign="top">:</td>
     <td nowrap ="nowrap"  valign="top"><?= $kel->hari ?> / <?= $kel->jam_mulai ?>-<?= $kel->jam_selesai ?></td>
     <td nowrap ="nowrap" ></td>
     <td nowrap ="nowrap" ></td>
     <td nowrap ="nowrap" ></td>
   </tr>
   <tr>
     <td nowrap ="nowrap"  valign="top">Dosen Pengampu</td>
     <td nowrap ="nowrap"  valign="top">:</td>
     <td nowrap ="nowrap"  valign="top"><ol style="padding:0 0 0 15;">
     <?php
     foreach ($qd as $kd) {
      ?><li> <?= $kd->gelar_depan." ".$kd->nama_dosen.",".$kd->gelar_belakang ?></li><?php
     }
     ?>
    </ol></td>
     <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   </tr>
 </table>
 <br />
 <table width="100%" class="tabel-common">
  <tr>
   <th rowspan="3">No.</th>
   <th rowspan="3">NIM</th>
   <th rowspan="3" width="40%">Nama</th>
   <th colspan="16">Perkuliahan Ke / Tanggal</th>
   <th rowspan="2" colspan="3">Rekapitulasi</th>
   <th rowspan="3" width="6%">Ket</th>
 </tr>
 <tr>
   <th width="4%">1</th>
   <th width="4%">2</th>
   <th width="4%">3</th>
   <th width="4%">4</th>
   <th width="4%">5</th>
   <th width="4%">6</th>
   <th width="4%">7</th>
   <th width="4%">8</th>
   <th width="4%">9</th>
   <th width="4%">10</th>
   <th width="4%">11</th>
   <th width="4%">12</th>
   <th width="4%">13</th>
   <th width="4%">14</th>
   <th width="4%">15</th>
   <th width="4%">16</th>
 </tr>
 <tr>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   <th width="4%">Target</th>
   <th width="4%">Hadir</th>
   <th width="4%">%</th>

 </tr>
 <?php
 $q=$db->query("SELECT m.nim, m.nama FROM krs_detail k 
  JOIN kelas kl ON k.id_kelas=kl.kelas_id
  JOIN mahasiswa m ON k.nim = m.nim
  WHERE k.disetujui='1' AND kl.kelas_id='$kel->kelas_id'
  ORDER BY m.`nim` ASC");


 $no=1;
 foreach ($q as $kr) {
   echo "
   <tr>
   </tr>
   <tr>
   <td style='text-align:center; vertical-align:middle;' height='34' >$no</td>
   <td style='text-align:center; vertical-align:middle;' width='150'>$kr->nim</td>
   <td style='vertical-align:middle;' width='250'>$kr->nama</td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>
   <td style='text-align:center'></td>

   </tr>";
   $no++;
 }
 ?>
 <tr>
  <th colspan="23">DOSEN PENGAMPU</th>
</tr>
<?php
$qd = $db->query("select d.nip,d.gelar_depan,d.`nama_dosen`,d.gelar_belakang,dk.`dosen_ke` from dosen_kelas dk 
join dosen d on dk.`id_dosen`=d.nip where dk.`id_kelas`='$kel->kelas_id'");
foreach ($qd as $kd) {
 ?>
 <tr>
     <td nowrap ="nowrap" height="34" style="text-align:center; vertical-align:middle;"><?= $kd->dosen_ke ?></td>
     <td nowrap ="nowrap" style="text-align:center; vertical-align:middle;"><?= $kd->nip ?></td>
     <td style="vertical-align:middle;"><div class="nama"><?= $kd->gelar_depan." ".$kd->nama_dosen.",".$kd->gelar_belakang ?> </div></td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
     <td nowrap ="nowrap" >&nbsp;</td>
</tr>
 <?php
}
?>




<tr valign="center">
  <td colspan="3" height="34" >Jumlah Mahasiswa Hadir</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
</tr>
<tr valign="center">
  <td colspan="3" height="34" >Paraf Dosen</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
  <td nowrap ="nowrap" >&nbsp;</td>
</tr>
<tr style="display:none">
  <th colspan="23">MAHASISWA</th>
</tr>
<div>
  <div class=""></div>
  <table align="right" width="100%">
    <tr>
     <td width="70%" rowspan="3">&nbsp;</td> 
     <br><td align="center"><?= ucfirst($identitas_kota->kota) ?>, <?php echo tgl_indo(date("Y-m-d"));?><br /><span id="tipe_pengesahan0">...............</span><br /><span id="jabatan0"></span></td>
   </tr>
   <tr>
     <td align="center" height="50"></td>
   </tr>
   <tr>
     <td align="center" nowrap="nowrap"><span id="nama0">........................................</span><br />------------------------------
       <br />NIP: <span id="nip0">..............................</span></td>
     </tr>
   </table> 


   <span class="link"><a href="../popup/pejabat/?act=02f07abfc79c89cab181ae7ce470acda&w=0&x=4ac2eNvGz6!DNIf45c5zNzMaa0010&y=4ac2eDPCvOf45c5zBIz8a0010" onClick="window.open('../popup/pejabat/?act=02f07abfc79c89cab181ae7ce470acda&w=0&x=4ac2eNvGz6!DNIf45c5zNzMaa0010&y=4ac2eDPCvOf45c5zBIz8a0010','','height=600,resizable=yes,scrollbars=yes,width=750'); return false;">Ganti Pejabat Pengesah</a></span>
 </div>

 <?php
 $i++;
}
?> 

</div>

</page>
</body>
</html>
