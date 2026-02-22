<?php 
session_start();
include "../../inc/config.php";

session_check();

 $semester = $db->fetch_single_row('priode_kkn','aktif',1);
 $thn = substr($semester->tgl_awal, 0,4); 

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
    font-family: Times;
    font-size: 12px;
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
   font-family: Times;
   font-size: 15px;
   margin: 6px 0px 6px 0px;
   width: 17cm;
 }

 div.page-landscape {
   visibility: visible;
   font-family: Times;
   font-size: 15px;
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
   font-family: Times;
   font-size: 15px;
   padding: 0px 2px 0px 2px;
 }

 table tr th {
   font-family: Times;
   font-size: 15px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
 }

 .tabel-common tr td {
   font-family: Times;
   font-size: 15px;
   padding: 0px 2px 0px 2px;
   border: 1px solid #ccc;
   vertical-align: top;
 }

 .tabel-common .nama {
   width: 250px;
   overflow: hidden;
 }

 .tabel-common tr th {
   font-family: Times;
   font-size: 15px;
   font-weight: bold;
   background-color: #fff;
   padding: 2px;
   border: 1px solid #ccc;
 }

 .tabel-info tr td, th {
   font-family: Times;
   font-size: 15px;
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
 font-family: Times;
 font-size: 12px;
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
 font-family: Times;
 font-size: 15px;
 margin: 6px 0px 6px 0px;
 width: 17cm;
}

div.page-landscape {
 visibility: visible;
 font-family: Times;
 font-size: 15px;
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
 font-family: Times;
 font-size: 15px;
 padding: 0px 2px 0px 2px;
}

table tr th {
 font-family: Times; 
 font-size: 15px;
 font-weight: bold;
 background-color: #eee;
 padding: 2px;
}

.tabel-common tr td {
 font-family: Times;
 font-size: 15px;
 padding: 0px 2px 0px 2px;
 border: 1px solid #000;
 vertical-align: top;
}

.tabel-common tr th {
 font-family: Times;
 font-size: 15px;
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
 font-family: Times;
 font-size: 15px;
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
  //window.print();
</script>
</head>
<?php
$fakultas="";
$jurusan="";
$lokasi="";
$priode="";
$nim = de($_GET['n']);
//$nim = "1810205043";

$idt=$db->query("SELECT i.`isi` as header FROM identitas i WHERE i.`id_identitas`='1'");
foreach ($idt as $identitas) {
     # code...
}
$idt2=$db->query("SELECT i.`isi` as alamat FROM identitas i WHERE i.`id_identitas`='2'");
foreach ($idt2 as $identitas2) {
     # code...
}
$q = $db->query("select nim,nama,nama_jurusan,nama_fakultas from view_simple_mhs where nim='$nim'  ");

foreach ($q as $k) {

?>
<!-- <body onLoad="//window.print();"> -->
<body>
  <page size="A4"> 
   <div class="nobreak">
    <table style="width: 100%">
      <tbody>
        <tr>
         <td style="width: 123px"><img src="../../assets/login/img/logokerinci.png" width="130" height="130"></td>
        
         <td>
          <h3 style="text-align: center;font-size: 18px"><br>
            <?= $identitas->header ?></h3>
          <hr>
          <div style="font-size:12px">
            Jl. Kapten Muradi, Kecamatan Pesisir Bukit, Kota Sungai Penuh
Telepon (0748) 21065; Faksimili (0748) 22114; Kode Pos 37112 <br>
Website: www.iainkerinci.ac.id; email: info@iainkerinci.ac.id
            </div>
        </td>
      </tr>
  </tbody>
</table>
  <br><br><br><br>
  <h2 align="center" style="margin-bottom: 10px;font-size: 14px">SURAT PERNYATAAN<br>
      KESEDIAAN MENGIKUTI KKN TEMATIK BERBASIS MODERASI BERAGAMA<br> INSTITUT AGAMA ISLAM NEGERI KERINCI TAHUN <?= $thn ?></h2>
      <br><br><br>
      <p style="font-size: 17px">Saya yang bertandatangan dibawah ini :</p>
      <table style="font-size: 17px">
        <tr>
          <td style="width: 100px">Nama</td><td> : <?= $k->nama ?> </td>
        </tr>
        <tr>
          <td>NIM</td><td> : <?= $k->nim ?>  </td>
        </tr>
        <tr>
          <td>Fakultas</td><td> : <?= $k->nama_jurusan ?> </td>
        </tr>
        <tr>
          <td>Jurusan</td><td> : <?= $k->nama_fakultas ?> </td>
        </tr>
      </table>
      <p style="font-size: 17px">Dengan ini menyatakan bahwa saya : </p>
        <ol style="position: relative;left: -20px">
          <li style="font-size: 17px">Bersedia mengikuti dan menaati semua tata tertib;</li>
          <li style="font-size: 17px">Bersedia ditempatkan di lokasi KKN yang sudah ditentukan dan tidak akan pindah lokasi;</li>
          <li style="font-size: 17px">Melaksanakan semua rangkaian kegiatan Kuliah Kerja Nyata Tematik Berbasis Moderasi
      Beragama IAIN Kerinci.</li>
        </ol>
     <p style="font-size: 17px"> Demikian surat pernyataan ini saya buat dengan sebenar-benarnya dan dapat dipergunakan sebagaimana mestinya.</p>
   </div>

   <div style="float: right;">
     <div style="width: 300px;font-size: 15px">
        Sungai Penuh, <?= tgl_indo(date("Y-m-d")) ?><br>
        Yang membuat pernyataan <br><br><br><br><br>
        <b><?= $k->nama ?></b><br>
        NIM. <?= $k->nim ?>
     </div>
   </div>
  </page> 

</body>
<?php
}?>
</html>
