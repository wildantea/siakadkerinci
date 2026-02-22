<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_periode_update_biodata" method="post" class="form-horizontal" action="<?=base_admin();?>modul/periode_update_biodata/periode_update_biodata_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-3">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" >
<?php
$detil_mhs = $db->fetch_custom_single("select mahasiswa.mhs_id,nim,nama,view_prodi_jenjang.jurusan from mahasiswa inner join view_prodi_jenjang
on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur WHERE nim=?",array('nim' => $data_edit->nim));
?>
          <label for="nama" class="control-label col-lg-3">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" value="<?=$detil_mhs->nama;?>" class="form-control"  readonly>
          </div>
          <div class="col-lg-5">
            <input type="text" id="jurusan" name="jurusan" value="<?=$detil_mhs->jurusan;?>" class="form-control" readonly>
          </div>
        </div>


                 <div class="form-group">
                        <label for="aktif" class="control-label col-lg-3">Status Submit</label>
                        <div class="col-lg-9">
                          <?php if ($data_edit->is_submit_biodata=="Y") {
  ?>
  <input name="is_submit_biodata" class="make-switch" type="checkbox" data-on-text="Sudah" data-off-text="Belum" checked>
  <?php
} else {
  ?>
  <input name="is_submit_biodata" class="make-switch" data-on-text="Sudah" data-off-text="Belum" type="checkbox">
  <?php
}?>
                        </div>
                      </div><!-- /.form-group -->
              
              <input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">

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

    
    
                    $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    $("#edit_periode_update_biodata").validate({
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
                            $('#modal_periode_update_biodata').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_periode_update_biodata.draw();
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
