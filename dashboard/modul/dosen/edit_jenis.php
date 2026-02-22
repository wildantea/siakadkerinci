<?php
include "../../inc/config.php";
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data_tanggal" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_tanggal"></span>
        </div>
            <form id="edit_setting_tagihan_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/dosen/dosen_action.php?act=up_jenis">

                      <div class="form-group">
                <label for="Program Studi" class="control-label col-lg-3">Jenis Dosen <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-9">
                  <select  id="id_jenis_dosen" name="id_jenis_dosen" data-placeholder="Pilih Jenis Dosen..." class="form-control chzn-select" tabindex="2" required>
                  <?php foreach ($db->fetch_all("jenis_dosen") as $isi) {
                      echo "<option value='$isi->id_jenis_dosen'>$isi->nama_jenis</option>";
                    } ?>
                  </select>
          </div>
                      </div><!-- /.form-group -->
            </div><!-- /.form-group -->

              <input type="hidden" name="id" value="<?=$_POST['all_id'];?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">

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
        
                $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    $(document).ready(function() {
    
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
            $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_setting_tagihan_mahasiswa").validate({
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
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
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
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning_tanggal").html(data.responseText);
                  $(".error_data_tanggal").focus()
                  $(".error_data_tanggal").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning_tanggal").text(responseText[index].error_message);
                             $(".error_data_tanggal").focus()
                             $(".error_data_tanggal").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $('#modal_setting_tagihan_mahasiswa').modal('hide');
                            $('#aksi_krs option:first').prop('selected',true);
                            $(".bulk-check").prop("checked",0);
                            select_deselect('unselect');
                            $(".error_data_tanggal").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_dosen.draw();
                            });
                          } else {
                             console.log(responseText);
                             $(".isi_warning_tanggal").text(responseText[index].error_message);
                             $(".error_data_tanggal").focus()
                             $(".error_data_tanggal").fadeIn();
                          }
                    });
                }

            });
        }
    });
});
</script>
