<?php
$data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jadwal_ujian","id_jadwal_ujian",$check_jadwal->id_jadwal_ujian);


?>

<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2" width="20%"><strong>NIM</strong></td>
          <td width="30%"><?=$mhs_data->nim;?></td>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$mhs_data->nama_jurusan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td><?=$mhs_data->nama;?> </td>
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
 <tr>
          <td class="info2"><strong>Jenis Pendaftaran</strong></td>
          <td colspan="3"> <?=$mhs_data->nama_jenis_pendaftaran;?></td>
        </tr>

      </tbody>

    </table>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_jadwal_sidang" method="post" class="form-horizontal" action="<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_action.php?act=up">

              <div class="form-group">
              <label for="Tanggal Sidang" class="control-label col-lg-2">Tanggal Sidang <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" value="<?=$data_edit->tanggal_ujian;?>" name="tanggal_ujian" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          
                 <!-- in case you need two column <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">S/d</div> -->
          </div><!-- /.form-group -->
          <input type="hidden" name="id" value="<?=$data_edit->id_jadwal_ujian;?>">
                 <div class="form-group">
                        <label for="Jam Mulai" class="control-label col-lg-2">Jam Mulai</label>
                        <div class="col-lg-3">
                            <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" min="00:00" max="24:00" value="<?=$data_edit->jam_mulai;?>" required>
                        </div>
                        <div class="col-lg-1" style="padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d
                        </div>
                                  <div class="col-lg-3">
                      <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" min="01:00" max="23:59" value="<?=$data_edit->jam_selesai;?>" required>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Tempat</label>
                        <div class="col-lg-10">
<?php
$tempat = array('Daring','Ruangan');
?>
            <select  id="tempat" name="tempat" data-placeholder="Pilih Tempat Sidang/Seminar ..." class="form-control select2" tabindex="2" required="">
               <option value="">Pilih Tempat Sidang/Seminar</option>
               <?php foreach ($tempat as $isi) {
                  if ($data_edit->tempat==$isi) {
                    echo "<option value='$isi' selected>$isi</option>";
                  } else {
                    echo "<option value='$isi'>$isi</option>";
                  }
                  
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group --> 


  <div class="form-group show-ruangan" <?=($data_edit->tempat=='Daring')?'style="display: none"':'';?>>
                        <label for="Ruangan" class="control-label col-lg-2">Ruangan</label>
                        <div class="col-lg-8">
                       <span class="loader-ruang"></span>
            
            <select name="ruang_id" id="ruang_id" data-placeholder="Pilih Ruangan ..." class="form-control urang_select" tabindex="2">
               <option value="">Pilih Ruangan</option>
               <?php 

$jur_filter = "";
  $akses_prodi = get_akses_prodi();
  $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
  if ($akses_jur) {
    $jur_filter = "kode_jur in(".$akses_jur->kode_jur.")";
  } else {
  //jika tidak group tidak punya akses prodi, set in 0
    $jur_filter = "kode_jur in(0)";
  }
  
                 $ruangan = $db2->query("select * from view_ruang_prodi where $jur_filter group by ruang_id"
                );
                  foreach ($ruangan as $isi) {
                  if ($data_edit->id_ruang==$isi->ruang_id) {
                    echo "<option value='$isi->ruang_id' selected>$isi->nm_gedung - $isi->nm_ruang</option>";
                  } else {
                    echo "<option value='$isi->ruang_id'>$isi->nm_gedung - $isi->nm_ruang</option>";
                  }

                  }
 ?>
              </select>
               
                        </div>
            </div>
<?php
/*echo "<pre>";
print_r($mhs_data);
echo "</pre>";*/
if ($mhs_data->ada_penguji=='Y') {
      $penguji = $db2->query("select nip,nama_gelar,tb_data_pendaftaran_penguji.nip_dosen,penguji_ke from tb_data_pendaftaran_penguji 
inner join view_nama_gelar_dosen on nip_dosen=nip where tb_data_pendaftaran_penguji.id_jadwal_ujian=?",array(
'id_jadwal_ujian' => $check_jadwal->id_jadwal_ujian));
      $id_dosen = array();
      foreach ($penguji as $dosen_penguji) {
        $id_dosen[] = array(
          'id_dosen' => $dosen_penguji->nip_dosen,
          'nama_gelar' => $dosen_penguji->nama_gelar
        );
      }



  for ($i=0; $i < $mhs_data->jumlah_penguji; $i++) { 

      if ($i+1==1) {
            $jabatan = 'Ketua';
          } else {
            $jabatan = 'Anggota';
          }
    $penguji_ke = ($i);
?>
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-2"><?=$jabatan;?> Penguji</label>
            <div class="col-lg-9">
              <select id="penguji_<?=$penguji_ke;?>" name="id_dosen[]" data-placeholder="Pilih Dosen Penguji <?=$i;?>..." class="form-control penguji" tabindex="2" required="">
                <?php
                echo "<option value='".$id_dosen[$i]['id_dosen']."'>".$id_dosen[$i]['nama_gelar']."</option>";
                ?>
              </select>
            </div>
          </div><!-- /.form-group -->
<?php
  }
}
?>

<hr>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->
 
      </form>

<div class="modal right fade" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><button type="button" class="close close-dosen-pengajar" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pilih Dosen Pengajar</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {

$.fn.modal.Constructor.prototype.enforceFocus = function() {};

$( ".penguji" ).select2({
    allowClear: true,
  width: "100%",
  ajax: {
    url: '<?=base_admin();?>modul/jadwal_sidang/data_dosen.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama Dosen"
});

$(".urang_select" ).select2({
        allowClear: true,
  width: "100%"
});

$(document).on("click", ".hapus_komponen_dosen", function() {
      $(this).parent().parent().remove(); 
});
$("#add_dosen").on('click',function(event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');

    $.ajax({
        url : "<?=base_admin();?>modul/jadwal_sidang/modal_pilih_dosen.php",
        type : "post",
        success: function(data) {
            $("#isi_dosen").html(data);
            $("#loadnya").hide();
      }
    });

$('#modal_list_dosen').modal({ keyboard: false,backdrop:'static' });

});

$(".close-dosen-pengajar").on("click",function(){
  $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
});

$("#tempat").change(function(){
  if (this.value=='Ruangan') {
    $('.show-ruangan').show();
  } else {
    $('.show-ruangan').hide();
  }
}) 
     
    $('.clockpicker').clockpicker();
  $(".chzn-select").chosen();
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
        
    
        $("#modal_jadwal_sidang").scroll(function(){
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
    $("#edit_jadwal_sidang").validate({
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
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
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
            
          tanggal_ujian: {
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
        
        },
         messages: {
            
          tanggal_ujian: {
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
          "penguji_ke[]": {
          required: "Isi urutan Penguji",
          //minlength: "Your username must consist of at least 2 characters"
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
                            $('#modal_jadwal_sidang').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_jadwal_sidang.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
