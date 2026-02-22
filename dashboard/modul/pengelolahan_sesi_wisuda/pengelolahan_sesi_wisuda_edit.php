<?php
session_start();
include "../../inc/config.php";
session_check();
$data_edit = $db->fetch_single_row("detail_wisuda","id_detail",$_POST['id_data']);
?>
  <style type="text/css"> .datpicker {z-index: 1200 !important; } </style>
  <form id="edit_wisuda" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_action.php?act=up">
    <div class="form-group">
      <label for="nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
      <?php
        if($_SESSION['level']=='3') {
      ?>
          <input type="text" name="nim" class="form-control" value="<?=$data_edit->nim?>" readonly>
      <?php
        } else{
      ?>
          <input type="text" name="nim" class="form-control" value="<?=$data_edit->nim?>" required>
      <?php
        }
      ?>
      </div>
    </div><!-- /.form-group -->
    <div class="form-group">
      <label for="priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
          <select name="id_wisuda" data-placeholder="Pilih priode..." class="form-control chzn-select" tabindex="2" required>
           <option value="all">Semua</option>
           <?php foreach ($db->fetch_all("kelola_wisuda") as $isi) {
              if ($data_edit->id_wisuda==$isi->id_wisuda) {
                echo "<option value='$isi->id_wisuda' selected>$isi->priode</option>";
              } else {
                echo "<option value='$isi->id_wisuda'>$isi->priode</option>";
              }
           } ?>
          </select>
      </div>
    </div><!-- /.form-group -->

    <input type="hidden" name="id" value="<?=$data_edit->id_detail;?>">

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

    $("#edit_wisuda").validate({
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

          id_wisuda: {
          required: true,
          //minlength: 2
          },
        },
        messages: {

          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          id_wisuda: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        },

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
              type: "post",
              url: $(this).attr("action"),
              data: $("#edit_wisuda").serialize(),
              success: function(data) {
                  $('#modal_wisuda').modal('hide');
                  $("#loadnya").hide();
                  if (data == "good") {
                    $(".notif_top_up").fadeIn(1000);
                    $(".notif_top_up").fadeOut(1000, function(){
                      location.reload();
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
