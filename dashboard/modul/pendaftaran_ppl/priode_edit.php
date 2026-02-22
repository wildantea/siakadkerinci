<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("priode_ppl","id_priode",$_POST['id_data']);
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<form id="edit_priode" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=up_priode">
      <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Nama Periode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input type="text" name="nama_periode" value="<?= $data_edit->nama_periode ?>" class="form-control" id="nama_periode">
          </div>
        </div>
                
         <div class="form-group">
          <label for="priode_muna" class="control-label col-lg-3">Priode</label>
          <div class="col-lg-9">
            <select id="priode_beasiswa" name="priode_beasiswa" data-placeholder="Pilih Priode Kukerta/PPL ..." class="form-control chzn-select" tabindex="2" required>
              <option value=""></option>
              <?php
                foreach ($db->query("select * from semester_ref sr join jenis_semester j on sr.id_jns_semester=j.id_jns_semester order by sr.id_semester desc") as $jm) {
                  if ($jm->id_semester==$data_edit->priode) {
                    echo "<option value='$jm->id_semester' selected>$jm->jns_semester $jm->tahun/".($jm->tahun+1)."</option>";
                  }else{
                    echo "<option value='$jm->id_semester'>$jm->jns_semester $jm->tahun/".($jm->tahun+1)."</option>";
                  }
                  
                }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl2" type="text" name="tgl2" autocomplete="off" placeholder="Awal Periode Kukerta" class="form-control" value="<?=$data_edit->tgl_awal;?>" required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl3" type="text" name="tgl3" autocomplete="off" placeholder="Akhir Periode Kukerta" class="form-control" value="<?=$data_edit->tgl_akhir;?>" required>
          </div>
        </div><!-- /.form-group -->   

         <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal Daftar <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl4" type="text" name="tgl_awal_daftar" autocomplete="off" placeholder="Awal Pendaftaran Kukerta" class="form-control" value="<?=$data_edit->tgl_awal_daftar;?>" required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir Daftar <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input id="tgl5" type="text" name="tgl_akhir_daftar" autocomplete="off" placeholder="Akhir Pendaftaran Kukerta" class="form-control" value="<?=$data_edit->tgl_akhir_daftar;?>"  required>
          </div>
        </div>  
         <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Awal Input Nilai <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input  type="text" name="tgl_awal_input_nilai" autocomplete="off" placeholder="Awal Input Nilai" class="form-control tgl" value="<?=$data_edit->tgl_awal_input_nilai;?>" required>
          </div>
        </div><!-- /.form-group -->                    
    
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-3">Tanggal Akhir Input Nilai <span style="color:#FF0000">*</span></label>
          <div class="col-lg-9">
            <input  type="text" name="tgl_akhir_input_nilai" autocomplete="off" placeholder="Akhir Input Nilai" class="form-control tgl" value="<?=$data_edit->tgl_akhir_input_nilai;?>"  required>
          </div> 
        </div>       

        <input type="hidden" name="id" value="<?=$data_edit->id_priode;?>">

      <div class="form-group">
        <label for="tags" class="control-label col-lg-3">&nbsp;</label>
        <div class="col-lg-9">
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
     $(".tgl").datepicker( {
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
    
    $("#edit_priode").validate({
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
            
          priode: {
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
            
          priode: {
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
                data: $("#edit_priode").serialize(),
                success: function(data) {
                    $('#modal_priode_edit').modal('hide');
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
</script>
