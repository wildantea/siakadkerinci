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
        <div class="box-body table-responsive">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" id="filter_kelas_form" method="post"
                action="<?= base_admin(); ?>modul/kelas_jadwal/cetak.php" target="_blank">
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
                  </div><!-- /.form-group -->
              </form>
            </div>
            <!-- /.box-body -->
          </div>

          <table width="100%" id="dtb_kelas_jadwal" class="table table-bordered table-striped display nowrap">
            <thead>
              <tr>
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
<script type="text/javascript">

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
    'bProcessing': true,
    'paging': false,
    'scrollX': true,
    'bServerSide': true,

    'columnDefs': [
      {
        'targets': [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        'width': '3%',
        'orderable': false,
        'searchable': false
      },
    ],


    'ajax': {
      url: '<?= base_admin(); ?>modul/rekap_presensi/rekap_presensi_data_dosen.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        d.sem_filter = $("#sem_filter").val();
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

</script>