<style type="text/css">
  .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Kelas & Jadwal Perkuliahan
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kelas-jadwal">Kelas & Jadwal Perkuliahan</a></li>
                        <li class="active">Kelas & Jadwal Perkuliahan List</li>
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
                                      <a href="<?=base_index();?>kelas-jadwal/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>

                                       <a id="gen-jadwal" class="btn btn-primary "><i class="fa fa-gear"> Generate Jadwal</i></a>
                                       <a id="reset-jadwal" class="btn btn-danger "><i class="fa fa-close"> Reset Jadwal</i></a>
                                      <?php
                                          }
                                      if ($role_act["import_act"]=="Y") {
                                          ?>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-success">Import Data</button>
                                          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                          </button>
                                          <ul class="dropdown-menu" role="menu">
                                            <li class="import_kelas"><a href="#"><i class="fa fa-cloud-upload"></i> Import Kelas</a></li>
                                            <li  class="import_dosen"><a href="#"><i class="fa fa-cloud-upload"></i> Import Dosen Ajar</a></li>
                                            <li  class="import_jadwal"><a href="#"><i class="fa fa-cloud-upload"></i> Import Jadwal Kuliah</a></li>
                                          </ul>
                                        </div>
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
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/kelas_jadwal/cetak.php" target="_blank">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi

                                  </label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                  <option value="all" selected>-Semua Jurusan-</option>
                                <?php
                                if ($_SESSION['group_level']=='dosen') {
                                   $q=$db->query("select k.kelas_id,k.kls_nama, m.nama_mk,m.kode_mk,ku.kode_jur,j.nama_jur from kelas k join dosen_kelas dk on k.kelas_id=dk.id_kelas inner join dosen ds on ds.nip=dk.id_dosen inner join matkul m on m.id_matkul=k.id_matkul inner join kurikulum ku on ku.kur_id=m.kur_id inner join jurusan j on j.kode_jur=ku.kode_jur where dk.id_dosen='99016' group by ku.kode_jur");
                                   foreach ($q as $k) {
                                     echo "<option value='$k->kode_jur'>$k->nama_jur</option>";
                                   }
                                }else{
                                  looping_prodi();
                                }
                                
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="">Pilih Semester</option>
                        <?php 
                        looping_semester();
                        ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Matakuliah</label>
                        <div class="col-lg-5">
                        <select id="matkul_filter" name="matkul_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                          <?php
                          looping_matkul_kelas();
                          ?>
                        </select>
                      </div>
                      </div><!-- /.form-group -->
                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Hari</label>
                        <div class="col-lg-3">
                        <select id="hari_filter" name="hari_filter" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <?php
                          $array_hari = array(
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                            'minggu' => 'Minggu'
                          );
                          foreach ($array_hari as $h => $hari) {
                            echo "<option value='$h'>$hari</option>";
                          }
                          ?>
                        </select>


 </div>
                      </div><!-- /.form-group -->
    <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Kelas</label>
                        <div class="col-lg-3">
                        <select id="jenis_kelas" name="jenis_kelas" data-placeholder="Pilih Jenis kelas ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <?php
                          foreach ($db->query("select * from jenis_kelas") as $jenis) {
                            echo "<option value='$jenis->id'>$jenis->nama_jenis_kelas</option>";
                          }
                          ?>
                        </select>


 </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                          <span id="exsport-jadwal" class="btn btn-success"><i class="fa fa-file-excel-o"></i>  Exsport Jadwal</span>
                  <div class="btn-group">
                  <?php

                  ?>
                  <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Cetak Data</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="cetak-data"><button type="submit" name="jenis_print" value="kelas" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi Kelas</li>
                    <li class="cetak-data"><button type="submit" name="jenis_print" value="uts" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi UTS</li>
                    <li class="cetak-data"><button type="submit" name="jenis_print" value="uas" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Presensi UAS</button>
                    <li class="cetak-data"><button type="submit" name="jenis_print" value="jadwal" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Jadwal</button>
                    </li>
                  </ul>

                </div>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                                <div class="row">
                                    <div class="col-sm-12" style="text-align: right;margin-bottom: 10px">
                                    <button id="select_all" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $lang["select_all"];?></button>
                                    <button id="deselect_all" class="btn btn-primary btn-xs"><i class="fa fa-remove"></i> <?php echo $lang["deselect_all"];?></button>
                                    <button id="bulk_delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
                        <table width="100%" id="dtb_kelas_jadwal" class="table table-bordered table-striped display responsive nowrap">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Matakuliah</th>
                                  <th>Kelas</th>
                                  <th>Ruang</th>
                                  <th>Hari/Jam</th>
                                  <th>Dosen</th>
                                  <th>Kuota Kelas</th>
                                  <th>KRS Disetujui</th>
                                  <th>KRS Belum Disetujui</th>
                                  <th>Program Studi</th>
                                  <th>Jenis Kelas</th>
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
                $jadwal = "<a data-id='+aData+' data-toggle=\"tooltip\" title=\"Pengaturan Jadwal\" class=\"edit-jadwal\"><i class=\"fa fa-calendar\"></i> Pengaturan Jadwal</a>";
                $print = "<a data-id='+aData+' data-toggle=\"tooltip\" title=\"Cetak\" class=\"cetak\"><i class=\"fa fa-print\"></i> Cetak Data</a>";
                $edit = "<a data-id='+aData+' href=".base_index()."kelas-jadwal/edit/'+aData+' data-toggle=\"tooltip\" title=\"Edit Kelas\"><i class=\"fa fa-pencil\"></i> Edit Kelas</a>";

              } else {
                $jadwal = "";
                  $edit ="";
                  $print = "";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<a data-id='+aData+'  data-uri=".base_admin()."modul/kelas_jadwal/kelas_jadwal_action.php".' data-variable="dataTable_jadwal" class="hapus_dtb_notif" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i> Hapus</a>';
            } else {
                $del="";
            }
                             }
            }

        ?>
  <!-- generate jadwal -->
    <div class="modal" id="modal_generate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Silakan Pilih Prodi dan Semester yang akan di generate</h4> </div> <div class="modal-body" id="isi_modal_generate"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <!-- reset jadwal -->
    <div class="modal" id="modal_reset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Silakan Pilih Prodi dan Semester yang akan di Reset</h4> </div> <div class="modal-body" id="isi_modal_reset"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4> </div> <div class="modal-body" id="isi_modal_cetak"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_kelas_jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Pengaturan Dosen Pengajar & Jadwal Perkuliahan</h4> </div> <div class="modal-body" id="isi_kelas_jadwal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
       <div class="modal" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pilih Dosen Pengajar</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
         <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title title-import">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->
        <script type="text/javascript">
$(".import_kelas").click(function() {
    $('.title-import').html('Import Kelas Kuliah');
          $.ajax({
              url : "<?=base_admin();?>modul/kelas_jadwal/import_kelas.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

$(".import_dosen").click(function() {
    $('.title-import').html('Import Dosen Ajar Kelas');
          $.ajax({
              url : "<?=base_admin();?>modul/kelas_jadwal/import_dosen.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });
$(".import_jadwal").click(function() {
    $('.title-import').html('Import Jadwal Kelas');
          $.ajax({
              url : "<?=base_admin();?>modul/kelas_jadwal/import_jadwal.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

//generate jadwal
    $("#gen-jadwal").on('click',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        id = currentBtn.attr('data-id');
        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_generate_jadwal.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_modal_generate").html(data);
                $("#loadnya").hide();
          }
        });
    $('#modal_generate').modal({ keyboard: false,backdrop:'static' });
    });

//generate jadwal
    $("#reset-jadwal").on('click',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        id = currentBtn.attr('data-id');
        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_reset_jadwal.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
              $("#loadnya").hide();
                $("#isi_modal_reset").html(data);
          }
        });
    $('#modal_reset').modal({ keyboard: false,backdrop:'static' });
    });

$("#sem_filter").change(function(){
    if ($("#jur_filter").val()!="" && $("#sem_filter").val()!="") {
        $.ajax({
              url : "<?=base_admin();?>modul/kelas_jadwal/get_matkul.php",
              type : "POST",
              data : {jur_filter:$("#jur_filter").val(),sem_filter:$("#sem_filter").val()},
              success: function(data) {
                  $("#matkul_filter").html(data);
                  $("#matkul_filter").trigger("chosen:updated");
              }
          });
    }
});
$("#jur_filter").change(function(){
    if ($("#jur_filter").val()!="" && $("#sem_filter").val()!="") {
        $.ajax({
              url : "<?=base_admin();?>modul/kelas_jadwal/get_matkul.php",
              type : "POST",
              data : {jur_filter:$("#jur_filter").val(),sem_filter:$("#sem_filter").val()},
              success: function(data) {
                  $("#matkul_filter").html(data);
                  $("#matkul_filter").trigger("chosen:updated");
              }
          });
    }
});

    $(".table").on('click','.cetak',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_print_single.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_modal_cetak").html(data);
                $("#loadnya").hide();
          }
        });
    $('#modal_cetak_single').modal({ keyboard: false,backdrop:'static' });
    });


    $(".table").on('click','.edit-jadwal',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_setting_jadwal.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_kelas_jadwal").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_kelas_jadwal').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
          "order": [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0],
             'width': '3%',
              'orderable': false,
              'searchable': false
            },
            {
            'targets': [10],
              'className': 'none'
            },
           {
            'targets': [11],
              'orderable': false,
              'searchable': false,
              'className': 'all dt-center',
              "render": function(aData, type, full, meta){
                return '<div class="dropup"><div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$jadwal;?></li><li><?=$print;?></li><li><?=$edit;?></li><li><?=$del;?></li></ul></div></div>';
               }
            }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

      $("#exsport-jadwal").click(function(){
          // alert("<?= base_admin() ?>");
          var prodi = $("#jur_filter").val();
          var semester = $("#sem_filter").val();
          var matkul = $("#matkul_filter").val();
          var hari = $("#hari_filter").val();
          var jenis_kelas = $("#jenis_kelas").val();
          document.location="<?= base_admin() ?>modul/kelas_jadwal/exsport_excel.php?jur_filter="+prodi+"&sem_filter="+semester+"&matkul_filter="+matkul+"&hari_filter="+hari+"&jenis_kelas="+jenis_kelas;
      });

$('#filter').on('click', function() {
  dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
            "order": [[1,'asc']],  
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ 
            {
            'targets': [10],
              'className': 'none'
            },
           {
            'targets': [11],
              'orderable': false,
              'searchable': false,
              'className': 'all dt-center',
              "render": function(aData, type, full, meta){
                return '<div class="dropup"><div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$jadwal;?></li><li role="separator" class="divider"></li><li><?=$print;?></li><li role="separator" class="divider"></li><li><?=$edit;?></li><li role="separator" class="divider"></li><li><?=$del;?></li></ul></div></div>';
               }
            },
/*                        {
            'targets': [6,7,8],
              'className': 'none'
            },*/
            {
            'targets': [0],
             'width': '3%',
              'orderable': false,
              'searchable': false
            },
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter").val();
                    d.sem_filter = $("#sem_filter").val();
                    d.matkul_filter = $("#matkul_filter").val();
                    d.hari_filter = $("#hari_filter").val();
                    d.jenis_kelas = $("#jenis_kelas").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});

$(document).ready(function(){
          //chosen select
          $(".chzn-select").chosen();
          $(".chzn-select-deselect").chosen({
              allow_single_deselect: true
          });
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
       $.validator.addMethod("myFunc", function(val) {
        if(val=='all'){
          return false;
        } else {
          return true;
        }
      }, "Untuk Cetak Data Silakan Pilih Prodi");
    $("#filter_kelas_form").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          jur_filter: {
            myFunc:true
          //minlength: 2
          },
        
          sem_filter: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
        
          sem_filter: {
          required: "Untuk Cetak Data Silakan Pilih Semester",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        }
    });
});

 $(".table").on('click','.hapus_dtb_notif',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#ucing')
        .modal({ keyboard: false })
        .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          url: uri+"?act=delete&id="+id,
             success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                             window[dtb_var].draw(false);
                          } else {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          }
                    });
                }
          });
          $('#ucing').modal('hide');

        });
  });

$('#dtb_kelas_jadwal').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_kelas_jadwal tbody tr td', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          init_selected();

      }
  });



  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#select_all, #deselect_all, #bulk_delete, .selected-data');
      if (selected.length > 0) {
          btn_hide.show()
      } else {
          btn_hide.hide()
      }
  }


  function check_selected() {
      var table_select = $('#dtb_kelas_jadwal tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_kelas_jadwal tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_kelas_jadwal tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dataTable_jadwal );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
  $.ajax({
          type: "POST",
          dataType: 'json',
          url: '<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=del_massal',
          data: {data_ids:all_ids},
          success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                             dataTable_jadwal.draw(false);
                          } else {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          }
                    });
                }
          });

        $('#ucing').modal('hide');

    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }

</script>
            