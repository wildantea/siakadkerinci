  <style>
.timeline:before {
    left: 13px;
}

.timeline>li>.fa, .timeline>li>.glyphicon, .timeline>li>.ion {
    left: 0px;
}
.timeline>li>.timeline-item {
    margin-left: 35px;
    margin-right: 0;
}
  </style>
    <?php
   $data_dosen = $db->fetch_custom_single("select * from view_dosen where nip='".getUser()->username."'"); 
    ?>
      <?php
function first_words($text, $count=30)
{
    $words = explode(' ', $text);

    $result = '';
    for ($i = 0; $i < $count && isset($words[$i]); $i++) {
        $result .= $words[$i].' ';
    }

    return $result;
}
?>
    <div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
 <?php
        $bimbingan = $db->fetch_custom_single("select count(mhs_id) as jml from mahasiswa where nim not in (select nim from tb_data_kelulusan) and dosen_pemb='".getUser()->username."'");
        if ($bimbingan) {
            $bimbingan = $bimbingan->jml; 
        } else {
            $bimbingan = 0;
        }
        ?>

<div class="small-box bg-aqua">
  <div class="inner">
    <h3> <?=$bimbingan;?> </h3>
    <p>Total Mahasiswa Bimbingan</p>
  </div>
  <div class="icon">
    <i class="fa fa-users"></i>
  </div>
  <a href="
    <?=base_index();?>rencana-studi" class="small-box-footer"> Detail <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>


  </div>
  <div class="col-md-3 col-sm-12 col-xs-12">
 <?php
    $sks = $db->fetch_custom_single("select group_concat(mat_id) as matkul_id,count(id_kelas) as jml_kelas from view_jadwal_dosen_kelas where id_dosen='".getUser()->username."' and sem_id='".getSemesterAktif()."'");

    if ($sks) {
        $jml_kelas = $sks->jml_kelas;
    } else {
        $sks_ajar = 0;
        $jml_kelas = 0;
    }

    $total = 0;
    $total_sks_ajar = $db->fetch_custom_single("select sum(sks) as total from view_nama_kelas where kelas_id in(select id_kelas from view_jadwal_dosen_kelas where id_dosen='".getUser()->username."' and sem_id='".getSemesterAktif()."')");
    if ($total_sks_ajar) {
      $total = $total_sks_ajar->total;
    }
  ?>

<div class="small-box bg-aqua">
  <div class="inner">
    <h3> <?=$jml_kelas;?> </h3>
    <p>Jumlah Kelas </p>
  </div>
  <div class="icon">
    <i class="fa fa-building-o"></i>
  </div>
  <a href="
    <?=base_index();?>kelas" class="small-box-footer"> Detail <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>



  </div>

  <div class="col-md-3 col-sm-12 col-xs-12">
    <div class="small-box bg-aqua">
  <div class="inner">
    <h3> <?=$total;?> SKS </h3>
    <p>Beban Mengajar</p>
  </div>
  <div class="icon">
    <i class="fa fa-book"></i>
  </div>
  <a href="
    <?=base_index();?>kelas" class="small-box-footer"> Detail <i class="fa fa-arrow-circle-right"></i>
  </a>
</div>
  </div>
</div>
<?php
//get dosen id

$id_dosen = $db->fetch_single_row("dosen","nip",$_SESSION['username']);


$dosen_pembimbing = $db->fetch_custom_single("select group_concat(dosen SEPARATOR '#') as pembimbing from view_dosen where id_dosen in($periode->dpl,$periode->dpl2)");
if ($dosen_pembimbing) {
  
$periode = $db->fetch_custom_single("select left(priode,4) as periode,dpl,dpl2,lokasi_kkn.id_lokasi,nama_lokasi from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester 
join jenis_semester j on sr.id_jns_semester=j.id_jns_semester 

inner join lokasi_kkn on jm.id_priode=lokasi_kkn.id_periode

 where dpl='$id_dosen->id_dosen' or dpl2='$id_dosen->id_dosen'

order by sr.id_semester desc limit 1

");
$jml_peserta = $db->fetch_custom_single("select count(id_kkn) as jml from kkn 
    inner join mahasiswa on kkn.nim=mahasiswa.nim 
    inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
    inner join jurusan on kkn.kode_jur=jurusan.kode_jur 
    left join priode_kkn on priode_kkn.id_priode=kkn.id_priode
    left join lokasi_kkn lk on lk.id_lokasi=kkn.id_lokasi
    where lk.id_lokasi='$periode->id_lokasi'");
?>
<div class="row">
  <div class="col-lg-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Bimbingan Mahasiswa Kukerta <?=$periode->periode;?></h3>

      </div>

      <div class="box-body">

    <table class="table table-bordered">
      <tbody>
        <tr>
          <th width="200px">Nama Dosen Pembimbing</th>
          <td>
            <?php
            $pemb = explode("#", $dosen_pembimbing->pembimbing);
            $i = count($pemb) > 1 ? 1 : '';
            foreach ($pemb as $pb) {
              echo "<span class='btn btn-sm btn-info' style='margin-right:5px;'>$pb</span> ";
              if (count($pemb) > 1) $i++;
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>Lokasi</th>
          <td><span class="btn btn-sm btn-success"><?=$periode->nama_lokasi;?></span></td>
        </tr>
        <tr>
          <th>Jumlah Mahasiswa</th>
          <td><span class="btn btn-sm btn-success"><?=$jml_peserta->jml;?></span></td>
        </tr>
      </tbody>
    </table>

        <div class="table-responsive">
          <table class="table no-margin" id="dtb_manual" >
            <thead>
              <tr>
                <th>NIM</th>
                <th>NAMA</th>
                <th>Fakultas</th>
                <th>PROGRAM STUDI</th>
              </tr>
            </thead>
            <tbody>

              <?php
              $data_peserta = $db->query("select lk.nama_lokasi,mahasiswa.jk, kkn.nim,mahasiswa.nama,fakultas.nama_resmi,jurusan.nama_jur,kkn.id_kkn from kkn 
    inner join mahasiswa on kkn.nim=mahasiswa.nim 
    inner join fakultas on kkn.kode_fak=fakultas.kode_fak 
    inner join jurusan on kkn.kode_jur=jurusan.kode_jur 
    left join priode_kkn on priode_kkn.id_priode=kkn.id_priode
    left join lokasi_kkn lk on lk.id_lokasi=kkn.id_lokasi
    where lk.id_lokasi='$periode->id_lokasi'
    ");
           
              foreach ($data_peserta as $peserta) {
                ?>
                <tr>
                  <td><?=$peserta->nim;?></td>
                  <td><?=$peserta->nama;?></td>
                  <td><?=$peserta->nama_resmi;?></td>
                  <td><?=$peserta->nama_jur;?></td>
                </tr>
                <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="box-footer clearfix">
        <a target="_blank" href="<?=base_admin();?>modul/home/cetak.php?id=<?=$enc->enc($periode->id_lokasi);?>" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-print"></i> CETAK PESERTA</a>
      </div>
    </div>
  </div>
</div>
<?php

}
?>