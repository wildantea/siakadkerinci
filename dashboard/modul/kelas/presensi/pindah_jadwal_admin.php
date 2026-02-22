<?php
session_start();
include "../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];
$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi",array('id_pertemuan' => $_POST['pertemuan']));
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",array('id_pertemuan' => $_POST['pertemuan']));
$kelas_data = $db2->fetchSingleRow("view_nama_kelas","kelas_id",$pertemuan->getData()->kelas_id);


// Tanggal jadwal yang ditentukan
$tanggalJadwal = new DateTime($pertemuan->getData()->tanggal_pertemuan);
$dayOfWeek = $tanggalJadwal->format('N'); // 1=Senin, 7=Minggu

// Start = Senin dalam minggu tanggal jadwal
$start = clone $tanggalJadwal;
$start->modify('monday this week');

// End = Minggu dalam minggu tanggal jadwal
$end = clone $start;
$end->modify('sunday this week');

// Loop dari Senin sampai Minggu dalam minggu tersebut
$period = new DatePeriod($start, new DateInterval('P1D'), $end->modify('+1 day'));

?>
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
        </tr>
        <tr>
             <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?></td>
      </tr>
        <tr>
          <td class="info2" ><strong>Pertemuan</strong></td>
          <td><?=$pertemuan->getData()->pertemuan;?></td>
         
        </tr>
      </tbody></table>
     <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
          
            <form id="input_kelas_jadwal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=pindah_jadwal_admin">




   <div class="form-group">
      <div class="col-lg-12">
                <label for="Pengajar" class="control-label">Tanggal Pindah <span style="color:#FF0000">*</span></label>

                 <div class='input-group date' id="tgl11">
                    <input type='text' class="form-control tgl_picker_input" name="tanggal_pertemuan"  value="<?=$pertemuan->getData()->tanggal_pertemuan;?>" required />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>

       
            </div>
</div>

<input type="hidden" id="sks" name="sks" value="<?=$kelas_data->sks?>">



   <div class="form-group">
      <div class="col-lg-3">
                      <label for="Pengajar" class="control-label">Jam Mulai <span style="color:#FF0000">*</span></label>
                      <select  id="jam_mulai" name="jam_mulai" data-placeholder="Pilih jam_mulai ..." class="form-control select2" tabindex="2" required>
                <option value="">Pilih Jam</option>
                <?php 
                $sesi = $db->query("SELECT * FROM sesi_waktu");
                foreach ($sesi as $jam_mulai) {
                    if ($jam_mulai->jam_mulai.':00'==$pertemuan->getData()->jam_mulai) {
                         echo "<option value='".$jam_mulai->jam_mulai."' selected>".$jam_mulai->jam_mulai."</option>";
                    } else {
                         echo "<option value='".$jam_mulai->jam_mulai."'>".$jam_mulai->jam_mulai."</option>";
                    }
                   
                }
                 ?>
               
              </select>
            </div>
</div>


   <div class="form-group">
      <div class="col-lg-3">
                      <label for="Pengajar" class="control-label">Status Pindah</label>
                     <?php if ($data_edit->is_pindah=="Y") {
      ?>
      <input name="is_pindah" class="make-switch" type="checkbox" data-on-text="Pindah" data-off-text="Tidak" checked>
      <?php
    } else {
      ?>
      <input name="is_pindah" class="make-switch" data-on-text="Pindah" data-off-text="Tidak" type="checkbox">
      <?php
    }?>
            </div>
</div>




          <input type="hidden" name="id_pertemuan" value="<?=$_POST['pertemuan'];?>">


      
              <div class="form-group">
                <div class="col-lg-10">
             <a class="btn btn-default " data-dismiss="modal"><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
            <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>


<script type="text/javascript">
    $(document).ready(function() {


        
    $("#tgl11").datepicker({ 
  format: "yyyy-mm-dd",
  autoclose: true, 
  todayHighlight: true
}).on('change',function(){
  $("#tgl11 :input").valid();
});

    $.each($('.make-switch'), function () {
        $(this).bootstrapSwitch({
        onText: $(this).data('onText'),
        offText: $(this).data('offText'),
        onColor: $(this).data('onColor'),
        offColor: $(this).data('offColor'),
        size: $(this).data('size'),
        labelText: $(this).data('labelText')
        });
    });

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
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                $('#modal_input_absen').modal('hide');
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
