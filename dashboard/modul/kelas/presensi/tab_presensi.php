<?php
$cek_rps = $db2->fetchCustomSingle("select * from rps_file where semester='$kelas->sem_id' and id_matkul='$kelas->id_matkul'");
$cek_materi = $db2->fetchCustomSingle("select * from rps_materi_kuliah where id_kelas='$kelas->kelas_id'");
if ($cek_rps == false) {
  ?>
  <div class="box-header">
    <h3 class="box-title">Silakan Upload File RPS</h3>
  </div>

  <?php
} elseif ($cek_materi == false) {
  ?>
  <div class="box-header">
    <h3 class="box-title">Silakan Upload Materi Kuliah </h3>
  </div>

  <?php
} else {
  ?>
  <table id="dtb_presensi" class="table table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
      <tr>
        <th>Pert</th>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Pengajar</th>
        <!--  <th>Materi</th> -->
        <th>Peserta</th>
        <th>Hadir</th>
        <th>%</th>
        <th>Dosen Masuk</th>
        <th>Dosen Keluar</th>
        <th>Status Pertemuan</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>


  <script type="text/javascript">
    $("#dtb_presensi").on('click', '.edit_data', function (event) {
      event.preventDefault();
      var currentBtn = $(this);
      id = currentBtn.attr('data-id');
      $("#loadnya").show();
      //$(this).find('.fa').toggleClass('fa-plus fa-minus');
      $('.button-top').hide();
      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas/presensi/pertemuan_edit.php",
        type: "POST",
        data: { id_pertemuan: id },
        success: function (data) {
          $("#loadnya").hide();
          $("#isi_tambah_pertemuan").html(data);
          $("#isi_tambah_pertemuan").slideDown();
          $('.action-title').html('<b>Edit Pertemuan</b>');

        }
      });
    });

    //modal jadwal
    $("#dtb_presensi").on('click', '.input-absen', function (event) {
      // $("#loadnya").show();
      event.preventDefault();
      var currentBtn = $(this);

      id = currentBtn.attr('data-id');
      kelas_id = currentBtn.attr('data-kelas');

      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas/presensi/input_absensi_view.php",
        type: "post",
        data: { kelas_id: kelas_id, pertemuan: id },
        success: function (data) {
          $("#input_mahasiswa_absen").html(data);
          $(".modal-title-absen").html("Isi Presensi Mahasiswa");
          $("#loadnya").hide();
        }
      });

      $('#modal_input_absen').modal({ keyboard: false, backdrop: 'static' });

    });


    $("#dtb_presensi").on('click', '.pindah-jadwal-admin', function (event) {
      $("#loadnya").show();
      event.preventDefault();
      var currentBtn = $(this);

      id = currentBtn.attr('data-id');
      kelas_id = currentBtn.attr('data-kelas');

      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas/presensi/pindah_jadwal_admin.php",
        type: "post",
        data: { kelas_id: kelas_id, pertemuan: id },
        success: function (data) {
          $("#input_mahasiswa_absen").html(data);
          $(".modal-title-absen").html("Pindah Jadwal Perkuliahan");
          $("#loadnya").hide();
        }
      });

      $('#modal_input_absen').modal({ keyboard: false, backdrop: 'static' });

    });

    $("#dtb_presensi").on('click', '.pindah-jadwal', function (event) {
      $("#loadnya").show();
      event.preventDefault();
      var currentBtn = $(this);

      id = currentBtn.attr('data-id');
      kelas_id = currentBtn.attr('data-kelas');

      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas/presensi/pindah_jadwal.php",
        type: "post",
        data: { kelas_id: kelas_id, pertemuan: id },
        success: function (data) {
          $("#input_mahasiswa_absen").html(data);
          $(".modal-title-absen").html("Pindah Jadwal Perkuliahan");
          $("#loadnya").hide();
        }
      });

      $('#modal_input_absen').modal({ keyboard: false, backdrop: 'static' });

    });


    $("#dtb_presensi").on('click', '.input-materi', function (event) {
      $("#loadnya").show();
      event.preventDefault();
      var currentBtn = $(this);

      id = currentBtn.attr('data-id');
      kelas_id = currentBtn.attr('data-kelas');

      $.ajax({
        url: "<?= base_admin(); ?>modul/kelas/presensi/input_bukti.php",
        type: "post",
        data: { kelas_id: kelas_id, pertemuan: id },
        success: function (data) {
          $("#input_mahasiswa_absen").html(data);
          $(".modal-title-absen").html("Isi Materi Pertemuan");
          $("#loadnya").hide();
        }
      });

      $('#modal_input_absen').modal({ keyboard: false, backdrop: 'static' });

    });

    $("#tambah_pertemuan").click(function () {
      if ($("#isi_tambah_pertemuan").is(':visible')) {
        $(this).find('.fa').toggleClass('fa-plus fa-minus');
        $("#isi_tambah_pertemuan").html('');
        $("#isi_tambah_pertemuan").slideUp();
      } else {
        $("#loadnya").show();
        console.log('hallo');
        $('.button-top').hide();
        $.ajax({
          url: "<?= base_admin(); ?>modul/kelas/pertemuan/pertemuan_add.php",
          type: "POST",
          data: { kelas_id: <?= $kelas_id ?> },
          success: function (data) {
            $("#loadnya").hide();
            $("#isi_tambah_pertemuan").html(data);
            $("#isi_tambah_pertemuan").slideDown();
            $('.action-title').html('<b>Tambah Pertemuan</b>');

          }
        });
      }
    });


    var dtb_presensi = $("#dtb_presensi").DataTable({
      'dom': "",
      pageLength: -1,
      //"ordering" : false,
      responsive: true,
      'bProcessing': true,
      'bServerSide': true,

      "columnDefs": [
        {
          "targets": [0],
          "width": "2%",
          "orderable": false,
          "searchable": false,
        },
        {
          "targets": [0, 7],
          "class": "all"
        }

      ],

      'ajax': {
        url: '<?= base_admin(); ?>modul/kelas/presensi/tab_presensi_data.php',
        type: 'post',  // method  , by default get
        data: function (d) {
          //d.fakultas = $("#fakultas_filter").val();
          d.kelas_id = <?= $kelas_id ?>;
        },
        error: function (xhr, error, thrown) {
          console.log(xhr);

        }
      },
    });
  </script>
  <?php
}
?>