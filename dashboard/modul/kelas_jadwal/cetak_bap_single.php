<?php
session_start();
include "../../inc/config.php";

session_check();
 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
 $identity_kota = $db->fetch_single_row("identitas","id_identitas",3);

?>
<html>
<head>
<title>Cetak Presensi UTS</title>
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
   /* margin: 10px 0px 10px 0px; */
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

</style>
<script type="text/javascript">
 //window.print();
</script>
</head>
<?php
$q = $db->query("select j.nama_jur, ds.`gelar_depan`,ds.`nama_dosen`,ds.`gelar_belakang`,
  rf.`nm_ruang`,jk.`hari`,jk.`jam_mulai`,jk.`jam_selesai`, matkul.kode_mk, f.nama_resmi, j.nama_jur, jj.`jenjang`,kelas.kls_nama,kelas.sem_id, matkul.`sks_tm`,
  kelas.peserta_max,kelas.peserta_min,matkul.nama_mk,
  kelas.kelas_id from kelas inner join matkul on kelas.id_matkul=matkul.id_matkul
  join kurikulum k on k.kur_id=matkul.kur_id
  left JOIN jadwal_kuliah jk ON jk.`kelas_id`= kelas.`kelas_id`
  join jurusan j on j.kode_jur=k.kode_jur
  JOIN fakultas f ON f.`kode_fak`=j.`fak_kode`
  JOIN jenjang_pendidikan jj ON jj.`id_jenjang`=j.`id_jenjang`
  left JOIN ruang_ref rf ON rf.`ruang_id`=jk.`ruang_id`
  left JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id`
  left JOIN dosen ds ON ds.`nip`=dk.`id_dosen`
  left join semester sf on (sf.id_semester=kelas.sem_id and j.kode_jur=sf.kode_jur)
WHERE kelas.kelas_id=".$_POST['kelas_id']."  ORDER BY nama_dosen ASC limit 1 ");

$i=1;

$get_sem_first = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);
$periode = $db->fetch_custom_single("SELECT * from view_semester
WHERE id_semester='".$get_sem_first->sem_id."'");

$idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
 foreach ($idt as $identitas) {
   # code...
 }
 $idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
 foreach ($idt2 as $identitas2) {
   # code...
 }

?>

</html>
<!-- saved from url=(0191)https://siakad.fst.uinsgd.ac.id/gtakademik/peserta_kelas/presensi_ujian_cetak.php?w=bd1c68ccb9mrlum28002&x=14b5dpsoru5bf48uupur79ef0&y=14b5dlpqou5bf48uupur79ef0&z=14b5d5bf48pum3$79ef0&jenis=T -->
<html class="gr__siakad_fst_uinsgd_ac_id"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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


</style>
</head>
<body onLoad="window.print();">
<div class="page-portrait"  style="margin-left: 40px">
<?php
$no=0;
foreach ($q as $kel) {
  $matkul = $kel->nama_mk;
  $kls = $kel->kls_nama;
  $jur = $kel->nama_jur;
  $sem = $kel->sem_id;
  $kelas= $kel->kelas_id;
?>
  <div style="border: 1px solid black;width: 65px;height: 15px;position: fixed;top: 5px;right: 15px">
     FM-05-15-R0
  </div>
  <table width="100%" class="tabel-common">
  <tbody>
     <tr id="noborder">
    <td colspan="8" id="separator" width="*" >
   <table>
      <tbody><tr>
         <td style="vertical-align: top;">
            <img src="<?=base_url();?>upload/logo/<?=$db->fetch_single_row('identitas_logo','id_logo',1)->logo;?>" width="100" height="100">
           </td><td>
            <h1><?= $header_identity->isi ?></h1>

            <?= $alamat_identity->isi ?>
      </tr>
   </tbody></table>
<hr>


      <br>

      <h2 align="center"><u>BERITA ACARA PERKULIAHAN</u></h2>
      <h4 align="center">Semester : <?= $periode->tahun_akademik ?></h4><br>

      <table class="tabel-info">
      <tbody>
        <tr>
          <td>Nama Dosen</td>
          <td>:</td>
          <td><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></td>
        </tr>
        <tr>
          <td>Mata Kuliah</td>
          <td>:</td>
          <td><?= $matkul ?></td>
        </tr>
     <!--    <tr>
          <td>Hari/Tanggal</td>
          <td>:</td>
          <td><?= ucfirst($kel->hari) ?>,<?= tgl_indo($kel->uts) ?></td>
        </tr> -->
        <tr>
          <td>Kelas / Ruang</td>
          <td>:</td>
          <td><?= $kls ?> / <?= $kel->nm_ruang ?></td>
        </tr>


      <tr>
        <td>Prodi</td>
        <td>:</td>
        <td><?= $kel->nama_jur ?></td>
      </tr>
      </tbody></table>
    </td>

  </tr>

  <tr>
    <th>PERTEMUAN KE</th>
    <th>HARI /TANGGAL</th>
    <th style="width:40%">MATERI</th>
    <!-- <th>PRODI</th> -->
    <th nowrap="nowrap">TANDA TANGAN</th>

  </tr>
    <?php
            for ($i=1; $i <=14 ; $i++) {
             echo "
                  <tr style='height:50px'>
                      <td style='text-align:center; vertical-align:middle;' height='34' ></td>
                      <td style='text-align:center; vertical-align:middle;'></td>
                      <td style='vertical-align:middle;' width='250'></td>
                      <td style='text-align:center'></td>
                  </tr>";
            //  $no++;
            }
?>
  </tbody>
</table>

<?php
    $i++;
    $no++;
  }
  //echo "$no";
?>
</div>
</body></html>
