<?php
$data_edit = $db->fetch_single_row("bimbingan_dosen_pa","id",$_POST['id']);

    $mhs_data = $db->fetch_custom_single("select * from view_simple_mhs where nim=?",array('nim' => $data_edit->nim));
    $foto = $db->fetch_custom_single("select (select foto_user from sys_users where username='$dosen_id') as foto_dosen,(select foto_user from sys_users where username='$data_edit->nim') as foto_mhs"); 
    $foto_mhs = $foto->foto_mhs;

?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } 

.direct-chat-messages {
    height: 30vh;
}</style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_bimbingan_pa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/konsultasi/konsultasi_action.php?act=up_dosen">

<div class="box box-success direct-chat direct-chat-success">
            <div class="box-header with-border">
              <h3 class="box-title">Bimbingan</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">
                <!-- Message. Default to the left -->
                <div class="direct-chat-msg">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left"><?=$mhs_data->nama?></span>
                    <span class="direct-chat-timestamp pull-right"><?=tgl_time($data_edit->tanggal_tanya)?></span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img class="direct-chat-img" src="<?=$foto_mhs?>" alt="Message User Image"><!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                    <?=$data_edit->pertanyaan?>
                  </div>
                </div>
              </div>
            </div>
                     <input type="hidden" name="id" value="<?=$_POST['id']?>">
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="input-group">
                   <textarea id="msg-umum" type="text" class="form-control" name="jawaban" rows="5" placeholder="Silakan Ketika Jawaban..."><?=$data_edit->jawaban?></textarea>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-flat">Kirim Jawaban</button>
                      </span>
                </div>
              </form>
            </div>
            <!-- /.box-footer-->
          </div>

     
                      
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript">
    $(document).ready(function() {
             $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });  
    
    
    $("#input_bimbingan_pa").validate({
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
            } else if (element.attr("type") == "checkbox") {
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
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $('#modal_gedung_ref').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                bimbingan.draw();
                            });
                          } else {
                             console.log(responseText);
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          }
                    });
                }

            });
        }
    });
});
</script>
