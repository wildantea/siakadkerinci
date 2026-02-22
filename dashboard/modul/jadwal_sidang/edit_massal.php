<?php
session_start();
include "../../inc/config.php";
session_check_json();

$akses_prodi = getAksesProdi();
if ($akses_prodi) {
  $jur_kode = "and kode_jur in(".$akses_prodi.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and kode_jur in(0)";
}
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.* from view_simple_mhs 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran in(".$_POST['data_ids'].") limit 1");

?>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form id="input_jadwal_sidang_massal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_action.php?act=in_massal">

          <div class="form-group">
              <label for="Tanggal Sidang" class="control-label col-lg-2">Tanggal Sidang <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" name="tanggal_ujian" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
          <input type="hidden" name="id_pendaftaran" value="<?=$_POST['data_ids'];?>">

                      <div class="form-group">
                        <label for="Jenis Pendaftaran" class="control-label col-lg-2">Tempat</label>
                        <div class="col-lg-10">
<?php
$tempat = array('Daring','Ruangan');
?>
            <select  id="tempat_ruang" name="tempat" data-placeholder="Pilih Tempat Sidang/Seminar ..." class="form-control select2" tabindex="2" required="">
               <option value="">Pilih Tempat Sidang/Seminar</option>
               <?php foreach ($tempat as $isi) {
                  echo "<option value='$isi'>$isi</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group --> 
                      
  <div class="form-group show-ruangan-sidang" style="display: none">
                        <label for="Ruangan" class="control-label col-lg-2">Ruangan</label>
                        <div class="col-lg-8">
                       <span class="loader-ruang"></span>
            
            <select name="ruang_id" id="ruang_id" data-placeholder="Pilih Ruangan ..." class="form-control select2" tabindex="2">
               <option value="">Pilih Ruangan</option>
               <?php 
                $ruangan = $db2->query("select * from view_ruang_prodi where jenis_ruang=? $jur_kode",
                  array(
                    'jenis_ruang' => 2
                  )
                );
                  foreach ($ruangan as $isi) {
                    echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
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
  for ($i=1; $i <= $mhs_data->jumlah_penguji; $i++) { 
?>
          <div class="form-group">
          <label for="Nama Pendaftaran" class="control-label col-lg-2">Penguji <?=$i;?> </label>
            <div class="col-lg-9">
              <select id="penguji_<?=$i;?>" name="id_dosen[]" data-placeholder="Pilih Dosen Penguji <?=$i;?>..." class="form-control penguji" tabindex="2" required="">
              </select>
            </div>
          </div><!-- /.form-group -->
<?php
  }
}
?>
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
    // Do this before you initialize any of your modals
$.fn.modal.Constructor.prototype.enforceFocus = function() {};

  $('.select2').select2({
    allowClear: true,
    width: "100%",
  });

$( ".penguji" ).select2({
  ajax: {
    url: '<?=base_admin();?>modul/jadwal_sidang/data_dosen.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama Dosen",
  allowClear: true,
  width: "100%",
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

$("#tempat_ruang").change(function(){
  if (this.value=='Ruangan') {
    $('.show-ruangan-sidang').show();
  } else {
    $('.show-ruangan-sidang').hide();
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
    $("#input_jadwal_sidang_massal").validate({
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
            } else if (element.hasClass("select2-hidden-accessible")) {
               element.parent().append(error);
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
                            $('#modal_jadwal_sidang_massal').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                                dtb_jadwal_sidang.draw();
                                  select_deselect('unselect');
                                  dtb_jadwal_sidang.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
                                $('.check-selected').each( function() {
                                  $(this).prop('checked',false);
                                });
                                check_selected();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
