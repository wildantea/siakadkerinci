<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pengajuan Cuti Kuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pengajuan-cuti-kuliah">Pengajuan Cuti Kuliah</a></li>
                        <li class="active">Pengajuan Cuti Kuliah List</li>
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
                                      if (uri_segment(1)==$isi->url) {
                                          if ($role_act["insert_act"]=="Y") {
                                      ?>
                                      <a id="add_pengajuan_cuti_kuliah" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Pengajuan Cuti</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_pengajuan_cuti_kuliah" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Tanggal Ajuan</th>
                                  <th>Periode Ajuan</th>
                                  <th>Status Ajuan</th>
                                  <th>Keterangan</th>
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
    <div class="modal" id="modal_pengajuan_cuti_kuliah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pengajuan Cuti Kuliah</h4> </div> <div class="modal-body" id="isi_pengajuan_cuti_kuliah"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
      
      $("#add_pengajuan_cuti_kuliah").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pengajuan_cuti_kuliah/pengajuan_cuti_kuliah_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pengajuan_cuti_kuliah").html(data);
              }
          });

      $('#modal_pengajuan_cuti_kuliah').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pengajuan_cuti_kuliah/pengajuan_cuti_kuliah_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pengajuan_cuti_kuliah").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pengajuan_cuti_kuliah').modal({ keyboard: false,backdrop:'static' });

    });

     var dtb_pengajuan_cuti_kuliah = $("#dtb_pengajuan_cuti_kuliah").DataTable({
           'bProcessing': true,
            'bServerSide': true,
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
             ],
            'ajax':{
              url :'<?=base_admin();?>modul/pengajuan_cuti_kuliah/pengajuan_cuti_kuliah_data.php',
            type: 'post',  // method  , by default get
         /*   data: function ( d ) {
                  d.fakultas = $("#fakultas_filter").val();
                  d.sem_filter = $("#sem_filter").val();
                  d.jur_kode = $("#jur_kode").val();
                  d.mulai_smt = $("#mulai_smt").val();
                  d.mulai_smt_end = $("#mulai_smt_end").val();
                  d.jenis_keluar = $("#jenis_keluar").val();
                  d.control_semester = $("#control_semester").val();
                  d.semester = $("#semester").val();
                  d.control_ipk = $("#control_ipk").val();
                  d.ipk = $("#ipk").val();
                  d.input_search = $('.dataTables_filter input').val();
                },*/
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
</script>
            