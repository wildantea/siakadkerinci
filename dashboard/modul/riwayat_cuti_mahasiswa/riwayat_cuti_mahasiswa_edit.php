<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("cuti_mahasiswa","id_cuti",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_riwayat_cuti_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/riwayat_cuti_mahasiswa/riwayat_cuti_mahasiswa_action.php?act=up">
              
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="jenis_keluar" data-placeholder="Pilih Jenis Keluar ..." class="form-control chzn-select" tabindex="2" required>
                    <option value=""></option>
                    <?php
                      foreach ($db->fetch_all('jenis_keluar') as $isi) {
                        echo "<option name='jenis_keluar' value='$isi->id_jns_keluar'>$isi->ket_keluar</option>";
                      }
                    ?>
                  </select>
                </div>
            </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_keluar" class="control-label col-lg-2">Tanggal Keluar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl1" type="text" name="tgl_keluar" value="<?=$data_edit->tgl_keluar;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label for="tgl_keluar" class="control-label col-lg-2">Tanggal Berakhir <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input id="tgl2" type="text" name="tgl_berakhir" value="<?=$data_edit->tgl_berakhir;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_keluar" class="control-label col-lg-2">File Terupload <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" placeholder="file_sk" class="form-control" value="<?=$data_edit->file_sk?>" readonly>
                </div>
              </div><!-- /.form-group -->               
              
              <div class="form-group">
                <label for="file_sk" class="control-label col-lg-2">File </label>
                <div class="col-lg-10">
                  <input type="file" id="file" name="file" placeholder="file_sk" class="form-control">
                </div>
              </div><!-- /.form-group -->   

              <input type="hidden" name="id" value="<?=$data_edit->id_cuti;?>">

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

    $("#tgl1").datepicker({
      format: "yyyy-mm-dd",
    });

    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
    });  
    
    $("#edit_riwayat_cuti_mahasiswa").validate({
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
                data: $("#edit_riwayat_cuti_mahasiswa").serialize(),
                success: function(data) {
                    $('#modal_riwayat_cuti_mahasiswa').modal('hide');
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
