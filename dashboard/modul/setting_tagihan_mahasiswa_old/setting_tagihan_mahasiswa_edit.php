<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("keu_tagihan_mahasiswa","id",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_setting_tagihan_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" >
<?php
$detil_mhs = $db->fetch_custom_single("select mahasiswa.mhs_id,nim,nama,view_prodi_jenjang.jurusan from mahasiswa inner join view_prodi_jenjang
on mahasiswa.jur_kode=view_prodi_jenjang.kode_jur WHERE nim=?",array('nim' => $data_edit->nim));
?>
          <label for="nama" class="control-label col-lg-2">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" value="<?=$detil_mhs->nama;?>" class="form-control"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan" name="jurusan" value="<?=$detil_mhs->jurusan;?>" class="form-control" readonly>
          </div>
        </div>
<div class="form-group">
                        <label for="Periode Pembayaran" class="control-label col-lg-2">Periode Pembayaran <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-8">
              <select  id="periode" name="periode" data-placeholder="Pilih Periode Pembayaran..." class="form-control" tabindex="2" readonly>
               <?php foreach ($db->fetch_all("view_semester") as $isi) {

                  if ($data_edit->periode==$isi->id_semester) {
                    echo "<option value='$isi->id_semester' selected>$isi->tahun_akademik</option>";
                  }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                        <label for="Kategori Biaya" class="control-label col-lg-2">Kategori Biaya <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select  id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Kategori Biaya..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php 
               $kode_tagihan = $db->fetch_single_row("keu_tagihan","id",$data_edit->id_tagihan_prodi);
               foreach ($db->fetch_all("keu_jenis_tagihan") as $isi) {

                  if ($kode_tagihan->kode_tagihan==$isi->kode_tagihan) {
                    echo "<option value='$isi->kode_tagihan' selected>$isi->nama_tagihan</option>";
                  } else {
                  echo "<option value='$isi->kode_tagihan'>$isi->nama_tagihan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

              <input type="hidden" name="id" value="<?=$data_edit->id;?>">
              <input type="hidden" name="id_tagihan_prodi" value="<?=$data_edit->id_tagihan_prodi;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
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
      
    $("#edit_setting_tagihan_mahasiswa").validate({
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
        
          kode_tagihan: {
          required: true,
          //minlength: 2
          },
        
          periode: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "Isi Nim atau Cari",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_tagihan: {
          required: "Pilih Kelompok Biaya",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          periode: {
          required: "Pilih Periode Pembayaran",
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
                            $('#modal_setting_tagihan_mahasiswa').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_setting_tagihan_mahasiswa.draw();
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
