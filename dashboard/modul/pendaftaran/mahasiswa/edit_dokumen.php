<?php
session_start();
include "../../../inc/config.php";
$syarat = $db2->fetchCustomSingle("select * from tb_data_pendaftaran_jenis_bukti inner join tb_data_pendaftaran_bukti_dokumen using(id_jenis_bukti) where id_bukti=?",array('id_bukti' => $_POST['id_bukti']));

?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form method="post" class="form-horizontal" id="change_syarat" action="<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_action.php?act=change_syarat">
      <input type="hidden" name="id_bukti" value="<?=$syarat->id_bukti;?>">
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-2">Nama Syarat</label>
                        <div class="col-lg-9">
                          <input type="text" class="form-control" value="<?=$syarat->jenis_bukti;?>" readonly>
                        </div>
            </div>
                <div class="form-group" style="margin-bottom: 0">
                  <div class="col-lg-2" style="padding-top: 26px;">
                    <label class="radio-inline">
                      <input type="radio" name="type_dokumen" checked="" value='1' data-id="<?=$syarat->id_jenis_bukti;?>" class="radio-change">Upload
                    </label>
                    <label class="radio-inline">
                      <input type="radio" value="0" data-id="<?=$syarat->id_jenis_bukti;?>" name="type_dokumen"  class="radio-change">Link
                    </label>
                  </div>
                         <div class="col-lg-10">
                        <label for="File" class="control-label"><?=$syarat->jenis_bukti;?><span style="color:#FF0000">*</span></label>
                      <div id="show-link-<?=$syarat->id_jenis_bukti;?>" style="display: none">
                        <input type="text" id="link_upload_<?=$syarat->id_jenis_bukti;?>" name="link_dokumen" class="form-control" placeholder="https://linkbukti.com">
                      </div>
                      <div id="show-upload-<?=$syarat->id_jenis_bukti;?>">
                      <div class="fileinput fileinput-new" data-provides="fileinput" style="margin-bottom: 0">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" id="file_upload_<?=$syarat->id_jenis_bukti;?>" name="file_name" required class="file-upload-data" accept="image/*,application/pdf">
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists remove-<?=$syarat->id_jenis_bukti;?>" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                      </div>
                        </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                <button type="button" class="btn btn-default close-edit-dokumen"><i class="fa fa-close"></i> Cancel</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>

                  </div>
                </div>
              </div><!-- /.form-group -->
</form>
<script type="text/javascript">
$(document).ready(function() {
  $(".close-edit-dokumen").on("click",function(){
    $("#modal_edit_dokumen").modal( 'hide' ).data( 'bs.modal', null );
  });
});
      $('.radio-change').change(function() {
        var id = $(this).data('id');
        //if type upload
        if (this.value=='1') {
          $("#show-upload-"+id).show();
          $("#show-link-"+id).hide();
          $("#link_upload_"+id).prop('required',false);
          $("#file_upload_"+id).prop('required',true);
        } else {
          $("#show-upload-"+id).hide();
          $("#show-link-"+id).show();
          $("#link_upload_"+id).prop('required',true);
          $("#file_upload_"+id).prop('required',false);
          $( ".remove-"+id).trigger( "click" );
        }
    });
 $("#change_syarat").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: 'json',
                type : 'post',
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data').hide();
                            $("#modal_edit_dokumen").modal( 'hide' ).data( 'bs.modal', null );

                            dtb_syarat.ajax.reload( function ( json ) {
                              $("a.bukti-dokumen").fancybox({
                                autoSize : false,
                                fitToView: false, // 
                                maxWidth : '90%',
                                loop : false,
                                helpers : {
                                  title : {
                                    type: 'outside'
                                  },
                                  thumbs  : {
                                    width : 50,
                                    height  : 50
                                  }
                                }
                              });

                            } );
                           // dtb_syarat.draw(false);
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                               $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
</script>