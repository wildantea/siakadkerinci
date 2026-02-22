<?php
session_start();
include "../../../inc/config.php";
require_once '../../../lib/phpqrcode/qrlib.php'; // file library

$kelas_id = $_GET['ids'];

$header_attributes = $db2->fetchCustomSingle("SELECT vk.kode_jur,vk.kls_nama,vk.sks,vk.kode_mk,vk.nama_mk,vjk.hari as nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.jurusan as nama_jurusan,view_semester.tahun_akademik,vk.kelas_id,
(select group_concat(nama_gelar separator '#')
 from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=vjk.kelas_id and jadwal_id=vjk.jadwal_id order by dosen_ke asc) as nama_dosen,
(
  select count(id_krs_detail) as jml_peserta FROM krs_detail where id_kelas=vk.kelas_id
) as jml_peserta
  from view_nama_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
LEFT JOIN view_jadwal vjk using(kelas_id)
 WHERE vk.kelas_id=? ORDER BY nama_dosen ASC",array('kelas_id' => $kelas_id));
              function absen_label($status) {
    $colors = [
        'Hadir' => 'success',
        'Ijin'  => 'info',
        'Sakit' => 'warning',
        'Alpa'  => 'danger',
        ''      => 'default'
    ];
    $color = isset($colors[$status]) ? $colors[$status] : 'default';
    return "<span class='label label-$color'>$status</span>";
}
  function get_nim_absen($obj) {
    global $db;
    foreach ($obj as $key) {
      $nim[$key->nim] = $key;
    }
    return $nim;
  }

$counter = 1;
$dosen_datas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc",array("id_kelas" => $kelas_id));
if ($dosen_datas->rowCount()>1) {
    foreach ($dosen_datas as $dt) {
        $dosen_data[] = $counter.'. '.$dt->nama_gelar;
        $counter++;
        $data_dosen[$dt->nip] = $dt->nama_gelar;
    }
} else {
    foreach ($dosen_datas as $dt) {
        $dosen_data[] = $dt->nama_gelar;
        $data_dosen[$dt->nip] = $dt->nama_gelar;
    }
}


?>
<html>
<head>
  <title>Bukti Hadir</title>
    <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/dist/css/cetak/table.css">
  <style type="text/css">
@page { size: A4 portrait; }

.tabel-info tr td, th {
 font-family: Tahoma;
 font-size: 11px;
 padding: 2px;
 font-weight: bold;
}

  </style>
</head>
<?php




//onload="window.print()"
?>
<body class="A4 portrait" >
   <?php

              $ada_header=true;
              $last_page = false;
              $additional_page = false;

$jumlah_data = $db2->fetchCustomSingle("select count(*) as jml from tb_data_kelas_pertemuan where kelas_id=?",array('kelas_id' => $kelas_id));

              $row_count = $jumlah_data->jml;

              $row_perpage = 8;
              $row_perpage_include_signature = 8;

              $total_page = ceil($row_count/$row_perpage);

              $sisa_row = $row_count%$row_perpage;

              if ($sisa_row==0) {
                $sisa_row = $row_perpage;
              }

              if ($total_page==1 && $has_pengesah && $doc_name->enable_signature=='Y') {
                $row_perpage = $row_perpage-1;
                $total_page++;
              }

              $last_page_count = $total_page-1;



              $no=1;


              for ($i=0; $i < $total_page ; $i++) {

                  $offset = $i*$row_perpage;
                  $limit = $row_perpage;

                  if ($i==$last_page_count) {
                    $last_page = true;

                  }
/*                  if ($i==$last_page_count-1 && $additional_page==true) {
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
                  }*/

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
                      <img src="<?=base_url().'upload/logo/'.getPengaturan('logo');?>" width="100" height="100">
                    </td><td>
                     <h1><?= getPengaturan('header') ?></h1>

                     <?= getPengaturan('alamat') ?>
                    </tr>
                  </tbody></table>
                  <hr>



      <h2 align="center">BUKTI KEHADIRAN MAHASISWA</h2>
                  <h4 align="center">Periode Semester : <?= $header_attributes->tahun_akademik ?></h4><br>

                  <table class="tabel-info">
                    <tbody>
                      <tr>
                      <td>Kelas / Ruang</td>
                      <td>:</td>
                      <td style="width: 46%;"><?= $header_attributes->kls_nama ?> / <?= $header_attributes->nm_ruang ?></td>
                       <td>Program Studi</td>
                      <td>:</td>
                      <td><?= $header_attributes->nama_jurusan ?></td>
                    </tr>
                    <tr>
                      <td>Matakuliah</td>
                      <td>:</td>
                      <td><?= $header_attributes->kode_mk." - ".$header_attributes->nama_mk ?></td>
                      
                      <td>Hari/Jam</td>
                      <td>:</td>
                      <td><?= ucwords($header_attributes->nama_hari) ?>,<?= $header_attributes->jam_mulai.' - '.$header_attributes->jam_selesai ?></td>
                    </tr>
                    <tr>
                      <td valign="top">Dosen</td>
                      <td valign="top">:</td>
                      <td valign="top">
                       <?php
               $dosen = "";
              for ($j=0; $j <count($dosen_data); $j++) { 
                     echo $dosen_data[$j]."<br>";
                   }

  $mhs = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);

               ?>

              </td>
               <td>NAMA</td>
                      <td>:</td>
                      <td><?= $mhs->nama?></td>
                    </tr>
                  </tbody></table>
       <?php
       }
      ?>
      <br />
         <table width="100%" class="tabel-common">
  <tr>
    <th>PERT</th>
    <th>HARI /TANGGAL</th>
    <th style="width:50%">MATERI</th>
    <th nowrap="nowrap">STATUS</th>

  </tr>
            <?php



            //pertemuan 
            $meets = $db2->query("select tb_data_kelas_pertemuan.*,isi_absensi,(select materi from rps_materi_kuliah where id_kelas=tb_data_kelas_pertemuan.kelas_id and pertemuan=tb_data_kelas_pertemuan.pertemuan) as materi from tb_data_kelas_pertemuan left join tb_data_kelas_absensi using(id_pertemuan) where kelas_id=? limit $offset,$limit",array('kelas_id' => $kelas_id));
            $cek_jml_pertemuan = $db2->fetchCustomSingle("select count(*) from tb_data_kelas_pertemuan where kehadiran_dosen!='' and kelas_id=?",array('kelas_id' => $kelas_id));
            if ($meets->rowCount()>0) {
              foreach ($meets as $meet) {
$nim_user = array();
    if ($meet->isi_absensi!="") {
      $absen = json_decode($meet->isi_absensi);
      $nim_user = get_nim_absen($absen);
    }
                ?>
                  <tr style='height:40px'>
                      <td style='text-align:center; vertical-align:middle;' height='70' ><?=$meet->pertemuan;?></td>
                      <td style='text-align:center; vertical-align:middle;'><?=getHariFromDate($meet->tanggal_pertemuan).',<br>'.tgl_indo($meet->tanggal_pertemuan);?></td>


                      <td style='vertical-align:middle;' width='250'>
<?php
  if ( $meet->tanggal_pertemuan.' '.$meet->selesai < date('Y-m-d H:i:s') ) {
    echo $meet->materi;
  }

?>
  
</td>

                      <td style='text-align:center; vertical-align:middle;'>
    <?php
        if (in_array(getUser()->username, array_keys($nim_user))) {
        echo absen_label($nim_user[getUser()->username]->status_absen);
    } else {
        echo absen_label('');
    }
?>

</td>


                  </tr>
                <?php
              }
            }


       
        if ($last_page) {
         ?>

             </table>
                 <?php

          $nama_dosen = $db->fetch_single_row("view_dosen","nip",$_SESSION['username']);

           $nama_pengesah_3 = $nama_dosen->dosen;
           $nip_pengesah_3 = $nama_dosen->nip;
           $kota_3 = getPengaturan('kota');
           $tgl_3 = tgl_indo(date('Y-m-d'));
           $kategori_pejabat_3 = 'Dosen Ybs';
              ?>
              <br>
<!--           <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_pengesahan_dokumen">
              <tr>
                <td width="32%" style="text-align:center"><span class="preview_kota_1"><?=$kota_1;?></span><span class="preview_tgl_1"><?=$tgl_1;?></span><span class="preview_tipe_pengesah_1"><?=$tipe_pengesah_1;?></span>
                <span class="preview_kat_jabatan_1"><?=$kategori_pejabat_1;?></span></td>
                <td width="31%" style="text-align:center;"><span class="preview_kota_2"><?=$kota_2;?></span><span class="preview_tgl_2"><?=$tgl_2;?></span><span class="preview_tipe_pengesah_2"><?=$tipe_pengesah_2;?></span>
                <span class="preview_kat_jabatan_2"><?=$kategori_pejabat_2;?></span>
                </td>
                <td width="37%" style="text-align:center"><span class="preview_kota_3"><?=$kota_3;?>, <?=$tgl_3;?></span><span class="preview_tipe_pengesah_3"><?=$tipe_pengesah_3;?></span><br><span class="preview_kat_jabatan_3"><?=$kategori_pejabat_3;?></span>
                </td>
              </tr>
              <tr>  
                <td align="center" height="60">&nbsp;</td>
                <td align="center" height="50"></td>
                <td align="center" height="50">
               
     </td>
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
            </table> -->

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
