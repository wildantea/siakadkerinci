 <?php
include "../../../../inc/config.php";
$jadwal_kelas = $db->query("select * from view_jadwal_kelas where kelas_id=? order by id_hari asc",array('kelas_id' => $_POST['kelas_id']));
$jadwal_dosen = $db->query("select id_jadwal as jadwal_id,penanggung_jawab,dosen_ke from tb_data_kelas_dosen where kelas_id=? and nip_dosen=?",array('kelas_id' => $_POST['kelas_id'],'nip' => $_POST['nip']));
echo $db->getErrorMessage();
$explode_jadwal = array();
$penanggung_jawab = "";
foreach ($jadwal_dosen as $jados ) {
  $explode_jadwal[] = $jados->jadwal_id;
  $penanggung_jawab = $jados->penanggung_jawab;
}

$data_dosen = $db->fetchSingleRow("view_dosen",'nip',$_POST['nip']);
?>
<p>&nbsp;</p>
 <div class="alert alert-danger error_data_pengajar" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_pengajar">
          </span>
</div>
      <form method="post" class="form-horizontal" id="input_jadwal_pengajar" action="<?=base_admin();?>modul/kelas_jadwal/jadwal/dosen_pengajar/dosen_pengajar_action.php?act=update_pengajar">
        <input type="hidden" name="kelas_id" value="<?=$_POST['kelas_id'];?>">
        <input type="hidden" name="id" value="<?=$_POST['id'];?>">
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3">
          Dosen Pengajar </label>
            <div class="col-lg-7">
              <select id="pengajar" name="pengajar" data-placeholder="Pilih Dosen Pengajar..." class="form-control pengajar" tabindex="2" required="">
                <option value="<?=$data_dosen->nip;?>"><?=$data_dosen->nama_gelar;?></option>
              </select>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3 ">
          Dosen Ke </label>
            <div class="col-lg-2">
              <input type="number" class="form-control" name="dosen_ke" value="<?=$jados->dosen_ke;?>" required>
            </div>
          </div><!-- /.form-group -->

          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3">
          &nbsp;</label>
          <label for="Nama Pendaftaran" class="control-label col-lg-3" style="text-align: left;">
          Jadwal Kelas</label>
          </div>
          <?php
          if ($jadwal_kelas->rowCount()>0) {
          foreach ($jadwal_kelas as $jadwal) {
            if (in_array($jadwal->jadwal_id, $explode_jadwal)) {
              ?>
              <div class="form-group" style="margin-bottom:0">
              <label for="Nama Pendaftaran" class="control-label col-lg-3">
              &nbsp;</label>
              <div class="col-xs-9">
              <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <?=$jadwal->nama_hari;?> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?>
                <input type="checkbox" checked="" class="group-checkable" name="jadwal[]" value="<?=$jadwal->jadwal_id;?>"> <span></span>
              </label>
            </div>
            </div>
              <?php
            } else {
              ?>
<div class="form-group" style="margin-bottom:0">
              <label for="Nama Pendaftaran" class="control-label col-lg-3">
              &nbsp;</label>
              <div class="col-xs-9">
              <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <?=$jadwal->nama_hari;?> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?>
                <input type="checkbox" class="group-checkable" name="jadwal[]" value="<?=$jadwal->jadwal_id;?>"> <span></span>
              </label>
            </div>
            </div>
              <?php
            }
          }

          }
          ?>    
              </div>
          </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-3">Penanggung Jawab ?</label>
              <div class="col-lg-9">
                <input name="penanggung_jawab" class="make-switch" type="checkbox" <?=($penanggung_jawab=='Y'?'checked':'');?> data-on-text="Ya" data-off-text="Tidak">
                <span class="help-block text-aqua"><i class="fa fa-info-circle"></i> Jika penanggung jawab dipilih Ya, maka dosen bisa input nilai Mahasiswa</span>
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
    //$('#add_dosen').find('.fa').toggleClass('fa-plus fa-minus');
    $('#add_dosen').show();
    $("#dtb_dosen_pengajar").show();
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
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                            $("#form-input-jadwal-pengajar").html('');
                            $("#form-input-jadwal-pengajar").slideUp();
                            $('#add_dosen').find('.fa').toggleClass('fa-minus fa-plus ');
                            $("#dtb_dosen_pengajar").show();
                            $("#dtb_dosen_pengajar").slideDown();
                            $('#add_dosen').show();
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