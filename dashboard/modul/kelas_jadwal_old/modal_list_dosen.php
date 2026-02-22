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
 <table id="dtb_list_dosen" class="table table-bordered table-striped">
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
    var dataTable = $("#dtb_list_dosen").DataTable({
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
              url :'<?=base_admin();?>modul/kelas_jadwal/list_dosen_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
    $(document).ready(function() {
$(".close_syarat").on("click",function(){
  $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
});



});
    function hapus_dosen(id){
      $(this).parent().remove();
    }
</script>