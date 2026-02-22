<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manajemen Pengguna
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>manajemen-pengguna">Manajemen Pengguna</a></li>
                        <li class="active">Manajemen Pengguna List</li>
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
                                      <a href="<?=base_index();?>manajemen-pengguna/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
       <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/pendaftaran/download_data.php" target="_blank">

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Akun</label>
                        <div class="col-lg-5">
                        <select id="jenis_akun" name="jenis_akun" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <?php
                          $data_jenis_akun = $db->query("select * from sys_group_level");
                          foreach ($data_jenis_akun as $akun) {
                              echo "<option value='$akun->level'>".ucwords($akun->dekripsi)."</option>";
                          }
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Group Pengguna</label>
                                <div class="col-lg-5">
                                <select id="group_level" name="group_level" data-placeholder="Pilih group_level ..." class="form-control chzn-select" tabindex="2" required="">
                                  <option value="all">Semua</option>
                      </select>
                    </div>
                  </div>

                      <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Status Aktif</label>
                        <div class="col-lg-5">
                        <select id="aktif" name="aktif" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                            <option value="Y">Aktif</option>
                            <option value="N">Non Aktif</option>
                        </select>
                      </div>
                    </div>

                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                      </div>
                      </div><!-- /.form-group -->
                    </form>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_manajemen_pengguna" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Nama Awal</th>
                                  <th>Username</th>
                                  <th>Email</th>
                                  <th>Level</th>
                                  <th>Group Pengguna</th>
                                  <th>Aktif</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
        <?php

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."manajemen-pengguna/edit/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/manajemen_pengguna/manajemen_pengguna_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_manajemen_pengguna"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dtb_manajemen_pengguna = $("#dtb_manajemen_pengguna").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<a href="<?=base_index();?>manajemen-pengguna/reset/'+aData[indek]+'"  class="btn btn-danger btn-sm" data-toggle="tooltip" title="Reset Password"> Reset</a> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

              buttons: [
              {
                 extend: 'collection',
                 text: 'Export Data',
                 buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ],

              }
              ],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [6],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/manajemen_pengguna/manajemen_pengguna_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jenis_akun = $("#jenis_akun").val();
                    d.group_level = $("#group_level").val();
                    d.aktif = $("#aktif").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });


//filter
$('#filter').on('click', function() {
  dtb_manajemen_pengguna.ajax.reload();
});

$("#jenis_akun").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/manajemen_pengguna/get_group_level.php",
    data : {jenis_akun:this.value},
    success : function(data) {
        $("#group_level").html(data);
        $("#group_level").trigger("chosen:updated");

        }
    });
});
</script>
            