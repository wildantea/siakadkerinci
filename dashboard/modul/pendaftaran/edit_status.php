<?php
include "../../inc/config.php";
?>
 <form method="post" class="form-horizontal" id="change_status" action="<?=base_admin();?>modul/pendaftaran/pendaftaran_action.php?act=change_status_massal">
     
<input type="hidden" name="id_pendaftaran" value="<?=$_POST['data_ids'];?>">
            
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-2">Status</label>
                        <div class="col-lg-9">
        <select name="status" id="status_pendaftaran" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="" >
          <?php
          $array_status = array(1 => 'Sudah Acc',0 => 'Belum Acc',2 => 'Ditolak', 3 => 'Tidak Lulus',4 => 'Selesai/Lulus');
          foreach ($array_status as $status => $label) {
              echo "<option value='$status'>$label</option>";
          }
          ?>
              </select>
                        </div>
            </div>
              <div class="form-group alasan-ditolak" style="display: none">
                <label for="nim" class="control-label col-lg-2">Alasan Ditolak</label>
                <div class="col-lg-10">
                  <input type="text" name="ket_ditolak" placeholder="Isi kenapa ditolak, sehingga mahasiswa bisa memperbaikti" class="form-control isi-ditolak">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>

                  </div>
                </div>
              </div><!-- /.form-group -->
</form>

<div class="callout callout-info">
                <h4>Keterangan Status :</h4>

                <p>1. Sudah Acc : Gunakan status ini jika pendaftaran sudah memenuhi persyaratan<br>
                  2. Belum Acc : Status Default data pendaftaran Mahasiswa<br>
                  3. Tolak : Tolak Pendaftaran Mahasiswa jika ada kekurangan/salah syarat dll, sertakan keterangan admin untuk diketahui mahasiswa<br>
                  4 : Tidak Lulus : Gunakan status ini jika mahasiswa gagal / tidak lulus sidang/seminar <br>
                5 : Selesai/Lulus : Gunakan status ini jika pendaftaran telah selesai atau mahasiswa Lulus dalam Sidang / Seminar</p>
              </div>

<div class="modal right fade" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><button type="button" class="close close-dosen-pengajar" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pilih Dosen Pengajar</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

  <!-- Add fancyBox - thumbnail helper (this is optional) -->
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.css?v=2.1.7" />
  <script type="text/javascript" src="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.js?v=2.1.7"></script>

<script type="text/javascript">
          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#change_status").validate({
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
            
          penanggung_jawab: {
          required: true,
          //minlength: 2
          }
        },
         messages: {
            
          penanggung_jawab: {
          required: "Pilih Penanggung Jawab",
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
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $("#modal_pendaftaran").modal( 'hide' ).data( 'bs.modal', null );
                            $('.error_data').hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                              dtb_pendaftaran.draw(false);
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                               $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
</script>
