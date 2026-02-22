 <?php
include "../../../../inc/config.php";
$jadwal_kelas = $db->query("select * from view_jadwal_kelas where kelas_id=? order by id_hari asc",array('kelas_id' => $_POST['kelas_id']));
$error_dosen = "";
if ($jadwal_kelas->rowCount()<1) {
  $error_dosen = "Silakan Tambahkan terlebih dahulu Jadwal Kelas";
  ?>
   <p>&nbsp;</p>
   <div class="alert alert-danger error_data_pengajar">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_pengajar">
            <?=$error_dosen;?>
          </span>
</div>
<?php
exit();
}
?>
<p>&nbsp;</p>
 <div class="alert alert-danger error_data_pengajar" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_pengajar">
          </span>
</div>
      <form method="post" class="form-horizontal" id="input_jadwal_pengajar" action="<?=base_admin();?>modul/kelas_jadwal/jadwal/dosen_pengajar/dosen_pengajar_action.php?act=insert_pengajar">
        <input type="hidden" name="kelas_id" value="<?=$_POST['kelas_id'];?>">
        <input type="hidden" name="sem_id" value="<?=$_POST['sem_id'];?>">
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3">
          Dosen Pengajar </label>
            <div class="col-lg-7">
              <select id="pengajar" name="pengajar" data-placeholder="Pilih Dosen Pengajar..." class="form-control pengajar" tabindex="2" required="">
              </select>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3 ">
          Dosen Ke </label>
            <div class="col-lg-2">
              <input type="number" class="form-control" name="dosen_ke" required>
            </div>
          </div><!-- /.form-group -->

          <?php
          if ($jadwal_kelas->rowCount()>0) {
         
            ?>
          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-3">Pilih Jadwal</label>
              <div class="col-lg-5">
          <?php
          foreach ($jadwal_kelas as $jadwal) {
            ?>
<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <?=$jadwal->nama_hari;?> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?>
  <input type="checkbox" class="group-checkable check-jadwal" name="jadwal[]" value="<?=$jadwal->jadwal_id;?>"> <span></span>
</label>
            <?php
          }

          }
          ?>    
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-3">Penanggung Jawab ?</label>
              <div class="col-lg-5">
                <input name="penanggung_jawab" class="make-switch" type="checkbox" checked data-on-text="Ya" data-off-text="Tidak">
              </div>
          </div><!-- /.form-group -->

            <div class="form-group">
              <div class="col-lg-12">
                <div class="modal-footer"> 
                <button type="button" class="btn btn-default cancel-modal-pengajar-add"><i class="fa fa-close"></i> Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
              </div>
            </div><!-- /.form-group -->
</form>
<script type="text/javascript">

  $('.cancel-modal-pengajar-add').click(function(){
    $("#form-input-jadwal-pengajar").html('');
    $("#form-input-jadwal-pengajar").slideUp();
    $('#add_dosen').find('.fa').toggleClass('fa-plus fa-minus');
    $("#dtb_dosen_pengajar").slideDown();
  });

$( ".pengajar" ).select2({
  ajax: {
    url: '<?=base_admin();?>modul/kelas_jadwal/jadwal/dosen_pengajar/data_dosen.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama Dosen",
  allowClear: true,
  width: "100%",
});
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

    $("#input_jadwal_pengajar").validate({
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
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            
          "jadwal[]": {
          required: true,
          minlength: 1
          }
        
        },
         messages: {
            
          "jadwal[]": {
          required: "Pilih Jadwal Minimal 1",
          //minlength: "Your username must consist of at least 2 characters"
          }
        
        },
        
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: 'json',
                type : 'post',
                error: function(data ) { 
                  $("#loadnya").hide();
                  $(".isi_warning_pengajar").html(data.responseText);
                  $(".error_data_pengajar").focus()
                  $(".error_data_pengajar").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_pengajar').text(responseText[index].error_message);
                             $('.error_data_pengajar').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_pengajar').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_pengajar').hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                            $("#form-input-jadwal-pengajar").html('');
                            $("#form-input-jadwal-pengajar").slideUp();
                            $('#add_dosen').find('.fa').toggleClass('fa-plus fa-minus');
                            $("#dtb_dosen_pengajar").show();
                              dtb_dosen_pengajar.draw(false);
                              dtb_kelas_jadwal.draw(false);
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning_pengajar').text(responseText[index].error_message);
                               $('.error_data_pengajar').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_pengajar').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
</script>