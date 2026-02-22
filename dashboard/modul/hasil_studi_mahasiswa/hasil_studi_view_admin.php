<!-- Content Header (Page header) -->
<section class="content-header">
                    <h1>
                         KHS Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>khs-mahasiswa">KHS Mahasiswa</a></li>
                        <li class="active">KHS Mahasiswa List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body table-responsive">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post">
            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_khs_mahasiswa');
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->
            <?php
            }
            ?>    
              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Program studi</label>
                    <div class="col-lg-5">
                    <select id="jur_filter" name="jur_kode" data-placeholder="Pilih Program studi ..." class="form-control chzn-select" tabindex="2" required="">
                    <?php
                    $session_filter_fakultas = getFilter(array('filter_khs_mahasiswa' => 'fakultas'));
                    loopingProdi('filter_khs_mahasiswa',$session_filter_fakultas);
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                    <?php
                    $latest_angkatan = $db->fetch_custom_single("select (left(id_semester,4) - 7) as angkatan from semester_ref order by id_semester desc limit 1");
                    $awal = $latest_angkatan->angkatan;
                          $filter_session_mulai_smt = getFilter(array('filter_akm' => 'mulai_smt'));
                          $angkatan = $db->query("select left(mulai_smt,4) as mulai_smt from mahasiswa where mulai_smt > 1 and mahasiswa.nim not in(select nim from tb_data_kelulusan) group by left(mulai_smt,4) order by mulai_smt desc");
                           foreach ($angkatan as $ak) {
                             if ($awal==$ak->mulai_smt) {
                                echo "<option value='$ak->mulai_smt' selected>".$ak->mulai_smt."</option>";
                             } else {
                                echo "<option value='$ak->mulai_smt'>".$ak->mulai_smt."</option>";
                             }
                           }
                          ?>
                    </select>
                    </div>
                    <label for="Angkatan" class="control-label col-lg-1" style="width: 3%;padding-left: 0;text-align: left;">S/d</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt_end" name="mulai_smt_end" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                     
                      <?php
                          $filter_session_mulai_smt_end = getFilter(array('filter_akm' => 'mulai_smt_end'));
                          $angkatan = $db->query("select left(mulai_smt,4) as mulai_smt from mahasiswa where  mulai_smt > 1 and mahasiswa.nim not in(select nim from tb_data_kelulusan) group by left(mulai_smt,4) order by mulai_smt desc");
                           foreach ($angkatan as $ak) {
                             if ($filter_session_mulai_smt_end==$ak->mulai_smt) {
                               echo "<option value='$ak->mulai_smt' selected>".$ak->mulai_smt."</option>";
                             } else {
                               echo "<option value='$ak->mulai_smt'>".$ak->mulai_smt."</option>";
                             }
                             
                           }
                          ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Status" class="control-label col-lg-2">Status</label>
                    <div class="col-lg-5">
                    <select id="jenis_keluar" name="jenis_keluar" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
                        <option value="aktif">Aktif</option>
                    <?php
                    $jenis_keluar = $db->query("select id_jns_keluar,ket_keluar as jenis_keluar from jenis_keluar"); 
                     foreach ($jenis_keluar as $keluar) {
                     echo "<option value='$keluar->id_jns_keluar'>$keluar->jenis_keluar</option>";
                   }
                    ?>
                 
                    </select>
                    </div>
              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                         
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                        <table id="dtb_mahasiswa" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>Status</th>
                                  <th>SKS Ditempuh</th>
                                  <th>Program Studi</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  
            
              </div>
              </div>
              </div>
        <?php

    $edit ="";
    $del="";
        $edit = "<a data-id='+data+' href=".base_index()."hasil-studi-mahasiswa/show-nilai/".en('+data+')." class=\"edit_data btn btn-sm btn-primary\" data-toggle=\"tooltip\" title=\"Lihat KHS\"><i class=\"fa fa-eye\"></i> Lihat KHS</a>";
        ?>
    </section><!-- /.content -->

        <script type="text/javascript">
      var dtb_mahasiswa = $("#dtb_mahasiswa").DataTable({
            language: {
                searchPlaceholder: "Cari Nim atau Nama ..."
            },
            <?php
            if (getFilter(array('filter_khs_mahasiswa' => 'input_search'))!="") {
              ?>
                  "search": {
                    "search": "<?=getFilter(array('filter_khs_mahasiswa' => 'input_search'));?>"
                  },
              <?php
            }
            ?>   
           //'order' : [[3,'desc']],
               destroy : true,
           'bProcessing': true,
            'bServerSide': true,

         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [

              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }

              ],

            'ajax':{
              url :'<?=base_admin();?>modul/hasil_studi_mahasiswa/list_mahasiswa_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                  d.jur_kode = $("#jur_filter").val();
                  d.mulai_smt = $("#mulai_smt").val();
                  d.mulai_smt_end = $("#mulai_smt_end").val();
                  d.jenis_keluar = $("#jenis_keluar").val();
                  d.input_search = $('.dataTables_filter input').val();
                },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          });

//filter
$('#filter').on('click', function() {
  dtb_mahasiswa.ajax.reload();
});


$("#dtb_mahasiswa_filter").on('click','.reset-button-datatable',function(){
    dtb_mahasiswa
    .search( '' )
    .draw();
  });

  $("#mulai_smt").change(function(){
    console.log($(this).val());
        if ($(this).val()=='all') {
            $("#mulai_smt_end").val('all');
           $("#mulai_smt_end").trigger("chosen:updated");
        }
  });
$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jur_filter").html(data);
        $("#jur_filter").trigger("chosen:updated");

        }
    });
});
$("#jur_kode").change(function(){
            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/mahasiswa/get_angkatan.php",
            data : {jur_kode:this.value},
            success : function(data) {
                $("#mulai_smt").html(data);
                $("#mulai_smt").trigger("chosen:updated");
                $("#mulai_smt_end").html(data);
                $("#mulai_smt_end").trigger("chosen:updated");

            }
        });
});

</script>
            