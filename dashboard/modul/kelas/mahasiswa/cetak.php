<?php
session_start();
include "../../../inc/config.php";
session_check_json();
?>
<html>
<head>
  <title>Cetak Jadwal</title>
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 landscape; }

table {
   border-collapse: collapse;
}

.table-head tr td {
   font-family: Tahoma;
   font-size: 11px;
   padding: 2px;
}

.tabel-common tr td {
  padding: 5px;
}

  </style>
  <link rel="icon" type="image/png" href="<?=base_url().'upload/logo/'.getPengaturan('logo');?>">
</head>
<?php






?>
<!-- onload="window.print()" -->
<body class="A4 landscape">
   <?php
    

    

              $ada_header=true;
              $last_page = true;

             ?>

       <div class="page-break">
        <div class="sheet padding-10mm">
          

      <?php
      $header_attributes = $db2->fetchSingleRow("mahasiswa","nim",$_POST['nim']);
      $nim = $header_attributes->nim;
       if ($ada_header) {
       ?>

                   <table>
                    <tbody><tr>
                     <td style="vertical-align: top;">
                       <img src="<?=base_url().'upload/logo/'.getPengaturan('logo');?>" width="100" height="100">
                    </td><td>
                     <h1><?= getPengaturan('header') ?></h1>
                     <?= getPengaturan('alamat') ?>
                    </tr>
                  </tbody></table>
                  <hr>

      <h2 align="center">JADWAL KULIAH</h2>
      <h4 align="center">Periode <?=ganjil_genap($_POST['periode']);?></h4>
        <table width="100%" class="table-head">
            <tr>
               <td nowrap ="nowrap" width="5%">Nama</td>
               <td nowrap ="nowrap" width="3%">:</td>
               <td nowrap ="nowrap" colspan="2"><?=$header_attributes->nama ?></td>
            </tr>
        <tr>
               <td nowrap ="nowrap" >NIM</td>
               <td nowrap ="nowrap" >:</td>
               <td><?= $nim?></td>
            </tr>
         </table>
       <?php
       }
      ?>
      <br />
      <table class="tabel-common" width="100%">
         <thead>
          <tr>
            <th width="2%" rowspan="2">No</th>
             <th>Dosen</th>
            <th >Mata kuliah</th>
            <th>SMT</th>
            <th>SKS</th>
            <th >Kelas</th>
            <th>Hari</th>
            <th >Ruang</th>
            <th>Waktu</th>
           
            <th>Prodi Kelas</th>
         </tr>
       </thead>
       <tbody>
<?php
$periode = ' and view_nama_kelas.sem_id="'.$_POST['periode'].'"';
$data_krs = $db2->query("select view_nama_kelas.nm_matkul,view_nama_kelas.sem_matkul,view_nama_kelas.sks,view_nama_kelas.kls_nama,ruang_ref.nm_ruang,jadwal_kuliah.hari,jadwal_kuliah.jam_mulai,jadwal_kuliah.jam_selesai,
 (select group_concat(distinct nama_gelar separator '#')
    from view_nama_gelar_dosen inner join dosen_kelas on view_nama_gelar_dosen.nip=dosen_kelas.id_dosen where dosen_kelas.id_kelas=view_nama_kelas.kelas_id) as nama_dosen,
view_nama_kelas.jurusan as nama_jurusan,jadwal_kuliah.jadwal_id,view_nama_kelas.kelas_id

 from view_nama_kelas
left join jadwal_kuliah using(kelas_id)
left join ruang_ref using(ruang_id)
where view_nama_kelas.kelas_id in(select id_kelas from krs_detail where nim='".getUser()->username."' and 
krs_detail.id_kelas=view_nama_kelas.kelas_id)
$periode
$periode order by id_hari asc,jam_mulai asc");
echo $db2->getErrorMessage();

          $no=1;
          $jumlah_sks = 0;

          foreach ($data_krs as $value) {
            echo "<tr>
                     <td align='center'>$no</td><td>";
                        if ($value->nama_dosen!='') {
                          $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
                      $nama_dosen = trim(implode("<br>", $nama_dosen));
                        echo $nama_dosen;
                      } else {
                        echo $value->nama_dosen;
                      }
                    echo "</td>
                     <td>". $value->nm_matkul."</td>
                     <td>$value->sks</td>
                     <td>$value->sem_matkul</td>
                     <td align='center'>$value->kls_nama</td>
                     <td align='center'>".ucwords($value->hari)."</td>
                     <td align='center'>$value->nm_ruang</td>
                     
                     <td>".substr($value->jam_mulai,0,5)." - ".substr($value->jam_selesai,0,5)."</td>";
                    echo "</td><td>$value->nama_jurusan</td>
                 </tr>";
                 $no++;
                 $jumlah_sks+=$value->sks;
          }
          ?>
        
          <tr>
            <td colspan="4" class="right" align="center"><strong>JUMLAH SKS DIAMBIL</strong></td>
            <td align="center"><strong><?=$jumlah_sks;?></strong></td>
            <td colspan="6">&nbsp;</td>
          </tr>
        </tbody>
        </table>
        </div></div>

</body>
</html>
