


<div class="box-header">
  <div class="box-group" id="accordion">

 
<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
<?php
     $kategori = $db->query ("SELECT jm.`jalur` FROM jalur_masuk jm
JOIN mahasiswa m ON m.`id_jalur_masuk`=jm.`id_jalur_masuk`
WHERE m.`jur_kode`='".de($_GET['jur'])."' GROUP BY m.`id_jalur_masuk` ORDER BY m.`id_jalur_masuk` asc");
     

     $kat=array();
     $peng=array();
     

     foreach ($kategori as $k) {
        $ktg[] = $k->jalur;
     }
     $kateg=implode(',', $kat);


$jumlah = $db->query ("SELECT
(SELECT COUNT(m.`id_jalur_masuk`) FROM mahasiswa m WHERE m.`id_jalur_masuk`=jm.`id_jalur_masuk` and m.mulai_smt='".de($_GET['ang'])."' GROUP BY m.id_jalur_masuk) AS jml
FROM jalur_masuk jm
JOIN mahasiswa m ON m.`id_jalur_masuk`=jm.`id_jalur_masuk`
WHERE m.`jur_kode`='".de($_GET['jur'])."' GROUP BY m.`id_jalur_masuk` ORDER BY m.`id_jalur_masuk` asc");
foreach ($jumlah as $j) {
    $peng[]=$j->jml;
}
$jumlahtotal=implode(',', $peng);

//echo "SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."')";

?>


                </div>
</div><!-- /.box-header -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 
<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
</style>
<div id="container4" style="height:400px"></div>
<script>
$(document).ready(function(){
 
$(function () {
    var chart = Highcharts.chart('container4', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories:<?php echo json_encode($ktg);?>
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        title: {
            text: 'Grafik Jalur Masuk',
            align: 'center',
            x: 0
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            },
        },
        colors: ['#4ec4ce'],
        series: [{
            data: [<?= $jumlahtotal ?>],
            name: "Jumlah Mahasiswa"
             
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