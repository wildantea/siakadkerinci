<?php
include "../../inc/config.php";
?>
  <style type="text/css"> 
  .modal-backdrop {
    visibility: hidden !important;
}
.modal.in {
    background-color: rgba(0,0,0,0.5);
}
 </style>
 <table id="dtb_list_dosen" class="table table-bordered table-striped" width="100%">
                                      <thead>
                                          <tr>
                                            <th></th>                                            
                                            <th>NIP</th>
                                            <th>Nama</th>   
                                            <th>Jurusan</th>                                        
                                          </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                  </table>

<script type="text/javascript">
    var dataTableDosen = $("#dtb_list_dosen").DataTable({
       "pageLength": 5,
 "bFilter": true,
       "bLengthChange": false,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0],
              'orderable': false,
              'searchable': false
            },
                {
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],
            'ajax':{
              url :'<?=base_admin();?>modul/kelas_jadwal/jadwal/list_dosen_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });


$("#dtb_list_dosen_filter").on('click','.reset-button-datatable',function(){
    dataTableDosen
    .search( '' )
    .draw();
  });

    function hapus_dosen(id){
      $(this).parent().remove();
    }

    function pilih_dosen(id_dosen){
        // $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_action.php?act=pilih_dosen',
            data: 'id_dosen='+id_dosen,
            success: function(result) {
              // $('#loadnya').hide();
              $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
              $("#dosen_ajar").append(result);
            },
            //async:false
        });
    }
</script>