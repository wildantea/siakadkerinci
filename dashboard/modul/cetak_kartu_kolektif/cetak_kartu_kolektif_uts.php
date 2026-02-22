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
    window.print();
  </script>
</head>
<body>
<?php
 $mhs_id=$_POST['nim'];
 $k = $_POST['k'];
 $sem = $_POST['sem'];
 $jur = $_POST['jur'];
$id_krs = array();
$i=0;
 $m = get_atribut_mhs($mhs_id);
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
 $fak=$db->query("SELECT fk.nama_resmi, jr.`nama_jur` FROM fakultas fk
JOIN jurusan jr ON jr.`fak_kode`=fk.`kode_fak`
WHERE jr.`kode_jur`='$jur'");
 foreach ($fak as $fk) {
   # code...
 }
?>
 <?php
  foreach ($_POST['selector'] as $id) {
            $id_krs[] = $id;
          $in_id_krs = implode(",", $id_krs);
         
           $q=$db->query("select k.mhs_id, mh. mulai_smt, mh.nama, m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai, r.nm_ruang, m.semester, kl.kls_nama from krs k 
join krs_detail kr on k.krs_id=kr.id_krs
join matkul m on m.id_matkul=kr.kode_mk
left join jadwal_kuliah j on j.kelas_id=kr.id_kelas
join semester s on s.sem_id=k.sem_id 
left JOIN ruang_ref r ON j.ruang_id=r.ruang_id
left JOIN kelas kl ON kl.`kelas_id` = kr.`id_kelas`
JOIN mahasiswa mh ON mh.`nim`=k.`mhs_id`
where s.id_semester='$sem' and k.krs_id='$id'");
         /*  echo "select k.mhs_id, mh. mulai_smt, mh.nama, m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai, r.nm_ruang, m.semester, kl.kls_nama from krs k 
join krs_detail kr on k.krs_id=kr.id_krs
join matkul m on m.id_matkul=kr.kode_mk
left join jadwal_kuliah j on j.kelas_id=kr.id_kelas
join semester s on s.sem_id=k.sem_id 
left JOIN ruang_ref r ON j.ruang_id=r.ruang_id
left JOIN kelas kl ON kl.`kelas_id` = kr.`id_kelas`
JOIN mahasiswa mh ON mh.`nim`=k.`mhs_id`
where s.id_semester='$sem' and k.krs_id='$id'";*/
          ?>
          <?php foreach ($q as $kk) {
            # code...
          }
          ?>


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
   <h2 align="center">KARTU UJIAN</h2>
    <h4 align="center">TENGAH SEMESTER <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>/<?= $kkk->tahun+1 ?></h4>
            <h4 align="center"></h4>
      <br>
    <table width="100%">
      <tbody><tr>
        <td nowrap="nowrap" width="15%">Nama</td>
        <td width="2%">:</td>
        <td nowrap="nowrap" width="38%"><?= $kk->nama ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">NIM</td>
        <td nowrap="nowrap">:</td>
        <td nowrap="nowrap"><?= $kk->mhs_id ?></td>
      </tr>
      <tr>
        <td nowrap="nowrap">Program Studi</td>
        <td nowrap="nowrap">:</td>
        <td nowrap="nowrap"><?= $m->nama_jur ?> - S1 Reguler</td>
      </tr>
      <tr>
        <td nowrap="nowrap">Tahun Angkatan</td>
        <td nowrap="nowrap">:</td>
        <td nowrap="nowrap"><?= $kk->mulai_smt ?></td>
      </tr>
<!--      <tr>
        <td nowrap="nowrap" >Semester</td>
        <td nowrap="nowrap" >:</td>
        <td nowrap="nowrap" >Genap 2017/2018</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
      </tr>

 -->    </tbody></table><!-- <br /> -->
 <br>


<div class="nobreak">
    <table class="tabel-common" width="100%">
      <tbody><tr>
        <th width="3%">No.</th>
        <th width="8%">Kelas</th>
        <th width="30%">Matakuliah</th>
        <th width="3%">Sks</th>
        <th width="5%">Hari</th>
        <th width="12%">Jam</th>
        <th width="10%">Ruang</th>
        
        <th width="10%">Paraf</th>
      </tr>

 

      <?php        

          $q=$db->query("select m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai, r.nm_ruang, m.semester, kl.kls_nama from krs k 
join krs_detail kr on k.krs_id=kr.id_krs
join matkul m on m.id_matkul=kr.kode_mk
left join jadwal_kuliah j on j.kelas_id=kr.id_kelas
join semester s on s.sem_id=k.sem_id 
left JOIN ruang_ref r ON j.ruang_id=r.ruang_id
left JOIN kelas kl ON kl.`kelas_id` = kr.`id_kelas`
where s.id_semester='$sem' and k.krs_id='$id'");

          
          $no=1;
          foreach ($q as $k) {
            echo "<tr>
                     <td>$no</td>
                     <td>$k->kls_nama</td>
                     <td>$k->nama_mk</td>
                     <td>$k->sks_tm</td>
                     <td>$k->hari</td>
                     <td>$k->jam_mulai s/d $k->jam_selesai</td>
                     <td>$k->nm_ruang</td>
                     
                     <td></td>
                     
                 </tr>";
                 $no++;
          }
         ?>
        




    </tbody></table>
    Kartu ujian harus dibawa saat ujian
  </div>
 <div>
    <span class="link"><a href="https://siakad.fst.uinsgd.ac.id/gtakademik/popup/pejabat/?act=1d74cc14e89d00279d6c09fab506d1be&amp;w=0&amp;x=1fe0dIvDEf!144faPOMv6e007b&amp;y=1fe0dDPCvO144fazBIz8e007b&amp;f=1fe0d144fapur3$e007b&amp;i=1fe0d144fa0$e007b" onclick="window.open(&#39;../popup/pejabat/?act=1d74cc14e89d00279d6c09fab506d1be&amp;w=0&amp;x=1fe0dIvDEf!144faPOMv6e007b&amp;y=1fe0dDPCvO144fazBIz8e007b&amp;f=1fe0d144fapur3$e007b&amp;i=1fe0d144fa0$e007b&#39;,&#39;&#39;,&#39;height=600,resizable=yes,scrollbars=yes,width=750&#39;); return false;">Ganti Pejabat Pengesah</a></span>
      <table align="right" width="50%">
        <tbody><tr>
          <td width="50%" rowspan="3" align="center" valign="top">
            <div style="width: 1.5cm; height: 2cm; border: 1px solid #000;"><br><br><center>PHOTO</center></div>
          </td>
          <td align="center">Bandung, <?php echo tgl_indo(date("Y-m-d"));?><br><span id="tipe_pengesahan0">------------------</span><br><span id="jabatan0"></span></td>
        </tr>
        <tr>
          <td align="center" height="50"></td>
        </tr>
        <tr>
          <td align="center" nowrap="nowrap"><span id="nama0">............................</span><br>NIP: <span id="nip0">......................</span></td>
        </tr>
      </tbody></table>

  </div>
  <hr class="hidden">

  </div>

  <?php
  $i++;
}
?>
  
</body>
</html>