<?php
session_start();
include "../../inc/config.php";
session_check();
require_once('../../assets/plugins/html2pdf/html2pdf.class.php');

ob_start();
?>
<html>
<head>
<!-- <title>Cetak Surat Keterangan</title> -->
<style>body {
  background:#ffffff;
}




body {
  padding: 10px;
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 17cm;
}

div.page-landscape {
   visibility: visible;
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
   font-size: 11px;
   padding: 0px 2px 0px 2px;
}

table tr th {
   /*font-family: Tahoma;*/
   font-size: 11px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
}

.tabel-common tr td {
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
   font-size: 11px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
   border: 1px solid #ccc;
}

.tabel-info tr td, th {
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
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


div.page-portrait {
   visibility: visible;
   /*font-family: Tahoma;*/
   font-size: 11px;
   margin: 6px 0px 6px 0px;
   width: 17cm;
}

div.page-landscape {
   visibility: visible;
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
   font-size: 11px;
   padding: 0px 2px 0px 2px;
}

table tr th {
   /*font-family: Tahoma;*/
   font-size: 11px;
   font-weight: bold;
   background-color: #eee;
   padding: 2px;
}

.tabel-common tr td {
   /*font-family: Tahoma;*/
   font-size: 11px;
   padding: 0px 2px 0px 2px;
   border: 1px solid #000;
   vertical-align: top;
}

.tabel-common tr th {
   /*font-family: Tahoma;*/
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
   /*font-family: Tahoma;*/
   font-size: 11px;
   padding: 2px;
   font-weight: bold;
}

.page-break {
   clear: both;
}

.tabel-header{
  text-align:center;
  border: 2px solid black;
}

.tabel-body{
  text-align:center;
  border-right: 2px solid black;
  border-left: 2px solid black;
  border-top: 0px solid black;
  border-bottom: 0px solid black;
  padding-top: 1px;
  padding-bottom: 1px;
  vertical-align: middle;
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
  $no=1;
  $last_id_krs="";
  $mhs_id=$_POST['nim'];
  //$fakultas = $_SESSION['id_fak'];
  $fakultas = 7;
  $k = $_POST['k'];
  $id_krs = array();

  $m = get_atribut_mhs($mhs_id);
  // print_r($_POST); 
  // die();

  $header = $db->query("SELECT i.isi as header FROM identitas i WHERE i.id_identitas=1");
  foreach ($header as $identitas_1) {
    # code...
  }

  $alamat = $db->query("SELECT i.isi as alamat FROM identitas i WHERE i.id_identitas=2");
  foreach ($alamat as $identitas_2) {
    # code...
  }

  $kota = $db->query("SELECT i.isi as kota FROM identitas i WHERE i.id_identitas=4");
  foreach ($kota as $identitas_3) {
    # code...
  }

  $mahasiswa_query = $db->query("SELECT * FROM mahasiswa WHERE nim='$mhs_id'");
  foreach ($mahasiswa_query as $mhs_q) {
    # code...
  }

  $fakultas_query = $db->query("SELECT *,d.gelar_depan, d.nama_dosen, d.gelar_belakang FROM mahasiswa m
                              JOIN jurusan j ON m.jur_kode=j.kode_jur
                              JOIN fakultas f ON j.fak_kode=f.kode_fak
                              JOIN dosen d ON d.id_dosen=f.dekan
                              WHERE m.nim='$mhs_id'");
  foreach ($fakultas_query as $fak_q) {
    $dekan=$fak_q->gelar_depan." ".$fak_q->nama_dosen." ".$fak_q->gelar_belakang;
  }

  $semester_query = $db->query("SELECT * FROM semester_ref s JOIN jenis_semester j 
                                ON s.id_jns_semester=j.id_jns_semester ORDER BY s.id_semester DESC LIMIT 1");
  foreach($semester_query as $sem_q){
      $semester=$sem_q->jns_semester." ".$sem_q->tahun." / ".($sem_q->tahun+1);
  }

  $ta_query = $db->query("SELECT * FROM tugas_akhir WHERE nim='$mhs_id'");
  foreach ($ta_query as $ta_q) {
     # code...
  }

  $ipk_query = $db->query("SELECT * FROM akm WHERE mhs_nim='$mhs_id'");
  foreach ($ipk_query as $ipk_q) {
   } 

    $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=?",array('kode_jurusan' => $mhs_q->jur_kode));
                      foreach ($skala_nilai as $skala) {
                        $array_skala[$skala->nilai_huruf] = $skala->nilai_indeks;
                      }
?>
<body>

<div class="nobreak">
      <!--table width="100%">
         <tr>
            <td width="5%"><img src="../images/logo.jpg" /></td>
            <td width="95%"><h1></h1><h3>FAKULTAS SAINS DAN TEKNOLOGI</h3></td>
         </tr>
      </table-->

      <h2 align="center">TRANSKRIP NILAI PROGRAM STRATA SATU(S-1)</h2>
      <h4 align="center">Nomor :  B-3232/Un.05/III.7/PP.00.09/07/2020</h4><br>
      <!-- <h4 align="center">Semester : <?=$semester?></h4><br> -->

      <table width="100%">
         <tbody>
            <tr>
              <td nowrap="nowrap" width="15%">Nama</td>
              <td width="2%">:</td>
              <td nowrap="nowrap" style="width: 38%;"><b><?= $m->nama ?></b></td>
              <td nowrap="nowrap" width="15%">Fakultas</td>
              <td width="2%">:</td>
              <td nowrap="nowrap" width="38%"><?= ucwords(strtolower($m->nama_resmi)) ?></td>
              <!-- <td nowrap="nowrap" width="38%"><?=$fak_q->nama_resmi?></td> -->
            </tr>
            <tr>
                <td nowrap="nowrap">NIM</td>
                <td nowrap="nowrap">:</td>
                <td nowrap="nowrap"><?= $mhs_id ?></td>
                <td nowrap="nowrap">Jurusan </td>
                <td nowrap="nowrap">:</td>
                <td nowrap="nowrap"><?= ucwords(strtolower($m->nama_jur)) ?></td>
            </tr>
            <tr>
              <td nowrap="nowrap">Tempat/Tanggal Lahir</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap"><?= $mhs_q->tmpt_lahir ?>, <?=tgl_indo($mhs_q->tgl_lahir);?></td>
              <td nowrap="nowrap">No. Ijazah</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap">FST/S1/3232/2020</td>
              <!-- <td nowrap="nowrap"><?=tgl_indo($ta_q->tanggal_keluar);?></td> -->
            </tr>
            <tr>
              <td colspan="3"></td>
              <td nowrap="nowrap">SK BAN-PT </td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap">1803/SK/BAN-PT/Akred/S/VII/2018</td>
              <!-- <td nowrap="nowrap"><?=$ta_q->no_seri_ijasah?></td> -->
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
            </tr>
      </tbody>
    </table><!-- <br /> -->

  <div class="nobreak">
    <table style="width: 100%">
      <tbody>
        <tr>
          <td style="width: 50%; padding-right: 5px;">
            <table align="left" class="tabel" border="1" style="width: 100%;">
                <col width="10">
                <col width="50">
                <col width="190">
                <col width="15">
                <col width="15">
                <col width="10">
                 <tbody>
                   <tr>
                     <th rowspan="1" class="tabel-header">No</th>
                     <th rowspan="1" class="tabel-header">Kode</th>             
                     <th rowspan="1" class="tabel-header">Nama Mata Kuliah</th>
                     <th rowspan="1" class="tabel-header">SKS</th>
                     <th rowspan="1" class="tabel-header">Nilai</th>
                     <th rowspan="1" class="tabel-header">AK</th>
                   </tr>
                 <?php
                 foreach ($_POST['id_transkrip'] as $id) {
                    $id_krs[] = $id;
                  }
                  $in_id_krs = implode(",", $id_krs);
                  $q=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,
k.nilai_huruf from krs_detail k join matkul 
m on m.id_matkul=k.kode_mk where k.nim='$mhs_id' and k.bobot IS NOT NULL and k.id_krs_detail in($in_id_krs)");

                  // echo "select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                  //                  join krs ks on k.id_krs=ks.krs_id join matkul m on m.id_matkul=k.kode_mk where ks.mhs_id='$mhs_id' and k.bobot IS NOT NULL and k.id_krs_detail in($in_id_krs)";

                    $totalData = $q->rowCount();
                    $kredit=0;
                    $total_sks=0;
                    $total_kredit=0;

                    $batas = (ceil($totalData/2)+2);
                    $dat2 = [];
                    foreach ($q as $kr) {

                      if ($batas == $no) {
                        $dat2[] = $kr;
                        continue;
                      }

                      if (in_array($kr->nilai_huruf,array_keys($array_skala))) {
                         $kredit = $kr->sks * $array_skala[$kr->nilai_huruf];
                      } else {
                        $kredit = 0;
                      }
                      

                      /*switch ($kr->nilai_huruf) {
                        case 'A':
                          $kredit = $kr->sks * 4;
                          break;
                        case 'B':
                          $kredit = $kr->sks * 3;  
                          break;
                        case 'C':
                          $kredit = $kr->sks * 2;
                          break;
                        case 'D':
                          $kredit = $kr->sks * 1;
                          break;
                        default:
                          $kredit = 0;
                          break;
                      }*/
                      if ($batas == $no+1) {
                        echo "
                          <tr>
                          </tr>
                          <tr>
                              <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$no</td>
                              <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$kr->kode_mk</td>                      
                              <td class='tabel-body' style='text-align:left !important; border-bottom: 1px solid black !important;'>$kr->nama_mk</td>
                              <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$kr->sks</td>
                              <td class='tabel-body' style='border-bottom: 1px solid black !important;' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                              <td class='tabel-body' style='border-bottom: 1px solid black !important;' id='bobot-$kr->id_krs_detail'>$kredit</td>                  
                          </tr>";
                      }else {
                        echo "
                          <tr>
                          </tr>
                          <tr>
                              <td class='tabel-body'>$no</td>
                              <td class='tabel-body'>$kr->kode_mk</td>                      
                              <td class='tabel-body' style='text-align:left !important;'>$kr->nama_mk</td>
                              <td class='tabel-body'>$kr->sks</td>
                              <td class='tabel-body' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                              <td class='tabel-body' id='bobot-$kr->id_krs_detail'>$kredit</td>                  
                          </tr>";
                      }
                     
                      $no++;
                      $total_sks=$total_sks+$kr->sks;
                      $total_kredit=$total_kredit+$kredit;
                      $last_id_krs=$kr->id_krs_detail;
                    }
                 ?>
                </tbody>
            </table>

          </td>
          <td style="width: 50%; padding-left: 5px;">
            <table align="right" class="tabel" border="1" style="width: 100%;">
                 <col width="10">
                 <col width="50">
                 <col width="190">
                 <col width="15">
                 <col width="15">
                 <col width="10">
                  <tbody>
                    <tr>
                      <th rowspan="1" class="tabel-header">No</th>
                      <th rowspan="1" class="tabel-header">Kode</th>             
                      <th rowspan="1" class="tabel-header">Nama Mata Kuliah</th>
                      <th rowspan="1" class="tabel-header">SKS</th>
                      <th rowspan="1" class="tabel-header">Nilai</th>
                      <th rowspan="1" class="tabel-header">AK</th>
                    </tr>
                 <?php
                 foreach ($_POST['id_transkrip'] as $id) {
                    $id_krs[] = $id;
                  }
                  $in_id_krs = implode(",", $id_krs);
                  // $q=$db->query("select k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,k.bobot,k.nilai_huruf from krs_detail k
                  //                  join krs ks on k.id_krs=ks.krs_id join matkul m on m.id_matkul=k.kode_mk where ks.mhs_id='$mhs_id' and k.id_krs_detail > '$last_id_krs' and k.id_krs_detail in($in_id_krs)");
                    $no2 = 1;
                    $kredit=0;
                    // $total_sks=0;
                    // $total_kredit=0;

                   

                    foreach ($dat2 as $kr) {
                         if (in_array($kr->nilai_huruf,array_keys($array_skala))) {
                         $kredit = $kr->sks * $array_skala[$kr->nilai_huruf];
                      } else {
                        $kredit = 0;
                      }
                      
                      /*switch ($kr->nilai_huruf) {
                        case 'A':
                          $kredit = $kr->sks * 4;
                          break;
                        case 'B':
                          $kredit = $kr->sks * 3;  
                          break;
                        case 'C':
                          $kredit = $kr->sks * 2;
                          break;
                        case 'D':
                          $kredit = $kr->sks * 1;
                          break;
                        default:
                          $kredit = 0;
                          break;
                      }*/

                    if ($totalData == $no) {
                      echo "
                           <tr>
                           </tr>
                           <tr>
                               <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$no</td>
                               <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$kr->kode_mk</td>                      
                               <td class='tabel-body' style='text-align:left !important; border-bottom: 1px solid black !important;'>$kr->nama_mk</td>
                               <td class='tabel-body' style='border-bottom: 1px solid black !important;'>$kr->sks</td>
                               <td class='tabel-body' style='border-bottom: 1px solid black !important;' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                               <td class='tabel-body' style='border-bottom: 1px solid black !important;' id='bobot-$kr->id_krs_detail'>$kredit</td>                  
                           </tr>";
                    }else {
                      echo "
                          <tr>
                          </tr>
                          <tr>
                              <td class='tabel-body'>$no</td>
                              <td class='tabel-body'>$kr->kode_mk</td>                      
                              <td class='tabel-body' style='text-align:left !important;'>$kr->nama_mk</td>
                              <td class='tabel-body'>$kr->sks</td>
                              <td class='tabel-body' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                              <td class='tabel-body' id='bobot-$kr->id_krs_detail'>$kredit</td>                  
                          </tr>";
                    }
                     
                      $no++;
                      $total_sks=$total_sks+$kr->sks;
                      $total_kredit=$total_kredit+$kredit;
                    }
                 ?>
                  <tr>
                    <td colspan="3" class="right tabel-header" align="center"><strong>JUMLAH KREDIT </strong></td>
                    <td align="center" class="tabel-header"><strong><?=$total_sks?></strong></td>
                    <td align="center" class="tabel-header"></td>
                    <td class="right tabel-header" align="center"><strong><?=$total_kredit?></strong></td>
                  </tr>
                </tbody>
            </table>

          </td>
        </tr>
      </tbody>
    </table>
  </div>

   <!-- <div class="nobreak">
    
    
      <br>
   </div> -->


<div>
    <table class="tabel-info" align="left" width="50%" style="margin-top: 5px; float: left; clear: both;">
      <tbody>
        <tr>
         <td width="12%" align="left" style="font-size: 10px;"><strong>Judul Skripsi</strong></td>
         <td width="1%" align="left" style="font-size: 10px;"><strong>:</strong></td>
         <td width="35%" align="left" style="font-size: 10px;"><strong>Ini Adalah Judul Skripsi</strong></td>
        </tr>
        <tr>
          <td style="padding-bottom: 10px;"></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td width="12%" align="left" style="font-size: 10px;"><strong>Tanggal Sidang</strong></td>
          <td width="1%" align="left" style="font-size: 10px;"><strong>:</strong></td>
          <td width="35%" align="left" style="font-size: 10px;"><strong>25 Juli 2020</strong></td>
        </tr>
        <tr>
          <td width="12%" align="left" style="font-size: 10px;"><strong>IPK</strong></td>
          <td width="1%" align="left" style="font-size: 10px;"><strong>:</strong></td>
          <td width="35%" align="left" style="font-size: 10px;"><strong><?=$ipk_q->ipk?> ( <?=ipk_terbilang($ipk_q->ipk);?>)</strong></td>
        </tr>
        <tr>
          <td width="12%" align="left" style="font-size: 10px;"><strong>Predikat Kelulusan</strong></td>
          <td width="1%" align="left" style="font-size: 10px;"><strong>:</strong></td>
          <td width="35%" align="left" style="font-size: 10px;"><strong><i>Sangat Memuaskan</i></strong></td>
        </tr>
     </tbody>
    </table>

    <table style="clear: both; margin-top: 15px; width: 100%;">
      <tbody>
        <tr align="left">
          <td style="width: 15%;"></td>
          <td style="width: 35%;"></td>
          <td style="width: 10%;"></td>
          <td style="width: 35%;"><?= "$identitas_kota->kota, ".tgl_indo(date('Y-m-d')); ?></td>
          <td style="width: 5%;"></td>
        </tr>
        <tr align="left">
          <td style=""></td>
          <td style="">Dekan</td>
          <td style=""></td>
          <td style="">Ketua Jurusan</td>
          <td style=""></td>
        </tr>
        <tr align="left">
          <td style=""></td>
          <td style=""><?= ucwords(strtolower($m->nama_resmi)) ?>,</td>
          <td style=""></td>
          <td style=""><?=ucwords(strtolower($m->nama_jur))?>,</td>
          <td style=""></td>
        </tr> 
        <tr align="left">
          <td style="padding-top: 80px;"></td>
        </tr>
        <tr align="left">
          <td style=""></td>
          <td style=""><strong><?= get_dekan($m->kode_fak)['nama_pejabat'] ?></strong></td>
          <td style=""></td>
          <td style=""><strong><?= get_kajur($m->kode_jur)['nama_pejabat'] ?></strong></td>
          <td style=""></td>
        </tr>
        <tr align="left">
          <td style=""></td>
          <td style="">NIP. <?= get_dekan($m->kode_fak)['nip'] ?></td>
          <td style=""></td>
          <td style="">NIP. <?= get_kajur($m->kode_jur)['nip'] ?></td> 
          <td style=""></td>
        </tr>
      </tbody>
    </table>
   </div>
   <br>

   <table style="margin-top: 25px; float: left; clear: both; width: 50%;">
      <tbody>
        <tr>
         <td width="12%" align="left" style="font-size: 10px;"><strong>Keterangan :</strong></td>
        </tr>
        <tr>
         <td width="12%" align="left" style="font-size: 10px;">SKS : Satuan Kredit Semester, AK: Angka Kredit, IPK : Indeks Prestasi Kumulatif</td>
        </tr>
     </tbody>
    </table>

</div>


</body>
<html>

<?php
$content = ob_get_clean();


try
{
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    //$html2pdf->setModeDebug();

    $html2pdf->writeHTML($content);

    $html2pdf->Output('Transkrip Akhir '.'('.$mhs_id.' '.$m->nama.')'.'.pdf','D');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}

?>