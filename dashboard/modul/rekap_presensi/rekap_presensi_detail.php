<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Rekap Presensi</h1>
  <ol class="breadcrumb">
    <li><a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= base_index(); ?>rekap-presensi">Rekap Presensi</a></li>
    <li class="active">Detail Rekap Presensi</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title">Detail Kelas Kuliah</h3>
        </div>

        <div class="box-body">
          <a href="<?= base_index_new(); ?>rekap-presensi" class="btn btn-primary" id="btn-simpan"><i
              class="fa fa-backward"></i> Kembali</a>
          <p>
            <?php
            $kelas_id = uri_segment(3);
            $kelas_data = $db2->fetchCustomSingle("SELECT kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where disetujui='1' and id_kelas=view_nama_kelas.kelas_id) as approved_krs
#(select count(id_krs_detail) from krs_detail where disetujui='0' and id_kelas=view_nama_kelas.kelas_id) as pending_krs 
from view_nama_kelas where kelas_id=?", array('kelas_id' => $kelas_id));

            ?>
            <link rel="stylesheet" type="text/css"
              href="<?= base_admin(); ?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
            <style type="text/css">
              .clockpicker-span-hours,
              .clockpicker-span-minutes {
                font-size: 24px;
              }

              .nav-tabs-custom {
                border: 1px solid #3c8cbc;
              }

              .modal.left .modal-dialog,
              .modal.right .modal-dialog {
                top: 0;
                bottom: 0;
                position: fixed;
                overflow-y: scroll;
                overflow-x: hidden;
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

              .nav-tabs-custom>.nav-tabs {
                border-bottom-color: #3c8dbc;
              }

              .nav-tabs-custom>.nav-tabs>li.active>a {
                border-left-color: #3c8dbc;
                border-right-color: #3c8dbc;
              }

              .label {
                font-size: 100%;
                margin-left: 5px;
              }

              .nav-tabs-custom>.nav-tabs>li {
                margin-bottom: -1px;
                margin-right: 0px;
              }
            </style>


          <table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
            <tbody>
              <tr>
                <td class="info2" width="20%"><strong>Program Studi</strong></td>
                <td width="30%"><?= $kelas_data->nama_jurusan; ?></td>
                <td class="info2" width="20%"><strong>Periode</strong></td>
                <td><?= getPeriode($kelas_data->sem_id); ?></td>
              </tr>
              <tr>
                <td class="info2"><strong>Matakuliah</strong></td>
                <td><?= $kelas_data->kode_mk; ?> - <?= $kelas_data->nama_mk; ?> (<?= $kelas_data->total_sks; ?> sks) </td>
                <td class="info2"><strong>Kelas</strong></td>
                <td><?= $kelas_data->kls_nama; ?></td>
              </tr>
            </tbody>
          </table>



          <div class="row">
            <div class="col-xs-12">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="myTab">
                  <li class="active">
                    <a href="#pengajar" data-toggle="tab"><i class="fa fa-eye"></i> Detail Kelas Perkuliahan</a>
                  </li>


                  <li>
                    <a href="#peserta" data-toggle="tab"><i class="fa fa-users"></i> Peserta Kelas <span
                        class="approved-krs-count label label-primary pull-right"><?= $kelas_data->approved_krs; ?></span></a>
                  </li>
                  <li>
                    <a href="#presensi_keluar" data-toggle="tab"><i class="fa fa-sign-out"></i> Presensi Keluar
                      Dosen</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="pengajar">
                    <?php
                    include "pengajar.php";
                    ?>
                  </div>

                  <div class="tab-pane" id="peserta">
                    <?php
                    include "tab_approved_krs.php";
                    ?>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="presensi_keluar">
                    <?php
                    $row_datas_keluar = $db->query(
                      "SELECT t.*, vj.hari FROM tb_data_kelas_pertemuan t
                   INNER JOIN view_jadwal vj ON t.jadwal_id = vj.jadwal_id
                   WHERE t.kelas_id=? AND t.kehadiran_dosen_keluar IS NOT NULL AND t.kehadiran_dosen_keluar != ''",
                      ['kelas_id' => $kelas_id]
                    );

                    // ambil mapping nip -> nama dosen
                    $dosen_datas2 = $db2->query(
                      "SELECT view_jadwal_dosen_kelas.id_dosen as nip, view_nama_gelar_dosen.nama_gelar
                   FROM view_jadwal_dosen_kelas
                   INNER JOIN view_nama_gelar_dosen ON view_jadwal_dosen_kelas.id_dosen = view_nama_gelar_dosen.nip
                   WHERE id_kelas=? ORDER BY dosen_ke ASC",
                      ['id_kelas' => $kelas_id]
                    );
                    $data_dosen_keluar = [];
                    foreach ($dosen_datas2 as $dd) {
                      $data_dosen_keluar[$dd->nip] = $dd->nama_gelar;
                    }

                    $absensi_keluar = [];
                    foreach ($row_datas_keluar as $row_k) {
                      $tanggal = $row_k->tanggal_pertemuan;
                      $kehadiran_keluar = json_decode($row_k->kehadiran_dosen_keluar, true);
                      if (!empty($kehadiran_keluar)) {
                        foreach ($kehadiran_keluar as $hadir_k) {
                          $jam_absen = $hadir_k['tanggal_absen'];
                          $jam_str = substr($jam_absen, -8);
                          $hari_keluar = getHariFromDate(substr($jam_absen, 0, 10));
                          $sesuai = (
                            substr($jam_absen, 0, 10) == $tanggal &&
                            $jam_str >= $row_k->jam_mulai &&
                            $jam_str <= $row_k->jam_selesai &&
                            strtolower($hari_keluar) == strtolower($row_k->hari)
                          );
                          $status_keluar = isset($hadir_k['sesuai_jadwal'])
                            ? ($hadir_k['sesuai_jadwal'] == 'Y' ? 'hadir_tepat' : 'hadir_diluar')
                            : ($sesuai ? 'hadir_tepat' : 'hadir_diluar');

                          $absensi_keluar[] = [
                            'pertemuan' => $row_k->pertemuan,
                            'tanggal_pertemuan' => $tanggal,
                            'jam_mulai' => $row_k->jam_mulai,
                            'jam_selesai' => $row_k->jam_selesai,
                            'tanggal_absen' => substr($jam_absen, 0, 10),
                            'jam_absen' => $jam_absen,
                            'pengajar' => $data_dosen_keluar[$hadir_k['nip']] ?? ($hadir_k['nip'] ?? '-'),
                            'status' => $status_keluar,
                            'foto_absen' => $hadir_k['foto_absen'] ?? '',
                          ];
                        }
                      }
                    }
                    ?>

                    <div class="alert alert-info alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-info"></i> Informasi</h4>
                      Baris Berwarna Kuning adalah pertemuan minggu ini
                    </div>

                    <table class="table table-bordered table-striped" width="100%">
                      <thead>
                        <tr>
                          <th>Pertemuan</th>
                          <th>Jadwal Kelas</th>
                          <th>Jam Keluar</th>
                          <th>Pengajar</th>
                          <th>Status</th>
                          <th>Foto</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (empty($absensi_keluar)): ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data presensi keluar</td>
                          </tr>
                        <?php else:
                          foreach ($absensi_keluar as $rk):
                            $rowClass = (date('W', strtotime($rk['tanggal_pertemuan'])) == date('W') &&
                              date('o', strtotime($rk['tanggal_pertemuan'])) == date('o'))
                              ? "style='background:#fcf8e3'" : "";
                            ?>
                            <tr <?= $rowClass ?>>
                              <td><?= $rk['pertemuan'] ?></td>
                              <td>
                                <?= getHariFromDate($rk['tanggal_pertemuan']) . ', ' . tgl_indo($rk['tanggal_pertemuan']) ?>
                                <br><span
                                  class="text-muted"><?= substr($rk['jam_mulai'], 0, 5) . ' - ' . substr($rk['jam_selesai'], 0, 5) ?></span>
                              </td>
                              <td>
                                <?= getHariFromDate($rk['tanggal_absen']) . ', ' . tgl_indo($rk['tanggal_absen']) ?>
                                <br><span
                                  class="label label-default"><?= date('H:i:s', strtotime($rk['jam_absen'])) ?></span>
                              </td>
                              <td><?= $rk['pengajar'] ?></td>
                              <td>
                                <?php if ($rk['status'] == 'hadir_tepat'): ?>
                                  <span class="label label-success">Keluar Sesuai Jadwal</span>
                                <?php elseif ($rk['status'] == 'hadir_diluar'): ?>
                                  <span class="label label-warning">Keluar Tidak Sesuai</span>
                                <?php else: ?>
                                  <span class="label label-danger">Belum Absen Keluar</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                <?php if (!empty($rk['foto_absen'])): ?>
                                  <img src="<?= $rk['foto_absen'] ?>" alt="Foto Keluar" class="img-thumbnail"
                                    style="cursor:pointer; max-width:80px" data-toggle="modal" data-target="#modal_photo"
                                    onclick="$('#isi_photo').html('<img src=&quot;<?= $rk['foto_absen'] ?>&quot; style=&quot;max-width:100%&quot;>')">
                                <?php else: ?>
                                  <span class="text-muted">Belum ada foto</span>
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach; endif; ?>
                      </tbody>
                    </table>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
            </div>
          </div>

          <div class="modal" id="modal_input_absen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
              <div class="modal-content" style="height: auto;">
                <div class="modal-header"> <button type="button" class="close close-absensi" aria-label="Close"><span
                      aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
                  <h4 class="modal-title-absen">Isi Presensi Mahasiswa</h4>
                </div>
                <div class="modal-body" id="input_mahasiswa_absen" style="overflow-y: auto;"> </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>
          <div class="modal" id="modal_photo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span aria-hidden="true"><i
                        class="glyphicon glyphicon-remove"></i></span></button>
                  <h4 class="modal-title">Thumbnail</h4>
                </div>
                <div class="modal-body" id="isi_photo" style="text-align: center;"> </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>


          <div class="modal" id="modal_pertemuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close"><span aria-hidden="true"><i
                        class="glyphicon glyphicon-remove"></i></span></button>
                  <h4 class="modal-title"><?php echo $lang["add_button"]; ?> Pertemuan</h4>
                </div>
                <div class="modal-body" id="isi_pertemuan"> </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div>
          <style type="text/css">
            .modal-abs,
            .modal-paper {
              width: 98%;
              padding: 0;
            }

            .modal-content-abs,
            .modal-content-paper {
              height: 99%;
            }
          </style>


          <script>

            $(document).ready(() => {
              let url = location.href.replace(/\/$/, "");

              if (location.hash) {
                const hash = url.split("#");
                $('#myTab a[href="#' + hash[1] + '"]').tab("show");
                url = location.href.replace(/\/#/, "#");
                history.replaceState(null, null, url);
                setTimeout(() => {
                  $(window).scrollTop(0);
                }, 400);
              }

              $('a[data-toggle="tab"]').on("click", function () {
                let newUrl;
                const hash = $(this).attr("href");
                if (hash == "#pengajar") {
                  newUrl = url.split("#")[0];
                } else {
                  newUrl = url.split("#")[0] + hash;
                }
                newUrl += "/";
                history.replaceState(null, null, newUrl);
              });
            });

            $(document).ready(function () {
              // Do this before you initialize any of your modals
              $.fn.modal.Constructor.prototype.enforceFocus = function () { };

              $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                  .columns.adjust()
                  .responsive.recalc();
              });

            });
          </script>

        </div>
      </div>
    </div>
  </div>

</section><!-- /.content -->