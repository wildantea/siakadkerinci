<div class="box-header">
  <div class="box-group" id="accordion">

 
<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
<?php
     $kategori = $db->query ("SELECT d.`nama_dosen`,dk.`id_dosen`, d.`nip`, kelas.sem_id,
(SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND (nilai_huruf='A') AND kelas.`sem_id`='".de($_GET['sem'])."') AS a,
(SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND kelas.`sem_id`='".de($_GET['sem'])."') AS total,
(SELECT (a)/(total)*100) AS presentase
FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id`
JOIN dosen d ON d.`nip`=dk.`id_dosen`
WHERE j.kode_jur='".de($_GET['jur'])."' AND kelas.sem_id='".de($_GET['sem'])."' AND (SELECT ((SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND (nilai_huruf='A') AND kelas.`sem_id`='".de($_GET['sem'])."'))/((SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND kelas.`sem_id`='".de($_GET['sem'])."'))*100) >= '50'
GROUP BY dk.`id_dosen` ORDER BY dk.`id_dosen` asc");
     

     $kat=array();
     $peng=array();
     

     foreach ($kategori as $k) {
        $ktg[] = $k->nama_dosen;

     }
     $kateg=implode(',', $kat);


$jumlah = $db->query ("SELECT d.`nama_dosen`,dk.`id_dosen`, d.`nip`, kelas.sem_id,
(SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND (nilai_huruf='A') AND kelas.`sem_id`='".de($_GET['sem'])."') AS a,
(SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND kelas.`sem_id`='".de($_GET['sem'])."') AS total,
(SELECT (a)/(total)*100) AS presentase
FROM kelas 
INNER JOIN matkul ON kelas.id_matkul=matkul.id_matkul 
JOIN kurikulum k ON k.kur_id=matkul.kur_id
JOIN jurusan j ON j.kode_jur=k.kode_jur 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id`
JOIN dosen d ON d.`nip`=dk.`id_dosen`
WHERE j.kode_jur='".de($_GET['jur'])."' AND kelas.sem_id='".de($_GET['sem'])."' AND (SELECT ((SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND (nilai_huruf='A') AND kelas.`sem_id`='".de($_GET['sem'])."'))/((SELECT COUNT(nilai_huruf) FROM krs_detail kd 
JOIN kelas ON kd.id_kelas=kelas.`kelas_id` 
JOIN dosen_kelas dk ON dk.`id_kelas`=kelas.`kelas_id` WHERE dk.`id_dosen`=d.`nip` AND kelas.`sem_id`='".de($_GET['sem'])."'))*100) >= '50'
GROUP BY dk.`id_dosen` ORDER BY dk.`id_dosen` asc");
foreach ($jumlah as $j) {
    $peng[]=$j->presentase;
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
        	categories: <?php echo json_encode($ktg);?>
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah (%)'
            },
        },
        title: {
            text: 'Grafik Dosen yang Sering memberi Nilai A (>50%)',
            align: 'center',
            x: 0
        },
        colors: ['#cc113c'],
        series: [{
            data: [<?= $jumlahtotal ?>],
            name: "Persentase Nilai A (%)"
             
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