<?php
session_start();
include "../../../inc/config.php";
session_check_json();


$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,peserta_max as kuota,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?", array('kelas_id' => $_POST['kelas_id']));
$check_exist_dosen_kelas = $db2->fetchSingleRow('dosen_kelas', 'id_kelas', $kelas_data->kelas_id);
if ($check_exist_dosen_kelas) {
  $dosen = 1;
} else {
  $dosen = 0;
}
// Check current schedule
$is_jadwal_edit = $db->fetch_custom_single("select * from semester_ref where id_semester=?", array('id_semester' => $kelas_data->sem_id));

$current_data = strtotime(date("Y-m-d H:i:s"));

$contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal . " 00:00:00");
$contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal . " 23:59:59");

if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
  $is_edit = 1;
} else {
  $is_edit = 0;
}

//also check in semeste prodi
// Check current schedule
if ($is_edit == 0) {
  $is_jadwal_edit = $db->fetch_custom_single("select * from semester where id_semester=? and kode_jur=?", array('id_semester' => $kelas_data->sem_id, 'kode_jur' => $kelas_data->kode_jur));

  $current_data = strtotime(date("Y-m-d H:i:s"));

  $contractDateBegin = strtotime($is_jadwal_edit->tgl_mulai_input_jadwal . " 00:00:00");
  $contractDateEnd = strtotime($is_jadwal_edit->tgl_selesai_input_jadwal . " 23:59:59");

  if ($current_data >= $contractDateBegin && $current_data <= $contractDateEnd) {
    $is_edit = 1;
  } else {
    $is_edit = 0;
  }
}



?>

<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td class="info2" width="20%"><strong>Program Studi</strong></td>
      <td width="30%"><?= $kelas_data->nama_jurusan; ?></td>
      <td class="info2" width="10%"><strong>Periode</strong></td>
      <td><?= getTahunakademik($kelas_data->sem_id); ?></td>
    </tr>
    <tr>
      <td class="info2"><strong>Matakuliah</strong></td>
      <td><?= $kelas_data->kode_mk; ?> - <?= $kelas_data->nama_mk; ?> (<?= $kelas_data->total_sks; ?> sks) </td>
      <td><b>Kuota</b></td>
      <td><?= $kelas_data->kuota; ?></td>
    </tr>
    <tr>
      <td class="info2"><strong>Kelas</strong></td>
      <td colspan="3"><?= $kelas_data->kls_nama; ?> </td>
    </tr>
  </tbody>
</table>

<?php
if ($_POST['act'] == 'add') {
  if ($is_edit == 1 or $_SESSION['level'] == '1' or $_SESSION['group_level'] == 'tim_kecil') {
    ?>
    <button class="btn btn-primary btn-sm tambah-jadwal" data-toggle="tooltip" data-title="Tambah Jadwal"
      style="margin-bottom: 20px;"><i class="fa fa-plus"></i> Tambah Jadwal</button><br>
    <?php
  }
}
?>
<span class="loader-tambah-jadwal"></span>
<div id="form-input-jadwal" style="display: none;">

</div>
<div class="alert alert-warning fade in error_data_delete_jadwal" style="display:none">
  <button type="button" class="close hide_alert_notif">&times;</button>
  <i class="icon fa fa-warning"></i> <span class="isi_warning_delete_jadwal"></span>
</div>
<table id="dtb_jadwal_modal" class="table table-bordered table-striped display responsive nowrap" width="100%">
  <thead>
    <tr>

      <th>Ruang</th>
      <th>Hari</th>
      <th>Jam Mulai</th>
      <th>Jam Selesai</th>
      <th>Dosen</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<div class="modal-footer">

  <!-- <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i> Close Jadwal</button> -->
</div>
<div class="modal right fade" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><button type="button" class="close close-dosen-pengajar" aria-label="Close"><span
            aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></span></button>
        <h4 class="modal-title">Pilih Dosen Pengajar</h4>
      </div>
      <div class="modal-body" id="isi_dosen"> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div class="modal" id="modal-confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true"><i class="fa fa-times"></i></span></button>
        <h4 class="modal-title"><?php echo $lang['confirm']; ?></h4>
      </div>
      <div class="modal-body">
        <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span>
            <?php echo $lang['delete_confirm']; ?></span></p>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-default"
          data-dismiss="modal"><?php echo $lang['cancel_button']; ?></button><button type="button" id="delete"
          class="btn btn-danger"><?php echo $lang['delete']; ?></button> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
  $(document).ready(function () {
    // Do this before you initialize any of your modals
    $.fn.modal.Constructor.prototype.enforceFocus = function () { };

  });
  $(".tambah-jadwal").click(function () {
    console.log('yes');
    var $form = $("#form-input-jadwal");

    if ($form.is(':visible')) {
      $(this).find('.fa').removeClass('fa-minus').addClass('fa-plus');
      // Destroy semua Select2 sebelum hapus DOM
      $form.find('.pengajar').each(function () {
        if ($(this).data('select2')) {
          $(this).select2('destroy');
        }
      });
      $form.html('').slideUp();
      $("#dtb_jadwal_modal").slideDown();
      //show table pengajar
      $("#dtb_dosen_pengajar").slideDown();
      $("#add_dosen").find('.fa').removeClass('fa-minus').addClass('fa-plus');
      $("#add_dosen").show();
    } else {
      //dosen
      $("#form-input-jadwal-pengajar").html('');
      $("#form-input-jadwal-pengajar").slideUp();
      //hide table dosen pengajar
      $("#dtb_dosen_pengajar").slideUp();
      $("#add_dosen").find('.fa').removeClass('fa-minus').addClass('fa-plus');
      $("#add_dosen").hide();
      //hide jadwal table
      $("#dtb_jadwal_modal").slideUp();
      $(this).find('.fa').toggleClass('fa-plus fa-minus');
      loaderForm('show', 'loader-tambah-jadwal');
      $.ajax({
        type: "post",
        url: "<?= base_admin(); ?>modul/kelas_jadwal/jadwal/jadwal_add.php",
        data: { kode_jur: <?= $kelas_data->kode_jur ?>, sem_id: <?= $kelas_data->sem_id ?>, kelas_id: <?= $_POST['kelas_id'] ?>, sks: <?= $kelas_data->total_sks; ?> },
        success: function (data) {
          loaderForm('hide', 'loader-tambah-jadwal');
          $("#form-input-jadwal").html(data);
          $("#form-input-jadwal").slideDown();



        }
      });
    }

  });
  function initializeSelect2($el) {
    if ($el.length === 0) return;

    $el.each(function () {
      var $this = $(this);
      if ($this.data('select2')) {
        $this.select2('destroy');
      }

      $this.select2({
        width: "100%",
        ajax: {
          url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/dosen_pengajar/data_dosen.php',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return { q: params.term, sem_id: '<?= $_POST['sem_id'] ?>' };
          },
          processResults: function (data) {
            var results = [];
            $.each(data.results || [], function (i, item) {
              var sks = Number(item.sks || 0);
              var sksMax = Number(item.sks_max || 999);
              var disabled = (item.min !== 'yes' && sks > sksMax);
              results.push({
                id: item.id,
                text: item.text,
                disabled: disabled
              });
            });
            return { results: results };
          }
        },
        placeholder: "Cari & Pilih Nama Dosen",
        allowClear: true,
        language: { inputTooShort: function () { return "Cari & Pilih Nama Dosen"; } }
      });
    });
  }
  //modal jadwal
  $(".table").on('click', '.edit-jadwal-modal', function (event) {
    //dosen
    $("#form-input-jadwal-pengajar").html('');
    $("#form-input-jadwal-pengajar").slideUp();
    //hide table dosen pengajar
    $("#dtb_dosen_pengajar").slideUp();
    $("#add_dosen").find('.fa').removeClass('fa-minus').addClass('fa-plus');
    $("#add_dosen").hide();

    event.preventDefault();
    var currentBtn = $(this);
    id = currentBtn.attr('data-id');
    $("#dtb_jadwal_modal").slideUp();
    $('.tambah-jadwal').hide();
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/jadwal/jadwal_edit.php",
      type: "post",
      data: { kode_jur: <?= $kelas_data->kode_jur ?>, sem_id: <?= $kelas_data->sem_id ?>, kelas_id: <?= $_POST['kelas_id'] ?>, jadwal_id: id },
      success: function (data) {
        $("#form-input-jadwal").html(data);
        $("#form-input-jadwal").slideDown();
      }
    });
  });

  $("#add_dosen").click(function () {
    //jadwal
    $("#form-input-jadwal").html('');
    $("#form-input-jadwal").slideUp();
    $("#dtb_jadwal_modal").slideDown();
    $(".tambah-jadwal").find('.fa').removeClass('fa-minus').addClass('fa-plus');

    if ($("#form-input-jadwal-pengajar").is(':visible')) {
      $(this).find('.fa').toggleClass('fa-minus fa-plus');
      $("#form-input-jadwal-pengajar").html('');
      $("#form-input-jadwal-pengajar").slideUp();
      $("#dtb_dosen_pengajar").slideDown();
    } else {
      $("#dtb_dosen_pengajar").slideUp();
      $(this).find('.fa').toggleClass('fa-plus fa-minus');
      $.ajax({
        type: "post",
        url: "<?= base_admin(); ?>modul/kelas_jadwal/jadwal/dosen_pengajar/dosen_pengajar_add.php",
        data: { kode_jur: <?= $kelas_data->kode_jur ?>, sem_id: <?= $kelas_data->sem_id ?>, kelas_id: <?= $_POST['kelas_id'] ?> },
        success: function (data) {
          $("#form-input-jadwal-pengajar").html(data);
          $("#form-input-jadwal-pengajar").slideDown();


          $('#modal_kelas_jadwal').on('shown.bs.modal', function () {
            initializeSelect2('.pengajar');
          });

        }
      });
    }

  });

  //modal jadwal
  $(".table").on('click', '.edit-pengajar-modal', function (event) {
    event.preventDefault();
    var currentBtn = $(this);
    nip = currentBtn.attr('data-nip');
    id = currentBtn.attr('data-id');
    $("#dtb_dosen_pengajar").slideUp();
    $('#add_dosen').hide();
    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas_jadwal/jadwal/dosen_pengajar/dosen_pengajar_edit.php",
      type: "post",
      data: { kelas_id: <?= $_POST['kelas_id'] ?>, nip: nip, id: id },
      success: function (data) {
        $("#form-input-jadwal-pengajar").html(data);
        $("#form-input-jadwal-pengajar").slideDown();
      }
    });
  });


  var dtb_jadwal_modal = $("#dtb_jadwal_modal").DataTable({
    'bProcessing': true,
    'ordering': false,
    "info": false,
    'paging': false,
    'bServerSide': true,
    'searching': false,

    //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
    "columnDefs": [
      {
        "targets": [0],
        "width": "15%",
        "orderable": false,
        "searchable": false
      }

    ],

    'ajax': {
      url: '<?= base_admin(); ?>modul/kelas_jadwal/jadwal/jadwal_data.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        //d.fakultas = $("#fakultas_filter").val();
        d.kelas_id = <?= $_POST['kelas_id'] ?>;
      },
      error: function (xhr, error, thrown) {
        console.log(xhr);

      }
    },
  });



  $(".table").on('click', '.hapus_dtb_notif_jadwal', function (event) {

    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    id_kelas = currentBtn.attr('data-kelas');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#modal-confirm-delete')
      .modal({ keyboard: false })
      .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          error: function (data) {
            $('#loadnya').hide();
            $('.isi_warning_delete_jadwal').html(data.responseText);
            $('.error_data_delete_jadwal').fadeIn();
            $('html, body').animate({
              scrollTop: ($('.error_data_delete_jadwal').first().offset().top)
            }, 500);
          },
          url: uri + "?act=delete",
          data: { id: id, kelas_id: id_kelas },
          success: function (responseText) {
            $("#loadnya").hide();
            console.log(responseText);
            $.each(responseText, function (index) {
              console.log(responseText[index].status);
              if (responseText[index].status == 'die') {
                $("#informasi").modal("show");
              } else if (responseText[index].status == 'error') {
                $('.isi_warning_delete_jadwal').text(responseText[index].error_message);
                $('.error_data_delete_jadwal').fadeIn();
                $('html, body').animate({
                  scrollTop: ($('.error_data_delete_jadwal').first().offset().top)
                }, 500);
              } else if (responseText[index].status == 'good') {
                $('.error_data_delete_jadwal').hide();
                dataTable_jadwal.draw(false);
                $('#modal_kelas_jadwal').modal('hide');
              }
            });
          }
        });
        $('#modal-confirm-delete').modal('hide');

      });
  });
  $(".table").on('click', '.hapus_dtb_notif_pengajar', function (event) {

    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#modal-confirm-delete')
      .modal({ keyboard: false })
      .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          error: function (data) {
            $('#loadnya').hide();
            $('.isi_warning_delete_pengajar').html(data.responseText);
            $('.error_data_delete_pengajar').fadeIn();
            $('html, body').animate({
              scrollTop: ($('.error_data_delete_pengajar').first().offset().top)
            }, 500);
          },
          url: uri + "?act=delete",
          data: { id: id },
          success: function (responseText) {
            $("#loadnya").hide();
            console.log(responseText);
            $.each(responseText, function (index) {
              console.log(responseText[index].status);
              if (responseText[index].status == 'die') {
                $("#informasi").modal("show");
              } else if (responseText[index].status == 'error') {
                $('.isi_warning_delete_pengajar').text(responseText[index].error_message);
                $('.error_data_delete_pengajar').fadeIn();
                $('html, body').animate({
                  scrollTop: ($('.error_data_delete_pengajar').first().offset().top)
                }, 500);
              } else if (responseText[index].status == 'good') {
                $('.error_data_delete_pengajar').hide();
                window[dtb_var].draw(false);
              }
            });
          }
        });
        $('#modal-confirm-delete').modal('hide');

      });
  });
</script>