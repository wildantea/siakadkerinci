<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("periode_pembayaran","id_periode_pembayaran",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_periode_pembayaran" method="post" class="form-horizontal" action="<?=base_admin();?>modul/periode_pembayaran/periode_pembayaran_action.php?act=up">
                            
              <div class="form-group">
                <label for="Periode Bayar" class="control-label col-lg-3">Periode Bayar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-4">
                  <input type="text" readonly value="<?=ganjil_genap($data_edit->periode_bayar);?>" class="form-control" required>
                  <input type="hidden" name="periode" value="<?=$data_edit->periode_bayar;?>">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
              <label for="Tanggal Awal Bayar" class="control-label col-lg-3">Tanggal Awal Bayar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-4">
                <div class="input-group date" id="tgl1">
                    <input type="text" class="form-control" autocomplete="off" value="<?=$data_edit->tgl_mulai;?>" name="tgl_mulai" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
              <label for="Tanggal Akhir Bayar" class="control-label col-lg-3">Tanggal Akhir Bayar <span style="color:#FF0000">*</span></label>
              <div class="col-lg-4">
                <div class="input-group date" id="tgl2">
                    <input type="text" class="form-control" autocomplete="off" value="<?=$data_edit->tgl_selesai;?>" name="tgl_selesai" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

       <div class="form-group">
                        <label for="aktif" class="control-label col-lg-3">Aktif</label>
                        <div class="col-lg-9">
                          <?php if ($data_edit->is_active=="Y") {
      ?>
      <input name="is_active" class="make-switch" type="checkbox" data-on-text="Yes" data-off-text="No" checked>
      <?php
    } else {
      ?>
      <input name="is_active" class="make-switch" data-on-text="Yes" data-off-text="No" type="checkbox">
      <?php
    }?>
                        </div>
                      </div><!-- /.form-group -->
          
              <input type="hidden" name="id" value="<?=$data_edit->id_periode_pembayaran;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
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
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    
    $("#tgl1").datepicker({ 
    format: "yyyy-mm-dd",
    autoclose: true, 
    todayHighlight: true
    }).on("change",function(){
      $("#tgl1 :input").valid();
    });
    $("#tgl2").datepicker({ 
    format: "yyyy-mm-dd",
    autoclose: true, 
    todayHighlight: true
    }).on("change",function(){
      $("#tgl2 :input").valid();
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_periode_pembayaran").validate({
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
        
        rules: {
            
          periode_bayar: {
          required: true,
          //minlength: 2
          },
        
          tgl_mulai: {
          required: true,
          //minlength: 2
          },
        
          tgl_selesai: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          periode_bayar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_mulai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_selesai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
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
                            $('#modal_periode_pembayaran').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_periode_pembayaran.draw();
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
