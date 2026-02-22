<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Manage Nilai Ref</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai-ref">Nilai Ref</a></li>
    <li class="active">Nilai Ref List</li>
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
          <a href="<?=base_index();?>nilai-ref/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                  <th style="width:25px" align="center">No</th>
                  
                                  <th>Bobot</th>
                                  <th>Nilai Huruf</th>
                                  <th>Batas Bawah</th>
                                  <th>Batas Atas</th>
                                  <th>Tgl Mulai</th>
                                  <th>Tgl Selesai</th>
                                  <th>Prodi</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select nilai_ref.bobot,nilai_ref.nilai_huruf,nilai_ref.batas_bawah,nilai_ref.batas_atas,nilai_ref.tgl_mulai,nilai_ref.tgl_selesai,jurusan.nama_jur,nilai_ref.nilai_id from nilai_ref  inner join jurusan on nilai_ref.prodi_id=jurusan.kode_jur order by nilai_ref.nilai_id desc");
      $i=1;
      foreach ($dtb as $isi) {
        ?>
      <tr id="line_<?=$isi->nilai_id;?>">
        <td align="center"><?=$i;?></td>
          <td><?=$isi->bobot;?></td>
          <td><?=$isi->nilai_huruf;?></td>
          <td><?=$isi->batas_bawah;?></td>
          <td><?=$isi->batas_atas;?></td>
          <td><?=$isi->tgl_mulai;?></td>
          <td><?=$isi->tgl_selesai;?></td>
          <td><?=$isi->nama_jur;?></td>
        <td>
          <?php
          echo '<a href="'.base_index().'nilai-ref/detail/'.$isi->nilai_id.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
          if($role_act["up_act"]=="Y") {
            echo '<a href="'.base_index().'nilai-ref/edit/'.$isi->nilai_id.'" data-id="'.$isi->nilai_id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
          }
          if($role_act["del_act"]=="Y") {
            echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/nilai_ref/nilai_ref_action.php" data-id="'.$isi->nilai_id.'"><i class="fa fa-trash-o"></i></button>';
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
