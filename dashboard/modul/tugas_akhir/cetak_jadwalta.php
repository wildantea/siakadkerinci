<?php 
session_start();
include "../../inc/config.php";
session_check();

$kassubag = "";
$nip = "";
foreach ($db->fetch_all("pejabat") as $pj) {
  if($pj->id_pejabat == 4) {
    $kassubag = $pj->nama_pejabat;
    $nip = $pj->nip;
  }
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
  width: 25.8cm;
  height: 21cm;
}
page[size="A5"][layout="portrait"] {
  width: 21cm;
  height: 25.8cm;  
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
  $priode="";

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

  if(isset($_POST['priode_filter'])) {
    if($_POST['priode_filter']!='all') {
      $priode = ' and p.id_muna="'.$_POST['priode_filter'].'"';
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
    <pre style="margin-top: 10px;font-size: 20px;">Nomor    : <?php echo $_POST['no_sk'];?></pre>
    <pre align="right" style="margin-top: -52px;font-size: 20px;">Sungai Penuh, <?php echo date('Y-m-d');?></pre><br/>
    <pre style="margin-top: -10px;font-size: 20px;">Lampiran : Satu Lembar</pre>
    <pre style="margin-top: 5px;font-size: 20px;">Perihal  : Jadwal Ujian Munaqasyah</pre>
    <br>
    <div class="nobreak">
    <table class="tabel" width="100%" border="0" style="margin-top: 30px;">
    <tbody>
    <tr>
    <td>
    <pre style="margin-top: -35px;font-size: 20px;">
           <b>A.n. Anjas Putra, dkk</b>
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -5px;font-size: 20px;">
           Kepada
           Yth. Bapak/Ibu  ..............................
           Ketua/Sekretaris/Anggota Tim Penguji Ujian Skripsi
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: 5px;font-size: 20px">
           Di.
                Sungai Penuh
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="font-style: italic;font-size: 20px;">
           Assalamu’alaikum warrahmatullahi wabarakatuh,
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -2px;font-size: 20px;text-align: justify;">
           Dengan hormat, dalam rangka pelaksanaan ujian skripsi (munaqasah) mahasiswa<br/>
           IAIN Kerinci, kami mohon kesediaan Bapak/Ibu sebagai Ketua/Sekretaris/Anggota<br/>
           tim penguji pada ujian dimaksud sebagaimana jadwal terlampir.                
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="margin-top: -2px;font-size: 20px;">
           Demikian disampaikan, atas kesediaan bapak/ibu diucapkan terima kasih.
    </pre>
    </td>
    </tr>
    <tr>
    <td>
    <pre style="font-style: italic;font-size: 20px;">
           Wassalamu’alaikum warrahmatullahi wabarakatuh,
    </pre>
    </td>
    </tr>
    <tr>
    <td align="left">
    <pre style="margin-left: 700px;font-size: 20px;">
    An. Ketua
    <pre style="margin-top: -15px;font-size: 20px;">
      Kasubbag Akademik, Kemahasiswaan,
      Alumni dan Kerja Sama
    </pre><br/><br/><br/><br/><br/> 

      <?php echo $kassubag;?><br/>
    <pre style="margin-top: -25px;font-size: 20px;">
      NIP. <?php echo $nip;?> </pre> 
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

  <div class="page-landscape">
  <div class="page-break">
<table width="100%">
<tr>
<td>
<pre style="margin-top: -5px;font-size: 15px;">
LAMPIRAN : JADWAL UJIAN MUNAQASYAH SKRIPSI SEKOLAH TINGGI AGAMA ISLAM NEGERI (STAIN) KERINCI
NOMOR    : <?php echo $_POST['no_sk']; ?>
</pre>
</td>
</tr>
<tr>
<td>
<pre style="margin-top: -5px;font-size: 15px;">
         Hari     :
         Tanggal  : <?php echo tgl_indo(date('Y-m-d'));?><br/>
<pre style="margin-top: -20px;font-size: 15px;">
         Pukul    :
         Tempat   : Ruang Munaqasah
</pre>
</pre>s
</td>
</tr>
</table>
<table width="100%" style="margin-top: -40px;" border="1">
<tr>
<td rowspan="2" width="20">Jam Ke</td>
<td colspan="2" rowspan="2" width="70">NAMA</td>
<td colspan="2" rowspan="2" width="10">SMT</td>
<td colspan="2" rowspan="2" width="70">Prodi</td>
<td colspan="2" rowspan="2" width="70">JUDUL SKRIPSI</td>
<td colspan="2">Tim Penguji</td>
</tr>
<tr>
<td>Penguji</td>
<td>Jabatan</td>
</tr>
<?php
  $jadwal = $db->query("select *,ta.id_ta,ta.nim,ta.penguji_1,ta.penguji_2,ta.penguji_3,peng1.nama_dosen as peng_1,peng2.nama_dosen as peng_2,peng3.nama_dosen as peng_3,ketua.nama_dosen as ket,sekre.nama_dosen as sekertaris,mhs.nama,j.nama_jur from tugas_akhir ta
  inner join mahasiswa mhs on ta.nim=mhs.nim 
  inner join jadwal_muna p on p.id_muna=ta.priode_muna
  inner join dosen peng1 on ta.penguji_1=peng1.id_dosen
  inner join dosen peng2 on ta.penguji_2=peng2.id_dosen
  inner join dosen peng3 on ta.penguji_3=peng3.id_dosen
  inner join dosen ketua on ta.ketua_sidang=ketua.id_dosen
  inner join dosen sekre on ta.sekertaris_sidang=sekre.id_dosen 
  inner join fakultas f on ta.kode_fak=f.kode_fak
  inner join jurusan j on ta.kode_jurusan=j.kode_jur 
  where ta.id_ta is not null $fakultas $jurusan $priode order by ta.id_ta");
  $no = 1;
  foreach ($jadwal as $ta) {
    if($no > 4) {
      $no = 1;
    }
?>
<tr>
<td rowspan="2" width="20"><?php echo $no;?></td>
<td colspan="2" rowspan="2" width="70"><?php echo $ta->nama;?></td>
<td colspan="2" rowspan="2" width="10"><?php echo "";?></td>
<td colspan="2" rowspan="2" width="70"><?php echo $ta->nama_jur;?></td>
<td colspan="2" rowspan="2" width="70"><?php echo $ta->judul_ta;?></td>
</tr>
<tr>
<td>
  <?php echo "1.".$ta->ket;?><br>
  <?php echo "2.".$ta->sekertaris;?><br/>
  <?php echo "3.".$ta->peng_1;?><br>
  <?php echo "4.".$ta->peng_2;?><br>
  <?php echo "5.".$ta->peng_3;?>
</td>
<td>
  Ketua Sidang<br/>
  Sekertaris Sidang<br/>
  Penguji / Anggota<br/>
  Penguji / Anggota<br/>
  Penguji / Anggota<br/>
</td>
</tr>

<?php
    $no++;
  }
?>
</table>
<pre>
<b>Catatan :</b>
- Mahasiswa hadir 15 menit sebelum ujian dimulai
- Mahasiswa mengenakan pakaian ujian (hitam-putih, jas almamater)
- Setelah ujian dilaksanakan Penguji langsung menyerahkan nilai ke Sekretaris Ujian
- Untuk Jurusan Syariah Dilaksanakan di Kampus I dan Jurusan Tarbiyah Dilaksanakan di Kampus II
- Untuk waktu pelaksanaan :
  <b>Jam ke-1 : 11.30 WIB-12.30 WIB
  Istirahat : 12.30 WIB-13.30 WIB
  Jam ke-2 : 13.30 WIB-14.30 WIB
  Jam Ke-3 : 14.30 WIB-15.30 WIB
  Jam Ke-4 : 15.30 WIB-16.30 WIB</b>
  </pre>
  <pre style="margin-left: 700px;margin-top: -130px;">
  An. Ketua
  <pre style="margin-top: -15px;">
  Kasubbag Akademik, Kemahasiswaan,
  Alumni dan Kerja Sama
  </pre><br/><br/><br/><br/><br/> 

  <?php echo $kassubag;?><br/>
  <pre style="margin-top: -25px;">
  NIP. <?php echo $nip;?> </pre> 
  </pre>
</div>
  </div>
</body>
</html>
