<?php
session_start();
include "../../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran_jenis.id_jenis_pendaftaran, tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.* from view_simple_mhs 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_data']));

$data_edit = $db2->fetchSingleRow("tb_data_pendaftaran","id_pendaftaran",$mhs_data->id_pendaftaran);
?>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2" width="20%"><strong>NIM</strong></td>
          <td width="30%"><?=$mhs_data->nim;?></td>
          <td class="info2" width="20%"><strong>Angkatan</strong></td>
          <td width="30%"><?=$mhs_data->angkatan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td><?=$mhs_data->nama;?> </td>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td><?=$mhs_data->nama_jurusan;?></td>
        </tr>
        <?php
        if ($mhs_data->ada_judul=='Y') {
          ?>
          <tr>
          <td class="info2"><strong>Judul</strong></td>
          <td colspan="3"><?=$mhs_data->judul;?> </td>
        </tr>
          <?php
        }
        ?>
      </tbody>

    </table>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_pendaftaran" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran/mahasiswa/pendaftaran_action.php?act=up">
              
              <input type="hidden" name="id_jenis_pendaftaran_setting" value="<?=$mhs_data->id_jenis_pendaftaran_setting;?>">
          <?php
          if ($mhs_data->ada_judul=='Y') {
            ?>
                      <div class="form-group">
                        <label for="Judul " class="control-label col-lg-2">Judul</label>
                        <div class="col-lg-10">
                          <textarea class="form-control col-xs-12 editbox" rows="5" name="judul"><?=$mhs_data->judul;?></textarea>
                        </div>
                    </div><!-- /.form-group -->
          <?php
          }
          if ($mhs_data->ada_pembimbing=='Y') {
            $pembimbing_mahasiswa = array();
            $data_pembimbing = $db2->query("select tb_data_pendaftaran_pembimbing.* from tb_data_pendaftaran_pembimbing 
          inner join view_dosen on nip=nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=?",array(
          'id_pendaftaran' => $mhs_data->id_pendaftaran));
            if ($data_pembimbing->rowCount()>0) {
              foreach ($data_pembimbing as $pembimbing) {
                  $pembimbing_mahasiswa[$pembimbing->pembimbing_ke] = $pembimbing->nip_dosen_pembimbing;
              }
            }

          for ($i=1; $i <= $mhs_data->jumlah_pembimbing; $i++) { 
          ?>
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-3">Pembimbing <?=$i;?> </label>
            <div class="col-lg-9">
              <select id="pembimbing_<?=$i;?>" name="pembimbing[]" data-placeholder="Pilih Dosen Pembimbing <?=$i;?>..." class="form-control pembimbing" tabindex="2" required="">
               <option value=""></option>
               <?php 
                foreach ($db2->query("select * from view_nama_gelar_dosen") as $isi) {
                  if (!empty($pembimbing_mahasiswa) && $isi->nip==$pembimbing_mahasiswa[$i]) {
                    echo "<option value='$isi->nip' selected>$isi->nama_gelar</option>";
                  }
                } 
               ?>
              </select>
            </div>
          </div><!-- /.form-group -->
          <?php
            }
          }

if ($mhs_data->has_attr=='Y') {
          $attr_value = json_decode($mhs_data->attr_value);
          $form_attribute = "";
          $value_edit = "";
          $value_atribute = json_decode($data_edit->attr_value);
          foreach ($attr_value as $attr) {
            $attribute_name = $attr->attr_name;
            if (isset($value_atribute->{$attribute_name})) {
              $value_edit = $value_atribute->{$attribute_name};
            }
            if ($attr->attr_type=="dropdown") {
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                    <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                    <div class="col-lg-9">
              <select name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" class="form-control chzn-select">';
              foreach ($attr->dropdown_data->value as $key_lb => $lb) {
                if ($lb==$value_edit) {
                  $form_attribute.="<option value='$lb' selected>".$lb."</option>";
                } else {
                  $form_attribute.="<option value='$lb'>".$lb."</option>";
                }
              }
            $form_attribute.='</select>
            </div>
              </div>';
            } elseif ($attr->attr_type=="multiple_choice") {
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                    <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                    <div class="col-lg-9">';
                ?>
                <?php
              foreach ($attr->multiple_choice_data->value as $key_radio => $lb_radio) {
                $form_attribute.='<div class="radio radio-success ">
                  <input type="radio" name="'.$attr->attr_name.'"  id="radio'.$key_radio.'" value="'.$lb_radio.'" ';
                  if ($lb_radio==$value_edit) {
                    $checked = 'checked';
                  } else {
                    $checked = '';
                  }
                    $form_attribute.=$checked.'><label for="radio'.$key_radio.'" style="padding-left: 5px;">
                      '.$lb_radio.'
                    </label>
                </div>';
              }
            $form_attribute.='
            </div>
              </div>';
            } elseif ($attr->attr_type=="paragraph") {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-9">
                  <textarea name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.' class="form-control" rows="5">'.$value_edit.'</textarea>
                  </div>
              </div>';
            } elseif ($attr->attr_type=='number') {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-4">
                  <input type="text" name="'.$attr->attr_name.'" data-rule-number="true" id="'.$attr->attr_name.'" '.$required.' value="'.$value_edit.'" class="form-control">
                  </div>
              </div>';
            } elseif ($attr->attr_type=='date') {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-3">
                   <div class="input-group date tgl_picker">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.' value="'.$value_edit.'"  />
                   </div>
                  </div>
              </div>';
            }  elseif ($attr->attr_type=='text') {
                  if(array_key_exists('required', $attr)) {
                      $required = "required";
                  } else {
                    $required = "";
                  }
                     // print_r($attr);
                $form_attribute.='
              <div class="form-group">
                  <label for="single" class="control-label col-lg-3">'.$attr->attr_label.'</label>
                   <div class="col-lg-9">
                  <input type="text" name="'.$attr->attr_name.'" id="'.$attr->attr_name.'" '.$required.' value="'.$value_edit.'" class="form-control">
                  </div>
              </div>';
            }

             //$value_edit = "";

          }

          echo $form_attribute;

  ?>


  <?php
}

if ($mhs_data->id_jenis_pendaftaran=='1') {
  ?>
              <div class="form-group">
                <label for="Nim" class="control-label col-lg-4">Nomor SK Judul / Pembimbing Skripsi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                  <input type="text" id="nomor_sk_tugas" name="nomor_sk_tugas" placeholder="Nomor SK Judul / Pembimbing Skripsi, Lihat dibawah header" class="form-control" value="<?=$data_edit->nomor_sk_tugas;?>" required>
                   
                </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                 <label for="Semester" class="control-label col-lg-4">Tanggal SK Judul / Pembimbing Skripsi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-8">
                   <div class="input-group date tgl_picker">
                      <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </span>
                      <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="tanggal_sk_tugas" placeholder="Lihat di tanda tangan Dekan" required="" value="<?=$data_edit->tanggal_sk_tugas;?>"/>
                   </div>
                </div>
            </div>

              <div class="form-group">
                <label for="Nim" class="control-label col-lg-2">Lokasi Penelitian</label>
                <div class="col-lg-8">
                  <input type="text" id="lokasi" name="lokasi" placeholder="Isi Lokasi Penelitian Jika ada" class="form-control" value="<?=$data_edit->lokasi;?>">
                </div>
              </div><!-- /.form-group -->

  <?php
}

if ($mhs_data->keterangan!='') {
  ?>
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Keterangan Lain!</h4>
                <?=$mhs_data->keterangan;?>
              </div>
  <?php
}
?>
              <input type="hidden" name="id" value="<?=$data_edit->id_pendaftaran;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    
    $(document).ready(function() {

$( ".pembimbing" ).select2({
    allowClear: true,
  width: "100%",
  ajax: {
    url: '<?=base_admin();?>modul/pendaftaran/mahasiswa/data_dosen.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama Dosen"
});

    $("textarea.editbox" ).ckeditor();
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });

        $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true
        }).on("change",function(){
          $(":input",this).valid();
        });
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
    
    $("#edit_pendaftaran").validate({
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
                element.parent().parent().append(error);
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
                            $('#modal_pendaftaran').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_pendaftaran.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
