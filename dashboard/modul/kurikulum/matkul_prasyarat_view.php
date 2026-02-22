<!-- Content Header (Page header) -->
<section class="content-header">
<?php
$prodi = $db->fetch_custom_single("select jml_sks_wajib,jml_sks_pilihan,nama_kurikulum,jurusan.kode_jur,jurusan.nama_jur,jenjang_pendidikan.jenjang,
semester_ref.id_semester,
concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik from
jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
inner join kurikulum on jurusan.kode_jur=kurikulum.kode_jur
inner join semester_ref on kurikulum.sem_id=semester_ref.id_semester
inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
where kurikulum.kur_id=?",array('kur_id' =>uri_segment(3)))
?>
  <h1>Matakuliah Prasyarat</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>kurikulum">Kurikulum</a></li>
    <li class="active">Matkul List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <a onclick="window.history.back()" class="btn btn-success"><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-bordered table-striped">
      <tbody><tr>
          <td  width="20%">Nama Kurikulum <font color="#FF0000">*</font></td>
            <td colspan="5">: 
     <?=$prodi->nama_kurikulum;?></td>
        </tr>
        <tr>
            <td >Program Studi <font color="#FF0000">*</font></td>
            <td>:  <?=$prodi->jenjang.' '.$prodi->nama_jur;?></td>
            <td >Mulai Berlaku <font color="#FF0000">*</font></td>
          <!--  <td colspan="3">:  2017/2018 Genap</td>-->
            <td colspan="9">:  <?=$prodi->tahun_akademik;?>      </td></tr>
      <tr>
          <td >Jumlah sks</td>
            <td>: 
      <?=$prodi->jml_sks_wajib+$prodi->jml_sks_pilihan;?><font color="#999999"><em> ( sks Wajib + sks Pilihan )</em></font>
            </td>
          <td > Jumlah Bobot Matakuliah Wajib <font color="#FF0000">*</font></td>
            <td>: 
      <?=$prodi->jml_sks_wajib;?> sks</td>
          <td >Jumlah Bobot Matakuliah Pilihan <font color="#FF0000">*</font></td>
            <td>: 
      <?=$prodi->jml_sks_pilihan;?> sks</td>
        </tr>
    </tbody></table>
            <div class="box-header with-border">
            </div>
<?php
      $sum=$db->fetch_custom_single("select sum(matkul.total_sks) as total_sks,sum(matkul.sks_tm) as total_tm,sum(matkul.sks_prak) as total_pr,
sum(matkul.sks_prak_lap) as total_pl,sum(matkul.sks_sim) as total_sim from matkul 
         inner join kurikulum on matkul.kur_id=kurikulum.kur_id 
         where matkul.kur_id=?
      order by matkul.id_matkul desc",array('kur_id' =>uri_segment(3)));

$jenis_mk = $db->fetch_all("tipe_matkul");
$jnis = "";
foreach ($jenis_mk as $jm) {
    $jnis.=$jm->id_tipe_matkul.': '.$jm->tipe_matkul.'<br>';
}
         ?>
<style type="text/css">
  table.table thead th {
  vertical-align: middle;
  text-align:center;
}
</style>
            <table id="dtb_prasayarat" class="table table-bordered table-striped">
              <thead>
                <tr>
                                  <th colspan="5" style="text-align: center">MATAKULIAH</th>

                                  <th colspan="5" style="text-align: center">MATAKULIAH PRASYARAT</th>
                                  <th rowspan="2" style="text-align: center;">AKSI</th>
                </tr>
                <tr>
                                  <th style="width:25px;">No</th>
                                  <th >Kode</th>
                                  <th >Nama</th>
                                  <th><a data-toggle='tooltip' data-template='<div style="width:300px" class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>' data-html="true" data-placement="top" title="<?=$jnis;?>">Jenis MK</a></th>
                                  <th><a data-toggle='tooltip' title="Semester">Smt</a></th>
                                  <th>Kode</th>
                                  <th>Nama</th>
                                <th><a data-toggle='tooltip' data-template='<div style="width:300px" class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>' data-html="true" data-placement="top" title="<?=$jnis;?>">Jenis MK</a></th>
                                  <th><a data-toggle='tooltip' title="Semester">Smt</a></th>
                                  <th><a data-html="true" data-placement="left" data-toggle='tooltip' title="L: Matakuliah prasyarat harus sudah lulus.<br>S: Matakuliah prasyarat boleh diambil bersamaan.">Syarat</a></th>
                </tr>

              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select m1.id_matkul, m1.kode_mk as kode_mk1,m1.nama_mk as nama_mk1,m1.id_tipe_matkul as jenis1,m1.semester as sem1,
m2.id_matkul as id_matkul2, m2.kode_mk as kode_mk2,m2.nama_mk as nama_mk2
,m2.id_tipe_matkul as jenis2,m2.semester as sem2,syarat from matkul m1
left join prasyarat_mk p on m1.id_matkul=p.id_mk
left join matkul m2 on p.id_mk_prasyarat=m2.id_matkul
where m1.kur_id=? order by kode_mk1
",array('kur_id' =>uri_segment(3)));

$rowCount = $dtb->rowCount();
        # $arr is array which will be help ful during 
        # printing
        $arr = array();

        # Intialize the array, which will 
        # store the fetched data.
        $id_matkul = array();
        $kode_mk1 = array();
        $nama_mk1 = array();
        $jenis1 = array();
        $sem1 = array();
        $kode_mk2 = array();
        $nama_mk2 = array();
        $jenis2 = array();
        $sem2 = array();
        $syarat = array();

        #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
        #     data saving and rowspan calculation        #
        #%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

        # Loop over all the fetched data, and save the
        # data.
        foreach($dtb as $pes) {
            
            array_push($id_matkul, $pes->id_matkul);
            array_push($kode_mk1, $pes->kode_mk1);
            array_push($nama_mk1, $pes->nama_mk1);
            array_push($jenis1, $pes->jenis1);
            array_push($sem1, $pes->sem1);
            array_push($kode_mk2, $pes->kode_mk2);
            array_push($nama_mk2, $pes->nama_mk2);
            array_push($jenis2, $pes->jenis2);
            array_push($sem2, $pes->sem2);
            array_push($syarat, $pes->syarat);



            if (!isset($arr[$pes->id_matkul])) {
                $arr[$pes->id_matkul]['rowspan'] = 0;
            }
/*            if (!isset($arr[$pes->jurusan])) {
                $arr[$pes->jurusan]['rowspan'] = 0;
            }
            $arr[$pes->tgl_seminar]['printed'] = 'no';
            $arr[$pes->tgl_seminar]['rowspan'] += 1;*/
            //jurusan
            $arr[$pes->id_matkul]['printed'] = 'no';
            $arr[$pes->id_matkul]['rowspan'] += 1;
        }

             
         


        $no=1;
        for($i=0; $i < sizeof($id_matkul); $i++) {
           $kode_mk1Name = $kode_mk1[$i];
           $id_mk = $id_matkul[$i];
        ?>
      <tr id="line_<?=$id_matkul[$i];?>">
        
         <?php
           if ($arr[$id_mk]['printed'] == 'no') {
            echo "<td rowspan='".$arr[$id_mk]['rowspan']."' style='vertical-align: top'>$no</td>";
            echo "<td rowspan='".$arr[$id_mk]['rowspan']."' style='vertical-align: top'>".$kode_mk1Name."</td>";
            $no++;
                ?>

          <td rowspan="<?=$arr[$id_mk]['rowspan'];?>" style="vertical-align: top"><?=$nama_mk1[$i];?></td>

          <td rowspan="<?=$arr[$id_mk]['rowspan'];?>" style="vertical-align: top"><?=$jenis1[$i];?></td>

          <td rowspan="<?=$arr[$id_mk]['rowspan'];?>" style="vertical-align: top"><?=$sem1[$i];?></td>
                <?php
               
            } 

              ?> 


          <td><?=$kode_mk2[$i];?></td>
          <td><?=$nama_mk2[$i];?></td>
          <td style="text-align: center"><?=$jenis2[$i];?></td>
          <td style="text-align: center"><?=$sem2[$i];?></td>
          <td style="text-align: center"><?=$syarat[$i];?></td>
            <?php
           if ($arr[$id_mk]['printed'] == 'no') {
            ?>
        <td rowspan="<?=$arr[$id_mk]['rowspan'];?>">
          <?php
          if($role_act["up_act"]=="Y") {
            echo '<a href="'.base_index().'kurikulum/edit_prasayarat/'.$id_matkul[$i].'" data-id="'.$id_matkul[$i].'" class="btn edit_data btn-primary btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a> ';
          }
        ?>
        </td>
         <?php
                $arr[$id_mk]['printed'] = 'yes';
            } 

              ?> 
      </tr>
        <?php
      // $no++;
      }
      ?>
              </tbody>
            </table>
            </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->

    </section><!-- /.content -->

    <div class="modal" id="modal_kecamatan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"><button style="float: right;" class="btn btn-info btn-sm close_main"><i class="fa fa-times"></i></button> <h4 class="modal-title">Tambah/Edit Matakuliah Prasyarat</h4> </div> <div class="modal-body" id="isi_kecamatan"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script type="text/javascript">
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kurikulum/matkul_prasyarat_change.php",
            type : "post",
            data : {id_data:id,kur_id:<?=uri_segment(3);?>},
            success: function(data) {
                $("#isi_kecamatan").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_kecamatan').modal({ keyboard: false,backdrop:'static' });

    });
$('.close_main').click(function() {
    location.reload();
});
</script>
