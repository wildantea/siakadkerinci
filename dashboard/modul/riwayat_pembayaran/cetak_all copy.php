<?php 
session_start();
include "../../inc/config.php";
//session_check_end();
include "../rencana_studi/phpqrcode/qrlib.php"; //<-- LOKASI FILE UTAMA PLUGINNYA
 
$tempdir = "temp/"; //<-- Nama Folder file QR Code kita nantinya akan disimpan
$mhs_id=$dec->dec($_GET['nim']);
$namafile = $mhs_id.rand(0,10)."qrnew.png";
$quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
$ukuran = 8; //batasan 1 paling kecil, 10 paling besar
$padding = 0;


?>
<html>
<head>
<title>Kuitansi Pembayaran</title>
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
<!-- <script type="text/javascript">
 window.print();
</script> -->
</head>
<?php
 
 $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$mhs_id);
//jml bayar
$jml_bayar = $db->fetch_custom_single("select sum(keu_bayar_mahasiswa.nominal_bayar) as jml_bayar,keu_kwitansi.no_kwitansi,keu_kwitansi.tgl_bayar,nama_singkat,
keu_bayar_mahasiswa.id from keu_bayar_mahasiswa inner join keu_kwitansi on keu_bayar_mahasiswa.id_kwitansi=keu_kwitansi.id_kwitansi inner join keu_tagihan_mahasiswa on keu_bayar_mahasiswa.id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan left join keu_bank on keu_bayar_mahasiswa.id_bank=keu_bank.kode_bank where keu_tagihan_mahasiswa.nim=?",array('nim' => $mhs_id));


 $kajur = $db->fetch_single_row("view_prodi_jenjang","kode_jur",$data_mhs->jur_kode);

 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
 $identity_kota = $db->fetch_single_row("identitas","id_identitas",3);

     //check semester mahasiswa
     $semester_mhs = $db->fetch_custom_single("select count(akm_id) as semester from akm where mhs_nim=?",array('mhs_nim' => $data_mhs->nim));

$isi_teks = "NIM : $mhs_id\nNAMA:$data_mhs->nama\nJumlah Bayar : $jml_bayar->jml_bayar\nSemua Semester\nUrl : ".base_admin().'modul/riwayat_pembayaran/cetak_all.php?nim='.$_GET['nim']; 
//QRCode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 
 QRcode::png($isi_teks, $tempdir.$namafile, QR_ECLEVEL_L, 4);
//QRCode::png($isi_teks);
//onLoad="//window.print();"
?>
<body>

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

      <h2 align="center">KWITANSI PEMBAYARAN SEMUA</h2>
    <br>

      <table width="100%">
         <tbody><tr>
            <td nowrap="nowrap" width="15%">Nama</td>
            <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?= $data_mhs->nama ?></td>
			 <td nowrap="nowrap" width="15%">&nbsp;</td>
             <td width="2%"></td>
            <td nowrap="nowrap" width="38%">&nbsp;</td>
            <td width="50%" rowspan="6" align="right"><img width="100px" src="temp/<?=$namafile;?>" class="user-image" alt="User Image"/></td>
         </tr>
         <tr>
            <td nowrap="nowrap">NIM</td>
            <td nowrap="nowrap">:</td>
            <td nowrap="nowrap"><?= $mhs_id ?></td>
			  <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
            <td nowrap="nowrap">&nbsp;</td>
         </tr>
         <tr>
                <td nowrap="nowrap" width="15%">Program Studi</td>
             <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?=$data_mhs->jurusan;?></td>
            <td nowrap="nowrap">&nbsp;</td>
              <td nowrap="nowrap">&nbsp;</td>
            <td nowrap="nowrap">&nbsp;</td>
         </tr>
         <tr>
            <td height="30">&nbsp;</td>
            <td></td>
            <td></td>
         </tr>
      </tbody></table><!-- <br /> -->

   <div class="nobreak">
      <table class="tabel-common" width="100%">
         <thead>
            <tr>
            <th width="2%" rowspan="2">No</th>
            <th width="15%" rowspan="2">Periode</th>
            <th width="5%" rowspan="2">SMT</th>
            <th width="20%" rowspan="2">Jenis Tagihan</th>
            <th width="20%" rowspan="2">Tanggal Bayar</th>
            <th width="15%" rowspan="2">Nominal</th>
         </tr>
         </thead>
         <tbody>
         <?php
$array_where = array(
    'nim' => $mhs_id
  );

$data_bayar = $db->query("select keu_tagihan_mahasiswa.periode,keu_jenis_tagihan.nama_tagihan,keu_bayar_mahasiswa.tgl_bayar,keu_bank.nama_bank,keu_bayar_mahasiswa.nominal_bayar,nama_singkat,keu_kwitansi.id_kwitansi,nim,
((left(keu_tagihan_mahasiswa.periode,4)-left(berlaku_angkatan,4))*2)+right(keu_tagihan_mahasiswa.periode,1)-(floor(right(berlaku_angkatan,1)/2)) as smt,
keu_bayar_mahasiswa.id from keu_bayar_mahasiswa inner join keu_kwitansi on keu_bayar_mahasiswa.id_kwitansi=keu_kwitansi.id_kwitansi inner join keu_tagihan_mahasiswa on keu_bayar_mahasiswa.id_keu_tagihan_mhs=keu_tagihan_mahasiswa.id inner join keu_tagihan on keu_tagihan_mahasiswa.id_tagihan_prodi=keu_tagihan.id inner join keu_jenis_tagihan on keu_tagihan.kode_tagihan=keu_jenis_tagihan.kode_tagihan left join keu_bank on keu_bayar_mahasiswa.id_bank=keu_bank.kode_bank where nim=?  order by periode asc",$array_where);

          $no=1;
          $bayar = 0;
          foreach ($data_bayar as $value) {
            echo "<tr>
                     <td align='center'>$no</td>
                     <td>".getAngkatan($value->periode)."</td>
                     <td align='center'>$value->smt</td>
                     <td align='center'>$value->nama_tagihan</td>
                     <td align='center'>".tgl_indo($value->tgl_bayar)."</td>
                     <td>".rupiah($value->nominal_bayar)."</td>
                 </tr>";
                 $no++;
                 $bayar+=$value->nominal_bayar;
          }
          ?>

          <tr>
            <td colspan="5" class="right" align="right"><strong>TOTAL </strong></td>
            <td align="left"><strong><?=rupiah($bayar);?></strong></td>
          </tr>

      </tbody>
    </table>
      <br>
   </div>


<p>
<div>
      <table align="right" width="85%">
	  
         <tbody>
            <tr>
		   <td width="32%">&nbsp;
             <br>&nbsp;</td>
            <td width="31%">&nbsp;
             <br>&nbsp;
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
 <br>
          &nbsp;
		 </td>
            <td  nowrap="nowrap"> 
 <br>
                    &nbsp;
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
