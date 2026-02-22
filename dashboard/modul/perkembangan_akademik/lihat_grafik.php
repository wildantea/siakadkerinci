<div class="box-header">
  <div class="box-group" id="accordion">
<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
<?php
     $semester = $db->query ("select k.krs_id, js.jns_semester,js.nm_singkat, sf.tahun, s.id_semester,a.*,
                    (select sum(sks) from krs_detail where krs_detail.id_krs=k.krs_id
                     and krs_detail.batal='0' group by krs_detail.id_krs) as sks_diambil from krs k
                    join semester s on s.sem_id=k.sem_id
                    join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.mhs_id)
                    join semester_ref sf on sf.id_semester=s.id_semester
                    join jenis_semester js on js.id_jns_semester=sf.id_jns_semester where k.mhs_id='".de(uri_segment(3))."'
                    order by s.id_semester asc");
     $sem=array();
     $ipp=array();
     
     foreach ($semester as $s) {
        $sem[] = $s->sem_id;
     }
     $semid=implode(',', $sem);





$ip = $db->query ("SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."'");
foreach ($ip as $i) {
    $ipp[]=$i->ip;
}
$ipall=implode(',', $ipp);








$ipk = $db->query ("SELECT ipk FROM akm WHERE mhs_nim ='".de(uri_segment(3))."'");
foreach ($ipk as $ik) {
    $ippk[]=$ik->ipk;
}
$ipkall=implode(',', $ippk);
//echo "SELECT ip FROM akm WHERE mhs_nim ='".de(uri_segment(3))."')";

?>


                </div>
</div><!-- /.box-header -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 
<script src="https://code.highcharts.com/highcharts.js"></script>
<style>
</style>
<div id="container2" style="height:400px"></div>

<script>
$(document).ready(function(){
 

 
$(function () {
    Highcharts.chart('container2', {
        xAxis: {
            categories: [<?= $semid ?> ]
        },
        title: {
            text: 'Grafik Perkembangan Akademik',
            align: 'center',
            x: 0
        },
 
        plotOptions: {
            series: {
                name: 'Indeks Prestasi',
                stacking: 'normal'
            }
        },
 
        series: [{
            data: [<?= $ipall ?>],
            name: "IP Semester"
        },{
            data: [<?= $ipkall ?>],
            name: "IP Kumulatif"
        }
        ]
    });
});

});

</script>