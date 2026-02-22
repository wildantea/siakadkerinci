 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_approved_krs" class="table table-bordered table-striped display nowrap">
                            <thead>
                                <tr>
                                  <th style='padding-right:13px;' class='dt-center'>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>Program Studi</th>
                                  <th>Hadir/Total (%)</th>
                                  <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
 <?php
  $batal ="";
  $del="";
 if ($db2->userCan("update",'kelas-jadwal')) {
    
    $batal = "<button data-id='+data+' data-uri=".base_admin()."modul/kelas_jadwal/peserta_kelas/peserta_kelas_action.php".' class="btn btn-warning disapprove-krs btn-sm" data-toggle="tooltip" title="Batal Persetujuan KRS"><i class="fa fa-mail-reply "></i></button>';
    
 }

  if ($db2->userCan("delete",'kelas-jadwal')) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/kelas_jadwal/peserta_kelas/peserta_kelas_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_approved_krs"><i class="fa fa-trash"></i></button>';
    
 }
        ?>


<script type="text/javascript">

$(".table").on('click','.look-photo',function(event) {
          $("#loadnya").show();
          event.preventDefault();
          var currentBtn = $(this);

          id = currentBtn.attr('data-id');

          $.ajax({
              url : "<?php echo base_admin();?>modul/mahasiswa/photo.php",
              type : "post",
              data : {id:id},
              success: function(data) {
                  $("#isi_photo").html(data);
                  $("#loadnya").hide();
            }
          });

      $('#modal_photo').modal({ keyboard: false });

      });
       var dtb_approved_krs = $("#dtb_approved_krs").DataTable({
              
           'order' : [[1,'asc']],
           'scrollX' : true,
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
              url :'<?=base_admin();?>modul/kelas/peserta/approved_krs_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$_POST['kelas_id'];?>;
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>