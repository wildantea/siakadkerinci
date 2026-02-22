<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("mhs_registrasi","id_reg",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_kelola_keuangan" method="post" class="form-horizontal" action="<?=base_admin();?>modul/kelola_keuangan/kelola_keuangan_action.php?act=up">
                            
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="sem_id" class="control-label col-lg-2">Tahun Ajaran</label>
                <div class="col-lg-10">
                    <select name="sem_id" id="sem_id" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
                       <option value=""></option>
                       <?php 
                         $sem = $db->query("select * from semester_ref s join jenis_semester j 
                          on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                          foreach ($sem as $isi2) {
                            if ($isi2->id_semester==$data_edit->sem_id) {
                             echo "<option value='".$isi2->id_semester."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                            }else{
                              echo "<option value='".$isi2->id_semester."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                            }
                          
                       } ?>
                      </select>
                </div>
              </div>
              
              <div class="form-inline form-group">
                <label for="total_bayar" class="control-label col-lg-2">Total Biaya <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <div class="input-group">
                    <div class="input-group-addon">Rp.</div>
                    <input id="auto" type="text" name="total_bayar" class="form-control" data-a-sep="." data-a-dec="," value="<?=$data_edit->total_bayar;?>" required>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label for="no_kwitansi" class="control-label col-lg-2">No Kwitansi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="no_kwitansi" value="<?=$data_edit->no_kwitansi;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <input type="hidden" name="id" value="<?=$data_edit->id_reg;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
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
    $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });
    $("#edit_kelola_keuangan").validate({
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
            
          nim: {
          required: true,
          //minlength: 2
          },
        
          sem_id: {
          required: true,
          //minlength: 2
          },
        
          total_bayar: {
          required: true,
          //minlength: 2
          },
        
          no_kwitansi: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          sem_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          total_bayar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          no_kwitansi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_kelola_keuangan").serialize(),
                success: function(data) {
                    $('#modal_kelola_keuangan').modal('hide');
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
