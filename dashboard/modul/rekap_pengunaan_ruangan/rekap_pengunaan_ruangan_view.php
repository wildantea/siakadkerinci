<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Rekap Pengunaan Ruangan
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rekap-pengunaan-ruangan">Rekap Pengunaan Ruangan</a></li>
                        <li class="active">Rekap Pengunaan Ruangan List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                               
                            </div><!-- /.box-header -->
                            <div class="box-body">
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/rekap_pengunaan_ruangan/download_data.php" target="_blank">


                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="">Pilih Semester</option>
                        <?php
                        looping_semester();
                        ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
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
                                  </label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();

                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Gedung" class="control-label col-lg-2">Gedung</label>
                    <div class="col-lg-5">
                    <select id="gedung_id_filter" name="gedung_id" data-placeholder="Pilih Gedung ..." class="form-control chzn-select" tabindex="2" required="">

                      <option value="all">Semua</option>
                      <?php
                      foreach ($db->query("select * from gedung_ref") as $dt) {
                        echo "<option value='$dt->gedung_id'>$dt->nm_gedung</option>";
                      }
                      ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                    <label for="Program Studi" class="control-label col-lg-2">Ruangan</label>
                    <div class="col-lg-5">
                    <select id="ruang_filter" name="ruang_filter" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->

                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Hari</label>
                        <div class="col-lg-3">
                        <select id="hari_filter" name="hari_filter" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
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
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_rekap_pengunaan_ruangan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Hari</th>
                                  <th>Gedung</th>
                                  <th>Ruangan</th>
                                  <?php
                                  $sesi = $db->query("select * from sesi_waktu order by jam_mulai asc");
                                  foreach ($sesi as $jam) {
                                      echo "<th>$jam->jam_mulai</th>";
                                  }
                                  ?>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
      

    <div class="modal" id="modal_rekap_pengunaan_ruangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Rekap Pengunaan Ruangan</h4> </div> <div class="modal-body" id="isi_rekap_pengunaan_ruangan"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
      
       $("#gedung_id_filter").change(function(){
            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/rekap_pengunaan_ruangan/get_ruang.php",
            data : {gedung_id:this.value},
            success : function(data) {
                $("#ruang_filter").html(data);
                $("#ruang_filter").trigger("chosen:updated");
            }
        });
            });

    dtb_rekap_pengunaan_ruangan = $("#dtb_rekap_pengunaan_ruangan").DataTable({
        lengthMenu: [
            [10, 25, 50, -1], // Values for the dropdown (number of entries)
            [10, 25, 50, "All"] // Labels displayed in the dropdown
        ],
           'bProcessing': true,
           'searching' : false,
            'bServerSide': true,
            'scrollX' : true,
            'ordering' : false,

           'columnDefs': [
            {
            'targets': [0],
             'width': '3%',
              'orderable': false,
              'searchable': false
            },
             ],


            'ajax':{
              url :'<?=base_admin();?>modul/rekap_pengunaan_ruangan/rekap_pengunaan_ruangan_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                d.semester = $("#sem_filter").val();
                d.fakultas = $("#fakultas_filter").val();
                    d.jur_filter = $("#jur_filter").val();
                    d.gedung = $("#gedung_id_filter").val();
                    d.ruang = $("#ruang_filter").val();
                    d.hari = $("#hari_filter").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

//filter
$('#filter').on('click', function() {
  dtb_rekap_pengunaan_ruangan.ajax.reload();
});

</script>
            