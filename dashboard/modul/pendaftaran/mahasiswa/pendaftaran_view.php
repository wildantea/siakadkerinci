<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pendaftaran
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran">Pendaftaran</a></li>
                        <li class="active">Pendaftaran List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                if ($db2->userCan("insert")) {
                                      ?>
                                      <a id="add_pendaftaran" class="btn btn-primary "><i class="fa fa-plus"></i> Buat Ajuan Pendaftaran</a>
                                      <?php
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">
                                   <button id="bulk_delete" style="display: none;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_pendaftaran" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Jenis Pendaftaran</th>
                                  <th>Tanggal Daftar</th>
                                  <th>Status</th>
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
  $edit ="";
  $del="";
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/pendaftaran/mahasiswa/pendaftaran_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_pendaftaran"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal" id="modal_pendaftaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran</h4> </div> <div class="modal-body" id="isi_pendaftaran"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    


    </section><!-- /.content -->

        <script type="text/javascript">
      $(document).ready(function(){

$.fn.modal.Constructor.prototype.enforceFocus = function() {};

});
      $("#add_pendaftaran").click(function() {
         $(".modal-title").html("Tambah Baru Pendaftaran");
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pendaftaran").html(data);
              }
          });

      $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static',show:true });

    });


    $(".table").on('click','.lihat-jadwal',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Jadwal Ujian");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/lihat_jadwal.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });
    
    $(".table").on('click','.status-daftar',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Detail Pendaftaran Mahasiswa");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/modal_dokumen.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });

          $(".table").on('click','.lihat-nilai',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Nilai Penguji");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/jadwal_sidang/lihat_nilai.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Pendaftaran");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_pendaftaran = $("#dtb_pendaftaran").DataTable({
              
           
           'bProcessing': true,
            'bServerSide': true,
      
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>
            