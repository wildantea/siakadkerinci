<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<form id="input_priode" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=in_priode">
      <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Nama Periode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input type="text" name="nama_periode" class="form-control" id="nama_periode">
          </div>
        </div>
                
        <div class="form-group">
          <label for="priode_muna" class="control-label col-lg-3">Priode</label>
          <div class="col-lg-9">
            <select id="priode_beasiswa" name="priode_beasiswa" data-placeholder="Pilih Priode Kukerta/PPL ..." class="form-control chzn-select" tabindex="2" required>
              <option value=""></option>
              <?php
                foreach ($db->query("select * from semester_ref sr join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $jm) {
                  echo "<option value='$jm->id_semester'>$jm->jns_semester $jm->tahun/".($jm->tahun+1)."</option>";
                }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl2" type="text" name="tgl2" placeholder="Awal Pendaftaran Kukerta" class="form-control" required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl3" type="text" name="tgl3" placeholder="Akhir Pendaftaran Kukerta" class="form-control" required>
          </div>
        </div><!-- /.form-group -->       
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal Daftar <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl4" type="text" name="tgl_awal_daftar" autocomplete="off" placeholder="Awal Pendaftaran Kukerta" class="form-control"  required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir Daftar <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl5" type="text" name="tgl_akhir_daftar" autocomplete="off" placeholder="Akhir Pendaftaran Kukerta" class="form-control"  required>
          </div>
        </div>  

        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal Input Nilai <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input  type="text" name="tgl_awal_input_nilai" autocomplete="off" placeholder="Awal Input Nilai" class="form-control tgl" required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir Input Nilai <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input  type="text" name="tgl_akhir_input_nilai" autocomplete="off" placeholder="Akhir Input Nilai" class="form-control tgl"  required>
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
    $(document).ready(function() {
    
    //chosen select
    $(".chzn-select").chosen();
    $(".chzn-select-deselect").chosen({
        allow_single_deselect: true
    });

    $("#tgl2").datepicker( {
        format: "yyyy-mm-dd",
    });

    $("#tgl3").datepicker( {
        format: "yyyy-mm-dd",
    });
      $("#tgl4").datepicker( {
        format: "yyyy-mm-dd",
    });
      $("#tgl5").datepicker( {
        format: "yyyy-mm-dd",
    });

    //trigger validation onchange
    $('select').on('change', function() {
        $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });       
    
    $("#input_priode").validate({
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
            
          priode_beasiswa: {
          required: true,
          //minlength: 2
          },
        
          tgl2: {
          required: true,
          //minlength: 2
          },
        
          tgl3: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          priode_beasiswa: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl2: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl3: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_priode").serialize(),
                success: function(data) {
                    $('#modal_priode').modal('hide');
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
