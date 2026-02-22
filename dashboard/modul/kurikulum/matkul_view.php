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
where kurikulum.kur_id=?",array('kur_id' =>uri_segment(3)));
echo $db->getErrorMessage();
?>
  <h1>Matakuliah Kurikulum</h1>
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
          <?php
          foreach ($db->fetch_all("sys_menu") as $isi) {
              if (uri_segment(1)==$isi->url) {
                  if ($role_act["insert_act"]=="Y") {
          ?>
          <a href="<?=base_index();?>kurikulum" class="btn btn-success"><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
          <a href="<?=base_index();?>kurikulum/tambah_mk/<?=uri_segment(3)?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
          <a class="btn btn-primary" id="import_mat"><i class="fa fa-cloud-upload"></i> Import Excel</a>
          <?php
                  }
              }
          }
          ?>
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
            <td colspan="9">:  <?=$prodi->tahun_akademik;?>    </td></tr>
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

         ?>
<style type="text/css">
  table.table thead th {
  vertical-align: middle;
  text-align:center;
}
</style>
            <table id="dtb_matkul" class="table table-bordered table-striped">
              <thead>
                <tr>
                                  <th colspan="3" style="text-align: right">Total</th>
                                  <th style="text-align: center"><?=$sum->total_sks;?></th>
                                 <th style="text-align: center"><?=$sum->total_tm;?></th>
                                  <th style="text-align: center"><?=$sum->total_pr;?></th>
                                  <th style="text-align: center"><?=$sum->total_pl;?></th>
                                  <th style="text-align: center"><?=$sum->total_sim;?></th>
                                  <th style="text-align: center;"></th>
                </tr>
                <tr>
                                  <th style="width:25px;" rowspan="2">No</th>
                                  <th rowspan="2">Kode MK</th>
                                  <th rowspan="2">Nama Matkul</th>
                                  <th colspan="5">Jumlah SKS</th>
                                  <th rowspan="2">Action</th>
                </tr>
                <tr>
                                  <th>Total</th>
                                  <th>Tatap Muka</th>
                                  <th>Praktek</th>
                                  <th>Lapangan</th>
                                  <th>Simulasi</th>
                </tr>

              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select sum(matkul.sks_tm) as total_tm,sum(matkul.sks_prak) as total_pr,
sum(matkul.sks_prak_lap) as total_pl,sum(matkul.sks_sim) as total_sim,matkul.total_sks,
 matkul.kode_mk,matkul.nama_mk,matkul.sks_tm,matkul.sks_prak,sks_sim,
        matkul.sks_prak_lap,matkul.id_matkul from matkul 
         inner join kurikulum on matkul.kur_id=kurikulum.kur_id 
         where matkul.kur_id=?
         group by matkul.id_matkul
      order by matkul.id_matkul desc",array('kur_id' =>uri_segment(3)));
      echo $db->getErrorMessage();
      $i=1;
      foreach ($dtb as $isi) {
        ?>
      <tr id="line_<?=$isi->id_matkul;?>">
        <td align="center"><?=$i;?></td>
          <td><?=$isi->kode_mk;?></td>
          <td><?=$isi->nama_mk;?></td>
          <td style="text-align: center"><?=$isi->total_sks;?></td>
          <td style="text-align: center"><?=$isi->sks_tm;?></td>
          <td style="text-align: center"><?=$isi->sks_prak;?></td>
          <td style="text-align: center"><?=$isi->sks_prak_lap;?></td>
          <td style="text-align: center"><?=$isi->sks_sim;?></td>
        <td>
          <?php
          if($role_act["up_act"]=="Y") {
            echo '<a href="'.base_index().'kurikulum/edit_mk/'.$isi->id_matkul.'" data-id="'.$isi->id_matkul.'" class="btn edit_data btn-primary btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a> ';
          }
          if($role_act["del_act"]=="Y") {
            echo '<button data-toggle="tooltip" title="Hapus" class="btn btn-danger hapus btn-sm" data-uri="'.base_admin().'modul/kurikulum/matkul_action.php" data-id="'.$isi->id_matkul.'"><i class="fa fa-trash-o"></i></button>';
          }
        ?>
        </td>
      </tr>
        <?php
       $i++;
      }
      ?>
              </tbody>
            </table>
            </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->

    <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel Matakuliah</h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->
<script type="text/javascript">
        $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/kurikulum/import_mat.php?kur_id="+<?=uri_segment(3);?>,
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    });

DtableManual = $("#dtb_matkul").DataTable({
      "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                text: 'Download Matakuliah',
                action: function ( e, dt, node, config ) {
                    window.location=('<?=base_admin();?>modul/kurikulum/download_matkul.php?id='+<?=uri_segment(3);?>);
                }
            }
        ]
});

   $(".table").on('click','.hapus',function(event) {
  //  $('.hapus').click(function(){

    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');


    $('#ucing')
        .modal({ keyboard: false })
        .one('click', '#delete', function (e) {

                $.ajax({
          type: "POST",
          url: uri+"?act=delete&id="+id,
          success: function(data){
              $("#line_"+id).addClass('deleted');
              DtableManual.row('.deleted').remove().draw( false );
          }
          });
          $('#ucing').modal('hide');

        });



  });

</script>
