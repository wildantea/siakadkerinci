 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_approved_krs" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:13px;' class='dt-center'>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>Program Studi</th>
                                 <!--  <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
 <?php
  $batal ="";
  $del="";
 if ($db->userCan("update",'kelas-jadwal')) {
    
    $batal = "<button data-id='+data+' data-uri=".base_admin()."modul/kelas_jadwal/peserta_kelas/peserta_kelas_action.php".' class="btn btn-warning disapprove-krs btn-sm" data-toggle="tooltip" title="Batal Persetujuan KRS"><i class="fa fa-mail-reply "></i></button>';
    
 }

  if ($db->userCan("delete",'kelas-jadwal')) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/kelas_jadwal/peserta_kelas/peserta_kelas_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_approved_krs"><i class="fa fa-trash"></i></button>';
    
 }
        ?>


<script type="text/javascript">
       var dtb_approved_krs = $("#dtb_approved_krs").DataTable({
              
           'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
/*            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$batal;?> <?=$del;?>';
               }
            },*/
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas_jadwal/peserta_kelas/approved_krs_data.php',
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

//confirm batalkan KRS
$("#dtb_approved_krs").on('click','.disapprove-krs',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');

    $('#modal-confirm')
        .modal({ keyboard: false })
        .one('click', '#confirm-info', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          error: function(data ) { 
              $('#loadnya').hide();
              console.log(data); 
             $('.isi_warning_delete').html(data.responseText);
             $('.error_data_delete').fadeIn();
             $('html, body').animate({
                scrollTop: ($('.error_data_delete').first().offset().top)
            },500);
          },
          url: uri+"?act=disapprove-krs",
          data : {id:id},
             success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                            $("#line_"+id).fadeOut("slow");
                            $('.approved-krs-count').html(responseText[index].approved);
                            $('.pending-krs-count').html(responseText[index].pending);
                             dtb_approved_krs.draw(false);
                             dtb_pending_krs.draw(false);
                             dtb_kelas_jadwal.draw(false);
                          }
                    });
                }
          });
          $('#modal-confirm').modal('hide');

        });
  });
$(".table").on('click','.hapus_dtb_notif',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#modal-confirm-delete')
        .modal({ keyboard: false })
        .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          error: function(data ) { 
              $('#loadnya').hide();
              console.log(data); 
              $('.selected-data').html('');
              $('#bulk_delete').hide();
             $('.isi_warning_delete').html(data.responseText);
             $('.error_data_delete').fadeIn();
             $('html, body').animate({
                scrollTop: ($('.error_data_delete').first().offset().top)
            },500);
          },
          url: uri+"?act=delete",
          data : {id:id},
             success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.selected-data').html('');
                            $('#bulk_delete').hide();
                            $('.error_data_delete').hide();
                            $("#line_"+id).fadeOut("slow");
                            console.log(responseText[index].approved);
                            $('.approved-krs-count').html(responseText[index].approved);
                            $('.pending-krs-count').html(responseText[index].pending);
                             window[dtb_var].draw(false);
                          }
                    });
                }
          });
          $('#modal-confirm-delete').modal('hide');

        });
  });

$("#dtb_approved_krs_filter").on('click','.reset-button-datatable',function(){
    dtb_approved_krs
    .search( '' )
    .draw();
  });
</script>