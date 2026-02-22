<?php
session_start();
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } .chosen-container-multi .chosen-choices {
    padding: 3px;padding-left: 10px;}</style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_setting_tagihan_prodi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/setting_tagihan_prodi/setting_tagihan_prodi_action.php?act=in">
                      <div class="form-group">
                        <label for="Program Studi" class="control-label col-lg-2">Program Studi <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="kode_prodi" name="kode_prodi" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2" required>
               <?php
                                looping_prodi();
                                ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Tagihan Untuk Angkatan" class="control-label col-lg-2">Tagihan Untuk Angkatan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
<select  id="berlaku_angkatan" name="berlaku_angkatan" data-placeholder="Pilih Jenis Tagihan..." class="form-control chzn-select" tabindex="2" required>
<?php
$angkatan_exist = $db->query("select mahasiswa.mulai_smt,view_semester.angkatan from mahasiswa inner join view_semester 
on mahasiswa.mulai_smt=view_semester.id_semester
group by mahasiswa.mulai_smt
order by mulai_smt desc");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->mulai_smt'>$ak->angkatan</option>";
}
?>
                </select>
                </div>
              </div><!-- /.form-group -->
              
<div class="form-group">
                        <label for="Jenis Tagihan" class="control-label col-lg-2">Jenis Tagihan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Jenis Tagihan ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("keu_jenis_tagihan") as $isi) {
                  echo "<option value='$isi->kode_tagihan'>$isi->nama_tagihan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Nominal Tagihan" class="control-label col-lg-2">Besaran Tagihan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
<div class="input-group">
            <div class="input-group-addon">Rp.</div>
            <input id="auto" type="text" name="nominal_tagihan" class="form-control" data-a-sep="." data-a-dec="," required="">
          </div>
                 
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
        
    $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#input_setting_tagihan_prodi").validate({
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
            
          kode_prodi: {
          required: true,
          //minlength: 2
          },
        
          kode_tagihan: {
          required: true,
          //minlength: 2
          },
        
          nominal_tagihan: {
          required: true,
          //minlength: 2
          },
        
          berlaku_angkatan: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kode_prodi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_tagihan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nominal_tagihan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          berlaku_angkatan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
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
                            $('#modal_setting_tagihan_prodi').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_setting_tagihan_prodi.draw(false);
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
