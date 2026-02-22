<?php
session_start();
include "../../../inc/config.php";

$header_attributes = $db2->fetchCustomSingle("SELECT vk.kode_jur,vk.kls_nama,vk.sks,vk.kode_mk,vk.nama_mk,vjk.hari as nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.jurusan as nama_jurusan,view_semester.tahun_akademik,vk.kelas_id,
(select group_concat(nama_gelar separator '#')
 from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=vjk.kelas_id and jadwal_id=vjk.jadwal_id order by dosen_ke asc) as nama_dosen
  from view_nama_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
LEFT JOIN view_jadwal vjk using(kelas_id)
 WHERE vk.kelas_id=? and vjk.jadwal_id=? ORDER BY nama_dosen ASC", array('kelas_id' => $_POST['kelas_id'], 'jadwal_id' => $_POST['id_jadwal']));

dump($_POST);
$counter = 1;
$dosen_datas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc", array("id_kelas" => $_POST['kelas_id']));
if ($dosen_datas->rowCount() > 1) {
  foreach ($dosen_datas as $dt) {
    $dosen_data[] = $counter . '. ' . $dt->nama_gelar;
    $counter++;
  }
} else {
  foreach ($dosen_datas as $dt) {
    $dosen_data[] = $dt->nama_gelar;
  }
}
?>
<html>

<head>
  <title>Cetak Presensi Kelas</title>
  <link rel="stylesheet" type="text/css" href="<?= base_admin(); ?>assets/dist/css/cetak/paper.css">
  <link rel="stylesheet" type="text/css" href="<?= base_admin(); ?>assets/dist/css/cetak/table.css">
  <link rel="shortcut icon" href="<?= getPengaturan('favicon'); ?>">
  <style type="text/css">
    @page {
      size: A4 portrait
    }
  </style>
</head>

<!-- onload="window.print()" -->

<body class="A4 portrait">
  <?php


  $count_peserta = $db2->fetchCustomSingle("SELECT COUNT(tdk.id_krs_detail) as jml  from krs_detail tdk WHERE disetujui=1 AND id_kelas=?", array('kelas_id' => $_POST['kelas_id']));

  $per_page = 25;

  $sisa = $count_peserta->jml % $per_page;

  if ($sisa == 0) {
    $sisa = $per_page;
  }

  $page_count = ceil($count_peserta->jml / $per_page);

  $last_page_count = $page_count;

  $additional_page = false;
  $include_signature = 19;

  if ($sisa > $include_signature) {
    $page_count = $page_count + 1;
    $additional_page = true;
  }


  $ada_header = true;
  $last_page = false;

  $no = 1;

  for ($i = 0; $i < $page_count; $i++) {
    $offset = $i * $per_page;

    if ($i == $last_page_count - 1 && $additional_page == true) {
      $limit = $sisa - 1;
      $last_offset = $offset + $limit;
    } elseif ($i == $last_page_count) {
      $offset = $last_offset;
      $limit = 1;
      $last_page = true;
    } elseif ($i == $last_page_count - 1 && $additional_page == false) {
      $limit = $sisa;
      $last_page = true;
    } else {
      $limit = $per_page;
    }

    $row_mahasiswa = $db2->query("SELECT view_simple_mhs.nim,view_simple_mhs.nama,tdk.id_kelas as kelas_id from krs_detail tdk
INNER JOIN view_simple_mhs USING(nim)
WHERE tdk.disetujui=1 AND id_kelas=? order by nim asc limit $offset,$limit", array('id_kelas' => $header_attributes->kelas_id));




    ?>
    <div class="page-break">
      <div class="sheet padding-10mm">


        <?php
        if ($ada_header) {
          ?>
          <div style="text-align: right;margin-top: -10px;"><?= $kode_dokumen; ?></div>
          <table>
            <tbody>
              <tr>
                <td style="vertical-align: top;">
                  <img src="<?= base_url() . 'upload/logo/' . getPengaturan('logo'); ?>" width="100" height="100">
                </td>
                <td>
                  <h1><?= getPengaturan('header') ?></h1>

                  <?= getPengaturan('alamat') ?>
              </tr>
            </tbody>
          </table>
          <hr>

          <h3 align="center" style="margin-bottom: 7px;">DAFTAR PRESENSI KULIAH<br><?= $header_attributes->tahun_akademik ?>
          </h3>

          <table class="tabel-info" width="100%">
            <tr>
              <td nowrap="nowrap" height="17">Mata Kuliah</td>
              <td nowrap="nowrap">:</td>
              <td><?= $header_attributes->kode_mk . "/" . $header_attributes->nama_mk ?></td>
              <td>Program Studi</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap"><?= $header_attributes->nama_jurusan ?></td>

            </tr>
            <tr>
              <td nowrap="nowrap" height="17">Kelas</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap"><?= $header_attributes->kls_nama ?></td>

              <td nowrap="nowrap" valign="top">Hari / Waktu</td>
              <td nowrap="nowrap" valign="top">:</td>
              <td nowrap="nowrap" valign="top"><?= $header_attributes->nama_hari ?> /
                <?= $header_attributes->jam_mulai ?>-<?= $header_attributes->jam_selesai ?>
              </td>


            </tr>
            <tr>

              <td nowrap="nowrap" height="17">Bobot SKS</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap"><?= $header_attributes->sks ?>&nbsp;sks</td>
              <td nowrap="nowrap">Ruang</td>
              <td nowrap="nowrap">:</td>
              <td nowrap="nowrap"><?= $header_attributes->nm_ruang ?></td>
            </tr>
            <tr>
              <td nowrap="nowrap" valign="top">Dosen Pengampu</td>
              <td nowrap="nowrap" valign="top">:</td>
              <?php
              $dosen = "";
              for ($j = 0; $j < count($dosen_data); $j++) {
                $dosen .= $dosen_data[$j] . "<br>";
              }

              ?>

              <td nowrap="nowrap" valign="top"><?= $dosen; ?></td>
              <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              <td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
          </table>
          <br />
          <table width="100%" class="tabel-common">
            <?php
            //$jumlah_pertemuan = $header_attributes->jml_pertemuan;
            $jumlah_pertemuan = $db2->fetchCustomSingle("SELECT count(tb_data_kelas_pertemuan.id_pertemuan) AS jml from tb_data_kelas_pertemuan WHERE kelas_id='" . $_POST['kelas_id'] . "'");
            // if ($row_mahasiswa->rowCount()>0) {
            ?>
            <tr>
              <th rowspan="2">No.</th>
              <th rowspan="2" width="15%">NIM</th>
              <th rowspan="2">Nama</th>
              <th colspan="6">Pertemuan</th>
            </tr>
            <tr>
              <?php
              $array_select = array(
                'Hadir',
                'Ijin',
                'Sakit',
                'Alpa'
              );

              echo '<th >Total</th>';
              foreach ($array_select as $stat) {
                echo '<th>' . $stat . '</th>';
              }
              echo '<th>Persen</th>';

              ?>
            </tr>
            <?php
        }
        ?>

          <?php

          foreach ($row_mahasiswa as $mhs) {
            // check jumlah pertemuan
            $meets = $db2->query("SELECT tb_data_kelas_pertemuan.*, isi_absensi FROM tb_data_kelas_pertemuan LEFT JOIN tb_data_kelas_absensi USING (id_pertemuan) WHERE kelas_id='" . $mhs->kelas_id . "'");

            echo "<tr></tr>
          <tr>
            <td style='text-align:center; vertical-align:middle;' height='30'>$no</td>
            <td style='vertical-align:middle;' width='150'>$mhs->nim</td>
            <td style='vertical-align:middle;' width='250'>$mhs->nama</td>";

            $pert = 0;
            $jml_hadir = 0;
            $jml_ijin = 0;
            $jml_sakit = 0;
            $jml_alfa = 0;

            foreach ($meets as $meet) {
              $is_hadir = 0;

              if ($meet->isi_absensi != "") {
                $data_absen = json_decode($meet->isi_absensi);

                foreach ($data_absen as $nim) {
                  if ($mhs->nim == $nim->nim) {
                    if ($nim->status_absen == 'Hadir') {
                      $is_hadir = 1;
                      $jml_hadir++;
                    } elseif ($nim->status_absen == 'Ijin') {
                      $jml_ijin++;
                    } elseif ($nim->status_absen == 'Sakit') {
                      $jml_sakit++;
                    } elseif ($nim->status_absen == 'Alpa') {
                      $jml_alfa++;
                    }
                  }
                }
              }

              $pert++; // Increment the pertemuan counter
            }
            echo "<td align='center' style='vertical-align:middle'>" . $meets->rowCount() . "</td>";
            echo '<td  align="center" style="vertical-align:middle">' . $jml_hadir . '</td>';
            echo '<td  align="center" style="vertical-align:middle">' . $jml_ijin . '</td>';
            echo '<td  align="center" style="vertical-align:middle">' . $jml_sakit . '</td>';
            echo '<td  align="center" style="vertical-align:middle">' . $jml_alfa . '</td>';

            if ($pert > 0) {
              $persen = round(($jml_hadir / $pert) * 100, 0);
            } else {
              $persen = 0;
            }



            echo "<td align='center' style='vertical-align:middle'><b>$persen%</b></td></tr>";

            $no++;
          }




          if ($last_page) {
            ?>

          </table>
          <?php

          $nama_dosen = $db->fetch_single_row("view_dosen", "nip", $_SESSION['username']);

          $nama_pengesah_3 = $nama_dosen->dosen;
          $nip_pengesah_3 = $nama_dosen->nip;
          $kota_3 = getPengaturan('kota');
          $tgl_3 = tgl_indo(date('Y-m-d'));
          $kategori_pejabat_3 = 'Dosen Ybs';
          ?>
          <br>
          <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_pengesahan_dokumen">
            <tr>
              <td width="32%" style="text-align:center"><span class="preview_kota_1"><?= $kota_1; ?></span><span
                  class="preview_tgl_1"><?= $tgl_1; ?></span><span
                  class="preview_tipe_pengesah_1"><?= $tipe_pengesah_1; ?></span>
                <span class="preview_kat_jabatan_1"><?= $kategori_pejabat_1; ?></span>
              </td>
              <td width="31%" style="text-align:center;"><span class="preview_kota_2"><?= $kota_2; ?></span><span
                  class="preview_tgl_2"><?= $tgl_2; ?></span><span
                  class="preview_tipe_pengesah_2"><?= $tipe_pengesah_2; ?></span>
                <span class="preview_kat_jabatan_2"><?= $kategori_pejabat_2; ?></span>
              </td>
              <td width="37%" style="text-align:center"><span class="preview_kota_3"><?= $kota_3; ?>,
                  <?= $tgl_3; ?></span><span class="preview_tipe_pengesah_3"><?= $tipe_pengesah_3; ?></span><br><span
                  class="preview_kat_jabatan_3"><?= $kategori_pejabat_3; ?></span>
              </td>
            </tr>
            <tr>
              <td align="center" height="60">&nbsp;</td>
              <td align="center" height="50"></td>
              <td align="center" height="50"></td>
            </tr>
            <tr>
              <td align="justify" style="text-align:center">
                <span style="text-decoration: underline;"
                  class="preview_pengesah_1"><?= $nama_pengesah_1; ?></span><?= ($nama_pengesah_1 != '') ? '<br />------------------------------<br />' : ''; ?>
                <span class="preview_nip_1"><?= $nip_pengesah_1; ?></span>
              </td>
              <td nowrap="nowrap" style="text-align:center">
                <span
                  class="preview_pengesah_2"><?= $nama_pengesah_2; ?></span><?= ($nama_pengesah_2 != '') ? '<br />------------------------------<br />' : ''; ?>
                <span class="preview_nip_2"><?= $nip_pengesah_2; ?></span>
              </td>
              <td style="text-align:center">
                <span
                  class="preview_pengesah_3"><?= $nama_pengesah_3; ?></span><?= ($nama_pengesah_3 != '') ? '<br />------------------------------<br />' : ''; ?>
                <span class="preview_nip_3"><?= $nip_pengesah_3; ?></span>
              </td>
            </tr>
          </table>


          <?php
            //end if last page
          }
          //end page-break and sheet
          ?>
        </table>
        <p></p>
        <div style="text-align: right">Halaman <?= $i + 1; ?></div>
      </div>
    </div>
    <?php
    //end loop
  }




  ?>

</body>

</html>