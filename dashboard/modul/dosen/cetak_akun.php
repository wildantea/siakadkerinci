<html>
<head>
<title>Cetak Akun Dosen</title>
<style>body {
  background: rgb(255,255,255); 
}

page {
  padding: 40px;
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
    -webkit-print-color-adjust: exact;
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
   width: 240px;
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
   width: 240px;
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
 $header_identity = $db->fetch_single_row("identitas","id_identitas",1);
 $alamat_identity = $db->fetch_single_row("identitas","id_identitas",2);

?>
  <body>
<?php
 $kode_jur = "";
$aktif = "";

    
      if ($_POST['kode_jur']!='all') {
        $kode_jur = ' and dosen.kode_jur="'.$_POST['kode_jur'].'"';
      }
  
      if ($_POST['aktif']!='all') {
        $aktif = ' and dosen.aktif="'.$_POST['aktif'].'"';
      }




$query = $db->query("select dosen.nama_dosen as nama, dosen.nip,jurusan,sys_users.plain_pass as password from dosen 
inner join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
inner join sys_users on dosen.nip=sys_users.username
 where dosen.id_dosen is not null $kode_jur $aktif ");
$jumlah_data = $query->rowCount();

$rest_data = 0;

if ($jumlah_data<=40) {
  $jml = 1;
} else {
  $jml = ceil($jumlah_data/40);
}

$no = 1;
if ($jml>0) {
   for ($jumlah=0; $jumlah <$jml ; $jumlah++) {

          $check_row = $db->query("select dosen.nama_dosen as nama, dosen.nip,jurusan,sys_users.plain_pass as password from dosen 
inner join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
inner join sys_users on dosen.nip=sys_users.username
 where dosen.id_dosen is not null $kode_jur $aktif limit $rest_data,40");

          if ($check_row->rowCount()>0) {
         
          ?>
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

                    <h2 align="center">Akun Siakad Dosen</h2>
                    <h4 align="center">Silakan Login dengan username dan password anda Ke http://siakad.iainkerinci.ac.id </h4>
                    <br>

                        <table class="tabel-common" width="100%">
                       <thead>
                        <tr>
                          <th width="2%" >No</th>
                          <th>NIP</th>
                          <th width="30%">Nama Dosen</th>
                          <th>Dosen Program Studi</th>
                          <th>Username</th>
                          <th >Password</th>
                       </tr>
                       </thead>
                       <tbody>
                       <?php
                       $query_user = $db->query("select dosen.nama_dosen, dosen.nip,jurusan,sys_users.plain_pass as password from dosen 
inner join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
inner join sys_users on dosen.nip=sys_users.username
 where dosen.id_dosen is not null $kode_jur $aktif  group by dosen.nip limit $rest_data,40 ");
                       foreach ($query_user as $dt) {
                         ?>
                         <tr>
                           <td><?=$no;?></td>
                            <td><?=$dt->nip;?></td>
                           <td><?=$dt->nama_dosen;?></td>
                           <td><?=$dt->jurusan;?></td>
                           <td><?=$dt->nip;?></td>
                           <td><?=$dt->password;?></td>
                         </tr>
                         <?php
                         $no++;
                       }
                       ?>
                       

                    </tbody></table>
              </div>
                    </page>
          <?php
          //end row count
           }
      $rest_data+=40;
  }

} else {
  //only one page
  
}


?>

</body>
<html>
