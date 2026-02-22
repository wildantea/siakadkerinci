<style type="text/css">
  .modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
  }

  .peserta-kelas {
    cursor: pointer;
  }

  .modal-abs {
    width: 95%;
    padding: 0;
  }

  .modal-content-abs {
    width: 95%;
    margin: auto auto;
  }

  .modal-body {
    max-height: 90vh;
    ;
    /* Set a maximum height */
    overflow-y: auto;
    !important
  }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Kelas & Jadwal Perkuliahan
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= base_index(); ?>kelas-jadwal">Kelas & Jadwal Perkuliahan</a></li>
    <li class="active">Kelas & Jadwal Perkuliahan List</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <?php
          foreach ($db->fetch_all("sys_menu") as $isi) {
            if (uri_segment(1) == $isi->url) {
              if ($role_act["insert_act"] == "Y") {

                //dump($_SESSION);
// Check current schedule
                $is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => get_sem_aktif()));

                $current_data = strtotime(date("Y-m-d H:i:s"));

                $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
                $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

                if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
                  $is_edit = 1;
                } else {
                  $is_edit = 0;
                }

                //also check in semeste prodi
// Check current schedule
          
                if ($is_edit == 0) {
                  $akses = get_akses_prodi();
                  $is_jadwal_edit = $db->fetch_custom_single("select * from semester $akses and id_semester=?", array('id_semester' => get_sem_aktif()));

                  $current_data = strtotime(date("Y-m-d H:i:s"));

                  $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_kelas . " 00:00:00");
                  $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_kelas . " 23:59:59");

                  if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
                    $is_edit = 1;
                  } else {
                    $is_edit = 0;
                  }
                }


                if ($is_edit == 1 or $_SESSION['level'] == '1' or $_SESSION['group_level'] == 'tim_kecil') {
                  ?>
                  <a href="<?= base_index(); ?>kelas-jadwal/tambah" class="btn btn-primary "><i class="fa fa-plus"></i>
                    <?php echo $lang["add_button"]; ?></a>

                  <!-- <a id="gen-jadwal" class="btn btn-primary "><i class="fa fa-gear"> Generate Jadwal</i></a>
                                       <a id="reset-jadwal" class="btn btn-danger "><i class="fa fa-close"> Reset Jadwal</i></a> -->
                  <?php
                }
              }
              if ($role_act["import_act"] == "Y") {
                ?>
                <div class="btn-group">
                  <button type="button" class="btn btn-success">Import Data</button>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="import_kelas"><a href="#"><i class="fa fa-cloud-upload"></i> Import Kelas</a></li>
                    <li class="import_dosen"><a href="#"><i class="fa fa-cloud-upload"></i> Import Dosen Ajar</a></li>
                    <li class="import_jadwal"><a href="#"><i class="fa fa-cloud-upload"></i> Import Jadwal Kuliah</a></li>
                  </ul>
                </div>
                <?php
              }
            }
          }
          ?>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" id="filter_kelas_form" method="post"
                action="<?= base_admin(); ?>modul/kelas_jadwal/cetak.php" target="_blank">


                <?php
                if (hasFakultas()) {
                  ?>
                  <div class="form-group">
                    <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                    <div class="col-lg-5">
                      <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..."
                        class="form-control chzn-select" tabindex="2" required="">
                        <?php
                        loopingFakultas('filter_kelas');
                        ?>
                      </select>
                    </div>
                  </div><!-- /.form-group -->
                  <?php
                }
                ?>

                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Program Studi
                    <?php
                    //print_r($_SESSION);
                    ?>
                  </label>
                  <div class="col-lg-5">
                    <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..."
                      class="form-control chzn-select" tabindex="2" required="">
                      <?php
                      if ($_SESSION['group_level'] == 'dosen') {
                        $q = $db->query("select k.kelas_id,k.kls_nama, m.nama_mk,m.kode_mk,ku.kode_jur,
                                   j.nama_jur from kelas k join dosen_kelas dk on k.kelas_id=dk.id_kelas inner
                                    join dosen ds on ds.nip=dk.id_dosen inner join matkul m on m.id_matkul=k.id_matkul
                                    inner join kurikulum ku on ku.kur_id=m.kur_id inner join jurusan j on
                                    j.kode_jur=ku.kode_jur where dk.id_dosen='" . $_SESSION['username'] . "' group by ku.kode_jur");
                        foreach ($q as $k) {
                          echo "<option value='$k->kode_jur'>$k->nama_jur</option>";
                        }
                      } else {
                        looping_prodi();
                      }

                      ?>
                    </select>

                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Semester</label>
                  <div class="col-lg-5">
                    <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..."
                      class="form-control chzn-select" tabindex="2" required="">
                      <option value="">Pilih Semester</option>
                      <?php
                      looping_semester();
                      ?>
                    </select>

                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Matakuliah</label>
                  <div class="col-lg-5">
                    <select id="matkul_filter" name="matkul_filter" data-placeholder="Pilih Semester ..."
                      class="form-control chzn-select" tabindex="2">
                      <?php
                      looping_matkul_kelas();
                      ?>
                    </select>
                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Hari</label>
                  <div class="col-lg-3">
                    <select id="hari_filter" name="hari_filter" data-placeholder="Pilih Hari ..." class="form-control"
                      tabindex="2">
                      <option value="all">Semua</option>
                      <?php
                      $array_hari = array(
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                        'sabtu' => 'Sabtu',
                        'minggu' => 'Minggu'
                      );
                      foreach ($array_hari as $h => $hari) {
                        echo "<option value='$h'>$hari</option>";
                      }
                      ?>
                    </select>


                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Jenis Kelas</label>
                  <div class="col-lg-3">
                    <select id="jenis_kelas" name="jenis_kelas" data-placeholder="Pilih Jenis kelas ..."
                      class="form-control" tabindex="2">
                      <option value="all">Semua</option>
                      <?php
                      foreach ($db->query("select * from jenis_kelas") as $jenis) {
                        echo "<option value='$jenis->id'>$jenis->nama_jenis_kelas</option>";
                      }
                      ?>
                    </select>


                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                  <div class="col-lg-10">
                    <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                    <span id="exsport-jadwal" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Exsport
                      Jadwal</span>
                    <div class="btn-group">
                      <?php

                      ?>
                      <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Cetak Data</button>
                      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li class="cetak-data"><button type="submit" name="jenis_print" value="kelas"
                            class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi Kelas</li>
                        <li class="cetak-data"><button type="submit" name="jenis_print" value="uts"
                            class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi UTS</li>
                        <li class="cetak-data"><button type="submit" name="jenis_print" value="uas"
                            class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi UAS</button>
                        <li class="cetak-data"><button type="submit" name="jenis_print" value="jadwal"
                            class="btn cetak-data"><i class="fa fa-print"></i> Cetak Jadwal</button>
                        <li class="cetak-data"><button type="submit" name="jenis_print" value="bap"
                            class="btn cetak-data"><i class="fa fa-print"></i> Cetak BAP</button>
                        </li>
                      </ul>

                    </div>
                  </div><!-- /.form-group -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <div class="alert alert-warning fade in error_data_delete" style="display:none">
            <button type="button" class="close hide_alert_notif">&times;</button>
            <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
          </div>
          <div class="row" id="aksi_top_krs" style="display: none">
            <div class="col-sm-4" style="margin-bottom: 10px;">
              <div class="input-group input-group-sm">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
                </span>
                <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
                  <!--       <option value="1" data-aksi="aktifkan">Aktifkan Tagihan</option>
      <option value="0" data-aksi="aktifkan">Non-Aktifkan Tagihan</option> 
       <option value="tanggal" data-aksi="ubah_tanggal">Ubah Tanggal Massal</option>-->
                  <option value="delete" data-aksi="del_massal">Hapus Kelas</option>

                </select>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
                </span>
              </div>
            </div>
          </div>
          <table width="100%" id="dtb_kelas_jadwal"
            class="table table-bordered table-striped display responsive nowrap">
            <thead>
              <tr>
                <th rowspan="2" style="padding-right:7px;width: 3%"><label
                    class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox"
                      class="group-checkable bulk-check"> <span></span></label></th>
                <th rowspan="2">Matakuliah</th>
                <th rowspan="2">Kelas</th>
                <th colspan="2" class="dt-center">Jadwal</th>
                <th rowspan="2">Dosen</th>
                <th rowspan="2">Kuota</th>
                <th colspan="2" class="dt-center">Persetujuan KRS</th>
                <th rowspan="2">Program Studi</th>

                <th rowspan="2">Action</th>
              </tr>
              <tr>
                <th>Hari/Jam</th>
                <th>Ruang</th>
                <th>Sudah</th>
                <th>Belum</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>
  <?php
  $print = "<a data-id='+aData+' data-toggle=\"tooltip\" title=\"Cetak\" class=\"cetak\"><i class=\"fa fa-print\"></i> Cetak Data</a>";



  $del = "";
  $edit = "";
  foreach ($db->fetch_all("sys_menu") as $isi) {
    //jika url = url dari table menu
    if (uri_segment(1) == $isi->url) {
      //check edit permission
      if ($role_act["up_act"] == "Y") {
        $jadwal = "<a data-id='+aData+' data-toggle=\"tooltip\" title=\"Pengaturan Jadwal\" class=\"edit-jadwal\"><i class=\"fa fa-calendar\"></i> Pengaturan Jadwal</a>";
        if ($is_edit == 1 or $_SESSION['level'] == '1' or $_SESSION['group_level'] == 'tim_kecil') {
          $edit = "<a data-id='+aData+' href=" . base_index() . "kelas-jadwal/edit/'+aData+' data-toggle=\"tooltip\" title=\"Edit Kelas\"><i class=\"fa fa-pencil\"></i> Edit Kelas</a>";
        }


      } else {
        $jadwal = "";
        $edit = "";
        $print = "";
      }
      if ($role_act['del_act'] == 'Y') {
        if ($is_edit == 1 or $_SESSION['level'] == '1' or $_SESSION['group_level'] == 'tim_kecil') {
          $del = "<a data-id='+aData+'  data-uri=" . base_admin() . "modul/kelas_jadwal/kelas_jadwal_action.php" . ' data-variable="dataTable_jadwal" class="hapus_dtb_notif data_selected_id" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i> Hapus</a>';
        }

      } else {
        $del = "";
      }
    }
  }

  ?>
  <!-- generate jadwal -->
  <div class="modal" id="modal_generate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">×</span></button>
          <h4 class="modal-title">Silakan Pilih Prodi dan Semester yang akan di generate</h4>
        </div>
        <div class="modal-body" id="isi_modal_generate"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <!-- reset jadwal -->
  <div class="modal" id="modal_reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">×</span></button>
          <h4 class="modal-title">Silakan Pilih Prodi dan Semester yang akan di Reset</h4>
        </div>
        <div class="modal-body" id="isi_modal_reset"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">×</span></button>
          <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4>
        </div>
        <div class="modal-body" id="isi_modal_cetak"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <!-- modal jadwal -->
  <div class="modal fade" id="modal_kelas_jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modal-abs">
      <div class="modal-content modal-content-abs">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true"><span class="glyphicon glyphicon-remove"
                style="font-size: 24px;"></span></span></button>
          <h4 class="modal-title">Pengaturan Dosen Pengajar & Jadwal Perkuliahan</h4>
        </div>
        <div class="modal-body" id="isi_kelas_jadwal"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <div class="modal" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
          <h4 class="modal-title">Pilih Dosen Pengajar</h4>
        </div>
        <div class="modal-body" id="isi_dosen"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
  <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&nbsp;</span></button>
          <h4 class="modal-title title-import">Import Excel </h4>
        </div>
        <div class="modal-body" id="isi_import_data"> </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
</section><!-- /.content -->

<div class="modal" id="modal-confirm-action" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
        <h4 class="modal-title"><?php echo $lang['confirm']; ?></h4>
      </div>
      <div class="modal-body">
        <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span> Apakah Anda
            Yakin</span></p>
      </div>
      <div class="modal-footer"><button type="button" id="confirm-action" class="btn btn-primary">Ya Yakin</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal_peserta_kelas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
        <h4 class="modal-title">Peserta Kelas</h4>
      </div>
      <div class="modal-body" id="isi_peserta_kelas"> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript">




  $(".import_kelas").click(function () {
    $('.title-import').html('Import Kelas Kuliah');
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/import_kelas.php",
      type: "GET",
      success: function (data) {
        $("#isi_import_data").html(data);
      }
    });

    $('#modal_import_data').modal({ keyboard: false, backdrop: 'static', show: true });

  });

  $(".import_dosen").click(function () {
    $('.title-import').html('Import Dosen Ajar Kelas');
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/import_dosen.php",
      type: "GET",
      success: function (data) {
        $("#isi_import_data").html(data);
      }
    });

    $('#modal_import_data').modal({ keyboard: false, backdrop: 'static', show: true });

  });
  $(".import_jadwal").click(function () {
    $('.title-import').html('Import Jadwal Kelas');
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/import_jadwal.php",
      type: "GET",
      success: function (data) {
        $("#isi_import_data").html(data);
      }
    });

    $('#modal_import_data').modal({ keyboard: false, backdrop: 'static', show: true });

  });

  //generate jadwal
  $("#gen-jadwal").on('click', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);
    id = currentBtn.attr('data-id');
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/modal_generate_jadwal.php",
      type: "post",
      data: { kelas_id: id },
      success: function (data) {
        $("#isi_modal_generate").html(data);
        $("#loadnya").hide();
      }
    });
    $('#modal_generate').modal({ keyboard: false, backdrop: 'static' });
  });



  //generate jadwal
  $("#reset-jadwal").on('click', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);
    id = currentBtn.attr('data-id');
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/modal_reset_jadwal.php",
      type: "post",
      data: { kelas_id: id },
      success: function (data) {
        $("#loadnya").hide();
        $("#isi_modal_reset").html(data);
      }
    });
    $('#modal_reset').modal({ keyboard: false, backdrop: 'static' });
  });

  $("#sem_filter").change(function () {
    if ($("#jur_filter").val() != "" && $("#sem_filter").val() != "") {
      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas_jadwal/get_matkul.php",
        type: "POST",
        data: { jur_filter: $("#jur_filter").val(), sem_filter: $("#sem_filter").val() },
        success: function (data) {
          $("#matkul_filter").html(data);
          $("#matkul_filter").trigger("chosen:updated");
        }
      });
    }
  });
  $("#fakultas_filter").change(function () {
    $.ajax({
      type: "post",
      url: "<?= base_admin(); ?>modul/kelas_jadwal/get_prodi.php",
      data: { id_fakultas: this.value },
      success: function (data) {
        $("#jur_filter").html(data);
        $("#jur_filter").trigger("chosen:updated");

      }
    });
  });
  $("#jur_filter").change(function () {
    if ($("#jur_filter").val() != "" && $("#sem_filter").val() != "") {
      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas_jadwal/get_matkul.php",
        type: "POST",
        data: { jur_filter: $("#jur_filter").val(), sem_filter: $("#sem_filter").val() },
        success: function (data) {
          $("#matkul_filter").html(data);
          $("#matkul_filter").trigger("chosen:updated");
        }
      });
    }
  });

  $(".table").on('click', '.cetak', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/modal_print_single.php",
      type: "post",
      data: { kelas_id: id },
      success: function (data) {
        $("#isi_modal_cetak").html(data);
        $("#loadnya").hide();
      }
    });
    $('#modal_cetak_single').modal({ keyboard: false, backdrop: 'static' });
  });


/*    $(".table").on('click','.edit-jadwal',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?= base_admin(); ?>modul / kelas_jadwal / modal_setting_jadwal.php",
  type: "post",
    data : { kelas_id: id },
  success: function(data) {
    $("#isi_kelas_jadwal").html(data);
    $("#loadnya").hide();
  }
        });

  $('#modal_kelas_jadwal').modal({ keyboard: false, backdrop: 'static' });

    });
*/

  $(".table").on('click', '.edit-jadwal', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');
    act = currentBtn.attr('data-act');

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/jadwal/modal_jadwal.php",
      type: "post",
      data: { kelas_id: id, act: act },
      success: function (data) {
        $("#isi_kelas_jadwal").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_kelas_jadwal').modal({ keyboard: false, backdrop: 'static' });

  });


  dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
    "order": [[1, 'asc']],
    destroy: true,
    'bProcessing': true,
    'bServerSide': true,

    'columnDefs': [
      /* {
       'targets': [10],
         'className': 'none'
       },*/
      {
        'targets': [-1],
        'orderable': false,
        'searchable': false,
        'className': 'all dt-center',
        "render": function (aData, type, full, meta) {
          return '<div class="dropup"><div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?= $jadwal; ?></li><li role="separator" class="divider"></li><li><?= $print; ?></li><li role="separator" class="divider"></li><li><?= $edit; ?></li><li role="separator" class="divider"></li><li><?= $del; ?></li></ul></div></div>';
        }
      },
      /*                        {
                  'targets': [6,7,8],
                    'className': 'none'
                  },*/
      {
        'targets': [0],
        'width': '3%',
        'orderable': false,
        'searchable': false
      },
    ],


    'ajax': {
      url: '<?= base_admin(); ?>modul/kelas_jadwal/kelas_jadwal_data_coba.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        d.fakultas = $("#fakultas_filter").val();
        d.jur_filter = $("#jur_filter").val();
        d.sem_filter = $("#sem_filter").val();
        d.matkul_filter = $("#matkul_filter").val();
        d.hari_filter = $("#hari_filter").val();
        d.jenis_kelas = $("#jenis_kelas").val();
      },
      error: function (xhr, error, thrown) {
        console.log(xhr);

      }
    },
  });
  $(".table").on('click', '.peserta-kelas', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');
    action = currentBtn.attr('data-action');

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/peserta_kelas/peserta_kelas_modal_view.php",
      type: "post",
      data: { kelas_id: id, action: action },
      success: function (data) {
        $("#isi_peserta_kelas").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_peserta_kelas').modal({ keyboard: false, backdrop: 'static' });

  });
  $("#exsport-jadwal").click(function () {
    // alert("<?= base_admin() ?>");
    var fakultas = $("#fakultas_filter").val();
    var prodi = $("#jur_filter").val();
    var semester = $("#sem_filter").val();
    var matkul = $("#matkul_filter").val();
    var hari = $("#hari_filter").val();
    var jenis_kelas = $("#jenis_kelas").val();
    document.location = "<?= base_admin() ?>modul/kelas_jadwal/exsport_excel.php?jur_filter=" + prodi + "&fakultas=" + fakultas + "&sem_filter=" + semester + "&matkul_filter=" + matkul + "&hari_filter=" + hari + "&jenis_kelas=" + jenis_kelas;
  });


  //filter
  $('#filter').on('click', function () {
    dataTable_jadwal.ajax.reload();
  });


  $(document).ready(function () {

    $.fn.modal.Constructor.prototype.enforceFocus = function () { };

    //chosen select
    $(".chzn-select").chosen();
    $(".chzn-select-deselect").chosen({
      allow_single_deselect: true
    });

    //trigger validation onchange
    $('select').on('change', function () {
      $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    $.validator.addMethod("myFunc", function (val) {
      if (val == 'all') {
        return false;
      } else {
        return true;
      }
    }, "Untuk Cetak Data Silakan Pilih Prodi");
    $("#filter_kelas_form").validate({
      errorClass: "help-block",
      errorElement: "span",
      highlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass(
          "has-success").addClass("has-error");
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass(
          "has-error").addClass("has-success");
      },
      errorPlacement: function (error, element) {
        if (element.hasClass("chzn-select")) {
          var id = element.attr("id");
          error.insertAfter("#" + id + "_chosen");
        } else if (element.attr("type") == "checkbox") {
          element.parent().parent().append(error);
        } else if (element.attr("type") == "radio") {
          element.parent().parent().append(error);
        } else {
          error.insertAfter(element);
        }
      },

      rules: {

        jur_filter: {
          myFunc: true
          //minlength: 2
        },

        sem_filter: {
          required: true,
          //minlength: 2
        },

      },
      messages: {

        sem_filter: {
          required: "Untuk Cetak Data Silakan Pilih Semester",
          //minlength: "Your username must consist of at least 2 characters"
        },

      }
    });
  });

  $(".table").on('click', '.hapus_dtb_notif', function (event) {

    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#ucing')
      .modal({ keyboard: false })
      .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          url: uri + "?act=delete&id=" + id,
          success: function (responseText) {
            $("#loadnya").hide();
            console.log(responseText);
            $.each(responseText, function (index) {
              console.log(responseText[index].status);
              if (responseText[index].status == 'die') {
                $("#informasi").modal("show");
              } else if (responseText[index].status == 'error') {
                $('.isi_warning_delete').text(responseText[index].error_message);
                $('.error_data_delete').fadeIn();
                $('html, body').animate({
                  scrollTop: ($('.error_data_delete').first().offset().top)
                }, 500);
              } else if (responseText[index].status == 'good') {
                $('.error_data_delete').hide();
                window[dtb_var].draw(false);
              } else {
                $('.isi_warning_delete').text(responseText[index].error_message);
                $('.error_data_delete').fadeIn();
                $('html, body').animate({
                  scrollTop: ($('.error_data_delete').first().offset().top)
                }, 500);
              }
            });
          }
        });
        $('#ucing').modal('hide');

      });
  });


  $(".bulk-check").on('click', function () { // bulk checked
    var status = this.checked;
    if (status) {
      select_deselect('select');
    } else {
      select_deselect('unselect');
    }

    $(".check-selected").each(function () {
      $(this).prop("checked", status);
    });
  });

  function init_selected() {
    var selected = check_selected();
    var btn_hide = $('#aksi_top_krs');
    if (selected.length > 0) {
      btn_hide.show()
    } else {
      btn_hide.hide()
    }
  }

  function check_selected() {
    var table_select = $('#dtb_kelas_jadwal tbody tr.selected');
    var array_data_delete = [];
    table_select.each(function () {
      var check_data = $(this).find('.data_selected_id').attr('data-id');
      if (typeof check_data != 'undefined') {
        array_data_delete.push(check_data)
      }
    });
    $('.selected-data').text(array_data_delete.length + ' Data Terpilih');
    return array_data_delete
  }

  function select_deselect(type) {
    if (type == 'select') {
      $('#dtb_kelas_jadwal tbody tr').addClass('DTTT_selected selected')
    } else {
      $('#dtb_kelas_jadwal tbody tr').removeClass('DTTT_selected selected')
    }
    init_selected()
  }
  $(document).on('click', '#dtb_kelas_jadwal tbody tr .check-selected', function (event) {
    var btn = $(this).find('button');
    if (btn.length == 0) {
      $(this).parents('tr').toggleClass('DTTT_selected selected');
      var selected = check_selected();
      console.log(selected);
      init_selected();

    }
  });

  /* Add a click handler for the delete row */
  $('.submit-proses').click(function (event) {
    $("#loadnya").show();
    event.stopPropagation();
    event.preventDefault();
    event.stopImmediatePropagation();
    //var anSelected = fnGetSelected( dtb_kelas_jadwal );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    //var aksi = $(':selected', $(this)).data('aksi');
    var aksi = $("#aksi_krs option:selected").attr('data-aksi');
    if (aksi == 'ubah_tanggal') {
      $('.modal-title-tagihan').html("Edit Massal Tagihan Mahasiswa");
      $.ajax({
        url: "<?= base_admin(); ?>modul/setting_tagihan_mahasiswa/edit_tanggal_massal.php",
        type: "post",
        data: { all_id: all_ids },
        success: function (data) {
          $("#isi_setting_tagihan_mahasiswa").html(data);
          $("#loadnya").hide();
        }
      });
      $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false, backdrop: 'static' });
    } else {
      $("#loadnya").hide();
      $('#modal-confirm-action').modal({ keyboard: false }).one('click', '#confirm-action', function (e) {
        e.stopImmediatePropagation()
        $.ajax({
          type: 'POST',
          url: '<?= base_admin(); ?>modul/kelas_jadwal/kelas_jadwal_action.php?act=' + aksi,
          data: { aksi: $("#aksi_krs").val(), data_ids: all_ids },
          success: function (result) {
            $('#loadnya').hide();
            $(".bulk-check").prop("checked", 0);
            select_deselect('unselect');
            dtb_kelas_jadwal.draw(false);
          },
          //async:false
        });
        $('#modal-confirm-action').modal('hide').data('bs.modal', null);;
      });
    }
  });


</script>