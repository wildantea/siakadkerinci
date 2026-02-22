<?php
error_reporting(0);
?>  
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Version 2.0</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content"> 
        
            <?php
  if ($_SESSION['level']=='3'){
              include "home_mahasiswa.php";
         } elseif ($_SESSION['level']=='4') {
             include "home_dosen.php";
         } else{
         ?>        
 
           <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-body">
                  <div class="row">
                    
                   
                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 
<script src="https://code.highcharts.com/highcharts.js"></script>

<div id="container4" style="width:100%"></div>
<?php
     $kategori = $db->query ("SELECT f.`nama_resmi` FROM fakultas f ORDER BY f.nama_resmi ASC");
     

     $kat=array();
     $peng=array();
     

     foreach ($kategori as $k) {
        $ktg[] = $k->nama_resmi;

     }
     $kateg=implode(',', $kat);


// $jumlah = $db->query ("SELECT 
// (SELECT COUNT(m.`id_wil`) FROM mahasiswa m WHERE m.`id_wil`=d.`id_wil` GROUP BY m.`id_wil`) AS jml 
// FROM data_wilayah d
// JOIN mahasiswa m ON m.`id_wil` = d.`id_wil`
// GROUP BY d.`id_wil` ORDER BY d.`id_wil` ASC");
// foreach ($jumlah as $j) {
//     $peng[]=$j->jml;
// }
// $jumlahtotal=implode(',', $peng); 

$qj=$db->query("SELECT ff.kode_fak,
(SELECT COUNT(*) FROM mahasiswa m JOIN jurusan j ON j.kode_jur=m.jur_kode
JOIN fakultas f ON f.kode_fak=j.fak_kode WHERE f.kode_fak=ff.kode_fak AND m.stat_pd='A') AS aktif,
(SELECT COUNT(*) FROM mahasiswa m JOIN jurusan j ON j.kode_jur=m.jur_kode
JOIN fakultas f ON f.kode_fak=j.fak_kode WHERE f.kode_fak=ff.kode_fak AND m.stat_pd='C') AS cuti,
(SELECT COUNT(*) FROM mahasiswa m JOIN jurusan j ON j.kode_jur=m.jur_kode
JOIN fakultas f ON f.kode_fak=j.fak_kode WHERE f.kode_fak=ff.kode_fak AND m.stat_pd='D') AS drop_out,
(SELECT COUNT(*) FROM mahasiswa m JOIN jurusan j ON j.kode_jur=m.jur_kode
JOIN fakultas f ON f.kode_fak=j.fak_kode WHERE f.kode_fak=ff.kode_fak AND m.stat_pd='K') AS keluar    FROM fakultas ff
ORDER BY ff.nama_resmi ASC"); 
$jml=array();
foreach ($qj as $kj) {
  $jml['aktif'][]=$kj->aktif;
  $jml['cuti'][]=$kj->cuti;
  $jml['drop_out']=$kj->drop_out;
  $jml['keluar']=$kj->keluar;
}

//echo "SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."')";

?>

<script>
$(document).ready(function(){
 
$(function () {
    var chart = Highcharts.chart('container4', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: <?php echo json_encode($ktg);?>
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        title: {
            text: 'Grafik Aktifitas Kuliah Mahasiswa',
            align: 'center',
            x: 0
        },
        colors: ['#4ec4ce', '#cc113c', '#f7941d', '#d24087'],
        series: [{
            data: [<?= implode(',', $jml['aktif']) ?>],
            name: "Aktif"
             
        },
        {
            data: [<?= implode(',', $jml['cuti']) ?>],
            name: "Cuti"
        },
        {
            data: [<?= implode(',', $jml['drop_out']) ?>],
            name: "Drop Out"
        },
        {
            data: [<?= implode(',', $jml['keluar']) ?>],
            name: "Keluar"
        }]
    });
 
    // the button action
    $('#button').click(function () {
        var selectedPoints = chart.getSelectedPoints();
 
        if (chart.lbl) {
            chart.lbl.destroy();
        }
        chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
            .attr({
                padding: 10,
                r: 5,
                fill: Highcharts.getOptions().colors[1],
                zIndex: 5
            })
            .css({
                color: 'white'
            })
            .add();
    });
});


});

</script>
                      
                     
                     
           </div>
           </div>
           </div>
           </div>
           </div>
        <?php

    }


    ?>
         
        </section><!-- /.content -->
    