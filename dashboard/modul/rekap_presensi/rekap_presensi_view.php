<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Rekap Presensi Dosen
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= base_index(); ?>rekap-presensi">Rekap Presensi</a></li>
    <li class="active">Rekap Presensi List</li>
  </ol>
</section>


<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <a id="geser_jadwal" class="btn btn-primary "><i class="fa fa-calendar"></i> Geser Jadwal Kuliah</a>
          <a id="jadwal_puasa" class="btn btn-warning "><i class="fa fa-clock-o"></i> Ubah Jam Kuliah (Bulan Puasa)</a>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" id="filter_kelas_form" method="post"
                action="<?= base_admin(); ?>modul/rekap_presensi/download_data.php" target="_blank">


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
                  <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                  <div class="col-lg-10">
                    <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                    <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                  </div><!-- /.form-group -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>

          <table width="100%" id="dtb_kelas_jadwal" class="table table-bordered table-striped display nowrap">
            <thead>
              <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Matakuliah</th>
                <th rowspan="2">Kelas</th>
                <th colspan="2" class="dt-center">Jadwal</th>
                <th rowspan="2">Dosen</th>
                <?php
                for ($i = 1; $i <= 16; $i++) {
                  echo "<th style='width:100px' rowspan='2'>$i</th>";
                }
                ?>
                <th rowspan="2">Program Studi</th>

                <th rowspan="2">Jml Masuk</th>
                <th rowspan="2">Jml Keluar</th>
              </tr>
              <tr>
                <th>Hari/Jam</th>
                <th>Ruang</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          Ket :<br>
          <a class='btn btn-default'><i class='fa fa-check' style='color:green'></i></a> : Hadir Sesuai Jadwal<br>
          <a class='btn btn-default'><i class='fa fa-check' style='color:orange'></i></a> : Hadir Tidak Sesuai
          Jadwal<br>
          <a class='btn btn-default'><i class='fa fa-close' style='color:red'></i></a> : Tidak Hadir
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>

</section>
<!-- modal cetak single -->
<div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button>
        <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4>
      </div>
      <div class="modal-body" id="isi_modal_cetak"> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<div class="modal" id="modal_data_pegawai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
        <h4 class="modal-title"><?php echo $lang["add_button"]; ?> Data Pegawai</h4>
      </div>
      <div class="modal-body" id="isi_data_pegawai"> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript">

  $("#geser_jadwal").click(function () {
    $.ajax({
      url: "<?= base_admin(); ?>modul/rekap_presensi/geser_jadwal_form.php",
      type: "GET",
      success: function (data) {
        $("#isi_data_pegawai").html(data);
      }
    });

    $('#modal_data_pegawai').modal({ keyboard: false, backdrop: 'static', show: true });

  });

  $("#jadwal_puasa").click(function () {
    $.ajax({
      url: "<?= base_admin(); ?>modul/rekap_presensi/jadwal_puasa_form.php",
      type: "GET",
      success: function (data) {
        $("#isi_data_pegawai").html(data);
      }
    });

    $('#modal_data_pegawai').modal({ keyboard: false, backdrop: 'static', show: true });

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


  dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
    "order": [],
    "lengthMenu": [
      [10, 15, 20, 100, 500, -1],
      [10, 15, 20, 100, 500, "All"] // change per page values here
    ],
    'bProcessing': true,
    'scrollX': true,
    'bServerSide': true,

    'columnDefs': [
      {
        'targets': [0],
        'width': '8%',
        'orderable': false,
        'searchable': false
      },
      {
        'targets': [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        'width': '3%',
        'orderable': false,
        'searchable': false
      },
    ],


    'ajax': {
      url: '<?= base_admin(); ?>modul/rekap_presensi/rekap_presensi_data.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        d.fakultas = $("#fakultas_filter").val();
        d.jur_filter = $("#jur_filter").val();
        d.sem_filter = $("#sem_filter").val();
        d.matkul_filter = $("#matkul_filter").val();
        d.hari_filter = $("#hari_filter").val();
      },
      error: function (xhr, error, thrown) {
        console.log(xhr);

      }
    },
  });

  //filter
  $('#filter').on('click', function () {
    dataTable_jadwal.ajax.reload();
  });

  $(".table").on('click', '.cetak', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');
    id_jadwal = currentBtn.attr('data-jadwal');

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas/cetak/cetak_modal_single.php",
      type: "post",
      data: { kelas_id: id, id_jadwal: id_jadwal },
      success: function (data) {
        $("#isi_modal_cetak").html(data);
        $("#loadnya").hide();
      }
    });
    $('#modal_cetak_single').modal({ keyboard: false, backdrop: 'static' });
  });
</script>