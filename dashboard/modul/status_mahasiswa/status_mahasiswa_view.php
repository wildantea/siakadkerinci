<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Status Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>status-mahasiswa">Status Mahasiswa</a></li>
                        <li class="active">Status Mahasiswa List</li>
                    </ol>
                </section>

                <!-- Main content -->
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


$jumlah = $db->query ("SELECT 
(SELECT COUNT(m.`id_wil`) FROM mahasiswa m WHERE m.`id_wil`=d.`id_wil` GROUP BY m.`id_wil`) AS jml 
FROM data_wilayah d
JOIN mahasiswa m ON m.`id_wil` = d.`id_wil`
GROUP BY d.`id_wil` ORDER BY d.`id_wil` ASC");
foreach ($jumlah as $j) {
    $peng[]=$j->jml;
}
$jumlahtotal=implode(',', $peng);

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
            data: [231, 150, 15, 15, 560, 20, 20],
            name: "Aktif"
             
        },
        {
            data: [20, 15, 15, 15, 15, 20, 15],
            name: "Cuti"
        },
        {
            data: [30, 30, 10, 12, 7, 2, 2],
            name: "Drop Out"
        },
        {
            data: [15, 1, 1, 1, 2, 3, 4],
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