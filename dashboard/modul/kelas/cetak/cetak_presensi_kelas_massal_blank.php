<?php
session_start();
include "../../../inc/config.php";

$kode_dokumen = "";
$doc_name = $db->fetchSingleRow("tb_data_dokumen","short_name","peserta_kelas");
$has_pengesah = $db->fetchCustomSingle("select * from tb_data_jabatan_pengesah where kode_jur=? and id_dokumen=?",array('kode_jur' => $_POST['jur_filter'],'id_dokumen' => $doc_name->id_dokumen));

if ($has_pengesah) {

  $pejabat_pengesah = $has_pengesah->pejabat_pengesah;

  $kode_dokumen = $has_pengesah->kode_dokumen;

  $pejabat = json_decode($pejabat_pengesah);


  //mulai dari kiri
  $status_pengesah_0 = status_pengesah($pejabat,0);
  $status_pengesah_1 = status_pengesah($pejabat,1);
  $status_pengesah_2 = status_pengesah($pejabat,2);

  //mulai dari kiri
  $kota_1 = kota($pejabat,0);
  $kota_2 = kota($pejabat,1);
  $kota_3 = kota($pejabat,2);

  //ada tgl
  $tgl_1 = ada_tgl($pejabat,0);
  $tgl_2 = ada_tgl($pejabat,1);
  $tgl_3 = ada_tgl($pejabat,2);

  //tipe pengesah
  $tipe_pengesah_1 = tipe_pengesah($pejabat,0);
  $tipe_pengesah_2 = tipe_pengesah($pejabat,1);
  $tipe_pengesah_3 = tipe_pengesah($pejabat,2);

  //kategori pejabat
  $kategori_pejabat_1 = kategori_pejabat($pejabat,0);
  $kategori_pejabat_2 = kategori_pejabat($pejabat,1);
  $kategori_pejabat_3 = kategori_pejabat($pejabat,2);

  $nip_pengesah_1 = nip_pengesah($pejabat,0);
  $nip_pengesah_2 = nip_pengesah($pejabat,1);
  $nip_pengesah_3 = nip_pengesah($pejabat,2);

  $nama_pengesah_1 = nama_pengesah($pejabat,0);
  $nama_pengesah_2 = nama_pengesah($pejabat,1);
  $nama_pengesah_3 = nama_pengesah($pejabat,2);

//end has pengesah condition
}

?>
<html>
<head>
  <title>Cetak Presensi Kelas</title>
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 landscape }
  </style>
</head>
<?php

$periode = "";
$kur_id = "";
$sistem_kuliah = "";
$hari = "";
$matakuliah = "";



    if ($_POST['jur_filter']!='all') {
      $jur_kode = ' and view_matakuliah_kurikulum.kode_jur="'.$_POST['jur_filter'].'"';
    }
    if ($_POST['periode']!='all') {
      $periode = ' and vk.sem_id="'.$_POST['periode'].'"';
    }
    if ($_POST['kurikulum']!='all') {
      $kur_id = ' and view_matakuliah_kurikulum.kur_id="'.$_POST['kurikulum'].'"';
    }
    if ($_POST['matakuliah']!='all') {
      $matakuliah = ' and vk.id_matkul="'.$_POST['matakuliah'].'"';
    }
    if ($_POST['sistem_kuliah']!='all') {
      $sistem_kuliah = ' and id_jenis_kelas="'.$_POST['sistem_kuliah'].'"';
    }
    if ($_POST['hari']!='all') {
      $hari = ' and id_hari="'.$_POST['hari'].'"';
    }

$header_attributes = $db->query("SELECT vk.kode_jur,vk.kls_nama,vk.total_sks as sks,vk.kode_mk,vk.nama_mk,vjk.nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,fungsi_dosen_kelas(vk.kelas_id) as nama_dosen,vk.nama_jurusan,view_semester.tahun_akademik,vk.kelas_id,jml_pertemuan from view_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
inner join view_matakuliah_kurikulum using(id_matkul)
LEFT JOIN view_jadwal_kelas vjk using(kelas_id)
where vk.kelas_id is not null
$jur_kode $periode $kur_id $matakuliah $sistem_kuliah $hari
");

?>
<body class="A4 landscape" onload="window.print()">
   <?php
foreach ($header_attributes as $header_attribute) {


    $count_peserta = $db->fetchCustomSingle("SELECT COUNT(tdk.krs_detail_id) as jml  from tb_data_kelas_krs_detail tdk WHERE disetujui=1 AND kelas_id=?",array('kelas_id' => $header_attribute->kelas_id));

              $per_page = 15;

              $sisa = $count_peserta->jml%$per_page;
              if ($sisa==0) {
                $sisa = $per_page;
              }

              $page_count = ceil($count_peserta->jml/$per_page);

              $last_page_count = $page_count;

              $additional_page = false;
              $include_signature = 9;

              if ($sisa>$include_signature) {
                $page_count = $page_count+1;
                $additional_page = true;
              }


              $ada_header=true;
              $last_page = false;

              $no=1;

              for ($i=0; $i < $page_count ; $i++) {
                  $offset = $i*$per_page;

                  if ($i==$last_page_count-1 && $additional_page==true) {
                    $limit = $sisa-1;
                    $last_offset = $offset + $limit;
                  } elseif ($i==$last_page_count) {
                    $offset = $last_offset;
                    $limit = 1;
                    $last_page = true;
                  } elseif ($i==$last_page_count-1 && $additional_page==false) {
                    $limit = $sisa;
                    $last_page = true;
                  } else {
                    $limit = $per_page;
                  }
              $row_mahasiswa=$db->query("SELECT tb_data_kelas_krs.nim,view_simple_mhs.nama  from tb_data_kelas_krs_detail tdk
INNER JOIN tb_data_kelas_krs using(krs_id)
INNER JOIN view_simple_mhs USING(nim)
WHERE tdk.disetujui=1 AND kelas_id=? order by nim asc limit $offset,$limit",array('kelas_id' => $header_attribute->kelas_id));

                
?>
       <div class="page-break">
        <div class="sheet padding-10mm">

      <?php
       if ($ada_header) {
       ?>
       <div style="text-align: right;margin-top: -20px;"><?=$kode_dokumen;?></div>
        <table class="tabel-info" width="100%">
          <tr>
           <td rowspan = "6" width ="35%" valign="top">
             <table>
              <tr>
               <td>
                <img src="<?=getPengaturan('logo');?>" width="100" height="100">
              </td>
              <td>
                <h4><?= getPengaturan('header') ?></h4>
                <?php
                if (hasFakultas()) {
                  ?>
                  <h4>FAKULTAS <?= getFakultasName($header_attribute->kode_jur) ?></h4>
                  <?php
                }
                ?>
               
                <div style="font-size:10"><?= getPengaturan('alamat') ?></div>
              </td>
            </tr>
          </table>


        </td>
          <td nowrap ="nowrap" colspan="6"><u><b><h3>DAFTAR PRESENSI KULIAH</h3></b></u></td>
      </tr>
        <tr>
               <td nowrap ="nowrap" >Mata Kuliah</td>
               <td nowrap ="nowrap" >:</td>
               <td colspan="2"><?= $header_attribute->kode_mk."/".$header_attribute->nama_mk ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap" >Kelas</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->kls_nama ?></td>
               <td nowrap ="nowrap" >Periode</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->tahun_akademik ?></td>
            </tr>
            <tr>

               <td nowrap ="nowrap" >Bobot SKS</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->sks ?>&nbsp;sks</td>
               <td >Program Studi</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->nama_jurusan ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap"  valign="top">Hari / Waktu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <td nowrap ="nowrap"  valign="top"><?= $header_attribute->nama_hari ?> / <?= $header_attribute->jam_mulai  ?>-<?= $header_attribute->jam_selesai ?></td>
               <td nowrap ="nowrap" >Ruang</td>
               <td nowrap ="nowrap" >:</td>
               <td nowrap ="nowrap" ><?= $header_attribute->nm_ruang ?></td>
            </tr>
            <tr>
               <td nowrap ="nowrap"  valign="top">Dosen Pengampu</td>
               <td nowrap ="nowrap"  valign="top">:</td>
               <?php
               $dosen = "";
               if ($header_attribute->nama_dosen!="") {
                 $dosen_pengampu = explode("#", $header_attribute->nama_dosen);
                 if (count($dosen_pengampu)>0) {
                   for ($j=0; $j <count($dosen_pengampu); $j++) { 
                     $dosen.= '<li>'.$dosen_pengampu[$j]."</li>";
                   }
                 }
               }
               ?>
               <td nowrap ="nowrap"  valign="top"><ol style="padding:0 0 0 15;"><?=$dosen;?></ol></td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
               <td nowrap ="nowrap" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
         </table>
       <?php
       }
      ?>
      <br />
         <table width="100%" class="tabel-common">
           <?php
           $jumlah_pertemuan = $header_attribute->jml_pertemuan;
            if ($row_mahasiswa->rowCount()>0) {
              ?>
            <tr>
               <th rowspan="3">No.</th>
               <th rowspan="3">NIM</th>
               <th rowspan="3" width="20%">Nama</th>
               <th colspan="<?=$header_attribute->jml_pertemuan;?>">Perkuliahan Ke / Tanggal</th>
               <th rowspan="2" colspan="3">Rekapitulasi</th>
            </tr>
            <tr>
              <?php
              for ($head_pertemuan=1; $head_pertemuan <= $header_attribute->jml_pertemuan; $head_pertemuan++) { 
               echo '<th width="4%">'.$head_pertemuan.'</th>';
              }
              ?>
            </tr>
            <tr>
              <?php
              for ($body_pertemuan=1; $body_pertemuan <= $header_attribute->jml_pertemuan; $body_pertemuan++) { 
               echo '<th width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
              }
              ?>
              <th>Target</th>
              <th>Hadir</th>
              <th>(&nbsp;%&nbsp;)</th>
            </tr>
            <?php
                      foreach ($row_mahasiswa as $mhs) {
                     echo "
                          <tr>
                          </tr>
                          <tr>
                              <td style='text-align:center; vertical-align:middle;' height='34' >$no</td>
                              <td style='text-align:center; vertical-align:middle;' width='150'>$mhs->nim</td>
                             <td style='vertical-align:middle;' width='250'>$mhs->nama</td>";
                              for ($row_pertemuan=1; $row_pertemuan <= $jumlah_pertemuan; $row_pertemuan++) { 
                               echo '<td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                              }
                              echo "<td></td><td></td><td></td>";

                          echo "</tr>";
                      $no++;

                  //  echo "</div>";
              }

          }


       
        if ($last_page) {
         ?>

              <?php
               $dosen = "";
               if ($header_attribute->nama_dosen!="") {
                 $dosen_pengampu = explode("#", $header_attribute->nama_dosen);
                 ?>
                 <tr>
                    <th colspan="<?=$header_attribute->jml_pertemuan+6;?>">DOSEN PENGAMPU</th>
                  </tr>
                <?php
                 if (count($dosen_pengampu)>0) {
                   for ($l=0; $l <count($dosen_pengampu); $l++) { 
                    ?>
                  <tr>
                     <td style="text-align:center;vertical-align:middle;" height='34' style="width:20px"><?=($l+1);?></td>
                     <td style="vertical-align:middle;" colspan="2"><div class="nama">&nbsp;<?= $dosen_pengampu[$l] ?></div></td>
                     <?php
                              for ($row_dosen_pertemuan=1; $row_dosen_pertemuan <= $jumlah_pertemuan; $row_dosen_pertemuan++) { 
                               echo '<td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                              }
                     ?>
                     <td></td><td></td><td></td>
                  </tr>
                    <?php
                   }
                 }
               }
               ?>




            <tr valign="center">
                  <td colspan="3" height="34" style="text-align:center; vertical-align:middle;" width="100">Jumlah Mahasiswa Hadir</td>
                     <?php
                              for ($row_mhs_pertemuan=0; $row_mhs_pertemuan <= $jumlah_pertemuan; $row_mhs_pertemuan++) { 
                               echo '<td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
                              }
                     ?>
                      <td></td><td></td>
               </tr>
             </table>
             <?php
              if ($has_pengesah) {
                ?>
          <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_pengesahan_dokumen">
              <tr>
                <td width="32%" style="text-align:center"><span class="preview_kota_1"><?=$kota_1;?></span><span class="preview_tgl_1"><?=$tgl_1;?></span><span class="preview_tipe_pengesah_1"><?=$tipe_pengesah_1;?></span>
                <span class="preview_kat_jabatan_1"><?=$kategori_pejabat_1;?></span></td>
                <td width="31%" style="text-align:center;"><span class="preview_kota_2"><?=$kota_2;?></span><span class="preview_tgl_2"><?=$tgl_2;?></span><span class="preview_tipe_pengesah_2"><?=$tipe_pengesah_2;?></span>
                <span class="preview_kat_jabatan_2"><?=$kategori_pejabat_2;?></span>
                </td>
                <td width="37%" style="text-align:center"><span class="preview_kota_3"><?=$kota_3;?></span><span class="preview_tgl_3"><?=$tgl_3;?></span><span class="preview_tipe_pengesah_3"><?=$tipe_pengesah_3;?></span><span class="preview_kat_jabatan_3"><?=$kategori_pejabat_3;?></span>
                </td>
              </tr>
              <tr>  
                <td align="center" height="50">&nbsp;</td>
                <td align="center" height="50"></td>
                <td align="center" height="50"></td>
             </tr>
             <tr>  <td align="justify" style="text-align:center">
                        <span style="text-decoration: underline;" class="preview_pengesah_1"><?=$nama_pengesah_1;?></span><?=($nama_pengesah_1!='')?'<br />------------------------------<br />':'';?>
                        <span class="preview_nip_1"><?=$nip_pengesah_1;?></span></td>
                          <td nowrap="nowrap" style="text-align:center">
                              <span class="preview_pengesah_2"><?=$nama_pengesah_2;?></span><?=($nama_pengesah_2!='')?'<br />------------------------------<br />':'';?>
                              <span class="preview_nip_2"><?=$nip_pengesah_2;?></span></td>
                          <td style="text-align:center">
              <span class="preview_pengesah_3"><?=$nama_pengesah_3;?></span><?=($nama_pengesah_3!='')?'<br />------------------------------<br />':'';?>
                                  <span class="preview_nip_3"><?=$nip_pengesah_3;?></span></td>
                       </tr>
            </table>

         <?php
         //end has pengesah
         }
         //end if last page
        }
        //end page-break and sheet
        ?>
        </table>
        <p></p><div style="text-align: right">Halaman <?=$i+1;?></div>
        </div></div>
    <?php

    //end loop
    }


//end loop foreach
}


?>

</body>
</html>
