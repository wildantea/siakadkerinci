<?php
session_start();
include "../../inc/config.php";
$matakuliah = $db2->fetchSingleRow("matkul","id_matkul",$_POST['id_matkul']);

?>
 <div class="box box-success box-solid">
            <div class="box-header with-border">
              <div class="pull-left" style="float:left">
                <button style="float:left" type="button" class="btn btn-box-tool" class="close" data-dismiss="modal" data-toggle="tooltip" data-title="Close"><i class="fa fa-times"></i></button>
                <h3 class="btn btn-box-tool box-title" style="margin-top:0">Matakuliah : <?=$matakuliah->kode_mk.' - '.$matakuliah->nama_mk;?></h3>
              </div>
              

              <!-- /.box-tools -->
            </div>

            <div class="box-body">
              <h3 class="text-blue box-title" style="margin-top:0">Matakuliah Prasyarat</h3>
             <a class="btn btn-primary btn-sm add-syarat" id="add-syarat" style="margin-bottom: 5px"><i class="fa fa-plus"></i> Tambah Data</a>

             <div id="input_syarat_form" style="display: none">
               
             </div>
              </div>

            <!-- /.box-header -->
            <div class="box-body">
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_syarat">
                <thead>
                  <tr>
                  <th>#</th>
                  <th>Matakuliah</th>
                  <th>Semester</th>
                  <th>Syarat</th>
                </tr>
                </thead>
                <tbody>
              </tbody>
            </table>

 <h3 class="box-title text-blue">Matakuliah Setara</h3>
              <a class="btn btn-primary btn-sm add-setara" id="add-setara" style="margin-bottom: 5px"><i class="fa fa-plus"></i> Tambah Data</a>
             <div id="input_setara_form" style="display: none">
               
             </div>
  <table class="table table-bordered table-striped display nowrap" width="100%" id="dtb_setara">
                <thead>
                  <tr>
                  <th>#</th>
                  <th>Kurikulum</th>
                  <th>Matakuliah</th>
                  <th>Semester</th>
                </tr>
                </thead>
                <tbody>
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
          </div>

<div class="modal" id="modal-confirm-delete-syarat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang['confirm'];?></h4> </div> <div class="modal-body"> <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span> <?php echo $lang['delete_confirm'];?></span></p> </div> <div class="modal-footer"><button type="button" class="btn btn-default cancel-hapus"><?php echo $lang['cancel_button'];?></button><button type="button" id="delete" class="btn btn-danger"><?php echo $lang['delete'];?></button> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div><!-- /.modal -->
<script type="text/javascript">

        var dtb_syarat = $("#dtb_syarat").DataTable({
           'destroy' : true,
           'ordering' : false,
           'paging' : false,
           'bProcessing': true,
            'bServerSide': true,
            'searching' : false,
                 "columnDefs": [
              {
             "targets": [0,2],
             "width" : "5%",
              "class" : "dt-center",
              "orderable" : false,
            }
    
              ],

            'ajax':{
               url :'<?=base_admin();?>modul/matakuliah/matkul_prasyarat/matkul_prasyarat_data.php',
            type: 'post',  // method  , by default get
            data : {id_matkul:<?=$_POST['id_matkul'];?>},
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "fnDrawCallback": function() {
              $('#dtb_syarat tbody tr').toggleClass('DTTT_selected selected');
          }

        });

        var dtb_setara = $("#dtb_setara").DataTable({
           'destroy' : true,
           'ordering' : false,
           'paging' : false,
           'bProcessing': true,
            'bServerSide': true,
            'searching' : false,
                 "columnDefs": [
              {
             "targets": [0,3],
             "width" : "5%",
              "class" : "dt-center",
              "orderable" : false,
            }
    
              ],

            'ajax':{
               url :'<?=base_admin();?>modul/matakuliah/matkul_setara/matkul_setara_data.php',
            type: 'post',  // method  , by default get
           data : {id_matkul:<?=$_POST['id_matkul'];?>},
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "fnDrawCallback": function() {
              $('#dtb_setara tbody tr').toggleClass('DTTT_selected selected');
          }

        });

 $(".table").on('click','.hapus-syarat',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');

    var id_matkul = $("#add-syarat").attr('data-id');

    $('#modal-confirm-delete-syarat')
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
          url: "<?=base_admin();?>modul/matakuliah/matkul_prasyarat/matkul_prasyarat_action.php?act=delete",
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
                            dtb_matkul.ajax.reload(function()
                              {
                                // $('#dtb_matkul tbody #mat-<?=$_POST['id_matkul'];?>').addClass('DTTT_selected');
                              }
                              ,false);
                             dtb_syarat.draw();
                          }
                    });
                }
          });
          $('#modal-confirm-delete-syarat').modal('hide');

        });
  });
$(".add-syarat").click(function(){
              if ($("#input_syarat_form").is(':visible')) {
                $(this).find('.fa').toggleClass('fa-minus fa-plus');
                $("#input_syarat_form").html('');
                $("#input_syarat_form").slideUp();
              } else {
                $(this).find('.fa').toggleClass('fa-plus fa-minus');
                $.ajax({
                    type : "post",
                    url :'<?=base_admin();?>modul/matakuliah/matkul_prasyarat/matkul_prasyarat_add.php',
                    data : {id_matkul:<?=$_POST['id_matkul'];?>},
                    success : function(data) {
                        $("#input_syarat_form").html(data);
                        $("#input_syarat_form").slideDown();

                    }
                });
              }
              
      });

 //hapus setara
$(".table").on('click','.hapus-setara',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');

    var id_matkul = $("#add-syarat").attr('data-id');

    $('#modal-confirm-delete-syarat')
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
          url: "<?=base_admin();?>modul/matakuliah/matkul_setara/matkul_setara_action.php?act=delete",
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
                            dtb_matkul.ajax.reload(function()
                              {
                                 //$('#dtb_matkul tbody #mat-<?=$_POST['id_matkul'];?>').addClass('DTTT_selected');
                              }
                              ,false);
                             dtb_setara.draw();
                          }
                    });
                }
          });
          $('#modal-confirm-delete-syarat').modal('hide');

        });
  });
 $(".add-setara").click(function(){
              if ($("#input_setara_form").is(':visible')) {
                $(this).find('.fa').toggleClass('fa-minus fa-plus');
                $("#input_setara_form").html('');
                $("#input_setara_form").slideUp();
              } else {
                $(this).find('.fa').toggleClass('fa-plus fa-minus');
                $.ajax({
                    type : "post",
                    url :'<?=base_admin();?>modul/matakuliah/matkul_setara/matkul_setara_add.php',
                    data : {id_matkul:<?=$_POST['id_matkul'];?>},
                    success : function(data) {
                        $("#input_setara_form").html(data);
                        $("#input_setara_form").slideDown();

                    }
                });
              }
              
      });
    $(".cancel-hapus").on('click', function() {
        $('#modal-confirm-delete-syarat').modal('hide');
    });
</script>