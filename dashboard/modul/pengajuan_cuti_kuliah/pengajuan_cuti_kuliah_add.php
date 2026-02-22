<?php
session_start();
include "../../inc/config.php";

function ganjil_genap($id_semester) {
  $year_next = substr($id_semester, 0,4) + 1;
  if (substr($id_semester, -1)==1) {
    $periode = substr($id_semester, 0,4)."/".$year_next." Ganjil";
  } elseif (substr($id_semester, -1)==2) {
    $periode = substr($id_semester, 0,4)."/".$year_next." Genap";
  } else {
    $periode = $id_semester;
  }
  return $periode;
}
function loop_periode($current_periode,$jml_loop_year) {
  //get current periode year
  $current_year = substr($current_periode, 0,4);
  $max_year = $current_year+$jml_loop_year;
  $array_loop = array();
  for ($i=$current_year; $i <$max_year; $i++) { 
    for ($j=1; $j <=2; $j++) { 
      if ($i.$j>=$current_periode) {
       $array_loop[$i.$j] = ganjil_genap($i.$j);
      }
    }
  }

  return $array_loop;
}

//data mahasiswa
$mhs = $db->fetch_single_row("view_simple_mhs_data",'nim',$_SESSION['username']);
$id_jenjang = $db->fetch_single_row("jurusan",'kode_jur',$mhs->jur_kode);
//get max cuti sekali pengajuan
$pengaturan_cuti = $db->query("select * from setting_cuti where id_jenjang=?",array('id_jenjang' => $id_jenjang->id_jenjang));
//current periode aktif
$periode_aktif = get_sem_aktif();
//current smt mahasiswa
$smt_mhs = $db->fetch_custom_single("select ((left($periode_aktif,4)-left($mhs->mulai_smt,4))*2)+right($periode_aktif,1)-(floor(right($mhs->mulai_smt,1)/2)) as smt");

  foreach ($pengaturan_cuti as $aturan) {
    $aturan_cuti[$aturan->nama_pengaturan] = $aturan->isi_pengaturan;
  }
?>
 <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/plugins/iCheck/minimal/_all.css">
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<div class="alert alert-warning alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-info"></i> Aturan Cuti!</h4>
  1. Maximal Cuti Per Ajuan Sebanyak <?=$aturan_cuti['max_cuti'];?> Semester<br>
  2. Total Maximal Cuti Selama Kuliah <?=$aturan_cuti['max_total_cuti'];?> Semester
</div>

 <span style="color:#FF0000"></span><br>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_pengajuan_cuti_kuliah" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pengajuan_cuti_kuliah/pengajuan_cuti_kuliah_action.php?act=in">
              
            <div class="form-group">
                <label for="Status Persetujuan" class="control-label col-lg-2">Periode Cuti Kuliah <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <br>
                  <?php
                  foreach (loop_periode($periode_aktif,$aturan_cuti['max_cuti']) as $key => $value) {
                    ?>
                    <label>
                      <input type="checkbox" name="periode[]" class="minimal" value="<?=$key;?>">  <?=$value;?>
                    </label>
                    <br>
                    <?php
                  }
                  ?>
                  <span id="error_periode"></span>
                </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Alasan Cuti" class="control-label col-lg-2">Alasan Cuti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="alasan_cuti" ></textarea>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ajukan Cuti Kuliah</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script src="<?= base_url() ?>dashboard/assets/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
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
        
    $('.minimal').on('ifChanged', function(event) {
      $(this).valid();
    });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      

    $("#input_pengajuan_cuti_kuliah").validate({
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
            } else if (element.hasClass("minimal")) {
               $("#error_periode").html(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
          "periode[]": { 
                required: true, 
                minlength: 1,
                maxlength : <?=$aturan_cuti['max_cuti'];?>
            },
        
          alasan_cuti: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
          "periode[]": {
            required : "Silakan Pilih Minimal Satu Semester!",
            maxlength : "Maximal Cuti Per Ajuan Sebanyak <?=$aturan_cuti['max_cuti'];?> Semester"
          },
          alasan_cuti: {
          required: "Silakan isi alasan anda Cuti Kuliah",
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
                            $('#modal_pengajuan_cuti_kuliah').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_pengajuan_cuti_kuliah.draw();
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
