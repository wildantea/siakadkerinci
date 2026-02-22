<?php
include "../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,jurusan as nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.*,tanggal_ujian,jam_mulai,jam_selesai,tempat,id_ruang,tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian,view_simple_mhs_data.jur_kode,view_simple_mhs_data.mulai_smt,
  (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on view_nama_gelar_dosen.nip=tb_data_pendaftaran_penguji.nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as penguji,
    (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_pembimbing on view_nama_gelar_dosen.nip=tb_data_pendaftaran_pembimbing.nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc) as pembimbing
from view_simple_mhs_data 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
inner join tb_data_pendaftaran_jadwal_ujian using(id_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_pendaftaran']));


?>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }

.modal.left .modal-dialog,
  .modal.right .modal-dialog {
    top: 0;
    bottom:0;
    position:fixed;
    overflow-y:scroll;
    overflow-x:hidden;
    margin: auto;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }
/*Right*/
  .modal.right.fade .modal-dialog {
    right: -320px;
    -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
       -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
         -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
  }
  
  .modal.right.fade.in .modal-dialog {
    right: 0;
  }
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2" width="20%"><strong>NIM</strong></td>
          <td width="30%"><?=$mhs_data->nim;?></td>
          <td class="info2" width="20%"><strong>Angkatan</strong></td>
          <td width="30%"><?=$mhs_data->angkatan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td><?=$mhs_data->nama;?> </td>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td><?=$mhs_data->nama_jurusan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Jenis Sidang</strong></td>
          <td colspan="3"><?=$mhs_data->nama_jenis_pendaftaran;?> </td>
        </tr>
        <?php
        if ($mhs_data->ada_judul=='Y') {
          ?>
          <tr>
          <td class="info2"><strong>Judul</strong></td>
          <td colspan="3"><?=$mhs_data->judul;?> </td>
        </tr>
          <?php
        }
        ?>
        <?php
  if ($mhs_data->ada_jadwal=='Y') {
      ?>
          <tr>
          <td class="info2"><strong>Tanggal Ujian</strong></td>
          <td colspan="3"><?=tgl_indo($mhs_data->tanggal_ujian);?> </td>
          </tr>
          <tr>
           <td class="info2"><strong>Waktu</strong></td>
          <td colspan="3"><?=$mhs_data->jam_mulai.' s/d '.$mhs_data->jam_selesai;?> </td>
          </tr>
          <tr>
          <td class="info2"><strong>Tempat</strong></td>
          <?php
          if ($mhs_data->tempat=='Daring') {
            $tempat = "Daring";
          } else {
            $tempat = getRuangName($mhs_data->id_ruang);
          }
          ?>
          <td colspan="3"><?=$tempat;?> </td>
          </tr>
      <?php

  }

  if ($mhs_data->ada_pembimbing=='Y') {
        $nama_dosen_pembimbing = array_map('trim', explode('#', $mhs_data->pembimbing));
        foreach ($nama_dosen_pembimbing as $nomor => $dosen) {
          ?>
           <tr>
          <td class="info2"><strong>Pembimbing <?=($nomor+1);?></strong></td>
          <td colspan="3"><?=$dosen;?> </td>
          </tr>
          <?php
        }
        
  }

?>
      </tbody>
</table>

<?php

       if ($mhs_data->ada_penguji=='Y') {
        //check nilai penguji
        $data_nilai = $db->query("select * from tb_data_pendaftaran_penguji where id_jadwal_ujian='$mhs_data->id_jadwal_ujian' order by penguji_ke asc");
        foreach ($data_nilai as $nilai) {
          $nilai_ujian[$nilai->penguji_ke] = $nilai->nilai_ujian;
        }
        ?>

        <table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
          <?php
        $nama_dosen = array_map('trim', explode('#', $mhs_data->penguji));
        ?>
        <tr>
          <th style="width:10%">Penguji</th>
          <th>Nama Penguji</th>
          <th colspan="3">Nilai Ujian</th>
        </tr>
        <?php
        $nilai_akhir = 0;
        foreach ($nama_dosen as $index => $dosen_uji) {
          $dosen_penguji[] = ($index+1).'. '.$dosen_uji;
          if ($nilai_ujian[$index+1]=="") {
            $nilai_dosen = 0;
          } else {
            $nilai_dosen = $nilai_ujian[$index+1];
          }

          if ($index+1==1) {
            $jabatan = 'Ketua';
          } else {
            $jabatan = 'Anggota';
          }
          ?>
           <tr>
            <td class="dt-center"><?=$jabatan;?></td>
          <td class="info2"><strong><?=$dosen_uji;?> </strong></td>
          <td colspan="3"><?=$nilai_dosen;?></td>
          </tr>
          <?php
          $nilai_akhir+=$nilai_dosen;
        }

$nilai_akhir = round($nilai_akhir/count($nama_dosen),2);
$berlaku_angkatan=$mhs_data->mulai_smt;
$where_berlaku = "";
if ($berlaku_angkatan>=20202) {
      $where_berlaku = "and berlaku_angkatan='".$berlaku_angkatan."'"; 
} else{
      $where_berlaku = "and berlaku_angkatan is null"; 
}
        $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku",array('kode_jurusan' => $mhs_data->jur_kode));
       if ($skala_nilai->rowCount()>0) {
            foreach ($skala_nilai as $skala) {
                  $min = $skala->bobot_nilai_min;
                  $max = $skala->bobot_nilai_maks;
                  if ( $nilai_akhir >=$min && $nilai_akhir <=$max) {
                        $huruf =  $nilai_akhir." (".$skala->nilai_huruf.")";
                  }
              
            }
       } else {
      $huruf =  "Skala Nilai Belum dibuat";
       }
        ?>
                <tr>
          <th colspan="2" style="text-align:right">Nilai Akhir</th>
          <th><?=$huruf;?></th>
        </tr>
               </tbody>
</table>
        <?php
        }
          ?>
