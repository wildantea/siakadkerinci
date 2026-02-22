<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Level</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>level">Level</a></li>
    <li class="active">Level List</li>
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
          <a href="<?=base_index();?>level/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                  
                                  <th>level</th>
                                  <th>dekripsi</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                
      <?php
      $dtb=$db->query("select sys_group_level.level,sys_group_level.dekripsi from sys_group_level");
      $i=1;
      foreach ($dtb as $isi) {
        ?><tr id="line_<?=$isi->level;?>">
          <td align="center"><?=$i;?></td>
          <td><?=$isi->level;?></td>
          <td><?=$isi->dekripsi;?></td>
        <td>
            <?php
            echo '<a href="'.base_index().'level/detail/'.$isi->level.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
            if($role_act["up_act"]=="Y") {
              echo '<a href="'.base_index().'level/edit/'.$isi->level.'" data-id="'.$isi->level.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/level/level_action.php" data-id="'.$isi->level.'"><i class="fa fa-trash-o"></i></button>';
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
