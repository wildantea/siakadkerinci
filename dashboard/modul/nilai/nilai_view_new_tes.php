<style type="text/css">
  .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Nilai Perkelas
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>nilai">Nilai Perkelas</a></li>
                        <li class="active">Nilai Perkelas List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                      <?php
                                        if ($role_act["import_act"]=="Y") {
                                        ?>
                                          <a class="btn btn-primary" id="import_data"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                          <?php
                                        }
                                     ?>
                                </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/nilai/download_data.php" target="_blank">
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jur_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                looping_prodi();
                                ?>
                      </select>

         </div>
                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <option value="all">Semua</option>
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
                        <label for="Semester" class="control-label col-lg-2">Lihat Berdasarkan</label>
                        <div class="col-lg-5">
                        <select id="lihat_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="kelas">Lihat Per Kelas</option>
                          <option value="mahasiswa">Lihat Per Nilai Mahasiswa</option>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

             <div class="form-group show-nim" style="display:none;">
                        <label for="Semester" class="control-label col-lg-2">Filter NIM</label>
                        <div class="col-lg-2" >
                        <select id="nim" name="nim" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" style="z-index: 1" required="">

                        <?php
                        $array_filter_nim = array(
                          'all' => 'Semua',
                          'nim' => 'NIM'
                        );
                          foreach ($array_filter_nim as $key => $value) {
                              echo "<option value='$key'>$value</option>";
                          }
                          ?>
                          </select>
                        </div>
                      <div class="col-lg-3" style="padding-left: 0;">
                        <input type="text" name="value_nim" id="value_nim" class="form-control" placeholder="Input NIM Mahasiswa" style="display:none;">
                      </div>
        </div><!-- /.form-group -->

              <div class="form-group show-angkatan" style="display:none">
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

                      <div class="form-group show-penilaian" style="display:none">
                        <label for="Semester" class="control-label col-lg-2">Status Penilaian</label>
                        <div class="col-lg-5">
                        <select id="status_penilaian" name="status_penilaian" data-placeholder="Pilih Status Nilai ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <option value="sudah">Sudah di Nilai</option>
                          <option value="belum">Belum di Nilai</option>
                            </select>
                          </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                          <?php
                            if ($role_act["import_act"]=="Y") {
                            ?>
                             <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                              
                              <?php
                            }
                         ?>

             <!--      <div class="btn-group">
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
                             </div> -->
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

                        <table id="dtb_nilai_permahasiswa" class="table table-bordered table-striped display nowrap" width="100%" style="display: none">
                            <thead>
                                <tr>
                                   <th rowspan="2" style='padding-right:7px;width: 4%' class='dt-center'>#</th>
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Periode</th>
                                  <th colspan="3" class="dt-center">Nilai</th>
                                  <th rowspan="2">Program Studi</th>

                                </tr>
                                <tr>
                                  <th>Angka</th>
                                  <th>Huruf</th>
                                  <th>Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <table id="dtb_nilai_per_kelas" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th rowspan="2">No</th>
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Dosen</th>
                                  <th rowspan="2">Peserta</th>
                                  <th colspan="2" style="text-align: center">Status Dinilai</th>
                                  <th rowspan="2">Prodi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Sudah</th>
                                  <th>Belum</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>

    <div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4> </div> <div class="modal-body" id="isi_modal_cetak"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_kelas_jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Pengaturan Dosen Pengajar & Jadwal Perkuliahan</h4> </div> <div class="modal-body" id="isi_kelas_jadwal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
       <div class="modal" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pilih Dosen Pengajar</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
     <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel (Max Sekali Import 10 ribu Data) </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->
        <script type="text/javascript">
   $("#lihat_filter").change(function(){
    if (this.value=='mahasiswa') {
      $(".show-nim").show();
      $(".show-angkatan").show();
      $(".show-penilaian").show();
        $("#dtb_nilai_permahasiswa").show();
        dtb_nilai_permahasiswa.ajax.reload();
        $("#dtb_nilai_per_kelas_wrapper").hide();
        $("#dtb_nilai_permahasiswa_wrapper").show();
    } else {
      $(".show-nim").hide();
      $(".show-penilaian").hide();
      $(".show-angkatan").hide();
      $("#value_nim").val('');
        $("#dtb_nilai_per_kelas_wrapper").show();
        dtb_nilai_per_kelas.ajax.reload();
        $("#dtb_nilai_permahasiswa_wrapper").hide();
    }

  });   

      $("#nim").change(function(){
    if (this.value=='nim') {
      $("#value_nim").show();
    } else {
      $("#value_nim").hide();
      $("#value_nim").val('');
    }
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

  dtb_nilai_per_kelas = $("#dtb_nilai_per_kelas").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '14%',
            'targets': 8,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/nilai/nilai_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter").val();
                    d.sem_filter = $("#sem_filter").val();
                    d.matkul_filter = $("#matkul_filter").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

var dtb_nilai_permahasiswa = $("#dtb_nilai_permahasiswa").DataTable({
           'bProcessing': true,
           'destroy' : true,
            'bServerSide': true,
            lengthMenu: [10, 20, 50, 100, 200, 500],
            "order": [],
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              {
             "targets": [0],
              "orderable": false,
              "searchable": false
            }
      
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/nilai/data_permahasiswa.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.matakuliah = $("#matkul_filter").val();
                    d.nim = $("#nim").val();
                    d.value_nim = $("#value_nim").val();
                    d.mulai_smt = $("#mulai_smt").val();
                    d.mulai_smt_end = $("#mulai_smt_end").val();
                    d.status_penilaian = $("#status_penilaian").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);


            }
          }

        });
$("#dtb_nilai_permahasiswa_wrapper").hide();
//filter
$('#filter').on('click', function() {
  lihat = $("#lihat_filter").val();
  if (lihat=='kelas') {
    $("#dtb_nilai_per_kelas_wrapper").show();
    dtb_nilai_per_kelas.ajax.reload();
    $("#dtb_nilai_permahasiswa_wrapper").hide();
  } else {
    $("#dtb_nilai_permahasiswa").show();
    dtb_nilai_permahasiswa.ajax.reload();
    $("#dtb_nilai_per_kelas_wrapper").hide();
    $("#dtb_nilai_permahasiswa_wrapper").show();
  }
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
          required: true,
          //minlength: 2
          },
        
          sem_filter: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          jur_filter: {
          required: "Untuk Cetak Data Silakan Pilih Prodi",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
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
                            $("#line_"+id).fadeOut("slow");
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

        $("#import_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/nilai/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });

</script>
            