<style type="text/css">
  .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Rekap Dosen Ajar
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rekap-dosen-ajar">Rekap Dosen Ajar</a></li>
                        <li class="active">Rekap Dosen Ajar</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/rekap_dosen_ajar/download_data.php" target="_blank">

                        <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_kelas');
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
                        <div class="col-lg-5">
                        <select id="hari_filter" name="hari_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
                         <?php
                         echo "<option value='all'>Semua</option>";
                         foreach ($db->query("select * from hari_ref") as $dt) {

                          $hari = strtolower($dt->hari);
                           
                           echo "<option value='$hari'>".ucwords($hari)."</option>";
                         }
                         ?>
                        </select>
                      </div>
                      </div><!-- /.form-group -->

                        <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Keterangan</label>
                        <div class="col-lg-5">
                        <select id="ket_filter" name="keterangan" data-placeholder="Pilih Keterangan ..." class="form-control chzn-select" tabindex="2">
                            <option value='all'>Semua</option>
                            <option value='tunggal'>Dosen Tunggal</option>
                            <option value='tim'>Dosen Tim</option>
                        </select>
                      </div>
                      </div><!-- /.form-group -->


                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                           <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                         
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
                                    <div class="box-body table-responsive">
                        <table id="dtb_kelas_jadwal" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th class='center' valign="center" rowspan='2'>NIDN</th>
                <th class='center' valign="center" rowspan='2'>Dosen</th>
                <th class='center' valign="center" rowspan='2'>Kelas</th>
                <th class='center' valign="center" rowspan='2'>Semester</th>
                <th class='center' valign="center" rowspan='2'>Mata Kuliah</th>
                <th class='center' valign="center" rowspan='2'>SKS</th>
                <th class='center' valign="center" rowspan='2'>Ruang</th>
                <th class='center' valign="center" rowspan='2'>Jurusan</th>
                <th class='center' valign="center" rowspan='2'>Keterangan</th>
                <th class='center' valign="center" rowspan='2'>Dosen Ke</th>
                <th class='center' valign="center" colspan='7'>Hari</th>

              </tr>
              <tr>
                <th>Senin</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
                <th>Sabtu</th>
                <th>Minggu</th>
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

    $("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/kelas_jadwal/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jur_filter").html(data);
        $("#jur_filter").trigger("chosen:updated");

        }
    });
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
            url : "<?=base_admin();?>modul/rekap_dosen_ajar/modal_setting_jadwal.php",
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
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [10,11,12,13,14,15,16],
              'orderable': false,
              'searchable': false
            }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/rekap_dosen_ajar/rekap_dosen_ajar_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').on('click', function() {
  dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [10,11,12,13,14,15,16],
              'orderable': false,
              'searchable': false
            }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/rekap_dosen_ajar/rekap_dosen_ajar_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                d.fakultas = $("#fakultas_filter").val();
                d.jur_filter = $("#jur_filter").val();
                d.sem_filter = $("#sem_filter").val();
                d.keterangan = $("#ket_filter").val();
                d.matkul_filter = $("#matkul_filter").val();
                d.hari = $("#hari_filter").val();
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
            