<?php
session_start();
include "../../../inc/config.php";
$id_kur = $db2->fetchSingleRow("matkul","id_matkul",$_POST['id_matkul'])->kur_id;
$all_syarat = array();
$all_syarat[] = $_POST['id_matkul'];
//syarat saat ini
$syarats = $db2->query("select id_mk_prasyarat from prasyarat_mk where id_mk=?",array('id_mk' => $_POST['id_matkul']));
if ($syarats->rowCount()>0) {
  foreach ($syarats as $syarat) {
    $all_syarat[] = $syarat->id_mk_prasyarat;
  }
}

$id_mk_syarat = implode(",", $all_syarat);

?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_input_syarat" method="post" action="<?=base_admin();?>modul/matakuliah/matkul_prasyarat/matkul_prasyarat_action.php?act=in">
                      <div class="form-group">
                        <label for="Matakuliah" class="control-label">Nama Matakuliah </label>
            <select id="id_mk_prasyarat" name="id_mk_prasyarat" data-placeholder="Pilih Matakuliah ..." class="form-control chzn-select" tabindex="2" required="">
               <option value=""></option>
               <?php foreach ($db2->query("select * from matkul where kur_id=? and id_matkul not in($id_mk_syarat)",array('kur_id' => $id_kur)) as $isi) {
                  echo "<option value='$isi->id_matkul'>$isi->kode_mk - $isi->nama_mk</option>";
               } ?>
              </select>
                      </div><!-- /.form-group -->
              <input type="hidden" name="id_mk" value="<?=$_POST['id_matkul'];?>">

            <div class="form-group">
                <label for="Syarat" class="control-label">Syarat </label>
                  <select name="syarat" id="syarat" data-placeholder="Pilih Syarat ..." class="form-control" tabindex="2" >     
                    <option value='L'>Matakuliah prasyarat harus sudah lulus</option>
                    <option value='A'>Matakuliah prasyarat boleh diambil saja</option>
                  </select>
            </div><!-- /.form-group -->
              <div class="form-group">
                <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
              </div><!-- /.form-group -->
 
      </form>
<script type="text/javascript">
    
    $(document).ready(function() {
    
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
        
    
    $("#input_input_syarat").validate({
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
                                 //$('#dtb_matkul tbody #mat-<?=$_POST['id_matkul'];?>').addClass('DTTT_selected');
                              }
                              ,false);
                                dtb_syarat.ajax.reload();
                                $('#add-syarat').find('.fa').toggleClass('fa-minus fa-plus');
                                $("#input_syarat_form").html('');
                                $("#input_syarat_form").slideUp();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
