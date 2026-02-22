<?php
session_start();
include "../../inc/config.php";
$id_survey = $_POST['id_survey'];
//check survey hasil
$user = $_SESSION['username'];

$survey = $db2->fetchSingleRow("tb_survey_kat","id",$id_survey);
?>
  <div class="box-header with-border">
    <div class="user-block" >
      <span class="username" style="margin-left:0px">
        <a href="#"><?=ucwords($survey->nama_survey);?></a>
      </span>
    </div>
  </div>
<div class="box box-primary">
<div class="box-body">
      <?=$survey->ket_survey;?>
<style type="text/css">
    .help-block {
        color:#f00;
    }
.timeline:before {
    left: 14px;
    bottom: 49px;
}
</style>

 <div class="alert alert-danger fade in error_data" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning"></span>
        </div>
         <form id="isi_kuesioner" method="post" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=in_lain">
<input type="hidden" name="id_jenis_survey" value="<?=$id_survey;?>">
                        <ul class="timeline">
                <?php
                $no=1;
                $data_aspek = $db2->query("select * from tb_survey_kat_pertanyaan where id_survey=?",array('id_survey' => $id_survey));
                foreach ($data_aspek as $aspek) {
                    ?>
                        <div class="box box-success box-solid" style="margin-bottom: 9px;">
                                <div class="box-header">
                                <h3 class="box-title"><?=$aspek->nama_kategori;?></h3>
                                </div>
                                </div>
                    <?php
                    $data_quetion = $db2->query("select tb_survey_pertanyaan.* from tb_survey_pertanyaan where id_kat_pertanyaan=?",array('id_kat_pertanyaan' => $aspek->id));
                    foreach ($data_quetion as $question) {
                        if ($question->jenis_opsi=='1') {
                            $option = json_decode($question->opsi);
                            $opsi = $db2->converObjToArray($option);
                            ?>
                            <li>
                            <i class="fa bg-green" style="left:0"><?=$no;?></i>
                            <div class="timeline-item" style="margin-left:40px">
                            <h3 class="timeline-header"><?=$question->pertanyaan;?></h3>
                            <div class="timeline-body">
                                <?php
                                foreach ($opsi as $key => $val) {
                                    ?>
                                        <div class="radio radio-success radio-inline">
                                          <input type="radio" class="nilai_<?=$question->id;?>"  name="nilai[<?=$question->id;?>]" id="nilai_<?=$question->id;?>_<?=$key;?>" value="<?=$key;?>" required data-msg-required="Silakan pilih salah satu jawaban">
                                            <label for="nilai_<?=$question->id;?>_<?=$key;?>" style="padding-left: 5px;">
                                              <?=$val;?>
                                            </label>
                                        </div>
                                    <?php
                                }
                                ?>
                            </div>
                            </div>
                            </li>
                            <?php
                        } elseif ($question->jenis_opsi=='3') {
                            ?>
                            <li>
                            <i class="fa bg-green" style="left:0"><?=$no;?></i>
                            <div class="timeline-item" style="margin-left:40px">
                            <h3 class="timeline-header"><?=$question->pertanyaan;?></h3>
                            <div class="timeline-body">
                                 <textarea class="form-control" name="nilai[<?=$question->id;?>]" rows="3" required></textarea>
                            </div>
                            </div>
                            </li>
                            <?php
                        } elseif ($question->jenis_opsi=='4') { 
                            $option = json_decode($question->opsi);
                            $opsi = $db2->converObjToArray($option);
                            ?>
                            <li>
                            <i class="fa bg-green" style="left:0"><?=$no;?></i>
                            <div class="timeline-item" style="margin-left:40px">
                            <h3 class="timeline-header"><?=$question->pertanyaan;?></h3>
                            <div class="timeline-body">
                                  <table class="table table-bordered" style="margin-bottom:0">
                                      <?php foreach ($opsi as$key => $val): ?>
                                          <tr>
                                            <td>
<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <?php echo $val ?></span>
  <input type="checkbox" class="group-checkable" name="nilai[<?=$question->id;?>][]" value="<?=$key;?>" data-rule-minlength='1' required data-msg-required="Pilih minimal satu jawaban"> <span></span>
</label>
                                          
                                        </td>
                                             </tr>
                                        <?php endforeach ?>
                                 </table><br>
                            </div>
                            </div>
                            </li>
                                   
                                    <?php
                        }
                        $no++;
                    }
                }

                ?>
                <div class="form-group" style="margin-left: 39px;">
                        <a data-dismiss="modal" aria-label="Close" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
                      <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?> Jawaban</button>
                    </div>
                </ul>
                   
                      
                      </form>

</div>
</div>
<link rel="stylesheet" href="<?=base_admin();?>assets/plugins/iCheck/all.css">
<script src="<?=base_admin();?>assets/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
    
        //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })

    $("#isi_kuesioner").validate({
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
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().parent().parent().parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
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
                            $('#modal_paper').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                location.reload();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>