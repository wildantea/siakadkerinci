<div class="box-header">


                            </div><!-- /.box-header -->


                    
                   
                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
<?php
     $kategori = $db->query ("SELECT p.`id_penghasilan`, p.penghasilan FROM penghasilan p order by p.`id_penghasilan` asc");
     

     $kat=array();
     $peng=array();
     $a=array();
     

     foreach ($kategori as $k) {
        $kat[] = $k->id_penghasilan;
        $ktg[] = $k->penghasilan;
     }
     $kateg=implode(',', $kat);


$jumlah = $db->query ("SELECT
(SELECT COUNT(akm_id) FROM akm a 
	JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
	JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
	WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."' AND a.`ipk` < '1') AS satu,
(SELECT COUNT(akm_id) FROM akm a 
	JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
	JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
	WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."' AND (a.`ipk` >= '1' AND a.`ipk` < '2')) AS dua,
(SELECT COUNT(akm_id) FROM akm a 
	JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
	JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
	WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."' AND (a.`ipk` >= '2' AND a.`ipk` < '3')) AS tiga,
(SELECT COUNT(akm_id) FROM akm a 
	JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
	JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
	WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."' AND (a.`ipk` > '3')) AS empat
FROM akm a
JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
	JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
	WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."'
	GROUP BY (satu)");
foreach ($jumlah as $j) {
    $a[]=$j->satu;
    $b[]=$j->dua;
    $c[]=$j->tiga;
    $d[]=$j->empat;

}
$jumlahtotal=implode(',', $a);

//echo "SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."')";

?>
<script>
	Highcharts.chart('container', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Browser market shares in January, 2018'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
        style: {
          color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
        }
      }
    }
  },
  series: [{
    name: 'Brands',
    colorByPoint: true,
    data: [{
      name: 'Chrome',
      y: <?= $jumlahtotal ?>,
      sliced: true,
      selected: true
    }, {
      name: 'Internet Explorer',
      y: <?php echo json_encode($a);?>
    }, {
      name: 'Firefox',
      y: 10.85
    }, {
      name: 'Edge',
      y: 4.67
    }, {
      name: 'Safari',
      y: 4.18
    }, {
      name: 'Sogou Explorer',
      y: 1.64
    }, {
      name: 'Opera',
      y: 1.6
    }, {
      name: 'QQ',
      y: 1.2
    }, {
      name: 'Other',
      y: 2.61
    }]
  }]
});
</script>                      
     
                    <div class="box-body table-responsive">
                    <form action="<?= base_admin().'modul/rekap_hasil_studi/' ?>cetak_rhs.php" target="_Blank" method="post">
                     <button class="btn btn-success" name="laporan" type="submit"><i class="fa fa-print"></i> Cetak</button>
                     <br>
                     <br>
                                
                        <table id="dtb_manual" class="table table-bordered table-striped">
                        
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th style='width:200px;'>Nama</th>
                                  <th>Jurusan</th>
                                  <th>SKS Diambil</th>                                                                  
                                  <th>IPK</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $no=1;
                             
                                  $qq = $db->query("SELECT m.`nim`, m.`nama`,j.`nama_jur`, a.`jatah_sks`, a.ipk, a.`sem_id`, m.`mulai_smt`,a.akm_id, j.kode_jur FROM akm a 
                                                    JOIN mahasiswa m ON m.`nim` = a.`mhs_nim`
                                                    JOIN jurusan j ON j.kode_jur=m.`jur_kode` 
                                                    WHERE j.`kode_jur`='".$dec->dec($_GET['jur'])."' AND m.`mulai_smt`='".$dec->dec($_GET['ang'])."' AND a.`sem_id`='".$dec->dec($_GET['sem'])."'");
            
                             
                                                              /*  echo "select m.kode_mk,  k.kelas_id, s.semester,s.tahun,m.nama_mk,k.kls_nama,js.jns_semester ,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf='')
                                                    and kk.batal=0 group by kk.id_kelas) as belum,
                                                    (select count(id_krs_detail) from krs_detail kk where kk.id_kelas=k.kelas_id and (nilai_huruf!='')
                                                     and kk.batal=0 group by kk.id_kelas) as sdh
                                                    from dosen_kelas dk join kelas k on dk.id_kelas=k.kelas_id
                                                    join semester_ref s on s.id_semester=k.sem_id
                                                    join matkul m on m.id_matkul=k.id_matkul
                                                    join jenis_semester js on js.id_jns_semester=s.id_jns_semester
                                                  where dk.id_dosen='".$_SESSION['username']."' and s.semester='".$dec->dec($_GET['sem'])."'";*/
                            
                              foreach ($qq as $k) {
                              
                               ?>
                                  <tr id="line_<?=$k->akm_id;?>">
                                  <td><?= $no ?></td>
                                  <td><?= $k->nim ?></td>
                                  <td><?= $k->nama ?></td>                                 
                                  <td><?= $k->nama_jur ?></td>                                
                                  <td><?= $k->jatah_sks ?></td>
                                  <td><?= $k->ipk ?></td>
                                </tr>
                               <?php
                               $no++;
                              }
                              ?>
                               
                            </tbody>
                            <input type='hidden' name='jur' value='<?= $k->kode_jur ?>' method="post">
                                <input type='hidden' name='ang' value='<?= $k->mulai_smt ?>' method="post">
                                 <input type='hidden' name='sem' value='<?= $k->sem_id ?>' method="post">
                        </table>
                        </form>
                    </div><!-- /.box-body -->


                    <script type="text/javascript">   
     $(document).ready(function() {
    $('#dtb_manual').DataTable( {
        dom: 'Bfrtip',
        text: 'Rekapitulasi Distribusi Nilai',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );
  
</script>