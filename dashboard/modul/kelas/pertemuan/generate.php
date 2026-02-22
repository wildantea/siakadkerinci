<?php
session_start();
include "../../../inc/config.php";
$kelas_id = $_POST['kelas_id'];

$jam_mulai = '';
$jam_selesai = '';
$kelas_data = $db2->fetchCustomSingle("select id_matkul,sem_id from kelas where kelas_id=?",array('kelas_id' => $kelas_id));

//check if rps is exist
$nips = $db2->fetchCustomSingle("select group_concat(id_dosen) as nip from view_dosen_kelas_single where id_kelas='$kelas_id'");
$check_exist = $db2->fetchCustomSingle("select * from rps_file where semester=? and id_matkul=? and nip in($nips->nip)",array('semester' => $kelas_data->sem_id,'id_matkul' => $kelas_data->id_matkul));



if (!$check_exist) {
  echo "<h3>Silakan Upload Terlebih dahulu RPS Matakuliah</h3>";
  exit();
}


$nip_dosen = getUser()->username;
//jadwal dosen
$jadwal = $db2->fetchCustomSingle("select * from view_jadwal_dosen_kelas where id_kelas=? and id_dosen=? order by id_hari asc",array('kelas_id' => $kelas_id,'nip' => $nip_dosen));
echo $db2->getErrorMessage();

$ruangan = "Online";

$ruangans = $db2->fetchCustomSingle("select nm_ruang from ruang_ref where ruang_id='$jadwal->id_ruang'");

$ruangan = $ruangans->nm_ruang;

$kode_jur = $db2->fetchCustomSingle("select kode_jur,kls_nama from view_kelas where kelas_id=?",array('kelas_id' => $kelas_data->kelas_id));

//check tgl awal perkuliahan
$tgl_awal = $db2->fetchCustomSingle("select tgl_awal_kuliah from semester_ref where id_semester=? and kode_jur=?",array('id_semester' => getSemesterAktif(),'kode_jur' => $kode_jur->kode_jur));

$ref_hari = $db2->fetchCustomSingle("select nama_hari from tb_ref_hari where id_hari='$jadwal->id_hari'");

$array_day = array(
 'Minggu' => 0,
 'Senin' => 1,
 'Selasa' => 2,
 'Rabu' => 3,
 'Kamis' => 4,
 'Jumat' => 5,
 'Sabtu' => 6
);
unset($array_day[$ref_hari->nama_hari]);
$days = array_values($array_day);
$hari = implode(",",$days);

$date = new DateTime($tgl_awal->tgl_awal_kuliah);

$dayOfWeek = $date->format('N');
$startOfWeek = clone $date;
$startOfWeek->modify('-' . ($dayOfWeek - 0) . ' days');
$endOfWeek = clone $startOfWeek;
$endOfWeek->modify('+6 days');


$startDate = new DateTime($startOfWeek->format('Y-m-d'));
$endDate = new DateTime($endOfWeek->format('Y-m-d'));

$currentDate = clone $startDate;
while ($currentDate <= $endDate) {
    if (getHariFromDate($currentDate->format('Y-m-d'))==$ref_hari->nama_hari) {
        $tgl_jadwal = $currentDate->format('Y-m-d');
    }
    $currentDate->modify('+1 day');
}

$tanggal_awal = getHariFromDate($tgl_jadwal).', '.tgl_indo($tgl_jadwal);


//echo $startOfWeek->format('Y-m-d') . ' - ' . $endOfWeek->format('Y-m-d');
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

<hr>
      <form id="input_pertemuan" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/pertemuan/pertemuan_action.php?act=generate">
                      
          <div class="form-group">
              <label for="Pertemuan" class="control-label col-lg-2">Pertemuan<span style="color:#FF0000">*</span></label>
              <div class="col-lg-2">
                 <select  id="awal_pertemuan" name="awal_pertemuan" data-placeholder="Pilih Pertemuan ..." class="form-control chzn-select" data-msg-required="Pilih Pertemuan Awal" tabindex="2" required>
               <?php 
               for ($i=1; $i <=16; $i++) { 
                echo "<option value='$i'>$i</option>";
               }
              ?>
              </select>
              </div>
              <div class="col-lg-2" style="padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d
              </div>
              <div class="col-lg-2">
              <select  id="akhir_pertemuan" name="akhir_pertemuan" data-msg-required="Pilih Pertemuan Akhir" data-placeholder="Pilih Pertemuan ..." class="form-control chzn-select" tabindex="2" required>
               <?php 
               for ($i=1; $i <=16; $i++) { 
                echo "<option value='$i'>$i</option>";
               }
              ?>
              </select>
              </div>
          </div><!-- /.form-group -->
          <input type="hidden" name="kelas_id" value="<?=$kelas_id;?>">
          <div class="form-group">
                        <label for="Jenis Pertemuan" class="control-label col-lg-2">Jenis Pertemuan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-4">
            <select  id="id_jenis_pertemuan" name="id_jenis_pertemuan" data-placeholder="Pilih Jenis Pertemuan ..." class="form-control chzn-select" tabindex="2" required>
               <?php foreach ($db2->query("select * from tb_data_jenis_pertemuan where id_jenis_pertemuan in(1,2)") as $isi) {
                  echo "<option value='$isi->id_jenis_pertemuan'>$isi->nama_jenis_pertemuan</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Jenis Pertemuan" class="control-label col-lg-2">Metode Pembelajaran <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-4">
            <select  id="metode_pembelajaran" name="metode_pembelajaran" data-placeholder="Metode Perkuliahan ..." class="form-control chzn-select" tabindex="2" required>
            <?php 
            //check if jadwal offline or online
            /*$is_online = $db2->fetchCustomSingle("select kuliah_mode from tb_data_kelas_jadwal where jadwal_id=?",array('jadwal_id' => $jadwal->jadwal_id));
            $ruang = "";
            if ($is_online->kuliah_mode=='O') {
              $method = array(
                'O' => 'Online',
               // 'M' => 'Campuran'
               );
               $ruang = 'style="display:none"';
            } else {*/
              $method = array(
                'F' => 'Offline',
                'O' => 'Online',
               // 'M' => 'Campuran'
               );
           // }

               foreach ($method as $key => $val) {
                echo "<option value='$key'>$val</option>";
               } ?>
              </select>
            </div>
            <div id="ruang" <?=$ruang;?>>
            <label for="Tanggal Pertemuan" class="control-label col-lg-1">Ruang</label>
              <div class="col-lg-4">
                <input type="text" class="form-control ruangan" name="ruang" value="<?=$ruangan;?>" readonly>
                <input type="hidden" name="ruang_id" class="ruang_id" value="<?=$jadwal->id_ruang;?>">
              </div>
                      </div><!-- /.form-group -->
                      </div>

            <div class="form-group">
                <label for="Jenis Pertemuan" class="control-label col-lg-2">Jadwal <span style="color:#FF0000">*</span></label>
                <div class="col-lg-4">
                  <select  id="jadwal" name="jadwal" data-placeholder="Pilih Jadwal ..." class="form-control" tabindex="2" required>
               <?php 
               
               foreach ($db2->query("select * from view_jadwal where kelas_id=?",array('kelas_id' => $kelas_id)) as $isi) {
                  if ($isi->jadwal_id==$jadwal->jadwal_id) {
                    echo "<option value='$isi->jadwal_id' selected>".ucwords($isi->hari).", ".substr($isi->jam_mulai,0,5)." - ".substr($isi->jam_selesai,0,5)."</option>";
                  } else {
                    echo "<option value='$isi->jadwal_id'>".ucwords($isi->hari).", ".substr($isi->jam_mulai,0,5)." - ".substr($isi->jam_selesai,0,5)."</option>";
                  }
                  
               } ?>
                  </select>
                </div>
            </div><!-- /.form-group -->

            <div class="form-group">
              <label for="Semester" class="control-label col-lg-2">&nbsp;</label>
              <div class="col-lg-4">
               <input type="hidden" name="id_hari" value="<?=$jadwal->id_hari;?>">
              <label class="control-label" for="inputError" style="color:#dd4b39"><i class="fa fa-warning"></i> Tanggal dibawah hanya bisa dipilih hari <?=ucwords($jadwal->hari);?></label>
              </div>
            </div><!-- /.form-group -->

          <div class="form-group">
          <label for="Tanggal Pertemuan" class="control-label col-lg-2">Tanggal Awal Pertemuan <span style="color:#FF0000">*</span></label>
          <div class="col-lg-4">
          <div class="input-group date tgl_picker">
              <input type="text" class="form-control tanggal-awal" value="<?=$tanggal_awal;?>" readonly>
              <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              
              </div>
              <input type="hidden" name="tgl_awal" class="d-none tgl_awal" value="<?=$tgl_jadwal;?>">
          </div>
              </div>

              <div class="form-group">
                        <label for="Pengajar" class="control-label col-lg-2">Pengajar <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-6">
            <select  id="nip_dosen" name="nip_dosen[]" data-placeholder="Pilih Pengajar ..." class="form-control select2" tabindex="2" required multiple>
               <option value=""></option>
               <?php foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where jadwal_id=?  order by dosen_ke asc",array('jadwal_id' => $jadwal->jadwal_id)) as $isi) {
                  echo "<option value='$isi->nip' selected>$isi->nama_gelar</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
              
              <div class="form-group" style="border-top: 1px solid #eee;padding-top: 5px;">
              <label for="Pengajar" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-4">
                <button type="button" class="btn btn-default batal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-success save-data"><i class="fa fa-save"></i> Generate Pertemuan</button>
                </div>
              </div><!-- /.form-group -->
 
      </form>
      <script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">
   
    $(document).ready(function() {

    $(".tgl_picker").datepicker({
        format: "DD, dd MM yyyy",
        autoclose: true,
        language: "id",
        todayHighlight: false,
        forceParse : false,
         daysOfWeekDisabled: [<?=$hari;?>]
        }).on("change",function(){
          var val = $(this).datepicker('getDate');
          var formatted = moment(val).format('YYYY-MM-DD');
          $('.tgl_awal').val(formatted);
          $(":input",this).valid();
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

$("#metode_pembelajaran").change(function(){
  if (this.value=='O') {
    $("#ruang").hide();
  } else {
    $("#ruang").show();
  }
});
$("#jadwal").change(function(){
  $("#loadnya").show();
    $.ajax({
    type : "post",
    dataType: "json",
    url : "<?=base_admin();?>modul/kelas/pertemuan/get_date.php",
    data : {id_jadwal:this.value},
    success : function(responseText) {
      $("#loadnya").hide();
      $('.tanggal-awal').datepicker('remove');
      console.log(responseText);
        $.each(responseText, function(index) {
          $("#nip_dosen").select2("destroy");
          $("#nip_dosen").html(responseText[index].pengajar);
          $("#nip_dosen").select2();
            $(".tanggal-awal").val(responseText[index].tanggal_awal);
            var arr_disable = [0,1,2,3,4,5,6];
            arr_disable.splice(responseText[index].disabled_day, 1); 
            $(".tanggal-awal").datepicker({
              format: "DD, dd MM yyyy",
              autoclose: true,
              language: responseText[index].lang,
              todayHighlight: false,
              forceParse : false,
              daysOfWeekDisabled: arr_disable
              });
              $(".tgl_awal").val(responseText[index].tgl_awal);
              $(".ruangan").val(responseText[index].ruang);
              $(".ruang_id").val(responseText[index].ruang_id);
        });
    }
    });

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
                            $('#modal_pertemuan').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                              $('.button-top').show();
                              $('.action-title').html('');
                              $("#isi_tambah_pertemuan").html('');
                              $("#isi_tambah_pertemuan").slideUp();
                                dtb_pertemuan.draw();
                                dtb_presensi.draw();
                            });
                          }
                    });
                }

            });
        }
    });
});
</script>
