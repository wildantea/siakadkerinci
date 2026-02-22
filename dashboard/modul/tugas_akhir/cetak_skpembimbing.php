<?php 
session_start();
include "../../inc/config.php";
session_check();

$data=array(
      "val_tugas_akhir" => "1",
    );
$up = $db->update('tugas_akhir',$data,'id_ta',$_POST['id']);

$pembimbing_1 ="";
$pembimbing_2 ="";
$nip_1="";
$nip_2="";
foreach ($db->fetch_all("dosen") as $do){
  if($do->id_dosen == $_POST['pembimbing_1']) {
    $pembimbing_1 = $do->gelar_depan.$do->nama_dosen.$do->gelar_belakang;
    $nip_1 = $do->nip;
  }else if ($do->id_dosen == $_POST['pembimbing_2']) {
    $pembimbing_2 = $do->gelar_depan.$do->nama_dosen.$do->gelar_belakang;
    $nip_2 = $do->nip;
  }
}

$nip = "";
$wakil_dekan = "";
foreach ($db->fetch_all("pejabat") as $pj) {
  $wakil_dekan = $pj->nama_pejabat;
  $nip = $pj->nip;
}

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

  $idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='4'");
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
    <table width="100%">
      <tbody>
        <tr>
          <td>
            <img src="../../assets/login/img/logokerinci.png" width="100" height="100">
          </td>
          <td align="center">
            <h1 align="center">
              <?= $identitas->header ?>
            </h1>
            <p align="center" style="margin-top: -10px;"><?= $identitas2->alamat ?></p>
          </td>
        </tr>
      </tbody>
    </table>
    <hr style="color: black; margin-top: -10px; margin-bottom: 5px;" width="100%">
    <hr style="color: black; margin-top: -5px; margin-bottom: 5px;" width="100%">
    <br>
      <h1 align="center" style="margin-bottom: 5px;margin-top: 20px">SURAT KEPUTUSAN</h1>
      <h1 align="center">WAKIL DEKAN I BIDANG AKADEMIK DAN KELEMBAGAAN</h1>
      <h1 align="center">SEKOLAH TINGGI AGAMA ISLAM NEGERI (STAIN) KERINCI</h1>
      <h4 align="center" style="margin-bottom: 5px;">Nomor : <?php echo $_POST['no_sk'];?></h4>
      
      <h3 align="center" style="margin-bottom: 5px;">TENTANG</h4>
      <h3 align="center">PENETAPAN JUDUL DAN PEMBIMBING SKRIPSI</h3>
      <h3 align="center" style="margin-bottom: 5px;">MAHASISWA STAIN KERINCI TAHUN <?php echo date('Y');?></h3>

      <h3 align="center" style="margin-bottom: 5px;">WAKIL DEKAN I BIDANG AKADEMIK DAN KELEMBAGAAN STAIN KERINCI</h3>
    <br>
    <div class="nobreak">
    <table class="tabel" width="100%" border="0" style="margin-top: 30px;">
    <tbody>
    <tr>
    <td>
    <pre>
    Menimbang &nbsp;&nbsp;&nbsp;&nbsp;: 1. Bahwa untuk kelancaran pelaksanaan penyusunan skripsi mahasiswa program S.1 STAIN Kerinci, dirasa perlu menetapkan judul dan dosen 
                       pembimbing skripsi mahasiswa.
                    2. Bahwa dosen yang namanya tersebut dalam Surat Keputusan ini dipandang cakap dan mampu melaksanakan tugas tersebut.
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -5px;">
    Mengingat &nbsp;&nbsp;&nbsp;&nbsp;: 1. Keputusan Menteri Agama Nomor 173 Tahun 2008 tentang Statuta STAIN Kerinci.
                    2. Buku Panduan Informasi Akademik STAIN Kerinci Tahun 2011.
                    3. Buku Pedoman Penulisan Skripsi Mahasiswa STAIN Kerinci Tahun 2011.
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -5px;">
    Memperhatikan : 1. Keputusan Ketua STAIN Kerinci Nomor 47/STAIN-Krc/2006 tanggal 7 Januari 2006, tentang Pengangkatan Pembimbing I dan II Penulisan Skripsi mahasiswa STAIN Kerinci.
                    2. Surat Ketua Jurusan Ekonomi dan Bisnis Islam Nomor 613 tanggal 09 Desember 2016 tentang penunjukan Dosen Pembimbing Skripsi sementara mahasiswa.
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <h3 align="center" style="margin-top: -5px; margin-top: -5px; margin-left: -200px;">
    MEMUTUSKAN
    </h3>
    </td>
    </tr>
    <tr>
    <td>
    <pre>
    Menetapkan : 1. Menunjuk staf pengajar yang tersebut dibawah ini
                    1. <?php echo $pembimbing_1; ?><br/>
                       <pre style="margin-top: -25px;">
                       NIP. <?php echo $nip_1;?><br/>
                       </pre>
                    <pre style="margin-top: -60px;">
                    2. <?php echo $pembimbing_2;?><br/>
                    </pre>
                       <pre style="margin-top: -60px">
                       NIP. <?php echo $nip_2;?><br/>
                       </pre>
                         
    </pre>
    </td>
    <td>
    <pre style="margin-left: -900px; margin-top: -72px;">
    &nbsp;
    Sebagai Pembimbing I
    &nbsp;
    Sebagai Pembimbing II
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -60px;">
                    Sebagai Pembimbing Skripsi mahasiswa yang tersebut dibawah ini:
                    <pre style="margin-top: -10px;">
                    Nama          : <?php echo $_POST['nama'];?><br/>
                    </pre>
                    <pre  style="margin-top: -60px;">
                    NIM           : <?php echo $_POST['nim'];?><br/>
                    </pre>
                    <pre style="margin-top: -60px;">
                    Jurusan       : <?php echo $_POST['fakultas'];?><br/>                     
                    </pre>
                    <pre style="margin-top: -60px;">
                    Program Studi : <?php echo $_POST['jurusan'];?><br/>
                    </pre>
                    <pre style="margin-top: -60px;">
                    Judul Skripsi : <b><?php echo $_POST['judul_ta'];?></b><br/>              
                    </pre>
                 <pre style="margin-top: -50px;">
                 2. Kepada dosen pembimbing yang ditunjuk agar dapat melaksanakan tugasnya sebagai pembimbing dengan sebaik - baiknya
                 3. Surat Keputusan ini berlaku sejak tanggal ditetapkan, seandainya ada kesalahan dalam penetapan ini akan diperbaiki sebagaimana mestinya.
     </pre>
    </pre>
    </td>
    </tr>
    <tr>
    <td align="left">
    <pre style="margin-left: 700px">
    DITETAPKAN DI : Sungai Penuh
    PADA tanggal  : <?php echo tgl_indo(date("Y-m-d"));?>
    <pre style="margin-top: -1px;">
    An. Ketua
    Wakil Dekan I Bidang Akademik Dan Kelembagaan,
    </pre><br/><br/><br/><br/><br/> 

    <?php echo $wakil_dekan;?><br/>
    <pre style="margin-top: -25px;">
    NIP. <?php echo $nip;?> </pre> 
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre>
    Tembusan:
    1. Yth. KETUA STAIN Kerinci
    2. Ketua Jurusan Ekonomi dan Bisnis Islam
    3. Ketua Program Studi Perbankan Syariah (PbS)
    4. Arsip
    </pre>
    </td>
    </tr>            
      <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
         </tr> -->
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
