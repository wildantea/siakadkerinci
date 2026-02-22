<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("matkul","id_matkul",$_POST['id_mat_syarat']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_kec" method="post" class="form-horizontal" action="<?=base_admin();?>modul/kurikulum/syarat_action.php?act=in">
            <div class="form-group">
                <label for="Nama Kecamatan" class="control-label col-lg-2">Matakuliah</label>
                <div class="col-lg-10">
                  <input type="text" value="<?=$data_edit->kode_mk.' - '.$data_edit->nama_mk;?>" class="form-control" readonly>
                  <input type="hidden" name="id_mk_prasyarat" value="<?=$data_edit->id_matkul;?>">
                  <input type="hidden" name="id_mk" value="<?=$_POST['id_mat'];?>">
                </div>
              </div><!-- /.form-group -->
            <div class="form-group">
                <label for="Nama Kecamatan" class="control-label col-lg-2">Syarat</label>
                <div class="col-lg-10">
             <select name="syarat" data-placeholder="Pilih Syarat..." class="form-control chzn-select" tabindex="2" required>
               <option value="L">Matakuliah prasyarat harus sudah lulus</option>
               <option value="S">Matakuliah prasyarat boleh diambil bersamaan</option>
              </select>
                </div>
              </div><!-- /.form-group -->
              <input type="hidden" name="id" value="<?=$data_edit->id_matkul;?>">
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  <button type="button" class="btn btn-default close_syarat">Cancel</button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">

    $(document).ready(function() {
$(".close_syarat").on("click",function(){
  $("#modal_kec").modal( 'hide' ).data( 'bs.modal', null );
});

/*    $(".close_syarat").on('click', function() {
        $('#modal_kec').modal('hide');
    });*/

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
      
    $("#edit_kec").validate({
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
                data: $("#edit_kec").serialize(),
                success: function(data) {
                    console.log(data);
                    $('#modal_kec').modal('hide').data( 'bs.modal', null );
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                               table.draw(false);
                               tables.draw(false);
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
