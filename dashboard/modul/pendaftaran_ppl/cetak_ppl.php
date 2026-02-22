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
<?php
$fakultas="";
$jurusan="";
$lokasi="";
$priode="";
$kelamin="";

if (isset($_POST['fakultas_filter']) && $_POST['fakultas_filter']!='' ) {

  if ($_POST['fakultas_filter']!='all') {
    $fakultas = ' and fakultas.kode_fak="'.$_POST['fakultas_filter'].'"';
  }
}
 
if(isset($_POST['jurusan_filter']) && $_POST['jurusan_filter']!='') {
  if ($_POST['jurusan_filter']!='all') {
    $jurusan = ' and jurusan.kode_jur="'.$_POST['jurusan_filter'].'"';
  }
}

if(isset($_POST['id_lokasi'])) {
  if ($_POST['id_lokasi']!='all') {
    $lokasi = ' and lokasi_ppl.id_lokasi="'.$_POST['id_lokasi'].'"';
  }
}

if(isset($_POST['jk'])) {
  if ($_POST['jk']!='all') {
    $kelamin = ' and mahasiswa.jk="'.$_POST['jk'].'"';
  }
}

if(isset($_POST['priode_filter'])) {
  if ($_POST['priode_filter']!='all') {
    $priode = ' and priode_ppl.id_priode="'.$_POST['priode_filter'].'"';
  }
}

//print_r($_POST); 

$idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
foreach ($idt as $identitas) {
     # code...
}
$idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
foreach ($idt2 as $identitas2) {
     # code...
}

$qq=$db->query("select lokasi_ppl.nama_lokasi,d.nama_dosen as dpl1,dd.nama_dosen as dpl2, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from ppl kkn 
  left join mahasiswa on kkn.nim=mahasiswa.nim 
  left join fakultas on kkn.kode_fak=fakultas.kode_fak 
  left join jurusan on kkn.kode_jur=jurusan.kode_jur 
  left join priode_ppl on priode_ppl.id_priode=kkn.id_priode
  left join lokasi_ppl lokasi_ppl on lokasi_ppl.id_lokasi=kkn.id_lokasi
  left join dosen d on d.id_dosen = lokasi_ppl.dpl 
  left join dosen dd on dd.id_dosen = lokasi_ppl.dpl2
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
       <td><img src="../../assets/login/img/logokerinci.png" width="100" height="100"></td>
       <!--halaman <?= $i ?>-->
       <td>
        <h3><br><?= $identitas->header ?></h3>
        <hr>
        <div style="font-size:10"><?= $identitas2->alamat ?></div>
      </td>
    </tr>
  </tbody></table>
  <br>
   <h2 align="center" style="margin-bottom: 10px;">DAFTAR PESERTA KUKERTA</h2>
  <table>
    <tr>
      <td style="font-weight: bold;">Lokasi</td><td style="font-weight: bold;">: <?= $nama_lokasi ?></td>
    </tr>
    <tr>
      <td style="font-weight: bold;">DPL 1</td><td style="font-weight: bold;">: <?= $dpl1 ?></td>
    </tr>
     <tr>
      <td style="font-weight: bold;">DPL 2</td><td style="font-weight: bold;">: <?= $dpl2 ?></td>
    </tr>
  </table>
 
  
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
          <th width="200" scope="col">Lokasi</th>
        </tr>         
        <?php   
      
        $q=$db->query("select kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn,lokasi_ppl.nama_lokasi from ppl kkn
 inner join mahasiswa on kkn.nim=mahasiswa.nim inner join fakultas on kkn.kode_fak=fakultas.kode_fak
 inner join jurusan on kkn.kode_jur=jurusan.kode_jur left join priode_ppl on
 priode_ppl.id_priode=kkn.id_priode left join lokasi_ppl on lokasi_ppl.id_lokasi=kkn.id_lokasi 
where id_kkn is not null 

$fakultas $jurusan $priode $lokasi $kelamin 

 order by mahasiswa.nama asc, kkn.id_kkn");


        $no=1;
        foreach ($q as $k) {

          echo "
          <tr>
          <td>$no</td>
          <td>$k->nim</td>
          <td>$k->nama</td>
          <td>$k->nama_resmi</td>
          <td>$k->nama_jur</td>
          <td>$k->nama_lokasi</td>
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
