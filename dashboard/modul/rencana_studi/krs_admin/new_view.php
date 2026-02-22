<style type="text/css">
  .modal { overflow: auto !important; }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       Rencana Studi
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rencana-studi">Rencana Studi</a></li>
                        <li class="active">Rencana Studi List</li>
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
                                       <a class="btn btn-primary add_krs"><i class="fa fa-plus"></i> Tambah KRS</a> 
                                      <button class="btn btn-primary" onclick="show_modal_import('krs')"><i class="fa fa-cloud-upload"></i> Import KRS</button>        
                    <br><br>
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
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                        <?php 
                        looping_semester();
                        ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                        <div class="col-lg-5">
                        <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2">
 <?php
                                loopingFakultas();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

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
                    <label for="Angkatan" class="control-label col-lg-2">Angkatan</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select left(id_semester,4) as angkatan from view_semester
                   group by left(id_semester,4) order by angkatan desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='".$ak->angkatan."'>$ak->angkatan</option>";
                   }
                    ?>
                    </select>
                    </div>
                    <label for="Angkatan" class="control-label col-lg-1" style="width: 3%;padding-left: 0;text-align: left;">S/d</label>
                    <div class="col-lg-2">
                    <select id="mulai_smt_end" name="mulai_smt_end" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" required="">
                      <option value="all">Semua</option>
                    <?php
                   $angkatan = $db->query("select left(id_semester,4) as angkatan from view_semester
                   group by left(id_semester,4) order by angkatan desc");
                   foreach ($angkatan as $ak) {
                     echo "<option value='".$ak->angkatan."'>$ak->angkatan</option>";
                   }
                    ?>
                    </select>
                    </div>  
              </div><!-- /.form-group -->

                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Status Bayar</label>
                        <div class="col-lg-3">
                        <select id="is_bayar" name="is_bayar" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <option value="1">Sudah Bayar</option>
                          <option value="2">Bebas Bayar</option>
                          <option value="0">Belum Bayar</option>
                        </select>
                      </div>
                    </div><!-- /.form-group -->
                    <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Status Disetujui</label>
                        <div class="col-lg-3">
                        <select id="disetujui" name="disetujui" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <option value="1">Sudah Disetujui</option>
                          <option value="0">Belum Disetujui</option>
                        </select>
                      </div>
                    </div>
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
     <div class="row" id="aksi_top_krs" style="display: none">
                                   
                                    <div class="col-sm-4" style="margin-bottom: 10px;">
                                      
                                      <div class="input-group input-group-sm">
                                          <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
                                          </span>
                                       <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
                                      <option value="1">Setujui KRS</option>
                                      <option value="0">Batalkan Persetujuan KRS</option>
                                    </select>
                                          <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
                                          </span>
                                    </div>
                                    </div>
                                  </div>
                                  
<!--                                         <div class="row" id="aksi_top_krs" style="display: none">
                                    <div class="col-sm-8 selected-data" style="text-align: right;padding-right: 10px;padding-top:5px;">2 Data Terpilih</div>
                                    <div class="col-sm-4" style="text-align: right;margin-bottom: 10px;padding-left: 0;">
                                      <div class="input-group input-group-sm">
                                       <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
                                      <option value="1">Setujui KRS</option>
                                      <option value="0">Batalkan Persetujuan KRS</option>
                                    </select>
                                          <span class="input-group-btn">
                                            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
                                          </span>
                                    </div>
                                    </div>
                                  </div>
                            </div> -->
                        <table id="dtb_kelas_jadwal" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                               <!--    <th rowspan="2"><div class="checkbox checkbox-primary"><input class="styled styled-primary bulk-check" type="checkbox" id="bulk_check"> <label for="checkbox2">#</label></div></th>  -->
                               <th rowspan="2" style="padding-right:0;width: 7%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Dosen Pembimbing</th>
                                  <th rowspan="2">Angkatan</th>
                                  <th colspan="4" style="text-align: center;">Status KRS</th>
                                  <th rowspan="2">Prodi</th>
                                  <th rowspan="2">Ket</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Bayar</th>
                                  <th>Disetujui</th>
                                  <th>Jatah</th>
                                  <th>Diambil</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
<div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title title-import">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?>/ Lihat KRS Mahasiswa</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->
    <script type="text/javascript">


      $(".add_krs").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/rencana_studi/add_krs_mhs.php",
              type : "GET",
              success: function(data) {
                  $("#isi_setting_tagihan_mahasiswa").html(data);
              }
          });

      $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static',show:true });

    });
     function show_modal_import(ket) {
             $.ajax({
              type: 'POST',
              url: '<?=base_admin();?>modul/rencana_studi/krs_admin/import_krs.php',
              data: {aksi:ket},
              success: function(result) {
               $("#isi_import_data").html(result);
               $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });
             },
            //async:false
          });
     }

$(".bulk-check").on('click',function() { // bulk checked
          var status = this.checked;
          if (status) {
            select_deselect('select');
          } else {
            select_deselect('unselect');
          }
          
          $(".check-selected").each( function() {
            $(this).prop("checked",status);
          });
        });

  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#aksi_top_krs');
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
          var check_data = $(this).find('.data_selected_id').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' Data Terpilih');
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
  $(document).on('click', '#dtb_kelas_jadwal tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          console.log(selected);
          init_selected();

      }
  });

/* Add a click handler for the delete row */
  $('.submit-proses').click( function() {
    $("#loadnya").show();
    //var anSelected = fnGetSelected( dataTable_jadwal );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    console.log(all_ids);
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=proses_krs',
            data: {aksi:$("#aksi_krs").val(), data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
                  $(".bulk-check").prop("checked",0);
                  select_deselect('unselect');
                  dataTable_jadwal.draw(false);
            },
            //async:false
        });

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


    
      var dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
           "lengthMenu": [[10,100, 200, 300, -1], [10,100, 200, 300, "All"]],
            //"order": [[ 2, "desc" ]],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0,9],
             //'width': '5%',
              'orderable': false,
              'searchable': false
            },
             ],
            'ajax':{
              url :'<?=base_admin();?>modul/rencana_studi/krs_admin/new_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$('#filter').on('click', function() {
  dataTable_jadwal = $("#dtb_kelas_jadwal").DataTable({
            "lengthMenu": [[10,100, 200, 300, -1], [10,100, 200, 300, "All"]],
            //"order": [[ 1, "desc" ]],
           destroy : true,
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [0,9],
              'orderable': false,
              'searchable': false
            },

             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/rencana_studi/krs_admin/new_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jur_filter = $("#jur_filter").val();
                    d.sem_filter = $("#sem_filter").val();
                    d.mulai_smt = $("#mulai_smt").val();
                    d.mulai_smt_end = $("#mulai_smt_end").val();
                    d.fakultas = $("#fakultas_filter").val();
                    d.is_bayar = $("#is_bayar").val();
                    d.disetujui = $("#disetujui").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});

/*$(document).ready(function(){
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
          required: "Untuk Cetak/Generate/Reset Data Silakan Pilih Prodi",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          sem_filter: {
          required: "Untuk Cetak Data Silakan Pilih Semester",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        }
    });
});*/

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
    //periode load
/*    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/get_periode_tagihan.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#berlaku_angkatan").html(data);
        $("#berlaku_angkatan").trigger("chosen:updated");

        }
    });*/
});
</script>
            