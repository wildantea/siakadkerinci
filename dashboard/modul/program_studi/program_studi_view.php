<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Program Studi
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>program-studi">Program Studi</a></li>
                        <li class="active">Program Studi List</li>
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
                                      <a href="<?=base_index();?>program-studi/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
      <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/mahasiswa/download_data.php">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                        <div class="col-lg-5">
                        <select id="fakultas_filter" name="fakultas_filter" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
               <?php 
               foreach ($db->fetch_all("fakultas") as $isi) {
                  echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
               } ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
  
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenjang</label>
                        <div class="col-lg-5">
                        <select id="jenjang" name="jenjang" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
               foreach ($db->query("select jenjang_pendidikan.id_jenjang, jenjang_pendidikan.jenjang from jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
group by jenjang") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        <!--   <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button> -->
                     <!--       <span id="akun" class="btn btn-primary"><i class="fa fa-cloud-download"></i> Download Akun</span>
                      -->   </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
  
                        <table id="dtb_program_studi" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Kode Prodi Dikti</th>
                                  <th>Nama Program Studi</th>
                                  <th>Status</th>
                                  <th>Jenjang</th>
                                  <th>Ketua</th>
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
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."program-studi/edit/'+aData[indek]+' class=\"btn btn-primary edit_data btn-sm\" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button  data-id='+aData[indek]+' data-uri=".base_admin()."modul/program_studi/program_studi_action.php".' class="btn btn-danger hapus_dtb btn-sm" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
      
      
      var dataTable = $("#dtb_program_studi").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
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
              url :'<?=base_admin();?>modul/program_studi/program_studi_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').on('click', function() {
  dataTable = $("#dtb_program_studi").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            destroy : true,
           'bProcessing': true,
            'bServerSide': true,
                 "columnDefs": [ {
               "targets": [0,6],
              "orderable": false,
              "searchable": false

            } ],
            "ajax":{
              url :'<?=base_admin();?>modul/program_studi/program_studi_data.php',
            type: "post",  // method  , by default get
            data: function ( d ) {
                    d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jurusan_filter").val();
                    d.jenjang = $("#jenjang").val();
                  },
          error: function (xhr, error, thrown) {
            console.log(xhr);
            }
          },
        });

      });
    $("#fakultas_filter").change(function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/program_studi/get_jurusan_filter.php",
          data : {fakultas:this.value},
          success : function(data) {
              $("#jurusan_filter").html(data);
              $("#jurusan_filter").trigger("chosen:updated");

          }
      });

    });
</script>
            