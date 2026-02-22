<section class="content-header">
  <h1>Detail Nilai Perkelas</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai_per_kelas">Nilai Per Kelas</a></li>
    <li class="active">Detail Nilai Perkelas</li>
  </ol>
</section>
<style type="text/css">
  .error {
    color: #f00;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
            <div class="box-body table-responsive">
              <form method="POST" action="<?= base_url() ?>dashboard/modul/nilai_per_kelas/nilai_per_kelas_action.php?act=input_nilai" id="form_input_nilai" >
            <span class="lead"><a href="<?=base_index();?>nilai-per-kelas" class="btn btn-warning"><i class="fa fa-reply"></i> Kembali</a></span>
            <p>

<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody><tr>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$kelas_data->nama_jurusan;?></td>
          <td class="info2" width="20%"><strong>Periode</strong></td>
          <td colspan="2"><?=getPeriode($kelas_data->sem_id);?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?> </td>
        </tr>
      </tbody></table>
        <table  class="table table-bordered table-striped">
         
         <thead>
           <tr class="bg-light-blue color-palette">
             <th rowspan="2" style="padding-right:0;width: 5%;text-align: center;vertical-align: middle;">No</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">NIM</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Angkatan</th>
             <th colspan="2" style="text-align: center;vertical-align: middle">Nilai</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nama Pengubah</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Tanggal diubah</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle"><span data-toggle="tooltip" data-title="Perubahan Nilai Ke"> Ke</span></th>
           </tr>
           <tr class="bg-light-blue color-palette">
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Angka</th>
             <th rowspan="2" style="text-align: center;vertical-align: middle">Nilai Huruf</th>
           </tr>
         </thead>
         <tbody>
         <?php
              $no=1;
              $array_skala = array();
              $skala_nilai = $db2->query("select * from tb_data_skala_nilai where kode_jurusan=?",array('kode_jurusan' => $kelas_data->kode_jur));
              foreach ($skala_nilai as $skala) {
                $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
              }
              $nilai_data = $db2->query("select history_nilai,tb_data_kelas_krs_detail.krs_detail_id,nilai_angka,nilai_huruf,nilai_indeks,tb_data_kelas_krs.nim,nama,left(mulai_smt,4) as angkatan,pengubah,tb_data_kelas_krs_detail.date_updated from tb_data_kelas_krs_detail inner join tb_data_kelas_krs using(krs_id) inner join tb_master_mahasiswa using(nim) where kelas_id=? and tb_data_kelas_krs_detail.disetujui='1' order by tb_data_kelas_krs.nim asc",array('kelas_id' => $id));
              echo $db2->getErrorMessage();
              foreach ($nilai_data as $nilai) {
                $bobot = $nilai->nilai_huruf."#".$nilai->nilai_indeks;
                ?>
                <tr class="row-data">
                  <td style="background: #fff;text-align: center;vertical-align: middle"><?=$no;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nim;?></td>
                  <td style="vertical-align: middle"><?=$nilai->nama;?></td>
                  <td style="vertical-align: middle"><?=$nilai->angkatan;?></td>
                  <td><input type="text" name="nilai_angka[<?=$nilai->krs_detail_id;?>]" disabled="" value="<?=$nilai->nilai_angka;?>" onkeydown="return onlyNumber(event,this,true,false)" class="form-control nilai_angka" maxlength="5" size="5" data-jurusan="<?=$kelas_data->kode_jur;?>"></td>
                  <td>
                    <select name="nilai_huruf[<?=$nilai->krs_detail_id;?>]" class="form-control nilai_huruf" disabled="">
<option value=""> </option>
<?php
foreach ($array_skala as $id_skala => $skala_bobot) {
  if ($bobot==$id_skala) {
    echo "<option value='$id_skala' selected>$skala_bobot</option>";
  } else {
    echo "<option value='$id_skala'>$skala_bobot</option>";
  }
  
}
?>
</select>
                  </td>
                  <td style="vertical-align: middle"><?=$nilai->pengubah;?></td>
                  <td style="vertical-align: middle"><?=tgl_time($nilai->date_updated);?></td>
<?php
$jml_history = 0;
if ($nilai->nilai_indeks!="") {
  $jml_history = 1;
}
if ($nilai->history_nilai!="") {
  $history_count = json_decode($nilai->history_nilai);
  $jml_history =  count($history_count);
}
?>
                  <td style="text-align: center;vertical-align: middle"><span class="btn btn-primary btn-sm lihat-log" data-id="<?=$nilai->krs_detail_id;?>" data-toggle="tooltip" data-title="Klik Untuk Melihat Log Nilai"><?=$jml_history;?></span></td>
                </tr>
                <?php
                $no++;
              }
         ?>
         </tbody>

        </table>
       </form>
     </div>

   </div>
  </div>
</div>
</section>
<div class="modal fade" id="modal_log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Log Nilai Perkuliahan</h4> </div> <div class="modal-body" id="isi_log"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script type="text/javascript">
$(".lihat-log").click(function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/nilai_per_kelas/lihat_log.php",
            type : "post",
            data : {kelas_id:<?=$kelas_data->kelas_id;?>,krs_id:id},
            success: function(data) {
                $("#isi_log").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_log').modal({ keyboard: false,backdrop:'static' });

    });
</script>