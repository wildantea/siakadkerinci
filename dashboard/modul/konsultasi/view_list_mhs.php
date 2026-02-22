<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Bimbingan Pa
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= base_index(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= base_index(); ?>bimbingan-pa">Bimbingan Pa</a></li>
    <li class="active">Bimbingan Pa List</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Data Konsultasi</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" id="filter_kelas_form" method="post"
                action="<?= base_admin(); ?>modul/konsultasi/cetak_multi.php" target="_blank">
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Periode Semester</label>
                  <div class="col-lg-5">
                    <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..."
                      class="form-control chzn-select" tabindex="2" required="">
                      <?php
                      $data_semester = $db->query("select * from view_semester where id_semester in(select id_semester from bimbingan_dosen_pa where nip='" . $_SESSION['username'] . "')");
                      foreach ($data_semester as $isi) {
                        echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
                      }
                      ?>
                    </select>

                  </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="Semester" class="control-label col-lg-2">Status Dijawab</label>
                  <div class="col-lg-3">
                    <select id="dijawab" name="dijawab" data-placeholder="Pilih Hari ..." class="form-control"
                      tabindex="2">
                      <option value="all">Semua</option>
                      <option value="1">Sudah</option>
                      <option value="0">Belum</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                  <div class="col-lg-10">
                    <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                    <button id="cetak" class="btn btn-success"><i class="fa fa-print"></i> Cetak</button>

                  </div><!-- /.form-group -->
              </form>
            </div>

            <div class="alert alert-warning fade in error_data_delete" style="display:none">
              <button type="button" class="close hide_alert_notif">&times;</button>
              <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
            </div>
            <table id="data_bimbingan" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Jenis Konsultasi</th>
                  <th>Pertanyaan</th>
                  <th>Jawaban Dosen</th>
                  <th>Periode</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Mahasiswa Bimbingan</h3>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">

            <div class="alert alert-warning fade in error_data_delete" style="display:none">
              <button type="button" class="close hide_alert_notif">&times;</button>
              <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
            </div>
            <table id="dtb_bimbingan_pa" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Angkatan</th>
                  <th>Jurusan</th>
                  <th>IPS</th>
                  <th>IPK</th>
                  <th>Status</th>
                  <th>KRS</th>
                  <th>Terakhir Diskusi</th>
                  <th>Action</th>
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

    foreach ($db->fetch_all("sys_menu") as $isi) {

      //jika url = url dari table menu
      if (uri_segment(1) == $isi->url) {
        //check edit permission
        if ($role_act["up_act"] == "Y") {
          $edit = "<a data-id='+aData[indek]+' href=" . base_index() . "bimbingan-pa/edit/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
        } else {
          $edit = "";
        }
        if ($role_act['del_act'] == 'Y') {
          $del = "<button data-id='+aData[indek]+' data-uri=" . base_admin() . "modul/bimbingan_pa/bimbingan_pa_action.php" . ' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_bimbingan_pa"><i class="fa fa-trash"></i></button>';
        } else {
          $del = "";
        }
      }
    }

    ?>

</section><!-- /.content -->

<script type="text/javascript">

  bimbingan = $("#data_bimbingan").DataTable({
    'bProcessing': true,
    'bServerSide': true,
    'scrollX': true,
    'order': [],
    'ajax': {
      url: '<?= base_admin(); ?>modul/konsultasi/konsultasi_data_detail_mhs_all.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        d.nip = "<?= $dosen_id ?>";
        d.semester = $("#sem_filter").val();
        d.dijawab = $("#dijawab").val();
      },
      error: function (xhr, error, thrown) {
        console.log(xhr);

      }
    }
  });

  //filter
  $('#filter').on('click', function () {
    bimbingan.ajax.reload();
  });


  var dtb_bimbingan_pa = $("#dtb_bimbingan_pa").DataTable({
    'bProcessing': true,
    'bServerSide': true,

    'columnDefs': [{
      'targets': [-1],
      'orderable': false,
      'searchable': false
    },
    {
      'width': '5%',
      'targets': 0,
      'orderable': false,
      'searchable': false,
      'className': 'dt-center'
    }
    ],


    'ajax': {
      url: '<?= base_admin(); ?>modul/bimbingan_pa/bimbingan_pa_data.php',
      type: 'post',  // method  , by default get
      error: function (xhr, error, thrown) {
        console.log(xhr);

      }
    },
  });

</script>