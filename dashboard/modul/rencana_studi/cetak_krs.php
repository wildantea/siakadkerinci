<?php 
session_start();
include "../../inc/config.php";

session_check();



?>
<html>
<head>
<title>Cetak Surat Keterangan</title>
<style>body {
  background: rgb(255,255,255); 
}
page {
  padding: 50px;
  background: white;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
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

</style>
<script type="text/javascript">
 window.print();
</script>
</head>
<?php
 $mhs_id=$_GET['nim'];
 $k = $_GET['k'];
 $m = get_atribut_mhs($mhs_id);  
 $qq = $db->query("select s.id_semester from krs k join semester s on s.sem_id=k.sem_id where k.krs_id='$k' ");
 foreach ($qq as $kk) {
 	$semester = $kk->id_semester;
 }
 
 $dp = $db->query("SELECT d.`gelar_depan`, d.`nama_dosen`, d.`gelar_belakang`FROM dosen d
JOIN mahasiswa m ON m.`dosen_pemb`=d.`id_dosen`
WHERE m.`nim`='$mhs_id'");
 foreach ($dp as $dospem) {
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
      <tbody><tr>
         <td><img src="../../assets/login/img/logokerinci.png" width="100" height="100"></td>
         <td>
            <h1>KEMENTERIAN AGAMA</h1>
				<!--<h1></h1> /-->
				<h1>UNIVERSITAS ISLAM NEGERI (UIN)</h1>
				<h1>SUNAN GUNUNG DJATI BANDUNG</h1>
                  <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>

				<hr>
				Jalan A.H. Nasution No. 105 Cibiru - Bandung <br> Telp. (022) 7802276 Fax. (022) 7802276
         </td>
      </tr>
   </tbody></table>

      <!--table width="100%">
         <tr>
            <td width="5%"><img src="../images/logo.jpg" /></td>
            <td width="95%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
         </tr>
      </table-->

      <h2 align="center">KARTU RENCANA STUDI</h2>
      <h4 align="center">Semester : Genap 2016/2017</h4><br>

      <table width="100%">
         <tbody><tr>
            <td nowrap="nowrap" width="15%">Nama</td>
            <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?= $m->nama ?></td>
			 <td nowrap="nowrap" width="15%">Fakultas</td>
             <td width="2%">:</td>
            <td nowrap="nowrap" width="38%">SAINS DAN TEKNOLOGI</td>
            <td width="50%" rowspan="6" align="right"><div style="width: 1.6cm; height: 1.9cm; border: 1px solid #000;"><br>
    <br>
  <center>
    PHOTO
  </center>
</div></td>
         </tr>
         <tr>
            <td nowrap="nowrap">NIM</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $mhs_id ?></td>
			  <td nowrap="nowrap">Prodi - Jenjang  </td>
              <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $m->nama_jur ?> - S1 Reguler</td>
         </tr>
         <tr>
            <td nowrap="nowrap">Semester</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap">Genap 2016/2017</td>
			<td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
            <td nowrap="nowrap"></td>
         </tr>
		 
         <tr> 
            <td nowrap="nowrap">IP Semester Lalu </td>
            <td nowrap="nowrap">:</td>
			
			
            <td nowrap="nowrap">3.00</td> 
			<td nowrap="nowrap">Beban SKS </td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap">20</td>

         
         </tr><tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
         </tr>
      </tbody></table><!-- <br /> -->


   <div class="nobreak">
      <table class="tabel-common" width="100%">
         <tbody><tr>
            <th width="3%" rowspan="2">No.</th>
            <th width="6%" rowspan="2">KD. MTK</th>
            <th width="25%" colspan="2" rowspan="2">Matakuliah</th>
            <th width="2%" rowspan="2">SKS</th>
            <th width="2%" rowspan="2">SMT</th>
            <th width="15%" rowspan="2">Dosen Pengampu</th>
            <th colspan="2">Jadwal</th>
            <th width="5%" rowspan="2">KE</th>
         </tr>
         <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
         </tr> -->
         <tr>
            <th width="9%">Hari</th>
            <th width="9%">Waktu</th>
           
            <!-- <th>Mg</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Ruang</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Ruang</th> -->
         </tr>
         <?php
         echo "$semester $mhs_id";
          $q=$db->query("select m.kode_mk,m.nama_mk,m.sks_tm,j.hari,j.jam_mulai,j.jam_selesai,m.semester from krs k 
join krs_detail kr on k.krs_id=kr.id_krs
join matkul m on m.id_matkul=kr.kode_mk
left join jadwal_kuliah j on j.kelas_id=kr.id_kelas
join semester s on s.sem_id=k.sem_id where k.mhs_id='$mhs_id' and s.id_semester='$semester' and kr.`disetujui`='1'");
          $no=1;
          foreach ($q as $k) {
            echo "<tr>
                     <td>$no</td>
                     <td>$k->kode_mk</td>
                     <td colspan='2'>$k->nama_mk</td>
                     <td>$k->sks_tm</td>
                     <td>$k->semester</td>
                     <td></td>
                     <td>$k->hari</td>
                     <td>$k->jam_mulai s/d $k->jam_selesai</td>
                     <td></td>
                 </tr>";
                 $no++;
          }
         ?>
            


        
          <tr>
            <td colspan="4" class="right" align="center"><strong>JUMLAH KREDIT </strong></td>
            <td align="center"><strong></strong></td>
            <td colspan="7">&nbsp;</td>
          </tr>

      </tbody></table>
      <br>
   </div>


   
   <table class="tabel-info">
      <tbody><tr>
         <td width="44" align="left">*) KE </td>
         <td width="118" class="left">= Pengambilan Ke  </td>
      </tr>
      <tr>
         <td width="44" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
         <td class="left">&nbsp;</td>
      </tr>
   </tbody></table>
  
<div>
      <table align="right" width="85%">
	  
         <tbody><tr>
		   <td width="32%">Mengetahui
             <br>
             Pudek I
		     <!--<span id=""></span><br /><span id=""></span>--> </td>
            <td width="31%" align="center">
				<span id=""><br>Mengetahui<br>Dosen PA</span>

			  </td>
            <td width="37%" align="center">Bandung, <?php echo tgl_indo(date("Y-m-d"));?><br>
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
   <table align="left" width="100%">
      <tbody><tr>
         <td rowspan="2" valign="top" width="60px"><b>Catatan :</b></td>
         <td width="5px" valign="top">1. </td>
         <td align="justify">Pengisian rencana studi dilakukan oleh mahasiswa setelah konsultasi dengan dosen/pembimbing akademik.</td>
      </tr>
      <tr>
         <td width="5px" valign="top">2. </td>         
         <td align="justify">Pengisian rencana studi disesuaikan dengan penawaran penyajian mata kuliah di fakultas/program studi masing-masing.</td>
      </tr>
   </tbody></table>
   
<br><br><br><br><br>

   <span class="link"><a href="../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11" onclick="window.open('../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11','','height=600,resizable=yes,scrollbars=yes,width=750'); return false;">Ganti Pejabat Pengesah</a><br>
	<a href="#" onclick="document.getElementById('nama').innerHTML = ''; return false;">Kosongkan Pejabat Pengesah</a>

	</span>
   <hr class="hidden">
</div>
      </page>
 

</body>
<html>
