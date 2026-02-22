                                <div class="box-header">
                                <?php
                                if ($db2->userCan("insert")) {
                                      ?>
                                      <a id="add_penomoran_surat" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Penomoran</a>
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
                        <table id="dtb_penomoran_surat" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>Kode / Nama Singkat</th>
                                  <th>Nama Penomoran</th>
                                  <th>Urutan Penomoran</th>
                                  <th>Aktif</th>
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
    $edit = "<a data-id='+data+'  class=\"btn btn-primary btn-sm edit_data_nomor \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/penomoran_surat/penomoran_surat_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_penomoran_surat"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal" id="modal_penomoran_surat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Penomoran Surat</h4> </div> <div class="modal-body" id="isi_penomoran_surat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    

        <script type="text/javascript">
      
      $("#add_penomoran_surat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/penomoran_surat/penomoran_surat_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_penomoran_surat").html(data);
              }
          });

      $('#modal_penomoran_surat').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $("#dtb_penomoran_surat").on('click','.edit_data_nomor',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Penomoran Surat");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/penomoran_surat/penomoran_surat_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_penomoran_surat").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_penomoran_surat').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_penomoran_surat = $("#dtb_penomoran_surat").DataTable({
              
           'order' : [[1,'asc']],
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
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/penomoran_surat/penomoran_surat_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });$("#dtb_penomoran_surat_filter").on("click",".reset-button-datatable",function(){
    dtb_penomoran_surat
    .search( "" )
    .draw();
  });
</script>
            