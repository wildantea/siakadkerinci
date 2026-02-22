<style type="text/css">
.modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
}
.peserta-kelas {
  cursor: pointer;
}
.modal-abs {
  width: 88%;
  padding: 0;
}

.modal-content-abs {
  height: 99%;
}
  .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-left-color: #3c8dbc;
    border-right-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li {
  border-top: 2px solid transparent;
    margin-bottom: -1px;
}
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-top-color: #3c8dbc;
}
#modal_header_surat {
      z-index: 1500;
}
</style>
                                <div class="box-header">
                                <?php
                                if ($db2->userCan("insert")) {
                                      ?>
                                      <a id="add_header_surat" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Header</a>
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
                        <table id="dtb_header_surat" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  
                                  <th>Nama Header Surat</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
              
 <?php
  $edit ="";
  $del="";
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/header_surat/header_surat_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_header_surat"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal" id="modal_header_surat" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg modal-abs"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="glyphicon glyphicon-remove"></i></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Header Surat</h4> </div> <div class="modal-body" id="isi_header_surat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>


        <script type="text/javascript">
      
      $("#add_header_surat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/header_surat/header_surat_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_header_surat").html(data);
              }
          });

      $('#modal_header_surat').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Header Surat");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/header_surat/header_surat_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_header_surat").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_header_surat').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_header_surat = $("#dtb_header_surat").DataTable({
              
           
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
              url :'<?=base_admin();?>modul/header_surat/header_surat_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });$("#dtb_header_surat_filter").on("click",".reset-button-datatable",function(){
    dtb_header_surat
    .search( "" )
    .draw();
  });
</script>
            