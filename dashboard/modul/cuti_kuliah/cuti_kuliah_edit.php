<?php
include "../../inc/config.php";

function loop_periode($current_periode,$jml_loop_year,$minimal_kuliah) {
  //get current periode year
  $current_year = substr($current_periode, 0,4)+$minimal_kuliah;
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
$data_edit = $db->fetch_single_row("tb_data_cuti_mahasiswa","id_cuti",$_POST['id_data']);
//data mahasiswa
$mhs = $db->fetch_single_row("view_simple_mhs_data",'nim',$data_edit->nim);
$id_jenjang = $db->fetch_single_row("jurusan",'kode_jur',$mhs->jur_kode);
//get max cuti sekali pengajuan
$pengaturan_cuti = $db->query("select * from setting_cuti where id_jenjang=?",array('id_jenjang' => $id_jenjang->id_jenjang));
//current periode aktif
$periode_aktif = get_sem_aktif();
//current smt mahasiswa
$smt_mhs = $db->fetch_custom_single("select ((left($periode_aktif,4)-left($mhs->mulai_smt,4))*2)+right($periode_aktif,1)-(floor(right($mhs->mulai_smt,1)/2)) as smt");



$periode_selected = $db->query("select * from tb_data_cuti_mahasiswa_periode where id_cuti=?",array('id_cuti' => $data_edit->id_cuti));



$data_selected_periode = array();
foreach ($periode_selected as $periode) {
  $data_selected_periode[] = $periode->periode;
}

foreach ($pengaturan_cuti as $aturan) {
  $aturan_cuti[$aturan->nama_pengaturan] = $aturan->isi_pengaturan;
}

?>

   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_cuti_kuliah" method="post" class="form-horizontal" action="<?=base_admin();?>modul/cuti_kuliah/cuti_kuliah_action.php?act=up">
                            
 <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" name="nim" value="<?=$data_edit->nim;?>" class="form-control" readonly>
                </div>
              </div><!-- /.form-group -->
    <div class="form-group att-tambahan" >
          <label for="nama" class="control-label col-lg-2">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" value="<?=$mhs->nama;?>" class="form-control"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan" name="jurusan" value="<?=$mhs->jurusan;?>" class="form-control" readonly>
          </div>
        </div>
              
            <div class="form-group">
                <label for="Status Persetujuan" class="control-label col-lg-2">Periode Cuti Kuliah <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <br>
                  <?php
                  if ($id_jenjang->id_jenjang==35) {
                    $loop = 3;
                  } else {
                    $loop = 7;
                  }
                  foreach (loop_periode($mhs->mulai_smt,$loop,ceil($aturan_cuti['minimal_kuliah']/2)) as $key => $value) {
                    if (in_array($key, $data_selected_periode)) {
                      ?>
                      <label>
                      <input type="checkbox" name="periode[]" class="minimal" checked="" value="<?=$key;?>">  <?=$value;?>
                    </label>
                     <br>
                      <?php
                    } else {
                      ?>
                    <label>
                      <input type="checkbox" name="periode[]" class="minimal" value="<?=$key;?>">  <?=$value;?>
                    </label>
                    <br>
                      <?php
                    }
                  }
                  ?>
                  <span id="error_periode"></span>
                </div>
            </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Alasan Cuti" class="control-label col-lg-2">Alasan Cuti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
              <textarea class="form-control col-xs-12" rows="5" name="alasan_cuti" required><?=$data_edit->alasan_cuti;?> </textarea>
              </div>
          </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Status Persetujuan" class="control-label col-lg-2">Status Persetujuan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                    <select id="status_acc" name="status_acc" data-placeholder="Pilih Status Persetujuan..." class="form-control chzn-select" tabindex="2" required>
                      <option value=""></option>
                     <?php
                     $option = array(
                                  'waiting' => 'Menunggu',

                                  'approved' => 'Disetujui',

                                  'rejected' => 'Ditolak',
                                  );
                     foreach ($option as $isi => $val) {

                        if ($data_edit->status_acc==$isi) {
                          echo "<option value='$data_edit->status_acc' selected>$val</option>";
                        } else {
                       echo "<option value='$isi'>$val</option>";
                          }
                     } ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Pengajuan</label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input " name="date_created" value="<?=$data_edit->date_created;?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->
          <?php
          $show = 'style="display:none"';
          if ($data_edit->status_acc!='waiting') {
            $show = '';
          }
          ?>
          <div class="form-group" id="tgl_approve" <?=$show;?>>
              <label for="Tanggal Lahir" class="control-label col-lg-2">Tanggal Disetujui/tolak</label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" value="<?=$data_edit->date_approved;?>" class="form-control tgl_picker_input date-approved " name="date_approved" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->

              <div class="form-group" id="no_surat" <?=$show;?>>
                <label for="Nim" class="control-label col-lg-2">Nomor Surat</label>
                <div class="col-lg-8">
                  <input type="text" name="no_surat" value="<?=$data_edit->no_surat;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->

              <?php
              $show = 'style="display:none"';
              if ($data_edit->status_acc=='rejected') {
                $show = '';
              }
              ?>
              <div class="form-group" id="alasan_tolak" <?=$show;?>>
                  <label for="Alasan Cuti" class="control-label col-lg-2">Alasan ditolak</label>
                  <div class="col-lg-10">
                  <textarea class="form-control col-xs-12" rows="5" name="keterangan"><?=$data_edit->keterangan;?> </textarea>
                  </div>
              </div><!-- /.form-group -->
              
              <input type="hidden" name="id" value="<?=$data_edit->id_cuti;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#status_acc").change(function(){
            if (this.value=='approved') {
              $("#no_surat").show();
              $("#alasan_tolak").hide();
              $("#tgl_approve").show();
              $('.date-approved').prop('required',true);
            } else if(this.value=='rejected') {
              $("#alasan_tolak").show();
              $("#no_surat").show();
              $("#tgl_approve").show();
              $('.date-approved').prop('required',true);
            } else {
              $("#no_surat").hide();
              $("#tgl_approve").hide();
              $('.date-approved').prop('required',false);
              $("#alasan_tolak").hide();
            }
        });

        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
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
        
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_cuti_kuliah").validate({
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
                minlength: 1
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
                            $('#modal_cuti_kuliah').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_cuti_kuliah.draw();
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
