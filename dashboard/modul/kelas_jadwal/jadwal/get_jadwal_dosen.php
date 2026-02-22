 <?php
session_start();
include "../../../inc/config.php";
session_check();

$hari = $_POST['hari'];
$sem_id = $_POST['sem_id'];
$id_dosen = $_POST['id_dosen'];

if (!empty($id_dosen)) {
    // Use array_map to add single quotes around each element
$quotedArray = array_map(function($item) {
    return "'" . $item . "'";
}, $id_dosen);

// Implode the quoted array into a single string
$id_dos = implode(", ", $quotedArray);


 $dosen = $db->query("select * from dosen where nip in($id_dos) and nip in(select id_dosen from view_jadwal_dosen_kelas where hari=? and sem_id=?)",array('hari' => $hari,'sem_id' => $sem_id));

 if ($dosen->rowCount()>0) {
    foreach ($dosen as $dos) {
?>
        <div class="box box-primary box-solid" style="margin-bottom: 8px;">
            <div class="box-header with-border">
                 <h3 class="box-title">Jadwal Mengajar <?=$dos->nama_dosen;?> - Hari <?=strtoupper($hari);?></h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="padding-bottom: 0;">
              <div class="row">
                <?php
$get_jadwal = $db->query("select *,(select jurusan from view_nama_kelas where kelas_id=view_jadwal_dosen_kelas.id_kelas) as jurusan  from view_jadwal_dosen_kelas where hari=? and sem_id=? and id_dosen='$dos->nip' order by jam_mulai asc",array('hari' => $hari,'sem_id' => $sem_id));
echo $db->getErrorMessage();
if ($get_jadwal->rowCount()>0) {
    $i = 1;
    foreach ($get_jadwal as $jadwal) {
        $nama_ruang = $db->fetch_single_row("ruang_ref","ruang_id",$jadwal->id_ruang);
        ?>
    <div class="col-md-6">
        <div class="schedule-item">
            <div class="dot work"><?=$i;?></div>
            <div class="time"><i class="fa fa-clock-o"></i> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?></div>
            <div class="description"><i class="fa fa-book"></i> <?=$jadwal->matkul_dosen;?></div>
            <i class="fa fa-building"></i> <?=$nama_ruang->nm_ruang;?><br>
            <div class="description"><i class="fa fa-fa-institution"></i> <?=$jadwal->jurusan;?></div>
            
        </div>
    </div>
        <?php
        $i++;
    }
}
?>
 </div>
            <!-- /.box-body -->
          </div>
        </div>
        <?php
    }
 }
}




