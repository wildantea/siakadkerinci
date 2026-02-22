<h4 class="box-title action-title-rps"></h4>
<div id="isi_edit_rps">
<?php
if ($check_exist->rowCount()<1) {
  include "upload_rps.php";
}
?>
</div>    
                        <table id="dtb_file" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Tanggal Upload</th>
                                  <th>Pengupload</th>
                                  <th>File</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
 <?php
  $edit ="";
  $del="";
    $edit = "<a data-id='+data+'  class=\"btn btn-primary edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
        ?>    


        <script type="text/javascript">
    
    $("#dtb_file").on('click','.edit_data',function(event) {
            event.preventDefault();
            var currentBtn = $(this);
            id = currentBtn.attr('data-id');
            $("#loadnya").show();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/rps/edit_rps.php",
              type : "POST",
              data : {id_rps:id},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_edit_rps").html(data);
                  $("#isi_edit_rps").slideDown();
                  $('.action-title-rps').html('<b>Edit RPS</b>');
              }
           });
    });

      var dtb_file = $("#dtb_file").DataTable({
              
           'order' : [[1,'asc']],
           'paging' : false,
           'searching' : false,
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
                return '<?=$edit;?>';
               }
            },
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/rps/data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id;?>;
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });$("#dtb_file_filter").on("click",".reset-button-datatable",function(){
    dtb_file
    .search( "" )
    .draw();
  });
</script>
            