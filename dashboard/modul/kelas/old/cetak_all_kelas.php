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
$jur = $_POST['jur3'];
$sem = $_POST['sem3'];
$q = $db->query("SELECT ds.`nip`,ds.`gelar_depan`,ds.`nama_dosen`,ds.`gelar_belakang`,rf.`nm_ruang`,jk.`hari`,jk.`jam_mulai`,jk.`jam_selesai`, matkul.kode_mk, f.nama_resmi, j.nama_jur,
jj.`jenjang`,kelas.kls_nama,kelas.sem_id, matkul.`sks_tm`,kelas.kode_paralel, kelas.peserta_max,kelas.peserta_min,matkul.nama_mk, kelas.kelas_id FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id 
JOIN jadwal_kuliah jk ON jk.`kelas_id`= kelas.`kelas_id` 
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN fakultas f ON f.`kode_fak`=j.`fak_kode` 
JOIN jenjang_pendidikan jj ON jj.`id_jenjang`=j.`id_jenjang` 
JOIN ruang_ref rf ON rf.`ruang_id`=jk.`ruang_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` 
JOIN dosen ds ON ds.`nip`=dk.`id_dosen`
WHERE k.`kode_jur`='$jur' AND kelas.`sem_id`='$sem' ORDER BY nama_dosen ASC");

$i=1;


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
<body onLoad="window.print();">
   <div class="page-landscape">
   <?php
      foreach ($q as $kel) {
        $matkul = $kel->nama_mk;
  $kls = $kel->kls_nama;
  $jur = $kel->nama_jur;
  $sem = $kel->sem_id;
?>    
       <div class="page-break">      

        
         <!--table width="100%">
            <tr>
               <td width="5%"><img src="../images/logo.jpg" /></td>
               <td width="95%"><h1></h1><h3>Fakultas SAINS DAN TEKNOLOGI</h3></td>
            </tr>
         </table-->
           
          <!--  
         <h2 align="center">DAFTAR PRESENSI KULIAH</h2>
         <h4 align="center">Program Studi TEKNIK INFORMATIKA - S1 Reguler</h4>
         <h4 align="center">Semester : Ganjil 2010/2011</h4><br />
         /-->
         
         <table class="tabel-info" width="100%">
        <tr>
               <td rowspan = "6" width ="35%" valign="top">
   <table >
      <tr>
         <td>
            <img src="../../assets/login/img/logokerinci.png" width="100" height="100">
           </td>
         <td>
            <h4><?= $identitas->header ?></h4>
       
                  <h4>FAKULTAS <?= $kel->nama_resmi ?></h4>

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
               <td nowrap ="nowrap" ><?= $kkk->jns_semester ?> <?= $kkk->tahun ?>-<?= $kkk->tahun+1 ?></td>
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
               <td nowrap ="nowrap"  valign="top"><ol style="padding:0 0 0 15;"><li><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></li></ol></td>
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
                            JOIN krs kr ON k.id_krs = kr.krs_id
                            JOIN kelas kl ON k.id_kelas=kl.kelas_id 
                            JOIN mahasiswa m ON kr.mhs_id = m.nim
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

                  <tr>
                     <td nowrap ="nowrap" height="34" style="text-align:center; vertical-align:middle;">1</td>
                     <td nowrap ="nowrap" style="text-align:center; vertical-align:middle;"><?= $kel->nip ?></td>
                     <td style="vertical-align:middle;"><div class="nama"><?= $kel->gelar_depan ?> <?= $kel->nama_dosen ?> <?= $kel->gelar_belakang ?></div></td>
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
               <br><td align="center">Bandung, <?php echo tgl_indo(date("Y-m-d"));?><br /><span id="tipe_pengesahan0">...............</span><br /><span id="jabatan0"></span></td>
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
