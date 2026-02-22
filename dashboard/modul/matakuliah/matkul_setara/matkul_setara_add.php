<?php
session_start();
include "../../../inc/config.php";
$id_kur = $db2->fetchSingleRow("matkul","id_matkul",$_POST['id_matkul'])->kur_id;
//get kode prodi
$kode_prodi = $db2->fetchSingleRow("kurikulum","kur_id",$id_kur)->kode_jur;
$all_setara = array();
$all_setara[] = $_POST['id_matkul'];
//setara saat ini
$setaras = $db2->query("select id_matkul_baru as id_matkul_setara from matkul_setara where id_matkul_lama=?",array('id_matkul_lama' => $_POST['id_matkul']));
if ($setaras->rowCount()>0) {
  foreach ($setaras as $setara) {
    $all_setara[] = $setara->id_matkul_setara;
  }
}

$id_mk_setara = implode(",", $all_setara);

?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_input_setara" method="post" action="<?=base_admin();?>modul/matakuliah/matkul_setara/matkul_setara_action.php?act=in">
                      <div class="form-group">
                        <label for="Matakuliah" class="control-label">Nama Kurikulum </label>
            <select id="kurikulum" name="kurikulum" data-placeholder="Pilih Matakuliah ..." class="form-control chzn-select" tabindex="2" required="">
               <option value=""></option>
               <?php foreach ($db2->query("select * from kurikulum where kode_jur=? and kur_id!='$id_kur'",array('kode_jur' => $kode_prodi)) as $isi) {
                  echo "<option value='$isi->kur_id'>$isi->nama_kurikulum</option>";
               } ?>
              </select>
                      </div><!-- /.form-group -->

              <input type="hidden" name="id_matkul" value="<?=$_POST['id_matkul'];?>">

            <div class="form-group">
                <label for="setara" class="control-label">Matakuliah</label>
                  <select name="id_matkul_setara" id="id_matkul_setara" data-placeholder="Pilih matakuliah ..." class="form-control chzn-select" tabindex="2" >     
                  </select>
            </div><!-- /.form-group -->
            <div class="form-group">
              <label for="setara" class="control-label">Bolak - Balik ?</label><br>
              <input name="bolak_balik" class="make-switch" type="checkbox" data-on-text="Ya" data-off-text="Tidak">
          </div><!-- /.form-group -->
              <div class="form-group">
                <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
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
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      //chosen select
      $(".chzn-select").chosen({width: "100%"});
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
        
    
    $("#input_input_setara").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(500);
                            $(".notif_top").fadeOut(500, function() {
                                   dtb_matkul.ajax.reload(function()
                              {
                                 $('#dtb_matkul tbody #mat-<?=$_POST['id_matkul'];?>').addClass('DTTT_selected');
                              }
                              ,false);
                                dtb_setara.ajax.reload();
                                $('#add-setara').find('.fa').toggleClass('fa-minus fa-plus');
                                $("#input_setara_form").html('');
                                $("#input_setara_form").slideUp();
                            });
                          }
                    });
                }

            });
        }
    });
});
                  $("#kurikulum").change(function(){

                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/matakuliah/matkul_setara/get_matkul.php",
                        data : {kur_id:this.value,id_mat_setara:'<?=$id_mk_setara;?>'},
                        success : function(data) {
                            $("#id_matkul_setara").html(data);
                            $("#id_matkul_setara").trigger("chosen:updated");

                        }
                    });

                  });

</script>
