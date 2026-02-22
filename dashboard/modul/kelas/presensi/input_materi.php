<?php
session_start();
include "../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];
$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi",array('id_pertemuan' => $_POST['pertemuan']));
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",array('id_pertemuan' => $_POST['pertemuan']));
$kelas_data = $db2->fetchCustomSingle("SELECT kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where disetujui='1' and id_kelas=view_nama_kelas.kelas_id) as approved_krs
#(select count(id_krs_detail) from krs_detail where disetujui='0' and id_kelas=view_nama_kelas.kelas_id) as pending_krs 
from view_nama_kelas where kelas_id=?",array('kelas_id' => $kelas_id));
$dosen_pertemuan = explode("#",$pertemuan->getData()->nip_dosen);
$nip = sprintf("'%s'", implode("','", $dosen_pertemuan ) );
$counter = 1;
foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc",array("id_kelas" => $kelas_data->kelas_id)) as $isi) {
  $dosen_data[] = '- '.$isi->nama_gelar;
  $counter++;
}
$nama_dosen = trim(implode("<br>", $dosen_data));

$data_materi = $db2->fetchSingleRow("tb_data_kelas_pertemuan","id_pertemuan",$_POST['pertemuan']);
?>
<style type="text/css">
  #presensi > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
    padding:2px;
  }
 .help-block {
    color: #dd4b39;
}
.mt-checkbox {
  margin-bottom:0
}
#presensi.dataTable {
  border-color: #9e9595;
}
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?></td>
        </tr>
        <tr>
        <td class="info2"><strong>Dosen</strong></td>
          <td><?=$nama_dosen;?></td>
          <td class="info2" ><strong>Pertemuan</strong></td>
          <td><?=$pertemuan->getData()->pertemuan;?></td>
         
        </tr>
      </tbody></table>
     <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
          
            <form id="input_kelas_jadwal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=input_materi">
          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Rencana Materi</label>
                <textarea class="form-control" rows="5" name="rencana_materi" ><?=$data_materi->rencana_materi;?></textarea>
              </div>
          </div>
          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Realisasi Materi</label>
                <textarea class="form-control" rows="5" name="realisasi_materi"><?=$data_materi->realisasi_materi;?></textarea>
              </div>
          </div>

<div class="form-group">
      <div class="col-lg-11">
        <label for="Catatan" class="control-label">Lampiran Materi</label>
<div class="fileinput fileinput-new" data-provides="fileinput">
          <div class="input-group">
            <div class="form-control uneditable-input span3" data-trigger="fileinput">
              <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
            </div>
            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
              <input type="file" name="file_url" class="file-upload-data"  accept="image/* , application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.slideshow,application/vnd.openxmlformats-officedocument.presentationml.presentation">
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
          </div>
          <span class="help-block">File diijinkan pdf, excel, doc,image,ppt</span>
          <?php
          if ($data_materi->lampiran_materi!='') {
            ?>
             <a target="_blank" href="<?=$data_materi->lampiran_materi;?>"><i class="fa fa-cloud-download"></i> Download Lampiran Materi</a>
            <?php
          }
          ?>
         
        </div>
  </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              
              <div class="col-lg-11">
              <label for="Catatan" class="control-label">Link Materi/Foto </label>
                <input type="text" class="form-control" name="link_materi" value="<?=$data_materi->link_materi;?>">
              </div>
          </div>

   <div class="form-group">
      <div class="col-lg-11">
                <label for="Pengajar" class="control-label">Status Pertemuan <span style="color:#FF0000">*</span></label>
                      
              <select  id="nip_dosen" name="status_pertemuan" data-placeholder="Pilih Pengajar ..." class="form-control select2" tabindex="2" required>
              <?php
              $status_kelas = array(
                'Aktif' => 'A',
                'Selesai' => 'S'
              );
              foreach ($status_kelas as $key => $value) {
                  if ($data_materi->status_pertemuan==$value) {
                    echo '<option value="'.$value.'" selected>'.$key.'</option>';
                  } else {
                    echo '<option value="'.$value.'">'.$key.'</option>';
                  }
              }

              ?>
               
              </select>
            </div>
            </div><!-- /.form-group -->
          <input type="hidden" name="id_pertemuan" value="<?=$_POST['pertemuan'];?>">
      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
             <a class="btn btn-default " data-dismiss="modal"><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
            <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>


<script type="text/javascript">
    $(document).ready(function() {
    $("#input_kelas_jadwal").validate({
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
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
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
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                $('#modal_input_absen').modal('hide');
                                dtb_presensi.draw();
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
