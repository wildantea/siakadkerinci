<?php
session_start();
include "../../inc/config.php";
session_check();
$data_edit = $db->fetch_single_row("kkn","id_kkn",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_pendaftaran_kukerta" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                <?php
                  if($_SESSION['level']=='3'){
                ?>
                    <input id="nim" type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required readonly>
                <?php
                  }else {
                ?>
                    <input id="nim" type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" required>
                <?php
                  }
                ?>
                </div>
              </div><!-- /.form-group -->
              
              <div id="form_civitas"></div>

              <div class="form-group">
                <label for="Priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select id="id_priode" name="id_priode" data-placeholder="Pilih Priode Kukerta ..." class="form-control chzn-select" tabindex="2">
                     <option value="all">Semua</option>
                     <?php
                     foreach ($db->query("select * from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
                        if($isi->id_priode == $data_edit->id_priode) {
                          echo "<option value='$isi->id_priode' selected>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
                        } else{
                          echo "<option value='$isi->id_priode'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
                        }
                     } ?>
                  </select>
                </div>
              </div><!-- /.form-group -->
        
              
              <div class="form-group">
                <label for="Lokasi" class="control-label col-lg-2">Lokasi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select id="id_lokasi" name="id_lokasi" data-placeholder="Pilih Lokasi Kukerta ..." class="form-control chzn-select" tabindex="2">
                     <option value="all">Semua</option>
                     <?php
                     foreach ($db->fetch_all("lokasi_kkn") as $isi) {
                        if($data_edit->id_lokasi == $isi->id_lokasi) {
                          echo "<option value='$isi->id_lokasi' selected>$isi->nama_lokasi</option>";
                        }else {
                          echo "<option value='$isi->id_lokasi'>$isi->nama_lokasi</option>";
                        }
                     } ?>
                  </select>
                </div>
              </div><!-- /.form-group -->
        
              
              <input type="hidden" name="id" value="<?=$data_edit->id_kkn;?>">
              <input type="hidden" name="kode_jur" value="<?=$data_edit->kode_jur;?>">


              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
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
    
    $("#edit_pendaftaran_kukerta").validate({
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
        
          id_priode: {
          required: true,
          //minlength: 2
          },
        
          id_lokasi: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_priode: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_lokasi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        }, 
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_pendaftaran_kukerta").serialize(),
               success: function(data) { 
            $('#modal_pendaftaran_kukerta').modal('hide');
            $("#loadnya").hide();
            if (data == "good") {
              $(".notif_top").fadeIn(1000);
              $(".notif_top").fadeOut(1000, function() {
               // location.reload();
              });
            }  
            // else if (data == "die") {
            //   $("#isi_informasi").html(data);
            //   $("#informasi").modal("show");
            // } 
            else {
              $("#modal_pendaftaran_kukerta").modal("hide");
              $("#respon_kukerta").html(data);
              $("#modal_kukerta").modal("show");
                        //$(".errorna").fadeIn();
                        // $(".notif_top").fadeIn(1000);
                        // $(".notif_top").fadeOut(1000, 
                        //   function() {
                        //    // location.reload();
                        //   });
                      }
                    }
            });
        }
    });
});
$("#nim").on('input',function(){

      $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_kukerta/get_fakultas_jurusan.php",
      data : {nim:this.value},
      success : function(data) {
          $("#form_civitas").html(data);
          $("#form_civitas").trigger("chosen:updated");

      }
  });

});
</script>
