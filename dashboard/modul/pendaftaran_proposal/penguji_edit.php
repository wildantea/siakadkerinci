<?php
session_start();
include "../../inc/config.php";

$pendaftar = $db->fetch_single_row("tb_data_pendaftaran","id",$_POST['id_pendaftar']);

$penguji = $db->query("select * from tb_penguji where id_pendaftar=?",array('id_pendaftar' => $_POST['id_pendaftar']));
if ($penguji->rowCount()>0) {
  $jadwal = true;
} else {
  $jadwal = false;
  //get setting syarat
  $jumlah_penguji = $db->fetch_single_row("tb_data_persayaratan_pendaftaran","id_pendaftaran",1);
  $jumlah_penguji = $jumlah_penguji->jml_penguji;
}
?>
<style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }
</style>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_jadwal_penguji" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_proposal/penguji_action.php?act=up">
        <input type="hidden" name="id_pendaftar" value="<?=$pendaftar->id;?>">


<?php
if ($jadwal) {
  $counter=0;
  foreach ($penguji as $dt_penguji) {
    ?>
 <div class="form-group">
                        <label for="Periode" class="control-label col-lg-2">Penguji <?=$dt_penguji->penguji_ke;?></label>
                        <div class="col-lg-10">
            <input type="hidden" name="penguji_ke[]" value="<?=$dt_penguji->penguji_ke;?>">
            <select name="dosen_penguji[<?=$counter;?>]" data-placeholder="Pilih Dosen..." class="form-control chzn-select" id="penguji<?=$dt_penguji->penguji_ke;?>" tabindex="2" required>
              <option value="">Pilih Dosen</option>
                  <?php
                  $dosen = $db->query("select * from dosen");
                  foreach ($dosen as $dt) {
                    if ($dt_penguji->nip_dosen==$dt->nip) {
                      echo "<option value='$dt->nip' selected>$dt->nama_dosen</option>";
                    } else {
                      echo "<option value='$dt->nip'>$dt->nama_dosen</option>";
                    }
                    
                  }
                         ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
    <?php
    $counter++;
  }

} else {
  for ($i=1; $i <= $jumlah_penguji; $i++) { 
   ?>
<div class="form-group">
                        <label for="Periode" class="control-label col-lg-2">Penguji <?=$i;?></label>
                        <div class="col-lg-10">
            <input type="hidden" name="penguji_ke[]" value="<?=$i;?>">
            <select name="dosen_penguji[<?=$i-1;?>]" data-placeholder="Pilih Dosen..." class="form-control chzn-select" id="penguji<?=$i;?>" tabindex="2" required>
              <option value="">Pilih Dosen</option>
                  <?php
                  $dosen = $db->query("select * from dosen");
                  foreach ($dosen as $dt) {
                    echo "<option value='$dt->nip'>$dt->nama_dosen</option>";
                  }
                         ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
   <?php
  }
}
?>
 <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-2">Tanggal Seminar</label>
              <div class="col-lg-3">
                <div class='input-group date' id='tgl'>
                    <input type='text' class="form-control tanggal" name="tanggal_seminar" value="<?=$pendaftar->tanggal_seminar;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
$("#tgl").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl :input").valid();
});
$('.clockpicker').clockpicker();
  //Timepicker
  $(".time_mulai").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });
  //Timepicker
  $(".time_akhir").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });

$("#tgl").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl :input").valid();
});
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
        
    
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
       $.validator.addMethod("myFunc", function(val,element) {
        //console.log(this.currentElements);
        if(val=='all'){
          return false;
        } else {
          return true;
        }
      }, function(params, element) {
          return $(element).attr('data-placeholder');
        });

   $("#input_jadwal_penguji").validate({
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
            } else if (element.hasClass("tanggal")) {
                 element.parent().parent().append(error);
            } else if (element.hasClass("jam")) {
                 element.parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
                rules: {
            
          "dosen_penguji[]": {
           required:true
          //minlength: 2
          }
        
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
                            $('#modal_penguji').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dataTable_penguji.draw();
                                dataTable_rekap.draw();
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
