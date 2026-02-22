<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Manage Tipe Semester</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>tipe-semester">Tipe Semester</a></li>
    <li class="active">Tipe Semester List</li>
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
          <a href="<?=base_index();?>tipe-semester/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                  
                                  <th>Jenis Semester</th>
                                  <th>Singkatan</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select jenis_semester.jns_semester,jenis_semester.nm_singkat,jenis_semester.id_jns_semester from jenis_semester");
      $i=1;
      foreach ($dtb as $isi) {
        ?><tr id="line_<?=$isi->id_jns_semester;?>">
          <td align="center"><?=$i;?></td>
          <td><?=$isi->jns_semester;?></td>
          <td><?=$isi->nm_singkat;?></td>
        <td>
            <?php
            echo '<a href="'.base_index().'tipe-semester/detail/'.$isi->id_jns_semester.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
            if($role_act["up_act"]=="Y") {
              echo '<a href="'.base_index().'tipe-semester/edit/'.$isi->id_jns_semester.'" data-id="'.$isi->id_jns_semester.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/tipe_semester/tipe_semester_action.php" data-id="'.$isi->id_jns_semester.'"><i class="fa fa-trash-o"></i></button>';
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
