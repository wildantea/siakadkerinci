<style type="text/css">
.modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
}
</style>
<?php
$mhs_data = $db2->fetchSingleRow("view_simple_mhs","nim",$_SESSION['nim']);
?>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Jadwal Sidang
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jadwal-sidang">Jadwal Sidang</a></li>
                        <li class="active">Jadwal Sidang List</li>
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
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">

                            </div>
                            </div>
                        <table id="dtb_jadwal_sidang" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Pendaftaran</th>
                                  <th rowspan="2">Tanggal Daftar</th>
                                  <th colspan="2" class="dt-center">Jadwal & Penguji</th>
                                  <th rowspan="2">Pembimbing</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Jadwal</th>
                                  <th>Penguji</th>
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
    $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit Jadwal\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/jadwal_sidang/jadwal_sidang_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus Jadwal" data-variable="dtb_jadwal_sidang"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal fade" id="modal_jadwal_sidang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Jadwal Sidang</h4> </div> <div class="modal-body" id="isi_jadwal_sidang"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">
      
      $("#add_jadwal_sidang").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_jadwal_sidang").html(data);
              }
          });

      $('#modal_jadwal_sidang').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Jadwal Sidang");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/jadwal_sidang/jadwal_act.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_jadwal_sidang").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_jadwal_sidang').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_jadwal_sidang = $("#dtb_jadwal_sidang").DataTable({
              
          <?php
          if (getFilter(array('filter_pendaftaran' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_pendaftaran_jadwal' => 'input_search'));?>"
                },
            <?php
          }
          ?>    

           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$edit;?> <?=$del;?>';
               }
            },
      
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jurusan").val();
                    d.periode = $("#periode").val();
                    d.jenis_pendaftaran = $("#jenis_pendaftaran").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_jadwal_sidang.ajax.reload();
});

$("#dtb_jadwal_sidang_filter").on('click','.reset-button-datatable',function(){
    dtb_jadwal_sidang
    .search( '' )
    .draw();
  });
</script>
            