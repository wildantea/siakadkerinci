<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("ppl","id",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="set_pendaftaran_ppl" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=set">
                            
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">Pembimbing Kampus <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="pembimbing_kam" data-placeholder="Pilih Pembimbing Kampus ..." class="form-control chzn-select" tabindex="2">
                     <option value="all">Semua</option>
                     <?php
                       foreach ($db->fetch_all("dosen") as $isi) {
                          if($isi->id_dosen == $data_edit->pembimbing_kam){
                            echo "<option value='$isi->id_dosen' selected>$isi->nama_dosen</option>";
                          }else {
                            echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                          }
                       } 
                     ?>
                  </select>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="judul_kp" class="control-label col-lg-2">Pembimbing Luar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                    if($data_edit->pembimbing_per != NULL) {
                      
                  ?>
                    <input type="text" name="pembimbing_per" placeholder="Pembibing Perusahaan" class="form-control" value="<?=$data_edit->pembimbing_per;?>" required>
                  <?php
                    }else {
                  ?>
                    <input type="text" name="pembimbing_per" placeholder="Pembibing Perusahaan" class="form-control" required>
                  <?php
                    }
                  ?>                    
                </div>
              </div><!-- /.form-group -->
          
              <input type="hidden" name="id" value="<?=$data_edit->id;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary">Updated Pembimbing</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    $(document).ready(function() {
    
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
    
    $("#set_pendaftaran_ppl").validate({
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
            
          pembimbing_kam: {
          required: true,
          //minlength: 2
          },
        
          pembimbing_per: {
          required: true,
          //minlength: 2
          }
        
        },
         messages: {
            
          pembimbing_kam: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          pembimbing_per: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#set_pendaftaran_ppl").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_ppl_set').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                               dataTable.draw(false);
                            });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
