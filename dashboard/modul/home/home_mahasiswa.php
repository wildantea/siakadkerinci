

<script src="https://code.highcharts.com/highcharts.js"></script>


<div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-body">
                  <div class="row">
                    
<?php
$nim = $_SESSION['username'];
     $semester = $db->query ("select k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                    (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
                     and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
                    join semester s on s.sem_id=k.sem_id
                    join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
                    join semester_ref sf on sf.id_semester=s.id_semester
                    join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='$nim'
                    order by s.id_semester asc");

     $sem=array();
     $ipp=array();
     
     foreach ($semester as $s) {
        $sem[] = $s->sem_id;
     }
     $semid=implode(',', $sem);

$ip = $db->query ("SELECT ip FROM akm WHERE mhs_nim ='$nim'");
foreach ($ip as $i) {
    $ipp[]=$i->ip;
}
$ipall=implode(',', $ipp);



$ipk = $db->query ("SELECT ipk FROM akm WHERE mhs_nim ='$nim'");
foreach ($ipk as $ik) {
    $ippk[]=$ik->ipk;
}
$ipkall=implode(',', $ippk);
//echo "SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."')";

?>
<div id="performance" style="width:100%"></div>
<script>
 
$(function () {
Highcharts.chart('performance', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Grafik Perkembangan Akademik',
        align: 'center'
    },
    xAxis: {
        categories: [<?= $semid ?>]
    },
    yAxis: {
        title: {
            text: 'Nilai IP / IPK'
        },
        min: 0,
        max: 4,
        tickInterval: 0.5
    },
    tooltip: {
        shared: true
    },
    series: [{
        name: 'IP Semester',
        data: [<?= $ipall ?>],
        color: '#3c8dbc'
    }, {
        name: 'IP Kumulatif',
        data: [<?= $ipkall ?>],
        color: '#00a65a'
    }]
});

});


</script>
                      
                     
                     
           </div>
           </div>
           </div>
           </div>
           </div>

          
<?php
//get dosen id
//get kkn data 
$kkn = $db->fetch_custom_single("select id_lokasi from kkn where nim='".$_SESSION['username']."' order by id_kkn desc limit 1");
if ($kkn) {
 
$periode = $db->fetch_custom_single("select left(priode,4) as periode,dpl,dpl2,lokasi_kkn.id_lokasi,nama_lokasi from priode_kkn jm join semester_ref sr on jm.priode=sr.id_semester 
join jenis_semester j on sr.id_jns_semester=j.id_jns_semester 

inner join lokasi_kkn on jm.id_priode=lokasi_kkn.id_periode

 where lokasi_kkn.id_lokasi='$kkn->id_lokasi'

order by sr.id_semester desc limit 1

");

$dosen_pembimbing = $db->fetch_custom_single("select group_concat(dosen SEPARATOR '#') as pembimbing from view_dosen where id_dosen in($periode->dpl,$periode->dpl2)");

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
        <h3 class="box-title">Peserta Kukerta <?=$periode->periode;?></h3>

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