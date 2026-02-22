<?php
session_start();
include "../../../inc/config.php";

$kode_jur = $db->fetchCustomSingle("select kode_jur from view_kelas where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));

  $kode_dokumen = "";
              $doc_name = $db->fetchSingleRow("tb_data_dokumen","short_name","cetak_bap");
              $has_pengesah = $db->fetchCustomSingle("select * from tb_data_jabatan_pengesah where kode_jur=? and id_dokumen=?",array('kode_jur' => $kode_jur->kode_jur,'id_dokumen' => $doc_name->id_dokumen));

              if ($has_pengesah) {

              if ($doc_name->enable_signature=='Y') {
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
              } else {
                $kode_dokumen = $has_pengesah->kode_dokumen;
              }

              

            }
?>
<html>
<head>
  <title>Cetak Berita Acara Perkuliahan</title>
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 portrait }

.tabel-info tr td, th {
 font-family: Tahoma;
 font-size: 11px;
 padding: 2px;
 font-weight: bold;
}

  </style>
</head>
<?php


$header_attributes = $db->fetchCustomSingle("SELECT vk.sem_id,vk.kode_jur,vk.kls_nama,vk.total_sks as sks,vk.kode_mk,vk.nama_mk,vjk.nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.nama_jurusan,view_semester.tahun_akademik,jml_pertemuan,
(select group_concat(nama_gelar separator '#' ) from view_dosen_kelas where kelas_id=vk.kelas_id order by dosen_ke asc) as nama_dosen from view_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
LEFT JOIN view_jadwal_kelas vjk using(kelas_id)
LEFT JOIN view_dosen_kelas USING(kelas_id) WHERE vk.kelas_id=? ORDER BY nama_dosen ASC",array('kelas_id' => $_POST['kelas_id']));

//onload="window.print()"
?>
<body class="A4 portrait" >
   <?php

               $ada_header=true;
               $last_page = true;

              for ($loop=0; $loop < 1 ; $loop++) { 



?>
       <div class="page-break">
        <div class="sheet padding-10mm">
          

      <?php
       if ($ada_header) {
       ?>
       <div style="text-align: right;margin-top: -10px;"><?=$kode_dokumen;?></div>
                   <table>
                    <tbody><tr>
                     <td style="vertical-align: top;">
                      <img src="<?=getPengaturan('logo');?>" width="100" height="100">
                    </td><td>
                     <h1><?= getPengaturan('header') ?></h1>

                     <?= getPengaturan('alamat') ?>
                    </tr>
                  </tbody></table>
                  <hr>



      <h2 align="center">BERITA ACARA PERKULIAHAN</h2>
                  <h4 align="center">Periode Semester : <?= $header_attributes->tahun_akademik ?></h4><br>

                  <table class="tabel-info">
                    <tbody>
                      <tr>
                      <td>Kelas / Ruang</td>
                      <td>:</td>
                      <td style="width: 50%;"><?= $header_attributes->kls_nama ?> / <?= $header_attributes->nm_ruang ?></td>
                      <td>Hari/Jam</td>
                      <td>:</td>
                      <td><?= $header_attributes->nama_hari ?>,<?= $header_attributes->jam_mulai.' - '.$header_attributes->jam_selesai ?></td>
                    </tr>
                    <tr>
                      <td>Matakuliah</td>
                      <td>:</td>
                      <td><?= $header_attributes->kode_mk." - ".$header_attributes->nama_mk ?></td>
                       <td>Program Studi</td>
                      <td>:</td>
                      <td><?= $header_attributes->nama_jurusan ?></td>
                    </tr>
                    <tr>
                      <td valign="top">Dosen</td>
                      <td valign="top">:</td>
                      <td valign="top">
                        <?php
                         $dosen = array();
                         if ($header_attributes->nama_dosen!="") {
                           $dosen_pengampu = explode("#", $header_attributes->nama_dosen);
                           if (count($dosen_pengampu)>0) {
                             if (count($dosen_pengampu)>1) {
                               for ($j=0; $j <count($dosen_pengampu); $j++) {
                                 echo ($j+1).'. '.$dosen_pengampu[$j]."<br>";
                               }
                             } else {
                                 echo $header_attributes->nama_dosen;
                             }
                           }
                           $dosen_pengampu = array();
                         }
                         ?>
              </td>
                    </tr>
                  </tbody></table>
       <?php
       }
      ?>
      <br />
         <table width="100%" class="tabel-common">
  <tr>
    <th>PERT-KE</th>
    <th>HARI /TANGGAL</th>
    <th style="width:50%">MATERI</th>
    <!-- <th>PRODI</th> -->
    <th nowrap="nowrap">TANDA TANGAN</th>

  </tr>
            <?php
            $jumlah_pertemuan = $header_attributes->jml_pertemuan;
            for ($i=1; $i <=$jumlah_pertemuan ; $i++) {
             echo "
                  <tr style='height:45px'>
                      <td style='text-align:center; vertical-align:middle;' height='34' >$i</td>
                      <td style='text-align:center; vertical-align:middle;'></td>
                      <td style='vertical-align:middle;' width='250'></td>
                      <td style='text-align:center'></td>
                  </tr>";
            //  $no++;
            }


       
        if ($last_page) {
         ?>

             </table>
             <?php

             if ($has_pengesah && $doc_name->enable_signature=='Y') {
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
            //end has pengesah condition
            }
            ?>

         <?php
         //end if last page
        }
        //end page-break and sheet
         ?>
        </table>
        </div></div>
    <?php
    //end loop
    }




?>

</body>
</html>
