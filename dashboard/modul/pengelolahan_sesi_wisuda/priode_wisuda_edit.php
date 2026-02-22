<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("kelola_wisuda","id_wisuda",$_POST['id_data']);
?>
  <style type="text/css"> .datpicker {z-index: 1200 !important; } </style>
  <form id="edit_wisuda" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_action.php?act=up_priode">
    <div class="form-group">
      <label for="priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input id="tgl2" type="text" name="priode" class="form-control" value="<?=$data_edit->priode?>">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="nama" class="control-label col-lg-2">Nama <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input type="text" name="nama" class="form-control" value="<?=$data_edit->nama_wisuda?>">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="tempat" class="control-label col-lg-2">Tempat <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input type="text" name="tempat" class="form-control" value="<?=$data_edit->tempat?>">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="biaya" class="control-label col-lg-2">Biaya <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input type="text" name="biaya" class="form-control" value="<?=$data_edit->biaya?>">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="kuota" class="control-label col-lg-2">Kuota <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input type="text" name="kuota" class="form-control" value="<?=$data_edit->kuota?>">
      </div>
    </div><!-- /.form-group -->

    <div class="form-group">
      <label for="tanggal" class="control-label col-lg-2">Tanggal <span style="color:#FF0000">*</span></label>
      <div class="col-lg-10">
        <input id="tgl1" type="text" name="tanggal" class="form-control" value="<?=$data_edit->tanggal?>">
      </div>
    </div><!-- /.form-group -->

    <input type="hidden" name="id" value="<?=$data_edit->id_wisuda;?>">

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

    $("#tgl1").datepicker( {
        format: "yyyy-mm-dd",
    });

    $("#tgl2").datepicker({
        format: "yyyy-mm",
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
          priode: {
          required: true,
          //minlength: 2
          },

          nama: {
          required: true,
          //minlength: 2
          },
          tempat: {
          required: true,
          //minlength: 2
          },
          biaya: {
          required: true,
          //minlength: 2
          },
          kuota: {
          required: true,
          //minlength: 2
          },
          tanggal: {
          required: true,
          //minlength: 2
          },
        },
        messages: {

          priode: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },

          nama: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
          tempat: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
          biaya: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
          kuota: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
          tanggal: {
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
