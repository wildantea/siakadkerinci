<div class="box ">
  <div class="box-header ">
    <h3 class="box-title">Dosen Pengajar</h3>
  </div>
  <div class="box-body">
    <table id="dtb_dosen_pengajar" class="table table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
         <tr>
          <th>NIP</th>
          <th>Nama</th>                   
          <th>Jadwal</th>
          <th>Ruang</th>
         </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>
  </div>
</div>


<script type="text/javascript">
        var dtb_dosen_pengajar = $("#dtb_dosen_pengajar").DataTable({
           'bProcessing': true,
           "info": false,
           'paging' : false,
            'bServerSide': true,
            'searching' : false,
            'ordering' : false,
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/pengajar/tab_pengajar_data.php',
              type: 'post',  // method  , by default get
              data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id;?>;
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
</script>

<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title action-title-rps">Presensi Dosen</h3>
  </div>
  <div class="box-body">
  <?php
  $row_datas = $db->query("SELECT * FROM tb_data_kelas_pertemuan 
        WHERE kelas_id=? and kehadiran_dosen !=''",array('kelas_id' => $kelas_id));

  $counter = 1;
$dosen_datas = $db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc",array("id_kelas" => $kelas_id));
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


$absensi = [];
$dosen = "";

foreach ($row_datas as $row_data) {



    $row = $db->convert_obj_to_array($row_data);
    $dosen = $row['nip_dosen']; // bisa di-join dengan tabel dosen utk ambil nama
    $tanggal = $row['tanggal_pertemuan'];

    // decode JSON absensi dosen
    $kehadiran = json_decode($row['kehadiran_dosen'], true);

    if (!empty($kehadiran)) {
        foreach($kehadiran as $hadir) {
            $status = ($hadir['sesuai_jadwal'] == "Y") ? "hadir_tepat" : "hadir_diluar";
            $absensi[] = [
                "tanggal_absen" => substr($hadir['tanggal_absen'],0,10),
                 "tanggal_pertemuan" => $tanggal,
                "status"  => $status,
                "pertemuan" => $row['pertemuan'],
                "pengajar" =>  $data_dosen[$hadir['nip']],
                "foto_absen" => $hadir['foto_absen'],
                "jam_absen" => $hadir['tanggal_absen']
            ];
        }
    } else {
        $absensi[] = [
            "tanggal_absen" => $tanggal,
            "tanggal_pertemuan" => $tanggal,
            "status"  => "Alfa",
            "jam_absen" => null
        ];
    }
}

        ?>

<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Informasi</h4>
                Baris Berwarna Hijau adalah pertemuan minggu ini
              </div>

<table class="table table-bordered table-striped display responsive nowrap" width="100%">
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
  // Hitung minggu & tahun dari tanggal_pertemuan
    $week_pertemuan = date("W", strtotime($row['tanggal_pertemuan']));
    $year_pertemuan = date("o", strtotime($row['tanggal_pertemuan']));

    $week_now = date("W");
    $year_now = date("o");


    // Tentukan class highlight
    $rowClass = ($week_pertemuan == $week_now && $year_pertemuan == $year_now) ? "style='background:#dff0d8'" : "";


     ?>
    <tr <?= $rowClass?>>
      <td><?= $row['pertemuan']; ?></td>
      <td>
        <?= getHariFromDate($row['tanggal_pertemuan']).', '.tgl_indo($row['tanggal_pertemuan']) ?>
        <br><span class="text-muted"><?= substr($row_data->jam_mulai,0,5).' - '.substr($row_data->jam_selesai,0,5) ?></span>
      </td>
      <td>
        <?= getHariFromDate($row['tanggal_absen']).', '.tgl_indo($row['tanggal_absen']) ?>
        <br><span class="label label-default"><?= date("H:i:s", strtotime($row["jam_absen"])) ?></span>
      </td>
      <td><?= $row['pengajar'] ?></td>
      <td>
        <?php if($row["status"] == "hadir_tepat"): ?>
          <span class="label label-success">Hadir Sesuai Jadwal</span>
        <?php elseif($row["status"] == "hadir_diluar"): ?>
          <span class="label label-warning">Hadir Tidak Sesuai</span>
        <?php else: ?>
          <span class="label label-danger">Alfa</span>
        <?php endif; ?>
      </td>
      <td>
        <?php if (!empty($row['foto_absen'])): ?>
          <img src="<?= $row['foto_absen'] ?>" 
               alt="Foto Absen" 
               class="img-thumbnail" 
               style="cursor:pointer; max-width:80px"
               data-toggle="modal" 
               data-target="#fotoModal" 
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

<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title action-title-rps">RPS</h3>
  </div>
  <div class="box-body">

     <table id="dtb_file" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Tanggal Upload</th>
                                  <th>Pengupload</th>
                                  <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

        <script type="text/javascript">
      var dtb_file = $("#dtb_file").DataTable({
              
           'order' : [[1,'asc']],
           'paging' : false,
           'searching' : false,
           'bProcessing': true,
            'bServerSide': true,
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/rps/data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id?>;
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
</script>

  </div>
</div>

<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title action-title-rps">Materi Kuliah</h3>
  </div>
  <div class="box-body">  

     <table id="dtb_materi" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Pertemuan</th>
                                  <th>Materi</th>
                                  <th>Link Materi/Bukti Ajar</th>
                                  <th>Tgl Upload</th>
                                  <th>Uploader</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>


        <script type="text/javascript">
      var dtb_materi = $("#dtb_materi").DataTable({
              
           'order' : [[1,'asc']],
           'paging' : false,
           'searching' : false,
           'bProcessing': true,
            'bServerSide': true,
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/rps/materi_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id?>;
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

</script>
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
<script>
function showFoto(src) {
  document.getElementById("modalFoto").src = src;
}
</script>

  </div>
</div>


                      