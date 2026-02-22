<?php
session_start();
include "../../inc/config.php";

//include "../../inc/function.php";


session_check();
 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
 $identity_kota = $db->fetch_single_row("identitas","id_identitas",3);

?>
<html>
<head>
<title>Cetak Hasil Studi</title>
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
 //update_akm($mhs_id); 

 $mhs=$db->query("SELECT d.gelar_depan,d.gelar_belakang,d.nama_dosen, mh.mulai_smt, fk.nama_resmi, j.`nama_jur` FROM mahasiswa mh
JOIN jurusan j ON j.`kode_jur`=mh.`jur_kode`
JOIN fakultas fk ON fk.kode_fak=j.fak_kode
left join dosen d on d.id_dosen=mh.dosen_pemb WHERE mh.`nim`='$mhs_id'");
 //update_akm($mhs_id); 
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


      $qq = $db->query("select k.nim,k.id_krs_detail, k.id_semester, js.jns_semester,js.nm_singkat, s.tahun, s.id_semester,a.*, fungsi_jml_sks_diambil(k.nim,k.id_semester) as sks_diambil
 from krs_detail k left join semester_ref s on s.id_semester=k.id_semester left join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.nim) 
 left join jenis_semester js on js.id_jns_semester=s.id_jns_semester  where k.nim='$mhs_id'  group by k.id_semester          order by s.id_semester asc");
      $i=1;
 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);
 $identity_kota = $db->fetch_single_row("identitas","id_identitas",3);
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

      <!--table width="100%">
         <tr>
            <td width="5%"><img src="../images/logo.jpg" /></td>
            <td width="95%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
         </tr>
      </table-->
      <hr>

      <h2 align="center">KARTU HASIL STUDI</h2>
      <table class="tabel-info">
         <tbody><tr>
            <td width="150">Nama Mahasiswa</td>
            <td>:</td>
            <td><?= $m->nama ?></td>
            <td width="60%" rowspan="6" align="right">
                <?php
                    echo "<img src='temp/coba.png' height='80px' width='80px'>";
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
            <td><?= $mahasiswa->gelar_depan.".".$mahasiswa->nama_dosen.".".$mahasiswa->gelar_belakang ?></td>
         </tr>
         <tr>

         </tr>
      </tbody></table>
     <br>



<?php
$sks_total=0;
      foreach ($qq as $k) {

         if ($k->sem_id=='10') {
            $jatah_sks_semester = '-';
         } else {
            $jatah_sks_semester = $k->jatah_sks;
         }


$jatah_sks_mhs_berikutnya = $db->fetch_custom_single("select fungsi_get_jatah_sks_selanjutnya($k->nim,$k->id_semester) as jatah");
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
             <th width="5%" rowspan="2" style='text-align:center'>SKS</th>
             <th width="5%" rowspan="2" style='text-align:center'>Bobot</th>
             <th width="5%" rowspan="2" style='text-align:center'>Kredit</th>
              <th width="10%" rowspan="2" style='text-align:center'>Nilai Angka</th>
             <th width="10%" rowspan="2" style='text-align:center'>Nilai Huruf</th>

           </tr>
         <?php 
         // echo "select k.id_krs_detail,k.nilai_angka, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
         //                  join matkul m on m.id_matkul=k.kode_mk where k.id_semester='$k->sem_id' and k.nim='$k->nim' <br>";
            $q=$db->query("select k.id_krs_detail,k.nilai_angka, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                          join matkul m on m.id_matkul=k.kode_mk where k.id_semester='$k->id_semester' and k.nim='$k->nim' ");
            $no=1;
            $jml_sks=0;
            $jml_bobot=0;
            $jml_kredit = 0;
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
                       <td style='text-align:center' id='kredit-$kr->id_krs_detail'>".number_format(($kr->bobot*$kr->sks))."</td>
                      <td style='text-align:center' id='angka-$kr->id_krs_detail'>".number_format($kr->nilai_angka,2)."</td>
                      <td style='text-align:center' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                  </tr>";
              $no++;
              $sks_total=$sks_total+$kr->sks;
              $jml_sks = $kr->sks+$jml_sks;
              $jml_bobot=$jml_bobot + $kr->bobot;
              $jml_kredit = $jml_kredit + ($kr->bobot*$kr->sks);
            }
         ?>
          <tr>
            <td colspan="3" class="right" align="center"><strong>JUMLAH </strong></td>
           <td  class="right" align="center"><?= $jml_sks ?></td>
            <td  class="right" align="center"><?= number_format($jml_bobot,2) ?></td>
            <td  class="right" align="center"><?= number_format($jml_kredit) ?></td>
            <td  class="right" align="center" colspan="2"></td>
          </tr>
        </tbody>
    </table>
      <br>
      <table class="tabel-info">
            <tbody>
            <tr>
               <td>Jatah Sks Semester</td>
               <td>:</td>
               <td><?= $jatah_sks_semester ?></td>
            </tr>
            <tr>
               <td>IP Semester (IPS)</td>
               <td>:</td>
               <td><?= $k->ip ?></td>
            </tr>
            <tr>
               <td>Jumlah Sks Kumulatif</td>
               <td>:</td>
               <td><?= $sks_total ?></td>
            </tr>
            <tr>
               <td>IP Kumulatif (IPK)</td>
               <td>:</td>
               <td><?= $k->ipk ?></td>
            </tr>
            <tr>
               <td>Maks SKS Semester Berikutnya</td>
               <td>:</td>
               <td><?=$jatah_sks_mhs_berikutnya->jatah;?></td>
            </tr>
         </tbody>
       </table>
         <br>
   </div>

<?php
    $i++;
  }
?>


<?php
$pen = explode("==", $_POST['ttd']);
$ket = "";
$jabatan = "";
/*if ($pen[2]!='') {
  $ket     = "Ketua Jurusan";
  $jabatan = $mahasiswa->nama_jur;
}
if ($pen[3]!='') {
  $ket     = "Dekan";
  $jabatan = $mahasiswa->nama_resmi;
}*/

 ?>


<div style="width: 200px;height:200px;float:right">
  <center>
    <?=$identity_kota->isi;?>, <?php echo tgl_indo(date("Y-m-d"));?><br>
    Mengetahui<br>
    Kasubag Akademik,Kemahasiswaan, dan Alumni
    <?= $ket ?>
    <?= $jabatan ?>
    <br><br><br><br><br><br><br>

    <?= $pen[1] ?><br>
    NIP. <?= $pen[0] ?>
  </center>
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
