<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<form id="input_lokasi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=in_lokasi">
                
        <div class="form-group">
          <label for="Nim" class="control-label col-lg-2">Lokasi Kukerta<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="text" name="lokasi" placeholder="Lokasi Kukerta" class="form-control" required>
          </div>
        </div>

         <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-2">Periode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <select id="id_periode" name="id_periode" data-placeholder="Pilih Periode..." class="form-control chzn-select" tabindex="2">
               <option value="all">Semua</option>
               <?php
               foreach ($db->query("select * from priode_kkn order by priode desc") as $isi) {
                  echo "<option value='$isi->id_priode'>$isi->nama_periode</option>";
               } ?>
            </select>
          </div>        
        </div> 

        <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-2">DPL <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <select id="id_dpl" name="id_dpl" data-placeholder="Pilih Lokasi Kukerta ..." class="form-control chzn-select" tabindex="2">
               <option value="all">Semua</option>
               <?php
               foreach ($db->fetch_all("dosen") as $isi) {
                  echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
               } ?>
            </select>
          </div>       
        </div>

          <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-2">DPL 2<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <select id="dpl2" name="dpl2" data-placeholder="Pilih Lokasi Kukerta ..." class="form-control chzn-select" tabindex="2">
               <option value="all">Semua</option>
               <?php
               foreach ($db->fetch_all("dosen") as $isi) {
                  echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
               } ?>
            </select>
          </div>       
        </div>

        <div class="form-group">
          <label for="Nim" class="control-label col-lg-2">Kuota Utama<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="number" name="kuota" placeholder="Kuota Kukerta/PPL" class="form-control" required>
          </div>
        </div>

         <div class="form-group">
          <label for="Nim" class="control-label col-lg-2">Kuota Laki-Laki<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="number" name="kuota_l" placeholder="Kuota Laki-laki" class="form-control" required>
          </div>
        </div>

         <div class="form-group">
          <label for="Nim" class="control-label col-lg-2">Kuota Perempuan<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <input type="number" name="kuota_p" placeholder="Kuota Perempuan" class="form-control" required>
          </div>
        </div>
       

       <div class="form-group">
          <label for="Lokasi" class="control-label col-lg-2">Kouta Jurusan <span style="color:#FF0000">*</span></label>
          <div class="col-lg-10">
            <table class="table">
              <thead>
                <tr>
                  <th>Jurusan</th>
                  <th>Kuota</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $qj = $db->query("select kode_jur,nama_jur from jurusan");
                foreach ($qj as $kj) {
                  echo "<tr>
                          <td>$kj->kode_jur $kj->nama_jur</td>
                          <td><input type='text' class='form-control' name='kuota_jur_$kj->kode_jur'></td>
                       </tr>";
                }
                ?>
              </tbody>
            </table>
          </div>       
        </div>

        

        <div class="form-group" style="display: none">
          <label for="Nim" class="control-label col-lg-2">Jenis Kegiatan<span style="color:#FF0000">*</span></label>
          <div class="col-lg-10"> 
            <input type="radio" name="ket" value="kukerta"> Kukerta &nbsp;
            <input type="radio" name="ket" value="PPL"> PPL &nbsp;
          </div>
        </div>


       

        <div class="form-group">
          <div class="col-lg-12">
            <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?> Lokasi</button>
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

    $("#tgl1").datepicker( {
        format: "yyyy-mm",
    });

    $("#tgl2").datepicker( {
        format: "yyyy-mm-dd",
    });

    $("#tgl3").datepicker( {
        format: "yyyy-mm-dd",
    });

    //trigger validation onchange
    $('select').on('change', function() {
        $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });       
    
    $("#input_lokasi").validate({
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
            
          lokasi: {
          required: true,
          //minlength: 2
          },
        
          id_dpl: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          lokasi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_dpl: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
       
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_lokasi").serialize(),
                success: function(data) {
                    $('#modal_lokasi').modal('hide');
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
