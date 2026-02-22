<?php
include "../../inc/config.php";
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_mahasiswa_pindah" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=in">
                      
              <div class="form-group">
                <label for="Nim Lama" class="control-label col-lg-2">Nim Lama </label>
                <div class="col-lg-10">
                  <input type="text" name="nim_lama" placeholder="Nim Lama" class="form-control" >
                </div>
              </div><!-- /.form-group --> 
              
              <div class="form-group">
                <label for="Nama Mahasiswa" class="control-label col-lg-2">Nama Mahasiswa </label>
                <div class="col-lg-10">
                  <input type="text" name="nama_mhs" placeholder="Nama Mahasiswa" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="NIM Baru" class="control-label col-lg-2">NIM Baru </label>
                <div class="col-lg-10">
                  <input type="text" name="nim_baru" placeholder="NIM Baru" class="form-control" >
                </div>
              </div>
              <div class="form-group">
                <label for="NIM Baru" class="control-label col-lg-2">Angkatan Lama </label>
                <div class="col-lg-10">
                  <input type="text" name="angkatan_lama" placeholder="Angkatan Lama (contoh : 20201)" class="form-control" >
                </div>
              </div>
              <div class="form-group">
                <label for="NIM Baru" class="control-label col-lg-2">Angkatan Baru </label>
                <div class="col-lg-10">
                  <input type="text" name="angkatan_baru" placeholder="Angkatan Baru (contoh : 20211)" class="form-control" >
                </div>
              </div>

            <div class="form-group"> 
    <label for="Kampus Lama" class="control-label col-lg-2">Jenis Pindah</label>
    <div class="col-lg-10">
        <label><input type="radio" name="jenis_pindah" class="show-kampus" value="internal">&nbsp;Internal&nbsp;</label>
        <label><input type="radio" name="jenis_pindah" class="show-kampus" value="eksternal">&nbsp;Eksternal&nbsp;</label> 
    </div>
</div><!-- /.form-group -->


               <div id="form_kampus_lama" style="display: none">
              <div class="form-group" >
                  <label for="Jenis Pendaftaran" class="control-label col-lg-2">Kampus Lama</label>
                  <div class="col-lg-10">
                    <select name="kode_pt" id="kode_pt" data-placeholder="Pilih Asal Perguruan Tinggi ..." class="form-control kampus" tabindex="2">

        </select>
                  </div>
                </div>


                   <div class="form-group">
                      <label for="Kampus Baru" class="control-label col-lg-2">Kampus Baru </label>
                      <div class="col-lg-10">
                          <select name="kode_pt_baru" id="kode_pt_baru" data-placeholder="Pilih Asal Perguruan Tinggi ..." class="form-control kampus" tabindex="2" >
                                  <?php
                                    $kampuss = $db->query("select * from satuan_pendidikan where npsn='202036'");
                                    foreach ($kampuss as $kampus) {
                                        echo "<option value='$kampus->npsn' selected>$kampus->nm_lemb</option>";
                                    }
                                     
                                  ?>

                      </select>
                      </div>
                    </div><!-- /.form-group -->


              </div>

<!--               <div id="form_kampus_lama" style="display: none">
                <div class="form-group" >
                <label for="Kampus Lama" class="control-label col-lg-2">Kampus Lama </label>
                <div class="col-lg-10">
                  <input type="text" name="kampus_lama" placeholder="Kampus Lama" class="form-control" >
                </div>
              </div>
              
              <div class="form-group">
                <label for="Kampus Baru" class="control-label col-lg-2">Kampus Baru </label>
                <div class="col-lg-10">
                  <input type="text" name="kampus_baru" placeholder="Kampus Baru" class="form-control" >
                </div>
              </div>
              </div>
               -->
              
              <div class="form-group">
                <label for="Jurusan Lama" class="control-label col-lg-2">Jurusan Lama </label>
                <div class="col-lg-10">
                  <input type="text" name="jurusan_lama" placeholder="Jurusan Lama" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Jurusan Baru" class="control-label col-lg-2">Jurusan Baru <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="jurusan_baru" name="jurusan_baru" data-placeholder="Pilih Jurusan Baru ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Pindah" class="control-label col-lg-2">Tanggal Pindah </label>
              <div class="col-lg-3">
                <div class="input-group date" id="tgl1">
                    <input type="text" class="form-control" name="tgl_pindah"  />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="NO SK" class="control-label col-lg-2">NO SK </label>
                <div class="col-lg-10">
                  <input type="text" name="no_sk" placeholder="NO SK" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                      

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->
              <div class="alert alert-warning">
                <b>* Catatan</b><br>
              <strong>Data mahasiswa pindahan otomatis akan masuk ke data mahasiswa tetapi belum lengkap, silahkan melengkapi data mahasiswa di menu mahasiswa</strong>
              </div>
              

      </form>
<script type="text/javascript">


    $(document).ready(function() {



        $('.show-kampus').on('click', function() {
            let selectedValue = $(this).val(); // Get the value of the selected radio button
            if (selectedValue == 'eksternal') {
                $("#form_kampus_lama").show(); // Show the form for external transfer
                $('#kode_pt').prop("required",true);
            } else {
                $("#form_kampus_lama").hide(); // Hide the form for internal transfer
                $('#kode_pt').prop("required",false);
            }
            console.log("Selected value: " + selectedValue); // Log the selected value
        });
    });


  $(document).ready(function() {
       $( ".kampus" ).select2({
    allowClear: true,
  width: "100%",
  ajax: {
    url: '<?=base_admin();?>modul/mahasiswa_pindah/get_pt.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Asal Kampus"
});
     });

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
      
    
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    
    $("#tgl1").datepicker({ 
    format: "yyyy-mm-dd",
    autoclose: true, 
    todayHighlight: true
    }).on("change",function(){
      $("#tgl1 :input").valid();
    });
    $("#tgl1").datepicker({ 
    format: "yyyy-mm-dd",
    autoclose: true, 
    todayHighlight: true
    }).on("change",function(){
      $("#tgl1 :input").valid();
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#input_mahasiswa_pindah").validate({
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
            } else if (element.hasClass("select2-hidden-accessible")) {
               element.parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          jurusan_baru: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          jurusan_baru: {
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
                            $('#modal_mahasiswa_pindah').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_mahasiswa_pindah.draw();
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
