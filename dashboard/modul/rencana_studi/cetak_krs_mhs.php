<?php 
session_start();
include "../../inc/config.php";
session_check_end();
include "phpqrcode/qrlib.php"; //<-- LOKASI FILE UTAMA PLUGINNYA
 
$tempdir = "temp/"; //<-- Nama Folder file QR Code kita nantinya akan disimpan

$namafile = "qr.png";
$quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
$ukuran = 5; //batasan 1 paling kecil, 10 paling besar
$padding = 0;


?>
<html>
<head>
<title>Cetak KRS Mahasiswa</title>
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
      padding-top: 5px;
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
      padding-top: 5px;
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
 $mhs_id=$dec->dec($_GET['nim']);
 //periode semester, ex : 20171
 $sem = $dec->dec($_GET['sem']);
 $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$mhs_id);


 $kajur = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$data_mhs->jur_kode);

 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
 $identity_kota = $db->fetch_single_row("identitas","id_identitas",3);
  $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks(".$data_mhs->nim.",".$sem.") as jatah_sks,fungsi_jml_sks_diambil(".$data_mhs->nim.",".$sem.") as diambil,fungsi_get_ip_semester_sebelumnya(".$data_mhs->nim.",".$sem.") as ip_sebelum");

     //check semester mahasiswa
     $semester_mhs = $db->fetch_custom_single("select count(akm_id) as semester from akm where mhs_nim=?",array('mhs_nim' => $data_mhs->nim));
     $check_paket_semester = $db->fetch_single_row("data_paket_semester","id",1);
     
      $jatah_sks = $data_jatah_sks->jatah_sks;
      $dapat_paket = "";
     if ($check_paket_semester) {
      if ($semester_mhs) {
        //semester paket
        $xpl_semester = explode(",", $check_paket_semester->semester_mhs);
        if (in_array($semester_mhs->semester,$xpl_semester)) {
          $jatah_sks = $check_paket_semester->jml_sks;
          $dapat_paket = "(Paket Semester)";
        }
      }
     }

$isi_teks = "NIM : $mhs_id\nNama:$data_mhs->nama\nIPK Semester Lalu : $data_jatah_sks->ip_sebelum\nJatah SKS : $data_jatah_sks->jatah_sks"; 
QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 
?>
<body onLoad="//window.print();">

      <page size="A4">
       <div class="nobreak">

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
      <!--table width="100%">
         <tr>
            <td width="5%"><img src="../images/logo.jpg" /></td>
            <td width="95%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
         </tr>
      </table-->

      <h2 align="center">KARTU RENCANA STUDI</h2>
    <br>

      <table width="100%">
         <tbody><tr>
            <td nowrap="nowrap" width="15%">Nama</td>
            <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?= $data_mhs->nama ?></td>
			 <td nowrap="nowrap" width="15%">Program Studi</td>
             <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?=$data_mhs->jurusan;?></td>
            <td width="50%" rowspan="6" align="right"><img width="80px" src="temp/qr.png" class="user-image" alt="User Image"/></td>
         </tr>
         <tr>
            <td nowrap="nowrap">NIM</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $mhs_id ?></td>
			  <td nowrap="nowrap">Ip Semester Lalu</td>
              <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $data_jatah_sks->ip_sebelum?></td>
         </tr>
         <tr>
            <td nowrap="nowrap">Tahun Akademik</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?=get_tahun_akademik($sem);?></td>
			<td nowrap="nowrap">Maksimal SKS </td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?=$jatah_sks;?></td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
         </tr>
      </tbody></table><!-- <br /> -->


   <div class="nobreak">
      <table class="tabel-common" width="100%">
         <tbody><tr>
            <th width="2%" rowspan="2">No</th>
            <th width="15%" rowspan="2">Mata kuliah</th>
            <th width="2%" rowspan="2">Kelas</th>
            <th width="2%" rowspan="2">SKS</th>
            <th width="2%" rowspan="2">SMT</th>
            <th colspan="3">Jadwal</th>
         </tr>
         <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
         </tr> -->
         <tr>
            <th width="9%">Ruang</th>
            <th width="9%">Waktu</th>
            <th width="9%">Dosen</th>
         </tr>
         <?php
$array_where = array(
    'nim' => $mhs_id,
    'sem' => $sem
  );


$data_krs = $db->query("select krs_detail.kode_mk, m.semester as sem_matkul ,m.nama_mk as nm_matkul,vnk.kls_nama AS nm_paralel,
krs_detail.sks,vj.nm_ruang,vj.hari,waktu,fungsi_dosen_kelas(vnk.kelas_id) as 
  nama_dosen
 from view_nama_kelas vnk
left  join view_jadwal vj on vnk.kelas_id=vj.kelas_id
right  join krs_detail on vnk.kelas_id=krs_detail.id_kelas
join matkul m on m.id_matkul=krs_detail.kode_mk 
where krs_detail.nim=? and krs_detail.id_semester=?",$array_where);

          $no=1;
          $jumlah_sks = 0;
          foreach ($data_krs as $krs) {
            echo "<tr>
                     <td align='center'>$no</td>
                     <td>$krs->nm_matkul</td>
                     <td align='center'>$krs->nm_paralel</td>
                     <td align='center'>$krs->sks</td>
                     <td align='center'>$krs->sem_matkul</td>
                     <td>$krs->nm_ruang</td>
                     <td>".ucwords($krs->waktu)."</td><td>";
                         if ($krs->nama_dosen!='') {
                      $nama_dosen = array_map('trim', explode('#', $krs->nama_dosen));
                      $nama_dosen = trim(implode("<br>- ", $nama_dosen));
                      echo '- '.$nama_dosen;
                      } else {
                        echo $krs->nama_dosen;
                      }
                    echo "</td>
                 </tr>";
                 $no++;
                 $jumlah_sks+=$krs->sks;
          }
         ?>
            


        
          <tr>
            <td colspan="3" class="right" align="center"><strong>JUMLAH SKS </strong></td>
            <td align="center"><strong><?=$jumlah_sks;?></strong></td>
            <td colspan="5">&nbsp;</td>
          </tr>

      </tbody></table>
      <br>
   </div>


<p>
<div>
      <table align="right" width="85%">
	  
         <tbody><tr>
		   <td width="32%">Disahkan Tgl .................
             <br>Ketua Jurusan/Program Studi</td>
            <td width="31%">Disetujui Tgl .................
             <br>Penasehat Akademik
			  </td>
            <td width="37%" ><?=$identity_kota->isi;?>, <?php echo tgl_indo(date("Y-m-d"));?>
            <br>Mahasiswa
            </td>
         </tr>
         <tr>  <td align="center" height="50">&nbsp;</td>
            <td align="center" height="50"></td>
            <td align="center" height="50"></td>
         </tr>
         <tr>  <td align="justify">
          <span style="text-decoration: underline;"><?= $kajur->nama_kajur?></span> <br>
          NIP : <?=$kajur->nip_kajur;?>
		 </td>
            <td  nowrap="nowrap">
                    <span style="text-decoration: underline;"><?= $data_mhs->dosen_pa?></span> <br>
                    NIP : <?=$data_mhs->nip_dosen_pa;?>
            </td>
            <td >
<span style="text-decoration: underline;"><?= $data_mhs->nama?></span> <br>
                    NIM : <?=$data_mhs->nim;?>
			
         </tr>
		 <tr>  <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 </tr>
      </tbody></table>
   </div>
   <br>

   <hr class="hidden">
</div>
      </page>
 

</body>
<html>
