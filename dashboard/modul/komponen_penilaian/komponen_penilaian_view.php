<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Komponen Penilaian</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>komponen-penilaian">Komponen Penilaian</a></li>
    <li class="active">Komponen Penilaian List</li>
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
          <a id="add_komponen_penilaian" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
            echo '<a href="'.base_index().'komponen-penilaian/detail/'.$isi->id.'" class="btn btn-success "><i class="fa fa-eye"></i></a> ';
            if($role_act["up_act"]=="Y") {
              echo '<a data-uri="'.base_index().'komponen-penilaian/edit/'.$isi->id.'" data-id="'.$isi->id.'" class="btn edit_data btn-primary "><i class="fa fa-pencil"></i></a> ';
            }
            if($role_act["del_act"]=="Y") {
              echo '<button class="btn btn-danger hapus " data-uri="'.base_admin().'modul/komponen_penilaian/komponen_penilaian_action.php" data-id="'.$isi->id.'"><i class="fa fa-trash-o"></i></button>';
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

    <div class="modal" id="modal_komponen_penilaian" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Komponen Penilaian</h4> </div> <div class="modal-body" id="isi_komponen_penilaian"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->
<script type="text/javascript">
      
      $("#add_komponen_penilaian").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/komponen_penilaian/komponen_penilaian_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_komponen_penilaian").html(data);
              }
          });

      $('#modal_komponen_penilaian').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/komponen_penilaian/komponen_penilaian_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_komponen_penilaian").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_komponen_penilaian').modal({ keyboard: false,backdrop:'static' });

    });
    
    </script>