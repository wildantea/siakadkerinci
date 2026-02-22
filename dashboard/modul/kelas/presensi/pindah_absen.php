<?php
session_start();
include "../../../inc/config.php";

$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi", ['id_pertemuan' => $_POST['pertemuan']]);
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan", ['id_pertemuan' => $_POST['pertemuan']]);
$kelas_data = $db2->fetchSingleRow("view_nama_kelas","kelas_id",$pertemuan->getData()->kelas_id);


    $row = $db->convert_obj_to_array($check_pertemuan->getData());
    $dosen = $row['nip_dosen']; // bisa di-join dengan tabel dosen utk ambil nama
    $tanggal = $row['tanggal_pertemuan'];

    // decode JSON absensi dosen
    $kehadiran = json_decode($row['kehadiran_dosen'], true);

        foreach($kehadiran as $hadir) {
            $absensi = [
                "tanggal" => substr($hadir['tanggal_absen'],0,10),
                "pengajar" =>  $data_dosen[$hadir['nip']],
                "jam_absen" => $hadir['tanggal_absen']
            ];
        }
?>
<div class="container-sm" style="padding:50px">
<!-- Informasi kelas -->
<table id="tabelku" class="table table-bordered table-striped">
  <tbody>
    <tr>
      <td><strong>Matakuliah</strong></td>
      <td><?= $kelas_data->kode_mk; ?> - <?= $kelas_data->nama_mk; ?> (<?= $kelas_data->total_sks; ?> sks)</td>
    </tr>
    <tr>
      <td><strong>Kelas</strong></td>
      <td><?= $kelas_data->kls_nama; ?></td>
    </tr>
    <tr>
      <td><strong>Pertemuan</strong></td>
      <td><?= $pertemuan->getData()->pertemuan; ?></td>
    </tr>
  </tbody>
</table>

<!-- Error alert -->
<div class="alert alert-danger error_data d-none">
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  <span class="isi_warning"></span>
</div>

<!-- Form -->
<form id="input_kelas_jadwal" method="post" action="<?= base_admin(); ?>modul/kelas/kelas_action.php?act=pindah_absen_admin">

  <div class="mb-3">
    <label for="tanggal_pertemuan" class="form-label">Tanggal Pindah <span class="text-danger">*</span></label>
    <div class="input-group" id="tgl11">
      <input type="date" class="form-control tgl_picker_input" name="tanggal_pertemuan" 
             value="<?= $absensi['tanggal']; ?>" required />
    </div>
  </div>

  <input type="hidden" id="sks" name="sks" value="<?= $kelas_data->sks ?>">
  <input type="hidden" name="id_pertemuan" value="<?= $_POST['pertemuan']; ?>">

  <div class="mb-3">
    <label for="jam_mulai" class="form-label">Jam <span class="text-danger">*</span></label>
     <input type="text" class="form-control tgl_picker_input" name="jam" 
             value="<?= date("H:i:s", strtotime($absensi["jam_absen"])) ?>" required />
  </div>

  <div class="d-flex justify-content-between">
   
    <button type="submit" class="btn btn-primary save-data">
      <i class="bi bi-save"></i> <?= $lang["submit_button"]; ?>
    </button>
     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
      <i class="bi bi-x-circle"></i> <?= $lang["cancel_button"]; ?>
    </button>
  </div>
</form>
</div>
<script src="<?=base_admin();?>assets/login/js/jqueryform.js"></script>
    <script src="<?=base_admin();?>assets/login/js/validate_new.js"></script>

<script type="text/javascript">
   $(document).ready(function() {

  // Validate
  $("#input_kelas_jadwal").validate({
      debug: true, // <--- form tidak akan benar-benar submit
    errorClass: "is-invalid",
    validClass: "is-valid",
    errorElement: "div",
    errorPlacement: function(error, element) {
      error.addClass("invalid-feedback");
      if (element.parent(".input-group").length) {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    },
    submitHandler: function(form) {
      $("#loadnya").show();
      $(form).ajaxSubmit({
        dataType: "json",
        type : "post",
        error: function(data) { 
          $("#loadnya").hide();
          $(".isi_warning").html(data.responseText);
          $(".error_data").removeClass("d-none").focus();
        },
        success: function(responseText) {
          $("#loadnya").hide();
          $.each(responseText, function(index) {
            if (responseText[index].status=="good") {
              $(".save-data").attr("disabled", true);
              $(".error_data").addClass("d-none");
              $("#editAbsenModal").modal("hide"); 
              location.reload();
            } else if(responseText[index].status=="error") {
              $(".isi_warning").text(responseText[index].error_message);
              $(".error_data").removeClass("d-none").focus();
            }
          });
        }
      });
    }
  });
});

</script>
