 <?php
session_start();
include "../../../../inc/config.php";
session_check();

$hari = $_POST['hari'];
$ruang_id = $_POST['ruang_id'];
$sem_id = $_POST['sem_id'];

$kelas_name = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);


$get_jadwal_kelas = $db->query("select view_jadwal.jam_mulai,view_jadwal.jam_selesai,view_nama_kelas.nm_matkul,(select group_concat(id_dosen) from dosen_kelas where id_kelas=view_nama_kelas.kelas_id) as id_dosen from view_jadwal inner join view_nama_kelas using(kelas_id) where kelas_id!='".$_POST['kelas_id']."' and view_jadwal.sem_id=? and kelas_jadwal=? and kode_jur=? and sem_matkul=? and hari=? order by jam_mulai asc",array('sem_id' => $sem_id,'kelas_jadwal' => $kelas_name->nama_kelas,'kode_jur' => $kelas_name->kode_jur,'sem_matkul' => $kelas_name->sem_matkul,'hari' => $hari));

echo $db->getErrorMessage();
if ($get_jadwal_kelas->rowCount()>0) {
    ?>
        <div class="box box-primary box-solid" style="margin-bottom: 8px;margin-top: 13px;">
            <div class="box-header with-border">
              <h3 class="box-title">Jadwal Kelas <?=$kelas_name->nama_kelas;?> - Hari <?=strtoupper($hari);?></h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="padding-bottom: 0;">
              <div class="row">
    <?php
    $i = 1;
    foreach ($get_jadwal_kelas as $jadwal) {
        $nama_dosen = array();
        $id_dos = explode(",", $jadwal->id_dosen);
        foreach ($id_dos as $id_d) {
            $dosen = $db->fetch_single_row("dosen","nip",$id_d);
            $nama_dosen[] = $dosen->nama_dosen;
        }
        
        ?>
    <div class="col-md-6">
        <div class="schedule-item">
            <div class="dot work"><?=$i;?></div>
            <div class="time"><i class="fa fa-clock-o"></i> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?></div>
            <div class="description"><i class="fa fa-book"></i> <?=$jadwal->nm_matkul;?></div>
            <?php
            foreach ($nama_dosen as $dosen_nama) {
                ?>
                <i class="fa fa-user"></i> <?=$dosen_nama;?><br>
                <?php
            }
            ?>
            
        </div>
    </div>
        <?php
        $i++;
    }
    ?>
       </div>
            <!-- /.box-body -->
          </div>
        </div>
        <?php
}


$nama_ruang = $db->fetch_single_row("ruang_ref","ruang_id",$ruang_id);
$get_jadwal = $db->query("select *,group_concat(id_dosen) as id_dos,(select jurusan from view_nama_kelas where kelas_id=view_jadwal_dosen_kelas.id_kelas) as jurusan from view_jadwal_dosen_kelas where hari=? and id_ruang=? and sem_id=? and id_kelas!='$kelas_name->kelas_id' group by id_kelas order by jam_mulai asc",array('hari' => $hari,'ruang_id' => $ruang_id,'sem_id' => $sem_id));
echo $db->getErrorMessage();
if ($get_jadwal->rowCount()>0) {
    ?>
        <div class="box box-primary box-solid" style="margin-bottom: 8px;margin-top: 13px;">
            <div class="box-header with-border">
              <h3 class="box-title">Penggunaan Ruangan <?=$nama_ruang->nm_ruang;?> - Hari <?=strtoupper($hari);?></h3>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="padding-bottom: 0;">
              <div class="row">
    <?php
    $i = 1;
    foreach ($get_jadwal as $jadwal) {
        $nama_dosen = array();
        $id_dos = explode(",", $jadwal->id_dos);
        foreach ($id_dos as $id_d) {
            $dosen = $db->fetch_single_row("dosen","nip",$id_d);
            $nama_dosen[] = $dosen->nama_dosen;
        }
        
        ?>
    <div class="col-md-6">
        <div class="schedule-item">
            <div class="dot work"><?=$i;?></div>
            <div class="time"><i class="fa fa-clock-o"></i> <?=$jadwal->jam_mulai;?> - <?=$jadwal->jam_selesai;?></div>
            <div class="description"><i class="fa fa-book"></i> <?=$jadwal->matkul_dosen;?></div>
            
            <?php
            foreach ($nama_dosen as $dosen_nama) {
                ?>
                <i class="fa fa-user"></i> <?=$dosen_nama;?><br>
                <?php
            }
            ?>
            <div class="description"><i class="fa fa-fa-institution"></i> <?=$jadwal->jurusan;?></div>
            
        </div>
    </div>
        <?php
        $i++;
    }
    ?>
       </div>
            <!-- /.box-body -->
          </div>
        </div>
        <?php
}
?>
