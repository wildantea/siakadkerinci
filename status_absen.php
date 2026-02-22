<?php
session_start();
include "dashboard/inc/config.php";

// Contoh: ambil data untuk 1 dosen (NIP) dan 1 mata kuliah (kelas_id)
$nip_dosen = "199012112019031007";
$kelas_id  = 64600;

$id_pertemuan = $_GET['id'];


$row_data = $db->fetch_custom_single("SELECT * FROM tb_data_kelas_pertemuan 
        WHERE id_pertemuan=?",array('id_pertemuan' => $id_pertemuan));

$kelas = $db->fetch_custom_single("select * from view_nama_kelas where kelas_id=?",array('kelas_id' => $row_data->kelas_id));



$header_attributes = $db2->fetchCustomSingle("SELECT vk.kode_jur,vk.kls_nama,vk.sks,vk.kode_mk,vk.nama_mk,vjk.hari as nama_hari,vjk.jam_mulai,vjk.jam_selesai,nm_ruang,vk.jurusan as nama_jurusan,view_semester.tahun_akademik,vk.kelas_id,
(select group_concat(nama_gelar separator '#')
 from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=vjk.kelas_id and jadwal_id=vjk.jadwal_id order by dosen_ke asc) as nama_dosen,
(
  select count(id_krs_detail) as jml_peserta FROM krs_detail where id_kelas=vk.kelas_id
) as jml_peserta
  from view_nama_kelas vk
INNER JOIN view_semester on vk.sem_id=view_semester.id_semester
LEFT JOIN view_jadwal vjk using(kelas_id)
 WHERE vk.kelas_id=? ORDER BY nama_dosen ASC",array('kelas_id' => $kelas->kelas_id));

$counter = 1;
$dosen_datas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc",array("id_kelas" => $kelas->kelas_id));
if ($dosen_datas->rowCount()>1) {
    foreach ($dosen_datas as $dt) {
        $dosen_data[] = $counter.'. '.$dt->nama_gelar;
        $counter++;
        $data_dosen[$dt->nip] = $dt->nama_gelar;
    }
} else {
    foreach ($dosen_datas as $dt) {
        $dosen_data[] = $dt->nama_gelar;
        $data_dosen[$dt->nip] = $dt->nama_gelar;
    }
}


  $array_login_as = array('admin');

$absensi = [];
$dosen = "";
$matakuliah = "Pemrograman Web"; // bisa join tabel jadwal/kelas kalau ada

    $row = $db->convert_obj_to_array($row_data);
    $dosen = $row['nip_dosen']; // bisa di-join dengan tabel dosen utk ambil nama
    $tanggal = $row['tanggal_pertemuan'];

    // decode JSON absensi dosen
    $kehadiran = json_decode($row['kehadiran_dosen'], true);

    if (!empty($kehadiran)) {
        foreach($kehadiran as $hadir) {
            $status = ($hadir['sesuai_jadwal'] == "Y") ? "hadir_tepat" : "hadir_diluar";
            $absensi[] = [
                "tanggal" => substr($hadir['tanggal_absen'],0,10),
                "status"  => $status,
                "pengajar" =>  $data_dosen[$hadir['nip']],
                "foto_absen" => $hadir['foto_absen'],
                "jam_absen" => $hadir['tanggal_absen']
            ];
        }
    } else {
        $absensi[] = [
            "tanggal" => $tanggal,
            "status"  => "Alfa",
            "jam_absen" => null
        ];
    }
?>
<?php
// ... PHP logic tetap sama, saya fokus di UI bagian bawah
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Status Absensi Dosen</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,.05); }
    .table thead th { background-color: #0d6efd; color: #fff; }
    .info-table td { padding: 6px 12px; vertical-align: top; }
    .img-thumbnail { max-width: 80px; }
  </style>
</head>
<body>

<div class="container py-4">

  <h2 class="mb-4 text-center">📊 Status Absensi Dosen</h2>

  <!-- Informasi -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <strong>Informasi Kelas</strong>
    </div>
    <div class="card-body">
      <table class="info-table w-100">
        <tr>
          <td><strong>Kelas / Ruang</strong></td><td>:</td>
          <td><?= $header_attributes->kls_nama ?> / <?= $header_attributes->nm_ruang ?></td>
          <td><strong>Program Studi</strong></td><td>:</td>
          <td><?= $header_attributes->nama_jurusan ?></td>
        </tr>
        <tr>
          <td><strong>Matakuliah</strong></td><td>:</td>
          <td><?= $header_attributes->kode_mk." - ".$header_attributes->nama_mk ?></td>
          <td><strong>Hari / Jam</strong></td><td>:</td>
          <td><?= ucwords($header_attributes->nama_hari) ?>, <?= $header_attributes->jam_mulai.' - '.$header_attributes->jam_selesai ?></td>
        </tr>
        <tr>
          <td><strong>Dosen</strong></td><td>:</td>
          <td>
            <?php foreach($dosen_data as $ds) echo $ds."<br>"; ?>
          </td>
          <td><strong>Jumlah Peserta</strong></td><td>:</td>
          <td><?= $header_attributes->jml_peserta ?></td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Absensi -->
  <div class="card">
    <div class="card-header bg-success text-white">
      <strong>Rekap Absensi</strong>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead>
          <tr>
             <th>Pertemuan</th>
            <th>Jadwal Kelas</th>
            <th>Jam Absen</th>
            <th>Pengajar</th>
            <th>Status</th>
            <th>Foto</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($absensi as $row): 
            ?>
          <tr>
             <td><?=$row_data->pertemuan;?></td>
            <td>
              <?= getHariFromDate($row_data->tanggal_pertemuan).', '.tgl_indo($row_data->tanggal_pertemuan) ?>
              <br><span class="text-muted"><?= substr($row_data->jam_mulai,0,5).' - '.substr($row_data->jam_selesai,0,5) ?></span>
            </td>
            <td>
              <?= getHariFromDate($row['tanggal']).', '.tgl_indo($row['tanggal']) ?>
              <br><span class="badge bg-secondary"><?= date("H:i:s", strtotime($row["jam_absen"])) ?></span>
              <?php
               if (in_array($_SESSION['group_level'], $array_login_as)) {

                echo "<span class='btn btn-primary btn-sm edit-absen' data-id='".$id_pertemuan."'>Edit</span>";

              }

              ?>
            </td>
            <td>
              <?=$row['pengajar']?>
            </td>
            <td>
              <?php if($row["status"] == "hadir_tepat"): ?>
                <span class="badge bg-success">Hadir Sesuai Jadwal</span>
              <?php elseif($row["status"] == "hadir_diluar"): ?>
                <span class="badge bg-warning text-dark">Hadir Tidak Sesuai</span>
              <?php else: ?>
                <span class="badge bg-danger">Alfa</span>
              <?php endif; ?>
          <td>
  <?php if (!empty($row['foto_absen'])): ?>
    <img src="<?= $row['foto_absen'] ?>" 
         alt="Foto Absen" 
         class="img-thumbnail" 
         style="cursor:pointer; max-width:80px"
         data-bs-toggle="modal" 
         data-bs-target="#fotoModal" 
         onclick="showFoto('<?= $row['foto_absen'] ?>')">
  <?php else: ?>
    <span class="text-muted">Belum ada foto</span>
  <?php endif; ?>
</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<!-- Modal Zoom Foto -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img id="modalFoto" src="" class="img-fluid rounded" alt="Foto Absen">
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Absen -->
<div class="modal fade" id="editAbsenModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <!-- AJAX content will be loaded here -->
    </div>
  </div>
</div>
    <script src="<?=base_admin();?>assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>

<script>
function showFoto(src) {
  document.getElementById("modalFoto").src = src;
}
 <?php
               if (in_array($_SESSION['group_level'], $array_login_as)) {
?>
   $(document).ready(function() {
    $(".edit-absen").click(function(e) {
        e.preventDefault();
        var id = $(this).data("id");

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/presensi/pindah_absen.php",
            type : "post",
            data : { pertemuan:id },
            success: function(data) {
                console.log("AJAX OK, data:", data);

                $("#editAbsenModal .modal-content").html(data);

                var modal = new bootstrap.Modal(document.getElementById("editAbsenModal"));
                modal.show();
            },
            error: function(xhr, status, err) {
                console.error("AJAX ERROR:", err);
            }
        });
    });
});
<?php
}
?>
</script>

</body>
</html>

