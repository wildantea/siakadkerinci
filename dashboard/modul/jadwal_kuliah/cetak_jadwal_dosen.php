<?php
session_start();
include "../../inc/config.php";
//include "../../inc/function.php";


session_check();

?>
<html>
<head>
  <title>Cetak Jadwal Mengajar</title>
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
   padding: 5px;
   border: 1px solid #ccc;
 }

 .tabel-info tr td, th {
   font-family: Tahoma;
   font-size: 11px;
   padding: 5px;
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
   padding: 5px;
 }

 table tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #eee;
   padding: 5px;
 }

 .tabel-common tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 5px;
   border: 1px solid #000;
   vertical-align: top;
 }

 .tabel-common tr th {
   font-family: Tahoma;
   font-size: 11px;
   font-weight: bold;
   background-color: #eee;
   padding: 5px;
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
   padding: 5px;
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
$mhs_id=$_POST['nip'];
 //$k = $_POST['k'];
$sem = $_POST['sem'];

 $qqq = $db->query("SELECT js.`jns_semester`, sf.`tahun`, sf.`tahun`+1 FROM kelas k 
JOIN semester s ON s.id_semester=k.sem_id 
JOIN semester_ref sf ON sf.`id_semester`=s.`id_semester`
JOIN jenis_semester js ON js.`id_jns_semester`=sf.`id_jns_semester`
WHERE k.sem_id='$sem' ");
 foreach ($qqq as $kkk) {
 } 
$dp = $db->query("SELECT d.`gelar_depan`, d.`nama_dosen`, d.`gelar_belakang`FROM dosen d where d.nip='".$mhs_id."' ");
foreach ($dp as $d) {
   
}

$idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
foreach ($idt as $identitas) {
   # code...
}
$idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
foreach ($idt2 as $identitas2) {
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

       <h2 align="center">JADWAL MENGAJAR</h2>
       <h4 align="center">Semester : <?= $kkk->jns_semester ?> <?= $kkk->tahun ?>-<?= $kkk->tahun+1 ?></h4><br>

       <table width="100%">
         <tbody>
           <tr>
            <td nowrap="nowrap" width="15%">Nama</td>
            <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?= $d->gelar_depan." $d->nama_dosen, ".$d->gelar_belakang ?></td>
          </tr>
           <tr>
            <td nowrap="nowrap" width="15%">NIP</td>
            <td width="2%">:</td>
            <td nowrap="nowrap" width="38%"><?= $_POST['nip'] ?></td>
          </tr>
        </tbody>
      </table><!-- <br /> -->


      <div class="nobreak">
        <table class="tabel-common" width="100%">
         <tbody>
           <tr>
            <th style="width:25px" class='center' valign="center" >No</th>
            <th class='center' valign="center" >Dosen</th>
            <th class='center' valign="center" >Mata Kuliah</th>
            <th class='center' valign="center" >SKS</th>
            <th class='center' valign="center" >Semester</th>
            <th class='center' valign="center" >Kelas</th>
            <th class='center' valign="center">Hari / Jam</th>
            <th class='center' valign="center" >Ruang</th>
            <th class='center' valign="center" >Program Studi</th>
          </tr>
<!--           <tr>
            <th>Senin</th>
            <th>Selasa</th>
            <th>Rabu</th>
            <th>Kamis</th>
            <th>Jumat</th>
            <th>Sabtu</th>
            <th>Minggu</th>
          </tr> -->
          <?php
          $i=1;
          $q=$db->query("select vnk.nm_matkul,vnk.kls_nama,sks,sem_matkul,hari,jam_mulai,jam_selesai,nm_ruang,
vnk.jurusan,fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,  vnk.sem_id, vnk.kelas_id from view_nama_kelas vnk
inner join dosen_kelas on vnk.kelas_id=dosen_kelas.id_kelas
inner join view_jadwal on vnk.kelas_id=view_jadwal.kelas_id
where vnk.sem_id='".$_POST['sem']."' and id_dosen='".$_POST['nip']."'");
//           echo "select vnk.nm_matkul,vnk.kls_nama,sks,sem_matkul,hari,jam_mulai,jam_selesai,nm_ruang,
// vnk.jurusan,fungsi_dosen_kelas(vnk.kelas_id) as nama_dosen,  vnk.sem_id, vnk.kelas_id from view_nama_kelas vnk
// inner join dosen_kelas on vnk.kelas_id=dosen_kelas.id_kelas
// inner join view_jadwal on vnk.kelas_id=view_jadwal.kelas_id
// where vnk.sem_id='".$_POST['sem']."' and id_dosen='".$_POST['nip']."'";

          $no=1;
          $jumlah_sks = 0;
          foreach ($q as $isi) {

            ?><tr>
              <td align="center"><?=$i;?></td>
              <td>
              <?php
               $l = 0;
                 $dosen_pengampu = explode("#", $isi->nama_dosen);
                 if (count($dosen_pengampu)>0) {
                   for ($l=0; $l <count($dosen_pengampu); $l++) { 
                      echo ($l+1).'. '.$dosen_pengampu[$l]."<br>";
                   }
                 } 

                 $dosen_pengampu = array();
                 ?>
               </td>


              <td><?=$isi->nm_matkul;?></td>
              <td><?=$isi->sks;?></td>
               <td><?=$isi->sem_matkul;?></td>
              <td><?=$isi->kls_nama;?></td>
              <td><?=ucwords($isi->hari);?>, <?=$isi->jam_mulai.'-'.$isi->jam_selesai;?></td>
               <td><?=$isi->nm_ruang;?></td>
               <td><?=$isi->jurusan;?></td>


            

            </tr>
            <?php
            $jumlah_sks+=$isi->sks;
            $i++;
          }

          $nama_dosen = $db->fetch_single_row("view_dosen","nip",$_POST['nip']);
          ?>
          <tr>
            <td colspan="9">Jumlah SKS : <?=$jumlah_sks;?></td>
          </tr>
        </tbody>
      </table>
      <table align="right" width="100%">
        <tr>
         <td width="70%" rowspan="3">&nbsp;</td>
         <br><td align="center">Sungai Penuh, <?php echo tgl_indo(date("Y-m-d"));?><br /><span id="tipe_pengesahan0">Dosen Ybs</span><br /><span id="jabatan0"></span></td>
       </tr>
       <tr>
         <td align="center" height="50"></td>
       </tr>
       <tr>
         <td align="center" nowrap="nowrap"><span id="nama0"><?= $nama_dosen->dosen ?></span>

           <br />NIP: <span id="nip0"><?=$_POST['nip'];?></span></td>
         </tr>
       </table> 
      <br>
    </div>



 </div>
</page>


</body>
<html>
