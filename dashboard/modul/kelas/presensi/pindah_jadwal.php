<?php
session_start();
include "../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];
$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi",array('id_pertemuan' => $_POST['pertemuan']));
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",array('id_pertemuan' => $_POST['pertemuan']));
$kelas_data = $db2->fetchSingleRow("view_nama_kelas","kelas_id",$pertemuan->getData()->kelas_id);


// Hari ini
$today = new DateTime();
$dayOfWeek = $today->format('N'); // 1=Senin, 7=Minggu

// Kalau Minggu → start = end = hari ini
if ($dayOfWeek == 7) {
    $start = clone $today;
    $end   = clone $today;
} else {
    // Start = hari ini
    $start = clone $today;
    // End = Minggu minggu ini
    $end = clone $today;
    $end->modify('sunday this week');
}

// Loop dari hari ini sampai akhir minggu (Minggu)
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
          
            <form id="input_kelas_jadwal" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=pindah_jadwal">
   <div class="form-group">
      <div class="col-lg-12">
                <label for="Pengajar" class="control-label">Tanggal Pindah <span style="color:#FF0000">*</span></label>
                      
              <select  id="tanggal_pertemuan" name="tanggal_pertemuan" data-placeholder="Pilih tanggal_pertemuan ..." class="form-control select2" tabindex="2" required>
                <option value="">Pilih Hari</option>
                <?php foreach ($period as $date): ?>
                    <option value="<?= $date->format('Y-m-d') ?>">
                        <?= getHariFromDate($date->format('Y-m-d')).",".tgl_indo($date->format('Y-m-d')) ?>
                    </option>
                <?php endforeach; ?>
               
              </select>
            </div>
</div>

<input type="hidden" id="sks" name="sks" value="<?=$kelas_data->sks?>">



   <div class="form-group">
      <div class="col-lg-3">
                      <label for="Pengajar" class="control-label">Jam Mulai <span style="color:#FF0000">*</span></label>
                      <select  id="jam_mulai" name="jam_mulai" data-placeholder="Pilih jam_mulai ..." class="form-control select2" tabindex="2" required>
                <option value="">Pilih Hari</option>
                <?php 
                $sesi = $db->query("SELECT *
FROM sesi_waktu
WHERE jam_mulai >= (
    SELECT jam_mulai
    FROM sesi_waktu
    WHERE CURTIME() BETWEEN jam_mulai AND jam_selesai
    LIMIT 1
);");
                foreach ($sesi as $jam_mulai) {
                    echo "<option value='".$jam_mulai->jam_mulai."'>".$jam_mulai->jam_mulai."</option>";
                }
                 ?>
               
              </select>
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

  $("#tanggal_pertemuan").change(function(){
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/kelas/presensi/get_waktu.php",
        data : {tanggal:this.value,sks:$("#sks").val()},
        success : function(data) {
            $("#jam_mulai").html(data);
        }
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
