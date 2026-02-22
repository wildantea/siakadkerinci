<?php 
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=daftar_nilai_kukerta.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, GET-check=0, pre-check=0");
header("Cache-Control: private",false);
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

</head>
<?php
$fakultas="";
$jurusan="";
$lokasi="";
$priode="";
$kelamin="";

if (isset($_GET['fakultas']) && $_GET['fakultas']!='' ) {

  if ($_GET['fakultas']!='all') {
    $fakultas = ' and fakultas.kode_fak="'.$_GET['fakultas'].'"';
  }
}
 
if(isset($_GET['jurusan']) && $_GET['jurusan']!='') {
  if ($_GET['jurusan']!='all') {
    $jurusan = ' and jurusan.kode_jur="'.$_GET['jurusan'].'"';
  }
}

if(isset($_GET['id_lokasi'])) {
  if ($_GET['id_lokasi']!='all') {
    $lokasi = ' and lokasi_kkn.id_lokasi="'.$_GET['id_lokasi'].'"';
  }
}

// if(isset($_GET['jk'])) {
//  // if ($_GET['lokasi_filter']!='all') {
//     $kelamin = ' and mahasiswa.jk="'.$_GET['jk'].'"';
//  // }
// }
  
if(isset($_GET['priode'])) {
  if ($_GET['priode']!='all') {
    $priode = ' and priode_kkn.id_priode="'.$_GET['priode'].'"';
  }
} 

//print_r($_GET); 

$idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
foreach ($idt as $identitas) {
     # code...
}
$idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
foreach ($idt2 as $identitas2) {
     # code...
}
// $fak=$db->query("SELECT fk.nama_resmi, jurusan.nama_jur FROM fakultas fk
//   JOIN jurusan ON jurusan.fak_kode=fk.kode_fak
//   WHERE fk.kode_fak is not null $jurusan");
// foreach ($fak as $fk) {
//      # code...
// }

// echo "select lokasi_kkn.nama_lokasi,d.nama_dosen as dpl1,dd.nama_dosen as dpl2, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from kkn 
//   left join mahasiswa on kkn.nim=mahasiswa.nim 
//   left join fakultas on kkn.kode_fak=fakultas.kode_fak 
//   left join jurusan on kkn.kode_jur=jurusan.kode_jur 
//   left join priode_kkn on priode_kkn.id_priode=kkn.id_priode
//   left join lokasi_kkn on lokasi_kkn.id_lokasi=kkn.id_lokasi
//   left join dosen d on d.id_dosen = lokasi_kkn.dpl 
//   left join dosen dd on dd.id_dosen = lokasi_kkn.dpl2
//   where 1=1 $fakultas $jurusan $priode $lokasi $kelamin order by kkn.id_kkn";
$qq=$db->query("select lokasi_kkn.nama_lokasi,d.nama_dosen as dpl1,dd.nama_dosen as dpl2, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from kkn 
  left join mahasiswa on kkn.nim=mahasiswa.nim 
  left join fakultas on kkn.kode_fak=fakultas.kode_fak 
  left join jurusan on kkn.kode_jur=jurusan.kode_jur 
  left join priode_kkn on priode_kkn.id_priode=kkn.id_priode
  left join lokasi_kkn on lokasi_kkn.id_lokasi=kkn.id_lokasi
  left join dosen d on d.id_dosen = lokasi_kkn.dpl 
  left join dosen dd on dd.id_dosen = lokasi_kkn.dpl2
  where 1=1 $fakultas $jurusan $priode $lokasi $kelamin order by kkn.id_kkn limit 1");
$nama_lokasi = "";
$dpl1 = "";
$dpl2 = "";
foreach ($qq as $kk) {
  $nama_lokasi = $kk->nama_lokasi;
  $dpl1 = $kk->dpl1; 
  $dpl2 = $kk->dpl2; 

}
?>
<body onLoad="//window.print();">
  <div class="page-break">
    <table>
      <tbody><tr>
       <td><img src="https://siakad.iainkerinci.ac.id/dashboard/assets/login/img/logokerinci.png" width="100" height="100"></td>
       <!--halaman <?= $i ?>-->
       <td>
        <h3><br><?= $identitas->header ?></h3>
        <hr>
        <div style="font-size:10"><?= $identitas2->alamat ?></div>
      </td>
    </tr>
  </tbody></table>
  <br>
   <h2 align="center" style="margin-bottom: 10px;">DAFTAR NILAI PESERTA KUKERTA</h2>
 <!--  <table>
    <tr>
      <td style="font-weight: bold;">Lokasi</td><td style="font-weight: bold;">: <?= $nama_lokasi ?></td>
    </tr>
    <tr>
      <td style="font-weight: bold;">DPL 1</td><td style="font-weight: bold;">: <?= $dpl1 ?></td>
    </tr>
     <tr>
      <td style="font-weight: bold;">DPL 2</td><td style="font-weight: bold;">: <?= $dpl2 ?></td>
    </tr>
  </table> -->
 
  
  <br>
  <div class="nobreak">
    <table class="tabel-common" width="100%">
      <tbody>
        <tr>
          <th width="10" scope="col">No.</th>
          <th width="35" scope="col">Nim</th>
          <th width="35" scope="col">Nama</th>
          <th width="30" scope="col">Fakultas</th>
          <th width="30" scope="col">Jurusan</th>
          <th>Periode</th>
          <th>Lokasi</th>
          <th>DPL 1</th>
          <th>DPL 2</th>
          <th>Nilai Huruf</th>
          <th>Nilai Angka</th>
         
        </tr>         
        <?php   
        
// echo "select priode_kkn.priode, mahasiswa.jur_kode, ds.nama_dosen as dpl1,ds2.nama_dosen as dpl2, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn,lokasi_kkn.nama_lokasi from kkn 
// inner join mahasiswa on kkn.nim=mahasiswa.nim inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
// inner join jurusan on kkn.kode_jur=jurusan.kode_jur left join priode_kkn on priode_kkn.id_priode=kkn.id_priode 
// left join lokasi_kkn on lokasi_kkn.id_lokasi=kkn.id_lokasi
// left join dosen ds on ds.id_dosen=lokasi_kkn.dpl
// left join dosen ds2 on ds2.id_dosen=lokasi_kkn.dpl2 where id_kkn is not null $fakultas $jurusan $priode $lokasi $kelamin order by mahasiswa.nama asc, kkn.id_kkn limit 10";
        $q=$db->query("select priode_kkn.priode, mahasiswa.jur_kode, ds.nama_dosen as dpl1,ds2.nama_dosen as dpl2, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn,lokasi_kkn.nama_lokasi from kkn 
inner join mahasiswa on kkn.nim=mahasiswa.nim inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
inner join jurusan on kkn.kode_jur=jurusan.kode_jur left join priode_kkn on priode_kkn.id_priode=kkn.id_priode 
left join lokasi_kkn on lokasi_kkn.id_lokasi=kkn.id_lokasi
left join dosen ds on ds.id_dosen=lokasi_kkn.dpl
left join dosen ds2 on ds2.id_dosen=lokasi_kkn.dpl2 where id_kkn is not null $fakultas $jurusan $priode $lokasi $kelamin order by mahasiswa.nama asc, kkn.id_kkn ");


        $no=1;
        foreach ($q as $k) {
          $qc = $db->query("select nilai_huruf,nilai_angka from krs_detail where kode_mk in(
select id_matkul from v_matkul_kukerta where kode_jur='$k->jur_kode') and nim='$k->nim'");
          $nilai_huruf = "";
          $nilai_angka = "";
          foreach ($qc as $kc) {
             $nilai_huruf = $kc->nilai_huruf;
             $nilai_angka = $kc->nilai_angka;
          }

          echo "
          <tr>
          <td>$no</td>
          <td>$k->nim</td>
          <td>$k->nama</td>
          <td>$k->nama_resmi</td>
          <td>$k->nama_jur</td>
          <td>$k->priode</td>
          <td>$k->nama_lokasi</td>
          <td>$k->dpl1</td>
          <td>$k->dpl2</td>
          <td style='text-align:center'>$nilai_huruf</td>
          <td style='text-align:center'>$nilai_angka</td>
          </tr>";
          $no++;
        }
        ?>
      <!-- <tr>
            <th colspan="7">Kuliah</th>
            <th colspan="3">Ujian Tengah</th>
            <th colspan="3">Ujian Akhir</th>
          </tr> -->
        </tbody>
      </table>
      <br> 
      <h4 align="right" ><?= tgl_indo(date('Y-m-d')); ?></h4>
      <h4 align="right" >Ketua Pelaksana</h4>
      <h4 align="right" style="margin-top: 50px;">(Dr. Usman, M.Ag)</h4>
    </div>
  </div>
</body>
</html>
