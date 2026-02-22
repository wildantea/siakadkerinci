<?php
//session_start();
include "../../inc/config.php";
//print_r($_SESSION);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<form id="input_pendaftaran_kukerta" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=in">
                
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input id="nim" type="text" name="nim" placeholder="Nim" class="form-control" required>
          </div>
        </div><!-- /.form-group -->

        <div id="form_civitas"></div>
        
        <div class="form-group">
          <label for="Priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <select id="id_priode" onchange="pilih_lokasi(this.value)" name="id_priode" data-placeholder="Pilih Priode Kukerta ..." class="form-control chzn-select" tabindex="2"> 
               <option value="all">Semua</option>
               <?php
               foreach ($db->query("select * from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $isi) {
                  echo "<option value='$isi->id_priode'>$isi->jns_semester $isi->tahun/".($isi->tahun+1)."</option>";
               } ?>
            </select>
          </div>
        </div><!-- /.form-group -->
        
        <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-2">Lokasi <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <select id="id_lokasi" name="id_lokasi" class="form-control id_lokasi" tabindex="2">
               <option value="all">Semua</option>            
            </select>
          </div>
        </div><!-- /.form-group -->                

      <div class="form-group">
        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
        <div class="col-lg-10">
          <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
          <button type="submit" id="btn_simpan" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
        </div>
      </div><!-- /.form-group -->


</form>
<script type="text/javascript">

   function pilih_lokasi(id_lokasi){
     // alert(id_lokasi);
      $.ajax({
          type : "POST",
          url : "<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=get_lokasi",
          data : {
            id_lokasi:id_lokasi 
          },
          success : function(data) {   
          //  alert(data);   
            $(".id_lokasi").html(data); 
        }
       });
   }

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
    
    $("#input_pendaftaran_kukerta").validate({
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
                data: $("#input_pendaftaran_kukerta").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_kukerta').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
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

       $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_kukerta/get_fakultas_jurusan2.php",
      data : {nim:this.value},
      success : function(data) { 
         //alert(data);  
         if (data=='0') {
            $("#btn_simpan").hide();
           // $("#input_pendaftaran_kukerta").
            $("#input_pendaftaran_kukerta input").prop("disabled", true);
         }else{
            $("#btn_simpan").show();
         }

      }
  });

});
</script>
