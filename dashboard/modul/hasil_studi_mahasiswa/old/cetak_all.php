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
  padding: 0px;
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
</head>
<?php
 $mhs_id=$_POST['nim2'];
 $m = get_atribut_mhs($mhs_id);

 $mhs=$db->query("SELECT mh.mulai_smt, fk.nama_resmi, j.`nama_jur` FROM mahasiswa mh 
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
 

      $qq = $db->query("select k.mhs_id,k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
          (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
           and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
          join semester s on s.sem_id=k.sem_id
          join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
          join semester_ref sf on sf.id_semester=s.id_semester
          join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='$mhs_id'
          order by s.id_semester asc");
      $i=1;
 
?>
<body onLoad="//window.print();">

      <page size="A4">
<div class="nobreak">
   <table>
      <tbody>
        <tr>
           <td>
             <img src="http://simak.uinsgd.ac.id/gtakademik/images/logo.jpg" width="100" height="100">
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

      <h2 align="center">KARTU HASIL STUDI</h2>
      <table class="tabel-info">
         <tbody><tr>
            <td width="150">Nama Mahasiswa</td>
            <td>:</td>
            <td><?= $m->nama ?></td>
            <td width="50%" rowspan="6" align="right">
             <?php
                      echo "<img src='code.png' height='80px' width='80px'>";
                  ?>
           </td>
         </tr>
         <tr>
            <td>Nomor Induk Mahasiswa</td>
            <td>:</td>
            <td><?= $mhs_id ?></td>
         </tr>
         <tr>
            <td>Angkatan</td>
            <td>:</td>
            <td><?= $mahasiswa->mulai_smt ?></td>
         </tr>
         <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td><?= $mahasiswa->nama_jur ?> - S1 Reguler</td>
         </tr>
         <tr>
            <td>Pembimbing Akademik</td>
            <td>:</td>
            <td>Jumadi, ST., M.Cs.</td>
         </tr>
         <tr>
           
         </tr>
      </tbody></table>
     <br>

      

<?php
      foreach ($qq as $k) {
?>
   <div class="nobreak">
   <h4>Semester : <?= $k->jns_semester ?> <?= $k->tahun ?>/<?= $k->tahun+1 ?></h4>
   <br>
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
                                     join matkul m on m.id_matkul=k.kode_mk where k.id_krs='$k->krs_id'");
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
            <td colspan="3" class="right" align="center"><strong>JUMLAH </strong></td>
            <td colspan="1" class="right" align="center"></td>
            <td colspan="1" class="right" align="center"></td>
            <td colspan="1" class="right" align="center"></td>
          </tr>
        </tbody>
    </table>
      <br>
      <table class="tabel-info">
            <tbody><tr>
               <td>Jumlah Sks Semester</td>
               <td>:</td>
               <td><?= $k->jatah_sks ?></td>
            </tr>
            <tr>
               <td>IP Semester (IPS)</td>
               <td>:</td>
               <td><?= $k->ip ?></td>
            </tr>
            <tr>
               <td>Jumlah Sks Kumulatif</td>
               <td>:</td>
               <td>42</td>
            </tr>
            <tr>
               <td>IP Kumulatif (IPK)</td>
               <td>:</td>
               <td><?= $k->ipk ?></td>
            </tr>
            <tr>
               <td>Maks. Beban sks semester berikutnya</td>
               <td>:</td>
               <td>24</td>
            </tr>

         </tbody></table>
         <br>
   </div>

<?php
    $i++;
  }
?>

   
<div>
      <table align="center" width="100%">

         <tbody><tr>
            <td width="85%" align="right">Bandung, <?php echo tgl_indo(date("Y-m-d"));?><br>
            <br> 
            <br> 
            <br>  </td>
         </tr>
         <tr>  <td align="center" height="50">&nbsp;</td>
            <td align="center" height="50"></td>
            <td align="center" height="50"></td>
         </tr>
         <tr>  
            
            <td align="right" width="85%">.................................
            <br>----------------------------
            <br>NIP:...........................</td>



         </tr>
		 <tr>  <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		 </tr>
      </tbody></table>
   </div>
   <br>



<br><br><br><br><br>

   <span class="link"><a href="../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11" onclick="window.open('../popup/pejabat/?act=0b2ed61851b2f8f31c0a5ccfa3732851&amp;w=0&amp;x=8b198DyPOd!vI20a3evxIzc20d11&amp;y=8b198DPCvO20a3ezBIz820d11&amp;f=8b19820a3epur3$20d11&amp;i=8b19820a3e0$20d11','','height=600,resizable=yes,scrollbars=yes,width=750'); return false;">Ganti Pejabat Pengesah</a><br>
	<a href="#" onclick="document.getElementById('nama').innerHTML = ''; return false;">Kosongkan Pejabat Pengesah</a>

	</span>
   <hr class="hidden">
</div>
      </page>


</body>
<html>
