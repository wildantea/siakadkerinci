<?php
session_start();
include "../../../inc/config.php";


  $kode_dokumen = "";
              $doc_name = $db->fetchSingleRow("tb_data_dokumen","short_name","cetak_uts");
              $has_pengesah = $db->fetchCustomSingle("select * from tb_data_jabatan_pengesah where kode_jur=? and id_dokumen=?",array('kode_jur' => $_POST['jurusan'],'id_dokumen' => $doc_name->id_dokumen));

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
  <title>Cetak Rekap Rencana Studi</title>
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


$jur_kode = aksesProdi('tb_master_mahasiswa.jur_kode');

$fakultas = "";
$periode = "";
$mulai_smt = "";
$is_bayar = "";
$disetujui = "";
$mulai_smt_end = "";

if (isset($_POST['jurusan'])) {
    if (hasFakultas()) {
      $array_filter['fakultas'] = $_POST['fakultas'];
      if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
        $fakultas = getProdiFakultas('tb_master_mahasiswa.jur_kode',$_POST['fakultas']);
      }
    }
    if ($_POST['periode']!='all') {
      $periode = ' and tb_data_kelas_krs.id_semester="'.$_POST['periode'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and tb_master_mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = ' and mulai_smt between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
        }

      }

    if ($_POST['is_bayar']!='all') {
      if ($_POST['is_bayar']=='Y') {
          $is_bayar = "and (select boleh_krs from tb_data_status_bayar where nim=tb_master_mahasiswa.nim and tb_data_status_bayar.id_semester='".$_POST['periode']."' and id_jenis_pembayaran=2) IS NOT NULL";

      } else {
          $is_bayar = "and (select boleh_krs from tb_data_status_bayar where nim=tb_master_mahasiswa.nim and tb_data_status_bayar.id_semester='".$_POST['periode']."' and id_jenis_pembayaran=2) IS NULL";
      }
    }
    if ($_POST['disetujui']!='all') {
      $disetujui = ' and disetujui="'.$_POST['disetujui'].'"';
    }

}


//onload="window.print()"
?>
<body class="A4 portrait" >
   <?php
    
if ($_POST['status_krs']=='1') {
  $count_peserta = $db->fetchCustomSingle("SELECT count(tb_data_kelas_krs.krs_id) as jml from tb_data_kelas_krs
INNER JOIN tb_master_mahasiswa USING(nim)
where nim not in(select nim from tb_data_kelulusan)
$periode $fakultas $jur_kode $mulai_smt $is_bayar $disetujui
");
} else {
  $count_peserta = $db->fetchCustomSingle("SELECT count(tb_master_mahasiswa.nim) as jml from tb_master_mahasiswa
where nim not in(select nim from tb_data_kelulusan) and tb_master_mahasiswa.nim not in(select nim from tb_data_kelas_krs where id_semester='".$_POST['periode']."') 
 $fakultas $jur_kode $mulai_smt $is_bayar");


}

             $ada_header=true;
              $last_page = false;

              $jml_peserta = $count_peserta->jml;


              //limit per page <=15, 9 record max + include tanda tangan dalam halaman pertama
              
               $no=1;
               $offset=0;
               $limit = 30;
               $include_signature = 25;

                $jml_page = floor($jml_peserta/$limit);
                if ($jml_page<1) {
                  $jml_page = 1;
                }



              for ($i=1; $i <=$jml_page ; $i++) {

                if ($i==$jml_page) {
                  $last_page = true;
                }

if ($_POST['status_krs']=='1') {
  $row_mahasiswa = $db->query("SELECT tb_data_kelas_krs.krs_id,tb_master_mahasiswa.nim,tb_master_mahasiswa.nama,mulai_smt,
 disetujui,tb_data_kelas_krs.id_semester,jatah_sks,sks_diambil,1 as is_krs,jur_kode
from tb_data_kelas_krs
INNER JOIN tb_master_mahasiswa USING(nim)
where nim not in(select nim from tb_data_kelulusan)
$periode $fakultas $jur_kode $mulai_smt $is_bayar $disetujui limit $offset,$limit
");
} else {
  $row_mahasiswa = $db->query("SELECT tb_master_mahasiswa.nim,tb_master_mahasiswa.nama,mulai_smt,0 as disetujui,0 as is_krs,0 as sks_diambil,".$_POST['periode']." as id_semester,jur_kode,
 (select sks_mak from tb_data_jatah_sks j where  IFNULL((select tb_data_akm.ip  from tb_data_akm where tb_data_akm.nim=tb_master_mahasiswa.nim
and tb_data_akm.id_semester!='".$_POST['periode']."' and tb_data_akm.id_semester<='".$_POST['periode']."'
and tb_data_akm.id_stat_mhs='A' ORDER BY id_semester DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks from tb_master_mahasiswa
where nim not in(select nim from tb_data_kelulusan) and tb_master_mahasiswa.nim not in(select nim from tb_data_kelas_krs where id_semester='".$_POST['periode']."') 
 $fakultas $jur_kode $mulai_smt $is_bayar limit $offset,$limit");

}

              $offset=$offset+$limit;

              if ($last_page==true && $row_mahasiswa->rowCount()>$include_signature) {
                $jml_page++;
                $last_page = false;
              }



?>
       <div class="page-break">
        <div class="sheet padding-10mm">
          

      <?php
       if ($ada_header) {
       ?>
       <div style="text-align: right"><?=$kode_dokumen;?></div>
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


                  <br>

      <h2 align="center">REKAP RENCANA STUDI</h2>
                  <h4 align="center">Periode Semester : <?= getPeriode($_POST['periode']) ?></h4>
       <?php
       }
      ?>
      <br />
         <table width="100%" class="tabel-common">
           <?php
            if ($row_mahasiswa->rowCount()>0) {
              ?>
            <tr>
               <th height='24'>NO</th>
               <th>NIM</th>
               <th width="25%">NAMA</th>
               <th>JATAH SKS</th>
               <th>SKS DIAMBIL</th>
               <th>PROGRAM STUDI</th>
            </tr>
            <?php
            $prodi_jenjang = getProdiJenjang();
                      foreach ($row_mahasiswa as $mhs) {
                     echo "
                          <tr>
                          </tr>
                          <tr>
                              <td style='text-align:center; vertical-align:middle;' height='24' >$no</td>
                              <td style='text-align:center; vertical-align:middle;' width='150'>$mhs->nim</td>
                              <td style='vertical-align:middle;' width='250'>$mhs->nama</td>
                              <td style='text-align:center'>$mhs->jatah_sks</td>
                              <td style='text-align:center'>$mhs->sks_diambil</td>
                              <td style='text-align:center'>".$prodi_jenjang[$mhs->jur_kode]."</td>

                          </tr>";
                      $no++;

                  //  echo "</div>";
              }

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
        <p></p><div style="text-align: right">Halaman <?=$i;?></div>
        </div></div>
    <?php
    //end loop
    }

?>

</body>
</html>
