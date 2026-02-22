<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Mahasiswa Lulus / DO
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa-lulus">Mahasiswa Lulus / DO</a></li>
                        <li class="active">Mahasiswa Lulus / DO List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                if ($db2->userCan("insert")) {
                                      ?>
                                      <a href="<?=base_index();?>mahasiswa-lulus/create" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                  }
                                if ($db2->userCan("import")) {
                                      ?>
                                <a class="btn btn-primary" id="import_data"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                <?php
                              }
                              ?>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">
                                   <button id="bulk_delete" style="display: none;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/mahasiswa_lulus/download_data.php" target="_blank">
            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_data_kelulusan');
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->
            <?php
            }
            ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jur_kode" name="jur_kode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingProdi('filter_data_kelulusan',getFilter(array('filter_data_kelulusan' => 'fakultas')));
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Keluar</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <?php 
                          $filter_session_sem_filter = getFilter(array('filter_data_kelulusan' => 'sem_filter'));
                          //get default akses prodi
                          $jur_kode = aksesProdi('tb_data_kelulusan.kode_jurusan');
                          $get_exist_periode = $db2->query("select tb_data_kelulusan.semester from tb_data_kelulusan 
inner join semester_ref on tb_data_kelulusan.semester=id_semester
where 1=1 $jur_kode
group by semester order by id_semester desc");
                          $index = 0;
                          foreach ($get_exist_periode as $periode) {
                            $smt = (string)$periode->semester;
                            if ($smt!="") {
                              if ($filter_session_sem_filter==$smt) {
                                echo "<option value='$periode->semester' selected>".ganjil_genap($periode->semester)."</option>";
                              } else {
                                  echo "<option value='$periode->semester'>".ganjil_genap($periode->semester)."</option>";
                              }
                               $index++;
                            }

                          }
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                    <?php

                  $filter_session_mulai_smt = getFilter(array('filter_data_kelulusan' => 'mulai_smt'));
                  
                   $angkatan = $db2->query("select mulai_smt from mahasiswa inner join tb_data_kelulusan using(nim) group by mulai_smt order by mulai_smt desc");
                   
                   foreach ($angkatan as $ak) {
                     if (trim($filter_session_mulai_smt)==$ak->mulai_smt) {
                       echo "<option value='$ak->mulai_smt' selected>".getAngkatan($ak->mulai_smt)."</option>";
                     } else {
                      echo "<option value='$ak->mulai_smt'>".getAngkatan($ak->mulai_smt)."</option>";
                      
                     }
                     
                   }
                    ?>
                    </select>
                    </div>
                    <label for="Angkatan" class="control-label col-lg-1" style="width: 3%;padding-left: 0;text-align: left;">S/d</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt_end" name="mulai_smt_end" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                    <?php
                  $filter_session_mulai_smt_end = getFilter(array('filter_data_kelulusan' => 'mulai_smt_end'));
                   $angkatan = $db2->query("select mulai_smt from mahasiswa inner join tb_data_kelulusan using(nim) group by mulai_smt order by mulai_smt desc");
                   foreach ($angkatan as $ak) {
                     if ($filter_session_mulai_smt_end==$ak->mulai_smt) {
                       echo "<option value='$ak->mulai_smt' selected>".getAngkatan($ak->mulai_smt)."</option>";
                     } else {
                      echo "<option value='$ak->mulai_smt'>".getAngkatan($ak->mulai_smt)."</option>";
                     }
                   }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Status" class="control-label col-lg-2">Jenis Keluar</label>
                    <div class="col-lg-5">
                    <select id="jenis_keluar" name="jenis_keluar" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                    <?php
                    $filter_session_jenis_keluar = getFilter(array('filter_data_kelulusan' => 'jenis_keluar'));
                    $jenis_keluar = $db2->query("select id_jns_keluar,ket_keluar from jenis_keluar");
                     foreach ($jenis_keluar as $keluar) {
                      if ($filter_session_jenis_keluar==$keluar->id_jns_keluar) {
                        echo "<option value='$keluar->id_jns_keluar' selected>$keluar->ket_keluar</option>";
                      } else {
                        echo "<option value='$keluar->id_jns_keluar'>$keluar->ket_keluar</option>";
                      }
                      
                   }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
 <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">IPK</label>
                <div class="col-lg-2" style="padding-right: 2px">
                  <select class="form-control" name="control_ipk" id="control_ipk">
                    <option value="all">Semua</option>
                        <option value=">=">>=</option>
                        <option value="<="><=</option>
                      </select>
                </div>
                <div class="col-lg-1" style="padding-left: 0">
                    <input type="text" class="form-control desimal" name="ipk" id="ipk" autocomplete="off">
                </div>
              </div>
 <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Semester Keluar</label>
                <div class="col-lg-2" style="padding-right: 2px">
                  <select class="form-control" name="control_semester" id="control_semester">
                    <option value="all">Semua</option>
                    <option value="=">=</option>
                        <option value=">=">>=</option>
                        <option value="<="><=</option>
                      </select>
                </div>
                <div class="col-lg-1" style="padding-left: 0">
                    <input type="number" class="form-control" name="semester" id="semester" autocomplete="off">
                </div>
              </div>

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                           <?php
                            resetFilterButton('filter_data_kelulusan');
                            ?>
                  <?php
                      if ($db2->userCan("import")) {
                        ?>
                  <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button>
                      </div><!-- /.form-group -->
                      <?php
                    }
                    ?>
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_mahasiswa_lulus" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>Jenis Keluar</th>
                                  <th>Tanggal Keluar</th>
                                  <th>Periode Keluar</th>
                                  <th>IPK</th>
                                  <th><a data-toggle="tooltip" data-title="Semester Keluar">SMT</a></th>
                                  <th>Program Studi</th>
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
    <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
 <?php
  $edit ="";
  $del="";
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."mahasiswa-lulus/edit/'+data+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/mahasiswa_lulus/mahasiswa_lulus_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_mahasiswa_lulus"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    </section><!-- /.content -->
        <script type="text/javascript">
        $("#import_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_lulus/import_data.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

      var dtb_mahasiswa_lulus = $("#dtb_mahasiswa_lulus").DataTable({
           'bProcessing': true,
            'bServerSide': true,
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$edit;?> <?=$del;?>';
               }
            },
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
             ],
            'ajax':{
              url :'<?=base_admin();?>modul/mahasiswa_lulus/mahasiswa_lulus_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                  d.fakultas = $("#fakultas_filter").val();
                  d.sem_filter = $("#sem_filter").val();
                  d.jur_kode = $("#jur_kode").val();
                  d.mulai_smt = $("#mulai_smt").val();
                  d.mulai_smt_end = $("#mulai_smt_end").val();
                  d.jenis_keluar = $("#jenis_keluar").val();
                  d.control_semester = $("#control_semester").val();
                  d.semester = $("#semester").val();
                  d.control_ipk = $("#control_ipk").val();
                  d.ipk = $("#ipk").val();
                  d.input_search = $('.dataTables_filter input').val();
                },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
$("#dtb_mahasiswa_lulus_filter").on("click",".reset-button-datatable",function(){
    dtb_mahasiswa_lulus
    .search( "" )
    .draw();
  });

//filter
$('#filter').on('click', function() {
  dtb_mahasiswa_lulus.ajax.reload();
});

$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/mahasiswa_lulus/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jur_kode").html(data);
        $("#jur_kode").trigger("chosen:updated");

        }
    });
});

</script>
            