<?php
session_start();
include "../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];
$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi", array('id_pertemuan' => $_POST['pertemuan']));
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan", array('id_pertemuan' => $_POST['pertemuan']));


$kelas_data = $db2->fetchCustomSingle("SELECT kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where disetujui='1' and id_kelas=view_nama_kelas.kelas_id) as approved_krs
#(select count(id_krs_detail) from krs_detail where disetujui='0' and id_kelas=view_nama_kelas.kelas_id) as pending_krs 
from view_nama_kelas where kelas_id=?", array('kelas_id' => $kelas_id));
$dosen_pertemuan = explode("#", $pertemuan->getData()->nip_dosen);
$nip = sprintf("'%s'", implode("','", $dosen_pertemuan));

$counter = 1;
foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc", array("id_kelas" => $kelas_data->kelas_id)) as $isi) {
  $dosen_data[] = '- ' . $isi->nama_gelar;
  $counter++;
}
$nama_dosen = trim(implode("<br>", $dosen_data));

$nip_dosen = getUser()->username;

$dosen_name = $db2->fetchSingleRow("view_nama_gelar_dosen", "nip", $nip_dosen);

$fotos = $db2->fetchSingleRow("sys_users", "username", $nip_dosen);

$foto_dosen = $fotos->foto_user;


//check if pernah absen MASUK
$pernah_absen_masuk = $db2->fetchCustomSingle("
    SELECT 
      JSON_UNQUOTE(JSON_EXTRACT(kehadiran_dosen, '$[0].tanggal_absen')) AS tanggal_absen_masuk,
      JSON_UNQUOTE(JSON_EXTRACT(kehadiran_dosen, '$[0].foto_absen')) AS foto_absen_masuk,
      JSON_UNQUOTE(JSON_EXTRACT(kehadiran_dosen_keluar, '$[0].tanggal_absen')) AS tanggal_absen_keluar,
      JSON_UNQUOTE(JSON_EXTRACT(kehadiran_dosen_keluar, '$[0].foto_absen')) AS foto_absen_keluar
    FROM tb_data_kelas_pertemuan 
    WHERE id_pertemuan = ?
", array('id_pertemuan' => $_POST['pertemuan']));


$tanggal_absen_masuk = '';
$foto_masuk = '';
$tanggal_absen_keluar = '';
$foto_keluar = '';
$has_absen_masuk = 0;
$has_absen_keluar = 0;
$status_absen_masuk = '';
$status_absen_keluar = '';
$hide_camera_masuk = '';
$hide_camera_keluar = '';
$show_preview_masuk = 'display:none;';
$show_preview_keluar = 'display:none;';

if ($pernah_absen_masuk->tanggal_absen_masuk != "") {
  $tanggal_absen_masuk = '<i class="fa fa-clock-o"></i> ' . tgl_time($pernah_absen_masuk->tanggal_absen_masuk);
  $foto_masuk = $pernah_absen_masuk->foto_absen_masuk;
  $status_absen_masuk = '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i> Sudah</span>';
  $has_absen_masuk = 1;
  $hide_camera_masuk = 'display:none;';
  $show_preview_masuk = '';
} else {
  $status_absen_masuk = '<span class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Belum</span>';
  $has_absen_masuk = 0;
  $hide_camera_masuk = '';
  $show_preview_masuk = 'display:none;';
}

if ($pernah_absen_masuk->tanggal_absen_keluar != "") {
  $tanggal_absen_keluar = '<i class="fa fa-clock-o"></i> ' . tgl_time($pernah_absen_masuk->tanggal_absen_keluar);
  $foto_keluar = $pernah_absen_masuk->foto_absen_keluar;
  $status_absen_keluar = '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i> Sudah</span>';
  $has_absen_keluar = 1;
  $hide_camera_keluar = 'display:none;';
  $show_preview_keluar = '';
} else {
  $status_absen_keluar = '<span class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Belum</span>';
  $has_absen_keluar = 0;
  $hide_camera_keluar = '';
  $show_preview_keluar = 'display:none;';
}

?>
<style type="text/css">
  #presensi>tbody>tr>td,
  .table>tfoot>tr>td {
    vertical-align: middle;
    padding: 2px;
  }

  .help-block {
    color: #dd4b39;
  }

  .mt-checkbox {
    margin-bottom: 0
  }

  #presensi.dataTable {
    border-color: #9e9595;
  }

  .widget-user .widget-user-header {
    height: 104px;
  }

  #presensi_absen thead th {
    background: #00a7d0;
    /* biru aqua AdminLTE */
    color: #fff;
    text-align: center;
  }

  #presensi_absen tbody tr:hover {
    background: #f5f5f5;
  }

  #video,
  #preview {
    border: 2px solid #ddd;
  }

  .nav-tabs-custom {
    margin-top: 20px;
  }

  .tab-content {
    padding: 15px;
    border: 1px solid #ddd;
    border-top: none;
  }

  .camera-container {
    position: relative;
    margin-bottom: 15px;
  }

  .preview-photo {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    z-index: 10;
  }

  .photo-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    z-index: 20;
  }
</style>

<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td class="info2"><strong>Matakuliah</strong></td>
      <td><?= $kelas_data->kode_mk; ?> - <?= $kelas_data->nama_mk; ?> (<?= $kelas_data->total_sks; ?> sks) </td>
      <td class="info2"><strong>Kelas</strong></td>
      <td><?= $kelas_data->kls_nama; ?></td>
    </tr>
    <tr>
      <td class="info2"><strong>Dosen</strong></td>
      <td><?= $nama_dosen; ?></td>
      <td class="info2"><strong>Pertemuan</strong></td>
      <td><?= $pertemuan->getData()->pertemuan; ?></td>

    </tr>
  </tbody>
</table>

<div class="alert alert-success success-absensi" style="display:none">
  Absensi Berhasil
</div>

<div class="row">
  <div class="col-md-12">
    <!-- Tab untuk Absen Masuk dan Keluar -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_masuk" data-toggle="tab" aria-expanded="true">Absen Masuk</a></li>
        <li><a href="#tab_keluar" data-toggle="tab" aria-expanded="false">Absen Keluar</a></li>
      </ul>
      <div class="tab-content">
        <!-- Tab Absen Masuk -->
        <div class="tab-pane active" id="tab_masuk">
          <div class="box box-widget widget-user-2">
            <div class="widget-user-header bg-aqua-active">
              <div class="widget-user-image">
                <img class="img-circle" src="<?= base_url(); ?>upload/back_profil_foto/<?= $foto_dosen; ?>"
                  alt="User Avatar">
              </div>
              <h3 class="widget-user-username"><?= $dosen_name->nama_gelar; ?></h3>
              <h5 class="widget-user-desc">&nbsp;</h5>
            </div>
            <p>
            <div class="alert alert-info alert-dismissible">
              <h4><i class="icon fa fa-info"></i> Presensi Masuk Dosen</h4>
              <p>Ambil foto selfie untuk absen masuk pada pertemuan ini.</p>
            </div>

            <div class="box-footer">
              <!-- Camera Container untuk Absen Masuk -->
              <div class="camera-container text-center" style="margin-bottom:15px; height: 240px;">
                <!-- Preview Foto yang Sudah Diambil -->
                <img id="preview_photo_masuk" class="preview-photo" style="<?= $show_preview_masuk ?>"
                  alt="Foto Absen Masuk" src="<?= $foto_masuk; ?>">

                <!-- Badge untuk foto yang sudah diambil -->
                <div id="photo_badge_masuk" class="photo-badge" style="<?= $show_preview_masuk ?>">
                  <i class="fa fa-camera"></i> Foto Absen Masuk
                </div>

                <!-- Kamera Live -->
                <video id="video_masuk" autoplay playsinline muted
                  style="width:100%; height:100%; <?= $hide_camera_masuk ?> background:#000; border-radius:6px;"></video>
                <canvas id="canvas_masuk" style="display:none;"></canvas>
              </div>

              <!-- Tombol-tombol untuk Absen Masuk -->
              <div class="text-center" style="margin-top:10px;">
                <button class="btn btn-primary btn-sm" style="margin:2px;" id="switchBtn_masuk" <?= $has_absen_masuk ? 'disabled' : '' ?>>🔄 Ganti Kamera</button>
                <button id="selfieBtn_masuk" class="btn btn-success btn-sm" style="margin:2px;" <?= $has_absen_masuk ? '' : '' ?>>
                  <i class="fa fa-camera"></i> Ambil Selfie Masuk
                </button>
                <button id="retryBtn_masuk" class="btn btn-warning btn-sm" style="display:none; margin:2px;">
                  <i class="fa fa-refresh"></i> Ulangi
                </button>
                <button id="absen_masuk_btn" class="btn btn-primary btn-sm" style="display:none; margin:2px;">
                  <i class="fa fa-sign-in"></i> Absen Masuk
                </button>
                <?php if ($has_absen_masuk): ?>
                  <button id="ubah_foto_masuk" class="btn btn-info btn-sm" style="margin:2px;">
                    <i class="fa fa-edit"></i> Ubah Foto
                  </button>
                <?php endif; ?>
                <input type="hidden" id="image_base64_masuk">
              </div>

              <!-- Status Absen Masuk -->
              <div class="row text-center" style="margin-top:15px;">
                <div class="col-sm-6 border-right">
                  <h5 class="description-header">Status Absen Masuk</h5>
                  <span class="label <?= $has_absen_masuk ? 'label-success' : 'label-danger' ?> status-absen-masuk">
                    <?= $has_absen_masuk ? 'Sudah' : 'Belum' ?>
                  </span>
                </div>
                <div class="col-sm-6 border-right">
                  <h5 class="description-header">Tanggal Absen Masuk</h5>
                  <span class="label label-info tanggal-absen-masuk">
                    <?= $tanggal_absen_masuk ?: '-' ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.tab-pane -->

        <!-- Tab Absen Keluar -->
        <div class="tab-pane" id="tab_keluar">
          <div class="box box-widget widget-user-2">
            <div class="widget-user-header bg-red-active">
              <div class="widget-user-image">
                <img class="img-circle" src="<?= base_url(); ?>upload/back_profil_foto/<?= $foto_dosen; ?>"
                  alt="User Avatar">
              </div>
              <h3 class="widget-user-username"><?= $dosen_name->nama_gelar; ?></h3>
              <h5 class="widget-user-desc">&nbsp;</h5>
            </div>
            <p>
            <div class="alert alert-info alert-dismissible">
              <h4><i class="icon fa fa-info"></i> Presensi Keluar Dosen</h4>
              <p>Ambil foto selfie untuk absen keluar pada pertemuan ini.</p>
            </div>

            <div class="box-footer">
              <!-- Camera Container untuk Absen Keluar -->
              <div class="camera-container text-center" style="margin-bottom:15px; height: 240px;">
                <!-- Preview Foto yang Sudah Diambil -->
                <img id="preview_photo_keluar" class="preview-photo" style="<?= $show_preview_keluar ?>"
                  alt="Foto Absen Keluar" src="<?= $foto_keluar; ?>">

                <!-- Badge untuk foto yang sudah diambil -->
                <div id="photo_badge_keluar" class="photo-badge" style="<?= $show_preview_keluar ?>">
                  <i class="fa fa-camera"></i> Foto Absen Keluar
                </div>

                <!-- Kamera Live -->
                <video id="video_keluar" autoplay playsinline muted
                  style="width:100%; height:100%; <?= $hide_camera_keluar ?> background:#000; border-radius:6px;"></video>
                <canvas id="canvas_keluar" style="display:none;"></canvas>
              </div>

              <!-- Tombol-tombol untuk Absen Keluar -->
              <div class="text-center" style="margin-top:10px;">
                <button class="btn btn-primary btn-sm" style="margin:2px;" id="switchBtn_keluar" <?= $has_absen_keluar ? 'disabled' : '' ?>>🔄 Ganti Kamera</button>
                <button id="selfieBtn_keluar" class="btn btn-success btn-sm" style="margin:2px;" <?= $has_absen_keluar ? 'disabled' : '' ?>>
                  <i class="fa fa-camera"></i> Ambil Selfie Keluar
                </button>
                <button id="retryBtn_keluar" class="btn btn-warning btn-sm" style="display:none; margin:2px;">
                  <i class="fa fa-refresh"></i> Ulangi
                </button>
                <button id="absen_keluar_btn" class="btn btn-primary btn-sm" style="display:none; margin:2px;">
                  <i class="fa fa-sign-out"></i> Absen Keluar
                </button>
                <?php if ($has_absen_keluar): ?>
                  <button id="ubah_foto_keluar" class="btn btn-info btn-sm" style="margin:2px;">
                    <i class="fa fa-edit"></i> Ubah Foto
                  </button>
                <?php endif; ?>
                <input type="hidden" id="image_base64_keluar">
              </div>

              <!-- Status Absen Keluar -->
              <div class="row text-center" style="margin-top:15px;">
                <div class="col-sm-6 border-right">
                  <h5 class="description-header">Status Absen Keluar</h5>
                  <span class="label <?= $has_absen_keluar ? 'label-success' : 'label-danger' ?> status-absen-keluar">
                    <?= $has_absen_keluar ? 'Sudah' : 'Belum' ?>
                  </span>
                </div>
                <div class="col-sm-6 border-right">
                  <h5 class="description-header">Tanggal Absen Keluar</h5>
                  <span class="label label-info tanggal-absen-keluar">
                    <?= $tanggal_absen_keluar ?: '-' ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- /.nav-tabs-custom -->

    <!-- Notifikasi -->
    <div class="alert alert-success alert-dismissible success-absensi" style="display:none; margin-top:10px;">
      <i class="icon fa fa-check"></i> Absensi berhasil!
    </div>
    <div class="alert alert-danger alert-dismissible error_data_absen" style="display:none; margin-top:10px;">
      <i class="icon fa fa-warning"></i> <span class="isi_warning_absen"></span>
    </div>

  </div>
</div>

<div class="alert alert-danger error_data_absen" style="display:none">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <span class="isi_warning_absen"></span>
</div>
<form id="input_absensi" method="post" class="form-horizontal foto_banyak"
  action="<?= base_admin(); ?>modul/kelas/pertemuan/pertemuan_action.php?act=in_absen">
  <input type="hidden" name="id_pertemuan" value="<?= $_POST['pertemuan']; ?>">

  <?php
  if (!$has_absen_masuk) {
    ?>
    <div class="alert alert-danger alert-dismissible">
      <h4><i class="icon fa fa-info"></i> Sebelum Presensi Mahasiswa, Silakan Presensi Masuk Dosen terlebih dahulu!</h4>
    </div>
    <?php
  }
  ?>

  <table id="presensi_absen" class="table table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
      <tr class="bg-blue">
        <th>No</th>
        <th>NIM</th>
        <th>Nama</th>
        <th>Angkatan</th>

        <?php
        if ($check_pertemuan) {
          echo "<th>Tgl Input</th>";
          ?>
          <th class='all' style="padding-right:10px"><label class='mt-checkbox mt-checkbox-single'>Status <input
                type='checkbox' class='group-checkable bulk-check'> <span></span></label> </th>
          <?php
        } else {
          ?>
          <th class='all' style="padding-right:10px"><label class='mt-checkbox mt-checkbox-single '>Status <input
                type='checkbox' class='group-checkable bulk-check'> <span></span></label> </th>
          <?php
        }
        ?>

      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

  <div class="form-group" style="border-top: 1px solid #eee;padding-top: 5px;">
    <label for="Pengajar" class="control-label col-lg-2">&nbsp;</label>
    <div class="col-lg-10" style="text-align: right;">
      <button type="button" class="btn btn-default close-absensi"><i class="fa fa-close"></i>
        <?php echo $lang["cancel_button"]; ?></button>
      <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> Simpan Presensi</button>
    </div>
  </div><!-- /.form-group -->
</form>

<script>
  // Inisialisasi kamera untuk absen masuk jika belum ada foto
  if (!<?= $has_absen_masuk ? 'true' : 'false' ?>) {
    startCamera('masuk');
  }

  // Fungsi untuk memulai kamera
  async function startCamera(type) {
    var video = $('#video_' + type)[0];
    var facingMode = "user";

    try {
      if (window['stream_' + type]) {
        window['stream_' + type].getTracks().forEach(track => track.stop());
      }

      // Constraints yang lebih stabil untuk Safari
      const stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: facingMode,
          width: { ideal: 640 },
          height: { ideal: 480 }
        },
        audio: false
      });

      window['stream_' + type] = stream;
      video.srcObject = stream;

      // PERBAIKAN KHUSUS IPHONE:
      // Paksa video play saat metadata sudah siap
      video.onloadedmetadata = function () {
        video.play().catch(function (e) {
          console.log("Autoplay dicegah browser, butuh klik user", e);
        });
      };

      $('#video_' + type).show();
      window['facingMode_' + type] = facingMode;
    } catch (e) {
      console.error("Gagal akses kamera: ", e);
      // Jangan tampilkan alert terus-menerus di mobile agar tidak mengganggu
    }
  }

  // Fungsi untuk mengambil foto
  function takeSnapshot(type) {
    var video = $('#video_' + type)[0];
    var canvas = $('#canvas_' + type)[0];
    var ctx = canvas.getContext('2d');

    var w = video.videoWidth || 480;
    var h = video.videoHeight || 360;
    canvas.width = w;
    canvas.height = h;

    // Mirror effect untuk kamera depan
    ctx.save();
    if (window['facingMode_' + type] === "user") {
      ctx.translate(w, 0);
      ctx.scale(-1, 1);
    }
    ctx.drawImage(video, 0, 0, w, h);
    ctx.restore();

    var dataUrl = canvas.toDataURL('image/jpeg', 0.9);
    $('#image_base64_' + type).val(dataUrl);

    // Tampilkan preview di atas kamera
    $('#preview_photo_' + type).attr('src', dataUrl).show();
    $('#photo_badge_' + type).show();

    // Stop kamera setelah mengambil foto
    stopCamera(type);

    $('#video_' + type).hide();
    $('#selfieBtn_' + type).hide();
    $('#retryBtn_' + type).show();
    $('#absen_' + type + '_btn').show();
  }

  // Fungsi untuk menghentikan kamera
  function stopCamera(type) {
    if (window['stream_' + type]) {
      window['stream_' + type].getTracks().forEach(track => track.stop());
      window['stream_' + type] = null;
    }
  }

  // Fungsi untuk mengganti kamera
  async function switchCamera(type) {
    var video = $('#video_' + type)[0];
    var currentFacingMode = window['facingMode_' + type] || "user";
    var newFacingMode = currentFacingMode === "user" ? "environment" : "user";

    // Stop stream sebelumnya
    if (window['stream_' + type]) {
      window['stream_' + type].getTracks().forEach(track => track.stop());
    }

    try {
      // Get media stream dengan mode baru
      window['stream_' + type] = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: newFacingMode },
        audio: false
      });

      video.srcObject = window['stream_' + type];
      window['facingMode_' + type] = newFacingMode;
    } catch (e) {
      console.error("Tidak bisa mengganti kamera: " + e.message);
      // Fallback ke mode sebelumnya
      window['stream_' + type] = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: currentFacingMode },
        audio: false
      });
      video.srcObject = window['stream_' + type];
    }
  }

  // Fungsi untuk mengaktifkan mode ubah foto
  function enableEditPhoto(type) {
    // Sembunyikan preview foto
    $('#preview_photo_' + type).hide();
    $('#photo_badge_' + type).hide();

    // Reset form
    $('#image_base64_' + type).val('');

    // Tampilkan tombol kamera
    $('#selfieBtn_' + type).show().text('Ambil Selfie ' + (type === 'masuk' ? 'Masuk' : 'Keluar'));
    $('#retryBtn_' + type).hide();
    $('#absen_' + type + '_btn').hide();
    $('#ubah_foto_' + type).hide();
    $('#switchBtn_' + type).prop('disabled', false);

    // Mulai kamera
    startCamera(type);
  }

  // Event handlers untuk ABSEN MASUK
  $('#selfieBtn_masuk').click(async function () {
    if (!window['stream_masuk']) {
      await startCamera('masuk');
    } else {
      takeSnapshot('masuk');
    }
  });

  $('#retryBtn_masuk').click(async function () {
    $('#preview_photo_masuk').hide();
    $('#photo_badge_masuk').hide();
    $('#image_base64_masuk').val('');
    $('#retryBtn_masuk').hide();
    $('#selfieBtn_masuk').show().text('Ambil Selfie Masuk');
    $('#absen_masuk_btn').hide();
    await startCamera('masuk');
  });

  $('#switchBtn_masuk').click(async function () {
    await switchCamera('masuk');
  });

  // Tombol Ubah Foto Masuk
  $('#ubah_foto_masuk').click(function () {
    enableEditPhoto('masuk');
  });

  // Event handlers untuk ABSEN KELUAR
  $('#selfieBtn_keluar').click(async function () {
    if (!window['stream_keluar']) {
      await startCamera('keluar');
    } else {
      takeSnapshot('keluar');
    }
  });

  $('#retryBtn_keluar').click(async function () {
    $('#preview_photo_keluar').hide();
    $('#photo_badge_keluar').hide();
    $('#image_base64_keluar').val('');
    $('#retryBtn_keluar').hide();
    $('#selfieBtn_keluar').show().text('Ambil Selfie Keluar');
    $('#absen_keluar_btn').hide();
    await startCamera('keluar');
  });

  $('#switchBtn_keluar').click(async function () {
    await switchCamera('keluar');
  });

  // Tombol Ubah Foto Keluar
  $('#ubah_foto_keluar').click(function () {
    enableEditPhoto('keluar');
  });

  // Tombol Absen Masuk
  $("#absen_masuk_btn").click(function () {
    $("#loadnya").show();
    event.preventDefault();
    var foto = $('#image_base64_masuk').val();
    var tipe = 'masuk';

    // Validasi foto harus ada
    if (!foto) {
      alert("Harap ambil foto terlebih dahulu!");
      $("#loadnya").hide();
      return false;
    }

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas/presensi/input_absen_dosen.php",
      type: "post",
      dataType: "json",
      data: {
        nip: "<?= $nip_dosen; ?>",
        kelas_id: "<?= $kelas_data->kelas_id ?>",
        pert: <?= $_POST['pertemuan']; ?>,
        selfie: foto,
        tipe: tipe
      },
      error: function (data) {
        $("#loadnya").hide();
        console.log(data);
      },
      success: function (responseText) {
        $("#loadnya").hide();
        console.log(responseText);
        $.each(responseText, function (index) {
          console.log(responseText[index].status);
          if (responseText[index].status == "die") {
            $("#informasi").modal("show");
          } else if (responseText[index].status == "error") {
            $(".isi_warning_absen").text(responseText[index].error_message);
            $(".error_data_absen").focus()
            $(".error_data_absen").fadeIn();
          } else if (responseText[index].status == "good") {
            $(".error_data_absen").hide();
            $(".success-absensi").fadeIn(1000);
            $(".success-absensi").fadeOut(1000, function () {
              // Update tampilan status
              $('.status-absen-masuk').html(responseText[index].status_absen);
              $('.tanggal-absen-masuk').html(responseText[index].tanggal_absen);

              // Update tampilan tombol
              $('#selfieBtn_masuk').prop('disabled', true);
              $('#switchBtn_masuk').prop('disabled', true);
              $('#absen_masuk_btn').hide();
              $('#ubah_foto_masuk').show();

              dtb_presensi.draw();
              dtb_approved_krs.draw();
            });
          }
        });
      }
    });
  });

  // Tombol Absen Keluar
  $("#absen_keluar_btn").click(function () {
    $("#loadnya").show();
    event.preventDefault();
    var foto = $('#image_base64_keluar').val();
    var tipe = 'keluar';

    // Validasi foto harus ada
    if (!foto) {
      alert("Harap ambil foto terlebih dahulu!");
      $("#loadnya").hide();
      return false;
    }

    $.ajax({
      url: "<?= base_admin(); ?>modul/kelas/presensi/input_absen_dosen.php",
      type: "post",
      dataType: "json",
      data: {
        nip: "<?= $nip_dosen; ?>",
        kelas_id: "<?= $kelas_data->kelas_id ?>",
        pert: <?= $_POST['pertemuan']; ?>,
        selfie: foto,
        tipe: tipe
      },
      error: function (data) {
        $("#loadnya").hide();
        console.log(data);
      },
      success: function (responseText) {
        $("#loadnya").hide();
        console.log(responseText);
        $.each(responseText, function (index) {
          console.log(responseText[index].status);
          if (responseText[index].status == "die") {
            $("#informasi").modal("show");
          } else if (responseText[index].status == "error") {
            $(".isi_warning_absen").text(responseText[index].error_message);
            $(".error_data_absen").focus()
            $(".error_data_absen").fadeIn();
          } else if (responseText[index].status == "good") {
            $(".error_data_absen").hide();
            $(".success-absensi").fadeIn(1000);
            $(".success-absensi").fadeOut(1000, function () {
              // Update tampilan status
              $('.status-absen-keluar').html(responseText[index].status_absen);
              $('.tanggal-absen-keluar').html(responseText[index].tanggal_absen);

              // Update tampilan tombol
              $('#selfieBtn_keluar').prop('disabled', true);
              $('#switchBtn_keluar').prop('disabled', true);
              $('#absen_keluar_btn').hide();
              $('#ubah_foto_keluar').show();

              dtb_presensi.draw();
              dtb_approved_krs.draw();
            });
          }
        });
      }
    });
  });

  $('.close-absensi').click(function () {
    $('#modal_input_absen').modal('hide');
    $('#modal_peserta_kelas').focus(); // Or focus a button inside it
  });



  var presensi = $("#presensi_absen").DataTable({
    'dom': "",
    'ordering': false,
    'bProcessing': true,
    'bServerSide': true,
    paging: false,

    //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
    "columnDefs": [

      {
        "targets": [0],
        "width": "5%",
        "orderable": false,
        "searchable": false,
        "class": "dt-center"
      },
      {
        "targets": [-1],
        "orderable": false,
        "searchable": false,
        "class": "all"
      }
    ],

    'ajax': {
      url: '<?= base_admin(); ?>modul/kelas/presensi/input_absensi_data.php',
      type: 'post',  // method  , by default get
      data: function (d) {
        //d.fakultas = $("#fakultas_filter").val();
        d.id_pertemuan = <?= $_POST['pertemuan']; ?>;
        d.kelas_id = <?= $_POST['kelas_id']; ?>;
      },
      error: function (xhr, error, thrown) {
        console.log(xhr);
      }
    },
    "createdRow": function (row, data, dataIndex) {
      val = $(row).find(":selected").val();
      if (val == 'Ijin') {
        $(row).css("background-color", "#bcfffc");
      } else if (val == 'Sakit') {
        $(row).css("background-color", "#ffffae");
      } else if (val == 'Alpa') {
        $(row).css("background-color", "#ffb4b4");
      }
      //var oData = row.rows('.selected').data();
    }
  });

  $("#presensi").on('change', '.absen-val', function (event) {
    event.preventDefault();
    val = this.value;
    console.log(val);
    if (val == 'Ijin') {
      $(this).parents('tr').css("background-color", "#bcfffc");
    } else if (val == 'Sakit') {
      $(this).parents('tr').css("background-color", "#ffffae");
    } else if (val == 'Alpa') {
      $(this).parents('tr').css("background-color", "#ffb4b4");
    } else {
      $(this).parents('tr').css("background-color", "#f9f9f9");
    }
  })
  $('.bulk-check').click(function () {
    var status = this.checked;
    if (status) {
      presensi.$('select').val('Hadir');
      presensi.$('select').parents('tr').css("background-color", "#f9f9f9");
      $("#input_absensi").valid();
    } else {
      presensi.$('select').val('');
      $("#input_absensi").valid();
    }

  });

  $("#input_absensi").validate({
    errorClass: "help-block",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
      $(element).removeClass(
        "has-success").addClass("has-error");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass(
        "has-error").addClass("has-success");
    },
    errorPlacement: function (error, element) {
      if (element.hasClass("chzn-select")) {
        var id = element.attr("id");
        error.insertAfter("#" + id + "_chosen");
      }
      if (element.hasClass("absen-val")) {
        element.parent().append(error);
      } else if (element.hasClass("tgl_picker_input")) {
        element.parent().parent().append(error);
      } else if (element.hasClass("file-upload-data")) {
        element.parent().parent().parent().append(error);
      } else if (element.attr("type") == "checkbox") {
        element.parent().parent().append(error);
      } else if (element.attr("type") == "radio") {
        element.parent().parent().append(error);
      } else {
        error.insertAfter(element);
      }
    },
    submitHandler: function (form) {
      $("#loadnya").show();
      // var val_absen = presensi.$('select').serialize();
      var foto = $('#image_base64_masuk').val(); // ambil foto selfie
      $(form).ajaxSubmit({
        url: $(this).attr("action"),
        dataType: "json",
        type: "post",
        error: function (data) {
          $("#loadnya").hide();
          console.log(data);
          $(".isi_warning_absen").html(data.responseText);
          $(".error_data_absen").focus()
          $(".error_data_absen").fadeIn();
        },
        success: function (responseText) {
          $("#loadnya").hide();
          console.log(responseText);
          $.each(responseText, function (index) {
            console.log(responseText[index].status);
            if (responseText[index].status == "die") {
              $("#informasi").modal("show");
            } else if (responseText[index].status == "error") {
              $(".isi_warning_absen").text(responseText[index].error_message);
              $(".error_data_absen").focus()
              $(".error_data_absen").fadeIn();
            } else if (responseText[index].status == "good") {
              $(".error_data_absen").hide();
              $(".notif_top_up").fadeIn(1000);
              $(".notif_top_up").fadeOut(1000, function () {
                $('#modal_input_absen').modal('hide');
                dtb_presensi.draw();
                dtb_approved_krs.draw();
              });
            }
          });
        }

      });
    }
  });

  // Aktifkan kamera saat tab berubah
  // Pastikan kamera nyala saat tab diklik (untuk iPhone)
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    var type = (target === '#tab_masuk') ? 'masuk' : 'keluar';

    // Cek apakah user sudah absen atau belum dari variabel PHP
    var sudahAbsen = (type === 'masuk') ? <?= $has_absen_masuk ? 'true' : 'false' ?> : <?= $has_absen_keluar ? 'true' : 'false' ?>;

    if (!sudahAbsen) {
      startCamera(type);
    }
  });

</script>