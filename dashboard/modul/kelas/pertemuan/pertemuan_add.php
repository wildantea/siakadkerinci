<?php
session_start();
include "../../../inc/config.php";
$kelas_id = $_POST['kelas_id'];
$jam_mulai = '';
$jam_selesai = '';
$kelas_data = $db2->fetchCustomSingle("select * from view_jadwal_kelas where kelas_id=?",array('kelas_id' => $kelas_id));
if ($kelas_data) {
  $jam_mulai = $kelas_data->jam_mulai;
  $jam_selesai = $kelas_data->jam_selesai;
}
?>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }
  .select2-container {
width: 100% !important;
padding: 0;
}


</style>

<hr>
      <form id="input_pertemuan" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/pertemuan/pertemuan_action.php?act=in">
                      
          <div class="form-group">
              <label for="Pertemuan" class="control-label col-lg-2">Pertemuan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-1">
              <select  id="pertemuan" name="pertemuan" data-placeholder="Pilih Pertemuan ..." class="form-control select2" tabindex="2" required>
               <?php 
               for ($i=1; $i <= 16; $i++) { 
                echo "<option value='$i'>$i</option>";
               }
               ?>
              </select>
              </div>
          </div><!-- /.form-group -->
          <input type="hidden" name="kelas_id" value="<?=$kelas_id;?>">
          <div class="form-group">
                        <label for="Jenis Pertemuan" class="control-label col-lg-2">Jenis Pertemuan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-3">
            <select  id="id_jenis_pertemuan" name="id_jenis_pertemuan" data-placeholder="Pilih Jenis Pertemuan ..." class="form-control chzn-select" tabindex="2" required>
               <?php foreach ($db2->fetchAll("tb_data_jenis_pertemuan") as $isi) {
                  echo "<option value='$isi->id_jenis_pertemuan'>$isi->nama_jenis_pertemuan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Pertemuan" class="control-label col-lg-2">Tanggal Pertemuan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" value="2023-01-01" class="form-control tgl_picker_input" name="tanggal_pertemuan" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->
          <div class="form-group">
              <label for="Jam Mulai" class="control-label col-lg-2">Jam Mulai</label>
              <div class="col-lg-3">
                <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
                  <input type="text" class="form-control waktu"  name="jam_mulai" autocomplete="off"  required="" value="<?=$jam_mulai;?>">
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
              </div>
              <div class="col-lg-1" style="padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d
              </div>
              <div class="col-lg-3">
                <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
                  <input type="text" autocomplete="off" class="form-control waktu" name="jam_selesai" required="" value="<?=$jam_selesai;?>">
                  <span class="input-group-addon">
                      <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
              </div>
            </div><!-- /.form-group -->
           
              <div class="form-group">
                        <label for="Pengajar" class="control-label col-lg-2">Pengajar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-6">
            <select  id="nip_dosen" name="nip_dosen[]" data-placeholder="Pilih Pengajar ..." class="form-control select2" tabindex="2" required multiple>
               <option value=""></option>
               <?php foreach ($db2->query("select nip,nama_gelar from view_dosen_kelas where kelas_id='$kelas_id'") as $isi) {
                  echo "<option value='$isi->nip' selected>$isi->nama_gelar</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
              
              <div class="form-group" style="border-top: 1px solid #eee;padding-top: 5px;">
              <label for="Pengajar" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-3">
                <button type="button" class="btn btn-default batal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                </div>
              </div><!-- /.form-group -->
 
      </form>
      <script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
   
    $(document).ready(function() {

      $('.batal').click(function(){
        $('.button-top').show();
            $('.action-title').html('');
            $("#isi_tambah_pertemuan").html('');
            $("#isi_tambah_pertemuan").slideUp();
      });
      $('.clockpicker').clockpicker();
     $('.select2').select2();
  $(".chzn-select").chosen();
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
        
    
        $("#modal_pertemuan").scroll(function(){
          $(".tgl_picker").datepicker("hide");
          $(".tgl_picker").blur();
        });
        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
    $("#input_pertemuan").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          pertemuan: {
          required: true,
          //minlength: 2
          },
        
          id_jenis_pertemuan: {
          required: true,
          //minlength: 2
          },
        
          tanggal_pertemuan: {
          required: true,
          //minlength: 2
          },
        
          jam_mulai: {
          required: true,
          //minlength: 2
          },
        
          jam_selesai: {
          required: true,
          //minlength: 2
          },
        
          nip_dosen: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          pertemuan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_jenis_pertemuan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tanggal_pertemuan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jam_mulai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jam_selesai: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nip_dosen: {
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            $(".save-data").attr("disabled", "disabled");
                            $('#modal_pertemuan').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                              $('.button-top').show();
                              $('.action-title').html('');
                              $("#isi_tambah_pertemuan").html('');
                              $("#isi_tambah_pertemuan").slideUp();
                                dtb_pertemuan.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
