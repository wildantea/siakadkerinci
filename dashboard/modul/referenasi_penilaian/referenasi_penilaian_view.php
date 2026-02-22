<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Manage Referenasi Penilaian</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>referenasi-penilaian">Referenasi Penilaian</a></li>
    <li class="active">Referenasi Penilaian List</li>
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
          <a href="<?=base_index();?>referenasi-penilaian/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                  
                                  <th>Nama Komponen</th>
                                  <th>Wajib</th>
                                  <th>Tampil</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select komponen_nilai.nama_komponen,komponen_nilai.wajib,komponen_nilai.isShow,komponen_nilai.id from komponen_nilai");
      $i=1;
      foreach ($dtb as $isi) {
        ?><tr id="line_<?=$isi->id;?>">
          <td align="center"><?=$i;?></td>
          <td><?=$isi->nama_komponen;?></td>
          <td><?=$isi->wajib;?></td>
          <td><?=$isi->isShow;?></td>
        <td>
            <?php
            echo '<a href="'.base_index().'referenasi-penilaian/detail/'.$isi->id.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
            if($role_act["up_act"]=="Y") {
              echo '<a href="'.base_index().'referenasi-penilaian/edit/'.$isi->id.'" data-id="'.$isi->id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/referenasi_penilaian/referenasi_penilaian_action.php" data-id="'.$isi->id.'"><i class="fa fa-trash-o"></i></button>';
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
