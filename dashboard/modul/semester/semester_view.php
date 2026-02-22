<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Setting Semester Berlaku</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>semester">Setting Semester Berlaku</a></li>
    <li class="active">Daftar Semester Berlaku</li>
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
          <a href="<?=base_index();?>semester/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
          <?php
                  }
              }
          }

          ?>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <table id="dtb_manual" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width:25px" align="center" rowspan="2">No</th>
                  
                                  <th rowspan="2">Tahun</th>
                                  <th rowspan="2">Status</th>
                                  <th rowspan="2">Periode Akademik</th>
                                  <th colspan="2">TGL KRS</th>
                                  <th colspan="2">TGL PKRS</th>
                                  <th colspan="2">TGL Input Nilai</th>
                                  <th rowspan="2">Action</th>
                </tr>
                <tr>
                  <th>Mulai</th>
                  <th>Selesai</th>
                  <th>Mulai</th>
                  <th>Selesai</th>
                  <th>Mulai</th>
                  <th>Selesai</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select   tgl_mulai,
  tgl_selesai,
  tgl_mulai_krs,
  tgl_selesai_krs,
  tgl_mulai_pkrs,
  tgl_selesai_pkrs,
  tgl_mulai_input_nilai,
  tgl_selesai_input_nilai,semester_ref.id_semester,semester_ref.tahun,jenis_semester.jns_semester,concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik
,aktif from semester_ref inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
order by aktif desc,id_semester desc");
      echo $db->getErrorMessage();
      $i=1;
      foreach ($dtb as $isi) {
        ?>
      <tr id="line_<?=$isi->id_semester;?>">
        <td align="center"><?=$i;?></td>
          <td><?=$isi->tahun;?></td>
          <td>
            <?php
            if ($isi->aktif=='1') {
              echo "<span class='label label-success'>Aktif</span>";
            } else {
               echo "<span class='label label-default'>Tidak Aktif</span>";
            }
            ?>
          </td>
          <td><?=$isi->tahun_akademik;?></td>
  <td><?=tgl_indo($isi->tgl_mulai_krs)?></td>
  <td><?=tgl_indo($isi->tgl_selesai_krs)?></td>
  <td><?=tgl_indo($isi->tgl_mulai_pkrs)?></td>
  <td><?=tgl_indo($isi->tgl_selesai_pkrs)?></td>
  <td><?=tgl_indo($isi->tgl_mulai_input_nilai)?></td>
  <td><?=tgl_indo($isi->tgl_selesai_input_nilai)?></td>
        <td>
           <?php
          if ($isi->aktif=='0') {
            ?>
            <a data-toggle="tooltip" title="Aktifkan Semester" onClick="aktifkan('<?=$isi->id_semester;?>')" class="btn btn-success btn-sm"><i class="fa fa-bolt"></i> Aktifkan</a>
            <?php
          }

          echo '<a data-toggle="tooltip" title="Lihat Detail Semester" href="'.base_index().'semester/detail/'.$isi->id_semester.'" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a> ';
          if($role_act["up_act"]=="Y") {
            echo '<a data-toggle="tooltip" title="Edit Semester" href="'.base_index().'semester/edit/'.$isi->id_semester.'" data-id="'.$isi->id_semester.'" class="btn edit_data btn-primary btn-sm"><i class="fa fa-pencil"></i></a> ';
          }
          if($role_act["del_act"]=="Y") {
            echo '<button data-toggle="tooltip" title="Hapus Semester" class="btn btn-danger hapus btn-sm" data-uri="'.base_admin().'modul/semester/semester_action.php" data-id="'.$isi->id_semester.'"><i class="fa fa-trash-o"></i></button>';
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

    </section><!-- /.content -->
<script type="text/javascript">
  function aktifkan(id,semester)
{

  $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/semester/semester_action.php?act=aktif",
    data : {id:id},
    success:function(data) {
      console.log(data);
        window.location.href="<?=base_index();?>semester";
    }
});
}
</script>