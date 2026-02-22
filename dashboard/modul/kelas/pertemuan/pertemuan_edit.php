<?php
session_start();
include "../../../inc/config.php";
$id_pertemuan = $_POST['id_pertemuan'];
$data_pertemuan = $db2->fetchCustomSingle("select * from tb_data_kelas_pertemuan where id_pertemuan=?",array('id_pertemuan' => $id_pertemuan));

//jadwal dosen
$jadwal = $db2->fetchCustomSingle("select * from view_jadwal_dosen_kelas where jadwal_id=?",array('jadwal_id' => $data_pertemuan->jadwal_id));


$ruangan = "Online";
if ($jadwal->nm_ruang!="") {
  $ruangan = $jadwal->nm_ruang;
}

$kelas_data = $db2->fetchCustomSingle("SELECT kuota,kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,total_sks,kode_jur,nama_jurusan,fungsi_jumlah_status_krs(kelas_id,1) as approved_krs,fungsi_jumlah_status_krs(kelas_id,0) as pending_krs from view_kelas where kelas_id=?",array('kelas_id' => $jadwal->kelas_id));
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

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #3c8dbc;
    border-color: #367fa9;
    padding: 1px 10px;
    color: #fff;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    margin-right: 5px;
    color: rgba(255, 255, 255, 0.7);
}
</style>

<hr style="margin-top:5px;margin-bottom:5px">
 <div class="alert alert-danger error_data_pertemuan" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_pertemuan"></span>
        </div>
      <form id="input_pertemuan" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/pertemuan/pertemuan_action.php?act=up">
                      
          <div class="form-group">
              <label for="Pertemuan" class="control-label col-lg-2">Pertemuan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-1">
              <select  id="pertemuan" name="pertemuan" data-placeholder="Pilih Pertemuan ..." class="form-control select2" tabindex="2" required>
               <?php 
               for ($i=1; $i <= 16; $i++) { 
                if ($data_pertemuan->pertemuan==$i) {
                  echo "<option value='$i'>$i</option>";
                }
                
               }
               ?>
              </select>
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenis Pertemuan" class="control-label col-lg-2">Jenis Pertemuan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-3">
            <select  id="id_jenis_pertemuan" name="id_jenis_pertemuan" data-placeholder="Pilih Jenis Pertemuan ..." class="form-control chzn-select" tabindex="2" required>
               <?php foreach ($db2->fetchAll("tb_data_jenis_pertemuan") as $isi) {
                if ($data_pertemuan->id_jenis_pertemuan==$isi->id_jenis_pertemuan) {
                  echo "<option value='$isi->id_jenis_pertemuan' selected>$isi->nama_jenis_pertemuan</option>";
                } else {
                  echo "<option value='$isi->id_jenis_pertemuan'>$isi->nama_jenis_pertemuan</option>";
                }
                  
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->



          <div class="form-group">
              <label for="Tanggal Pertemuan" class="control-label col-lg-2">Tanggal Pertemuan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <div class="input-group date tgl_picker">
                    <input type="text" autocomplete="off" class="form-control tgl_picker_input" id="tanggal_pertemuan" name="tanggal_pertemuan" required value="<?=$data_pertemuan->tanggal_pertemuan;?>" />
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
                  <input type="text" class="form-control waktu" id="jam_mulai"  name="jam_mulai" autocomplete="off"  required="" value="<?=$data_pertemuan->jam_mulai;?>">
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
                  <input type="text" autocomplete="off" class="form-control waktu" id="jam_selesai" name="jam_selesai" required="" value="<?=$data_pertemuan->jam_selesai;?>">
                  <span class="input-group-addon">
                      <span class="glyphicon glyphicon-time"></span>
                  </span>
                </div>
              </div>
            </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Jenis Pertemuan" class="control-label col-lg-2">Metode Pembelajaran <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-4">
            <select  id="metode_pembelajaran" name="metode_pembelajaran" data-placeholder="Metode Perkuliahan ..." class="form-control chzn-select" tabindex="2" required>
            <?php 
           
            $method = array(
              'F' => 'Offline',
              'O' => 'Online',
             // 'M' => 'Campuran'
             );
            if ($data_pertemuan->metode_pembelajaran=='O') {
               $ruang = 'style="display:none"';
            }

               foreach ($method as $key => $val) {
                if ($data_pertemuan->metode_pembelajaran==$key) {
                  echo "<option value='$key' selected>$val</option>";
                } else {
                  echo "<option value='$key'>$val</option>";
                }
               } ?>
              </select>
            </div>
            <div id="ruang" <?=$ruang;?>>
            <label for="Tanggal Pertemuan" class="control-label col-lg-1">Ruang</label>
              <div class="col-lg-4">
            <select  id="ruang_id" name="ruang_id" data-placeholder="Pilih Ruangan" class="form-control chzn-select" tabindex="2">
              <?php
              $ruang = $db2->fetchSingleRow("view_ruang_prodi","ruang_id",$jadwal->id_ruang);
              ?>
               <option value="<?=$ruang->ruang_id;?>"><?=$ruang->nm_gedung.' - '.$ruang->nm_ruang;?></option>
              </select>

              </div>
                      </div><!-- /.form-group -->
                      </div>
           
              <div class="form-group">
                        <label for="Pengajar" class="control-label col-lg-2">Pengajar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-6">
            <select  id="nip_dosen" name="nip_dosen[]" data-placeholder="Pilih Pengajar ..." class="form-control select2" tabindex="2" required multiple>
               <option value=""></option>
               <?php
               $dosen_pertemuan = explode("#",$data_pertemuan->nip_dosen);
               foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas='$data_pertemuan->kelas_id'  order by dosen_ke asc") as $isi) {
                  if (in_array($isi->nip,$dosen_pertemuan)) {
                    echo "<option value='$isi->nip' selected>$isi->nama_gelar</option>";
                  } else {
                    echo "<option value='$isi->nip'>$isi->nama_gelar</option>";
                  }
                  
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

         

                      <input type="hidden" name="id" value="<?=$data_pertemuan->id_pertemuan;?>">
              <div class="form-group" style="border-bottom: 1px solid #eee;padding-top: 5px;padding-bottom:5px">
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

/*
  //$('#ruang_id').on('click', function() {
$('#ruang_id').on('select2:open', function() {
    // Trigger AJAX request to fetch updated options
    $.ajax({
      url : "<?=base_admin();?>modul/kelas/pertemuan/get_ruang.php",
       type: 'POST',
      dataType: 'json',
      data : {
      kode_jur:"<?=$kelas_data->kode_jur;?>",
      tanggal:$("#tanggal_pertemuan").val(),
      jam_mulai:$("#jam_mulai").val(),
      jam_selesai:$("#jam_selesai").val()
    },
      success: function(data) {
         var $select = $('#ruang_id');
          // Clear the existing options
          $select.empty();
          // Add the new group
          var $optgroup = $('<optgroup>');
          $optgroup.attr('label', 'Ruangan Tersedia');
          
          // Add the new options to the group
          $.each(data.results, function(index, optionData) {
            var $option = $('<option>');
            $option.attr('value', optionData.id);
            $option.text(optionData.text);
            $optgroup.append($option);
          });
          
          // Add the group to the select element
          $select.append($optgroup);

          // Trigger the change event to update the select2 UI
          $select.trigger('change');
      },
      error: function(xhr, status, error) {
        console.log('Error: ' + error);
      }
    });
  });
*/



  // Add event listener for select2:select event
/*  $('#ruang_id').on('select2:select', function (e) {
    console.log('tes');
    // Get the selected value
    const selectedValue = e.params.data.id;

    // Send AJAX request to update the options
    $.ajax({
      url : "<?=base_admin();?>modul/kelas/pertemuan/get_ruang.php",
      type: 'POST',
        data : {
      kode_jur:"<?=$kelas_data->kode_jur;?>",
      tanggal:$("#tanggal_pertemuan").val(),
      jam_mulai:$("#jam_mulai").val(),
      jam_selesai:$("#jam_selesai").val()
    },
      success: function(response) {
        // Update the options of the select2 element
        const select2 = $(e.currentTarget);
        select2.empty();
        select2.append(response);
        select2.trigger('change');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
      }
    });
  });*/


//$("#ruang_id").click(function() {
/*  $("#ruang_id").on("chosen:showing_dropdown", function(event, params) {
  console.log('hello');
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/kelas/pertemuan/get_ruang.php",
    data : {
      kode_jur:"<?=$kelas_data->kode_jur;?>",
      kelas_id:"<?=$kelas_data->kelas_id;?>",
      tanggal:$("#tanggal_pertemuan").val(),
      jam_mulai:$("#jam_mulai").val(),
      jam_selesai:$("#jam_selesai").val(),
      selected : this.value,
      ruang_id : "<?=$jadwal->ruang_id;?>"
    },
    success : function(data) {
      console.log(data);
        $("#ruang_id").html(data);
        $("#ruang_id").trigger("chosen:updated");
      }
  });
});*/


$("#metode_pembelajaran").change(function(){
  if (this.value=='O') {
    $("#ruang").hide();
  } else {
    $("#ruang").show();
  }
});
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
                  $(".isi_warning_pertemuan").html(data.responseText);
                  $(".error_data_pertemuan").focus()
                  $(".error_data_pertemuan").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning_pertemuan").html(responseText[index].error_message);
                             $(".error_data_pertemuan").focus()
                             $(".error_data_pertemuan").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $(".error_data_pertemuan").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                              dtb_pertemuan.draw();
                              $('.button-top').show();
                            $('.action-title').html('');
                            $("#isi_tambah_pertemuan").html('');
                            $("#isi_tambah_pertemuan").slideUp();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
