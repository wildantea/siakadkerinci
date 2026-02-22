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
</head>
<!--<?php
include "phpqrcode/qrlib.php"; //<-- LOKASI FILE UTAMA PLUGINNYA
 
$tempdir = "temp/"; //<-- Nama Folder file QR Code kita nantinya akan disimpan
if (!file_exists($tempdir))#kalau folder belum ada, maka buat.
   mkdir($tempdir);

$isi_teks = "Belajar QR Code itu asik";
$namafile = "coba.png";
$quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
$ukuran = 5; //batasan 1 paling kecil, 10 paling besar
$padding = 0;
 
QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 
?>-->
<?php
 $mhs_id=$_POST['nim'];
 $k = $_POST['k'];
 $sem = de($_POST['sem']);
 $m = get_atribut_mhs($mhs_id);
 //echo "select s.id_semester from krs k join semester s on s.sem_id=k.sem_id where k.krs_id='$k'";
 $qqq = $db->query("SELECT js.`jns_semester`, sf.`tahun`, sf.`tahun`+1 FROM kelas k 
JOIN semester s ON s.id_semester=k.sem_id 
JOIN semester_ref sf ON sf.`id_semester`=s.`id_semester`
JOIN jenis_semester js ON js.`id_jns_semester`=sf.`id_jns_semester`
WHERE k.sem_id='$sem' ");
 
 foreach ($qqq as $kkk) {
 } 

 $ak=$db->query("SELECT a.`ip`, a.`jatah_sks` FROM akm a WHERE a.`sem_id`='$sem' AND a.`mhs_nim`='$mhs_id'");
 foreach ($ak as $akm) {
   # code...
 }

 $mhs=$db->query("SELECT fk.nama_resmi, j.`nama_jur` FROM mahasiswa mh 
JOIN jurusan j ON j.`kode_jur`=mh.`jur_kode`
JOIN fakultas fk ON fk.kode_fak=j.fak_kode WHERE mh.`nim`='$mhs_id'");
 foreach ($mhs as $mahasiswa) {
   # code...
 }

 $idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
 foreach ($idt as $identitas) {
   # code...
 }
 $idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
 foreach ($idt2 as $identitas2) {
   # code...
 }
  $dp = $db->query("SELECT d.`gelar_depan`, d.`nama_dosen`, d.`gelar_belakang` FROM dosen d
left JOIN mahasiswa m ON m.`dosen_pemb`=d.`id_dosen`
WHERE m.`nim`='$mhs_id'");
 foreach ($dp as $dospem) {
  $d=$dospem->nama_dosen;
   # code...
 }
 $p = $db->query("SELECT p.nama_pejabat FROM pejabat p WHERE p.id_pejabat='1'");
 foreach ($p as $kj) {
  $kajur=$kj->nama_pejabat;
   # code...
 }

 $pu = $db->query("SELECT p.nama_pejabat FROM pejabat p WHERE p.id_pejabat='2'");
 foreach ($pu as $pdk) {
  $pudek=$pdk->nama_pejabat;
   # code...
 }

?>
<body onLoad="//window.print();">
      <page size="A4">
<div class="nobreak">
   <table>
      <tbody>
        <tr>
           <td>
             <img src="../../assets/login/img/logokerinci.png" width="100" height="100">
           </td>
           <td>
               <h1><br><?= $identitas->header ?></h1>
              <h3>FAKULTAS <?= $mahasiswa->nama_resmi ?></h3>
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

      <h2 align="center">HASIL STUDI</h2>
      <h4 align="center">Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>-<?= $kkk->tahun+1 ?></h4><br>

      <table width="100%">
         <tbody>
           <tr>
              <td nowrap="nowrap" width="15%">Nama</td>
              <td width="2%">:</td>
              <td nowrap="nowrap" width="38%"><?= $m->nama ?></td>
  			      <td nowrap="nowrap" width="15%">Fakultas</td>
              <td width="2%">:</td>
              <td nowrap="nowrap" width="38%"><?= $mahasiswa->nama_resmi ?></td>
              <td width="50%" rowspan="6" align="right">
                  <?php
                      echo "<img src='temp/coba.png' height='80px' width='80px'>";
                  ?>
              </td>
            </tr>
         <tr>
            <td nowrap="nowrap">NIM</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $mhs_id ?></td>
			      <td nowrap="nowrap">Prodi - Jenjang  </td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $mahasiswa->nama_jur ?> - S1 Reguler</td>
         </tr>
         <tr>
            <td nowrap="nowrap">Semester</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $kkk->jns_semester ?> <?= $kkk->tahun ?>-<?= $kkk->tahun+1 ?></td>
			      <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
         </tr>
         <tr>
            <td nowrap="nowrap">IP Semester </td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $akm->ip ?></td>
			      <td nowrap="nowrap">Beban SKS </td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $akm->jatah_sks ?></td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
         </tr>
      </tbody>
    </table><!-- <br /> -->


   <div class="nobreak">
      <table class="tabel-common" width="100%">
         <tbody>
           <tr>
             <th width="5%" rowspan="2" style='text-align:center'>No</th>
             <th width="20%" rowspan="2" style='text-align:center'>Kode MK</th>
             <th width="40%" rowspan="2" style='text-align:center'>Nama MK</th>
             <th width="10%" rowspan="2" style='text-align:center'>SKS</th>
             <th width="5%" rowspan="2" style='text-align:center'>Bobot</th>
             <th width="20%" rowspan="2" style='text-align:center'>Nilai Huruf</th>
           </tr>
         <?php
            $q=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                           join krs ks on k.id_krs=ks.krs_id join matkul m on m.id_matkul=k.kode_mk JOIN semester s ON s.sem_id = ks.sem_id where ks.mhs_id='$mhs_id' and s.id_semester='$sem' ");
            $no=1;
            foreach ($q as $kr) {
             echo "
                  <tr>
                  </tr>
                  <tr>
                      <td style='text-align:center'>$no</td>
                      <td style='text-align:left'>$kr->kode_mk</td>
                      <td style='text-align:left'>$kr->nama_mk</td>
                      <td style='text-align:center'>$kr->sks</td>
                      <td style='text-align:center' id='bobot-$kr->id_krs_detail'>$kr->bobot</td>
                      <td style='text-align:center' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                  </tr>";
              $no++;
            }
         ?>
          <tr>
            <td colspan="3" class="right" align="center"><strong>JUMLAH KREDIT </strong></td>
            <td colspan="3" class="right" align="center">Bobot KRS</td>
          </tr>
        </tbody>
    </table>
      <br>
   </div>



   
<div>
      <table align="right" width="85%">

         <tbody><tr>
		   <td width="32%">Mengetahui
             <br>
             Wakil Dekan I Bidang Akademik dan Kelembagaan
		     <!--<span id=""></span><br /><span id=""></span>--> </td>
            <td width="31%" align="center">
				<span id=""><br>Mengetahui<br>Kasubbag Akademik, Kemahasiswaan dan Alumni</span>

			  </td>
            <td width="37%" align="center">Sungai Penuh, <?php echo tgl_indo(date("Y-m-d"));?><br>
              Mahasiswa</td>
         </tr>
         <tr>  <td align="center" height="50">&nbsp;</td>
            <td align="center" height="50"></td>
            <td align="center" height="50"></td>
         </tr>
         <tr>  <td align="justify">

                     <span id=""><?= $pudek ?></span>
                     <span class="hidden" id=""></span>
                     <span class="hidden" id=""></span>
                     <span class="hidden" id=""></span>
					 <!-- <input type="hidden" value="" id="nip0">
					 <input type="hidden" value="" id="jabatan0">
					 <input type="hidden" value="" id="tipe_pengesahan0"> -->


		 </td>
            <td align="center" nowrap="nowrap">
                     <span id=""><?= $kajur ?></span>
                     <span class="hidden" id=""></span>
                     <span class="hidden" id=""></span>
                     <span class="hidden" id=""></span>
					 <!-- <input type="hidden" value="" id="">
					 <input type="hidden" value="" id="">
					 <input type="hidden" value="" id=""> -->

            </td>
            <td align="center"><?= $m->nama ?></td>

         </tr>
		 <tr>  <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 </tr>
      </tbody></table>
   </div>
   <br>

<br><br><br><br><br><br><br>
   

<br><br><br><br><br>

   <span class="link"><a href="../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11" onclick="window.open('../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11','','height=600,resizable=yes,scrollbars=yes,width=750'); return false;">Ganti Pejabat Pengesah</a><br>
	<a href="#" onclick="document.getElementById('nama').innerHTML = ''; return false;">Kosongkan Pejabat Pengesah</a>

	</span>
   <hr class="hidden">
</div>
      </page>


</body>
<html>
