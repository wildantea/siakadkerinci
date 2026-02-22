<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
  <form id="input_pengelolahan_sesi_wisuda" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_action.php?act=in">
                  
    <div class="form-group">
        <label for="nim" class="control-label col-lg-2">nim </label>
        <div class="col-lg-10">
          <input id="nim" type="text" name="nim" placeholder="nim" class="form-control" required>
        </div>
    </div><!-- /.form-group -->
      
    <div id="form_civitas"></div> 

    <div class="form-group">
      <label for="Semester" class="control-label col-lg-2">Priode</label>
      <div class="col-lg-10">
        <select id="priode_filter" class="form-control chzn-select" name="id_wisuda" data-placeholder="Pilih Semester ..." tabindex="2">
          <option value="all">Semua</option>
          <?php
            foreach ($db->fetch_all("kelola_wisuda") as $isi) {
            echo "<option value='$isi->id_wisuda'>$isi->priode</option>";
            } 
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="tags" class="control-label col-lg-2">&nbsp;</label>
      <div class="col-lg-10">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
      </div>
    </div><!-- /.form-group -->

  </form>
<script type="text/javascript">
      //hidden validate because we use chosen select
  $.validator.setDefaults({ ignore: ":hidden:not(select)" });
  $("#nim").on('input',function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/get_jurusan_fakultas.php",
        data : {nim:this.value},
        success : function(data) {
            $("#form_civitas").html(data);
            $("#form_civitas").trigger("chosen:updated");

        }
    });

  });
    
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
        
    $("#tgl1").datepicker( {
        format: "yyyy-mm-dd",
    });
    
    $("#input_pengelolahan_sesi_wisuda").validate({
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
                data: $("#input_pengelolahan_sesi_wisuda").serialize(),
                success: function(data) {
                    $('#modal_wisuda').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                      $(".notif_top").fadeIn(1000);
                      $(".notif_top").fadeOut(1000, function() {
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
