<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa">Mahasiswa</a></li>
                        <li class="active">Mahasiswa List</li>
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
                                      <a href="<?=base_index();?>mahasiswa/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <a class="btn btn-primary" id="import_data"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                     
                                      <?php
                                          }

                                      if ($role_act["import_act"]=="Y") {
                                          ?>
                                       
                                          <a class="btn btn-primary" id="import_data_pa"><i class="fa fa-cloud-upload"></i> Import Dosen PA</a>
                                          <?php
                                          }
                                      }
                                  }
                                
                                     ?>
                                </div><!-- /.box-header -->



                            <div class="box-body table-responsive">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/mahasiswa/aksi.php" target="_blank">
                   
              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Program studi</label>
                    <div class="col-lg-5">
                    <select id="jur_kode" name="jur_kode" data-placeholder="Pilih Program studi ..." class="form-control chzn-select" tabindex="2" required="">
                    <?php
                    looping_prodi();
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select distinct(left(mulai_smt,4)) as mulai_smt from mahasiswa order by mulai_smt desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->mulai_smt'>$ak->mulai_smt</option>";
                   }
                    ?>
                    </select>
                    </div>
                    <label for="Angkatan" class="control-label col-lg-1" style="width: 3%;padding-left: 0;text-align: left;">S/d</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt_end" name="mulai_smt_end" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select distinct(left(mulai_smt,4)) as mulai_smt from mahasiswa order by mulai_smt desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='$ak->mulai_smt'>$ak->mulai_smt</option>";
                   }
                    ?>
                    </select>
                    </div>  
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Jenis Kelamin" class="control-label col-lg-2">Jenjang Pendidikan</label>
                    <div class="col-lg-5">
                    <select id="jenjang" name="jenjang" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
                    <?php
                   $jenjang_pendidikan = $db->query("select id_jenjang,jenjang from jenjang_pendidikan where id_jenjang in(select id_jenjang from jurusan)"); 
                   foreach ($jenjang_pendidikan as $jenjang) {
                     echo "<option value='$jenjang->id_jenjang'>$jenjang->jenjang</option>";
                   }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Kelamin</label>
                    <div class="col-lg-5">
                    <select id="jk" name="jk" data-placeholder="Pilih Jenis Kelamin ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
                   <option value="L">Laki - Laki</option>
                   <option value="P">Perempuan</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                    <label for="Jenis Kelamin" class="control-label col-lg-2">Jenis Pendaftaran</label>
                    <div class="col-lg-5">
                    <select id="id_jenis_daftar" name="id_jenis_daftar" data-placeholder="Pilih Jenis Pendaftaran ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
                    <?php
                   $jns_daftar = $db->query("select id_jenis_daftar,nm_jns_daftar from jenis_daftar "); 
                   foreach ($jns_daftar as $ak) {
                     echo "<option value='$ak->id_jenis_daftar'>$ak->nm_jns_daftar</option>";
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
                    <option value="calon">Calon Mahasiswa</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                    <label for="Status" class="control-label col-lg-2">Periode Cuti</label>
                    <div class="col-lg-5">
                    <select id="periode_cuti" name="periode_cuti" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="">
                       
                    <?php
                    $jenis_keluar = $db->query("select id_semester from semester_ref where right(id_semester,1) in(1,2) order by id_semester desc "); 
                     foreach ($jenis_keluar as $keluar) {
                     echo "<option value='$keluar->id_semester'>".getTahunakademik($keluar->id_semester)."</option>";
                   }
                    ?>
                    </select>
                    </div>
              </div>

                <div class="form-group">
                    <label for="Jenis Kelamin" class="control-label col-lg-2">Data Lengkap</label>
                    <div class="col-lg-5">
                    <select id="is_submit_biodata" name="is_submit_biodata" data-placeholder="Pilih Status Lengkap ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
                   <option value="Y">Lengkap</option>
                   <option value="N">Belum</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                  <div class="btn-group">
                  <button type="button" class="btn btn-info"><i class="fa fa-external-link-square"></i> Aksi</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="cetak-data"><button type="submit" name="jenis" value="download" class="btn cetak-data"><i class="fa fa-cloud-download"></i> Download Mahasiswa</li>
                    <li class="cetak-data"><button type="submit" name="jenis" value="cetak" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Akun Mahasiswa</li>
                  </ul>
                </div>
                      </div><!-- /.form-group -->
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
        <div id="hasil_sinkron">
     
        </div>
       <div class="progress" id="progress_sinkron" style="display: none">
        <div class="progress-bar" id="persen" role="progressbar" aria-valuenow="70"
        aria-valuemin="0" aria-valuemax="100" style="width:40%">
          70%
        </div>
      </div>
        
                        <table id="dtb_mahasiswa" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>Jenis Daftar</th>
                                  <th>Status</th>
                                  <th>Dosen Pembimbing</th>
                                  <th>Program Studi</th>
                                  <th>Data Lengkap</th>
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


        $edit_dosen = "<a data-id='+aData[indek]+'  class=\"btn btn-primary btn-xs edit_data_dosen \" data-toggle=\"tooltip\" title=\"Edit Dosen Pembimbing\"><i class=\"fa fa-edit\"></i></a>";

        $reset = "";
        $array_login_as = array('admin','admin_akademik','TIPD');

         if (in_array($_SESSION['group_level'], $array_login_as)) {
          $reset = "<a data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Reset Password\"  class=\"btn btn-danger reset_pass btn-xs\"><i class=\"fa fa-undo\"></i> Reset</a>";
        }

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Edit\" href=".base_index()."mahasiswa/edit/'+aData[indek]+' class=\"btn btn-primary edit_data btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
                 
                  $cetak_ktm = "<a href=\"".base_url()."dashboard/modul/mahasiswa/cetak_ktm.php?id='+aData[indek]+'\" target=\"_BLANK\" data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Cetak KTM\"  class=\"btn btn-success  btn-xs\"><i class=\"fa fa-credit-card\"></i> Cetak KTM</a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/mahasiswa/mahasiswa_action.php".' data-toggle="tooltip" title="Hapus" class="btn btn-danger hapus_dtb btn-xs"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            } 
                             }
            }

        ?>
    <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel (Max Sekali Import 10 ribu Data) </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <div class="modal" id="modal_reset_pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Reset Password</h4> </div> <div class="modal-body" id="isi_reset_pass"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_detail_sinkron" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Mahasiswa Berhasil Sinkron</h4> </div> <div class="modal-body" id="isi_detai_mhs">
       <table id="dtb_mahasiswa_sinkron" class="table table-bordered table-striped" width="100%">
        <thead>
                                <tr>
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Jurusan</th>

                                  <th>Fakultas</th>
                                  <th>Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
       </table>
        
 
     </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_bim_akademis"  role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Tambah/Edit Kolektif Penasehat Akademik</h4> </div> <div class="modal-body" id="isi_bim_akademis"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    </section><!-- /.content -->

        <script type="text/javascript">
 
    $(".table").on('click','.edit_data_dosen',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Pembimbing Akademik");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/mahasiswa/edit_dosenpa.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_bim_akademis").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_bim_akademis').modal({ keyboard: false,backdrop:'static' });

    });

        
    // setInterval(alert("test"), 60 * 1000);

       function sinkron_damba() {
        $("#loadnya").show();
        // $("#progress_sinkron").show();
        // $("#persen").attr('style','width:0%');
        //  $("#persen").html('0%');
        // $("#progress_sinkron").show();
        // $.ajax({
        //       url : "<?=base_admin();?>modul/mahasiswa/mahasiswa_action.php?act=get_total_mhs",
        //       type : "GET",
        //      // dataType : "JSON",
        //       success: function(data) { 
        //         $("#loadnya").hide();
        //       }
        //   });

          //setInterval(cek_progress(), 1000);
         


         $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa/mahasiswa_action.php?act=sinkron_damba",
              type : "GET",
             // dataType : "JSON",
              success: function(data) { 
                // alert(data);  
                $("#loadnya").hide();
                $("#hasil_sinkron").html(data); 
                $("#dtb_mahasiswa_sinkron").DataTable({
                
                  'order' : [1,'desc'],
                 'bProcessing': true,
                  'bServerSide': true,
                  
                 'columnDefs': [
                  // {
                  // 'targets': [0,7],
                  //   'orderable': false,
                  //   'searchable': false
                  // },
                      {
                  'width': '5%',
                  'targets': 0,
                  'orderable': false,
                  'searchable': false,
                  'className': 'dt-center'
                }
                   ],

          
                  'ajax':{
                    url :'<?=base_admin();?>modul/mahasiswa/mahasiswa_data_sinkron.php',
                  type: 'post',  // method  , by default get
                  error: function (xhr, error, thrown) {
                  console.log(xhr);

                  }
                },
              });
              }
          });
       }

       function  cek_progress() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa/mahasiswa_action.php?act=get_progress",
              type : "GET",
             // dataType : "JSON",
              success: function(data) {  
                $("#persen").attr('style','width:'+data+'%');
                $("#persen").html(data+'%');
              }
          });
       } 

        $("#import_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa/import_data.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });



        $("#import_data_pa").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa/import_pa.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $(".table").on('click','.reset_pass',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/mahasiswa/reset_password.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_reset_pass").html(data);
                $('#modal_reset_pass').modal({ keyboard: false,backdrop:'static' });
                $("#loadnya").hide();
          }
        });



    });
      
      var dtb_mahasiswa = $("#dtb_mahasiswa").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            var nim = aData.length-4;
            $('td:eq('+indek+')', nRow).html('<?=$edit_dosen;?> <?= $cetak_ktm ?> <?=$reset;?> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            'order' : [1,'desc'],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/mahasiswa/mahasiswa_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').click(function(){
        var dtb_mahasiswa = $("#dtb_mahasiswa").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            var nim = aData.length-4;
            $('td:eq('+indek+')', nRow).html('<?=$edit_dosen;?> <?= $cetak_ktm ?> <?=$reset;?> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              }, 
              destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [7],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/mahasiswa/mahasiswa_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                  //filter variable datatable
                  d.jur_kode = $("#jur_kode").val();
                  d.mulai_smt = $("#mulai_smt").val();
                  d.mulai_smt_end = $("#mulai_smt_end").val();
                  d.jenjang = $("#jenjang").val();
                  d.jk = $("#jk").val();
                  d.id_jenis_daftar = $("#id_jenis_daftar").val();
                  d.jenis_keluar = $("#jenis_keluar").val();
                  d.periode_cuti = $("#periode_cuti").val();
                  d.is_submit_biodata = $("#is_submit_biodata").val();
                  
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

});

  $("#mulai_smt").change(function(){
    console.log($(this).val());
        if ($(this).val()=='all') {
            $("#mulai_smt_end").val('all');
           $("#mulai_smt_end").trigger("chosen:updated");
        }
  });

  $("#jur_kode").change(function(){
            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/mahasiswa/get_angkatan.php",
            data : {jur_kode:this.value},
            success : function(data) {
                $("#mulai_smt").html(data);
                $("#mulai_smt").trigger("chosen:updated");

            }
        });
            });

</script>
            