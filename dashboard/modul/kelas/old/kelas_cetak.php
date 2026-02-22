<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
      <form id="cetak_presensi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=cetak">

              
              <div class="form-group">
                        <label for="kls_nama" class="control-label col-lg-2">Kelas <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="kls_nama" data-placeholder="Pilih Kelas ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("kelas") as $isi) {
                  echo "<option value='$isi->kelas_id'>$isi->kls_nama</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->


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

    $("#cetak_presensi").validate({
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
                type: "post",
                url: $(this).attr("action"),
                data: $("#cetak_presensi").serialize(),
                success: function(data) {
                    $('#modal_cetak_presensi').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
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
