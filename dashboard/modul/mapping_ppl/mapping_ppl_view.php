<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Mapping PPL</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>mapping-ppl">Mapping PPL</a></li>
    <li class="active">Mapping PPL List</li>
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
          <a href="<?=base_index();?>mapping-ppl/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
          <?php
                  }
              }
          }
          ?>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
            <table id="dtb_manual" class="table table-bordered table-striped">
              <thead>
                <tr> 
                  <th style="width:25px" align="center">No</th>
                  
                                  <th>Kode Mk</th>
                                  <th>Nama MK</th>
                                  <th>Jurusan</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select matkul_ppl.kode_mk,matkul.nama_mk,jurusan.nama_jur,matkul_ppl.id from matkul_ppl  left join jurusan on matkul_ppl.jurusan=jurusan.kode_jur inner join matkul on matkul_ppl.kode_mk=matkul.kode_mk group by matkul_ppl.jurusan order by jurusan.nama_jur desc ");
      $i=1;
      foreach ($dtb as $isi) {
        ?>
      <tr id="line_<?=$isi->id;?>">
        <td align="center"><?=$i;?></td>
          <td><?=$isi->kode_mk;?></td>
          <td><?=$isi->nama_mk;?></td>
          <td><?=$isi->nama_jur;?></td>
        <td>
          <?php
          echo '<a href="'.base_index().'mapping-ppl/detail/'.$isi->id.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
          if($role_act["up_act"]=="Y") {
            echo '<a href="'.base_index().'mapping-ppl/edit/'.$isi->id.'" data-id="'.$isi->id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
          }
          if($role_act["del_act"]=="Y") {
            echo '<button class="btn btn-danger hapus"  data-uri="'.base_admin().'modul/mapping_ppl/mapping_ppl_action.php" data-variable="dtb_mapping_ppl" data-id="'.$isi->id.'"><i class="fa fa-trash-o"></i></button>';
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
