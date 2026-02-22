<style type="text/css">
.modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
}
.peserta-kelas {
  cursor: pointer;
}

table.table-bordered.dataTable {
    border-width: 2px 1px 1px;
    border-style: solid;
    border-color: rgb(244, 67, 54) rgba(34, 36, 38, 0.15) rgb(17, 17, 17);
}
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Rencana Studi
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>rencana-studi">Rencana Studi</a></li>
                        <li class="active">Rencana Studi</li>
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
                                          if ($role_act["import_act"]=="Y") {
                                      ?>
                                      <button class="btn btn-primary" onclick="show_modal_import('krs')"><i class="fa fa-cloud-upload"></i> Import KRS</button>
                                      <?php
                                          }
                                     /* if ($role_act["insert_act"]=="Y") {
                                      ?>
                      <a class="btn btn-primary add_krs"><i class="fa fa-plus"></i> Tambah IRS</a>  
                                      <?php
                                          }*/
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box box-primary">
   <div class="box-header with-border">
              <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
            </div>
            <div class="box-body filter-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/rencana_studi/download_cetak/download_cetak.php" target="_blank">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="periode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <?php 
                          loopingSemester('filter_krs');
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jur_filter" name="jurusan" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                 $jurusan = $db2->query("select *,jurusan as nama_jurusan from view_prodi_jenjang where kode_jur in(select jur_kode from mahasiswa where dosen_pemb='".$_SESSION['username']."')");
                                  echo "<option value='all'>Semua</option>";
                                  foreach ($jurusan as $dt) {
                                      echo "<option value='$dt->kode_jur'>".string_rapih($dt->nama_jurusan)."</option>";
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
                          $filter_session_mulai_smt = getFilter(array('filter_akm' => 'mulai_smt'));
                          $angkatan = $db->query("select left(mulai_smt,4) as mulai_smt from mahasiswa where mulai_smt > 1 and mahasiswa.nim not in(select nim from tb_data_kelulusan) group by left(mulai_smt,4) order by mulai_smt desc");
                          echo "<option value='all'>Semua</option>";
                           foreach ($angkatan as $ak) {
                             if ($filter_session_mulai_smt==$ak->mulai_smt) {
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
                      <option value="all">Semua</option>
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
                      <label for="Semester" class="control-label col-lg-2">Status Ambil KRS</label>
                        <div class="col-lg-3">
                        <select id="status_krs" name="status_krs" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
                          <?php
                          $filter_session_status_sudah_krs = getFilter(array('filter_krs' => 'status_krs'));

                          $status_sudah_krs = array('1' => 'Mahasiswa Sudah KRS','0' => 'Mahasiswa Belum KRS');

                          foreach ($status_sudah_krs as $key_status_krs => $val_status_krs) {
                            if ((string)$filter_session_status_sudah_krs==(string)$key_status_krs) {
                              echo "<option value='$key_status_krs' selected>$val_status_krs</option>";
                            } else {
                              echo "<option value='$key_status_krs'>$val_status_krs</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Status Persetujuan KRS</label>
                        <div class="col-lg-3">
                        <select id="disetujui" name="disetujui" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <?php
                          $filter_session_status_disetujui = getFilter(array('filter_krs' => 'disetujui'));

                          $status_disetujui = array('0' => 'Belum Disetujui','1' => 'Sudah Disetujui');

                          foreach ($status_disetujui as $status_setuju => $disetujui) {
                            if ((string)$filter_session_status_disetujui==(string)$status_setuju) {
                              echo "<option value='$status_setuju' selected>$disetujui</option>";
                            } else {
                              echo "<option value='$status_setuju'>$disetujui</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>


                      <!-- /.form-group -->
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
                                            <button type="button" class="btn btn-info btn-flat selected-data">Terpilih</button>
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
                        <table id="dtb_krs" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                               <th rowspan="2" style="padding-right:0;width: 4%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Angkatan</th>
                                  <th colspan="4" style="text-align: center;">Status KRS</th>
                                  <th rowspan="2">Prodi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Bayar</th>
                                  <th>Disetujui</th>
                                  <th>Jatah SKS</th>
                                  <th>SKS Diambil</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
<!-- modal jadwal -->
<div class="modal fade" id="modal_kelas_jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pengaturan Dosen Pengajar & Jadwal Perkuliahan</h4> </div> <div class="modal-body" id="isi_kelas_jadwal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<!-- modal peserta -->
<div class="modal fade" id="modal_peserta_kelas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Peserta Kelas</h4> </div> <div class="modal-body" id="isi_peserta_kelas"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

<div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="font-size:30px">&nbsp;</span></button> <h4 class="modal-title title-import">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

<!-- modal cetak single -->
  <div class="modal" id="modal_cetak_single" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Silakan Pilih Jenis Yang Akan Dicetak</h4> </div> <div class="modal-body" id="isi_modal_cetak"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?>/ Lihat KRS Mahasiswa</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
 <?php
  $edit ="";
  $del="";
 if ($db->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."kelas-jadwal/edit/'+data+' data-toggle=\"tooltip\" title=\"Edit Kelas\"><i class=\"fa fa-pencil\"></i> Edit Kelas</a>";
    $jadwal = "<a data-id='+data+' data-toggle=\"tooltip\" title=\"Pengaturan Jadwal\" class=\"edit-jadwal\"><i class=\"fa fa-calendar\"></i> Pengaturan Jadwal</a>";
    $print = "<a data-id='+data+' data-toggle=\"tooltip\" title=\"Cetak\" class=\"cetak\"><i class=\"fa fa-print\"></i> Cetak Data</a>";
 }
  if ($db->userCan("delete")) {
    $del = "<a data-id='+data+'  data-uri=".base_admin()."modul/kelas_jadwal/kelas_jadwal_action.php".' data-variable="dtb_kelas_jadwal" class="hapus_dtb_notif" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i> Hapus</a>';
 }
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">

     function show_modal_import(ket) {
             $.ajax({
              type: 'POST',
              url: '<?=base_admin();?>modul/rencana_studi/import_krs.php',
              data: {aksi:ket},
              success: function(result) {
               $("#isi_import_data").html(result);
               $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });
             },
            //async:false
          });
     }

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


$(document).ready(function(){

  $(".import-krs").click(function() {
          $('.title-import').html('Import KRS');
          $.ajax({
            url : "<?=base_admin();?>modul/rencana_studi/import/import_krs.php",
            type : "GET",
            success: function(data) {
              $("#isi_import_data").html(data);
            }
          });

          $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

        });

  $('.show-filter').click(function(){
      if ($(".filter-body").is(':visible')) {
        $(this).find('.fa').toggleClass('fa-minus fa-plus');
        $(".filter-body").slideUp();
      } else {
        $(this).find('.fa').toggleClass('fa-plus fa-minus');
        $(".filter-body").slideDown();
      }
  });
});
      
      var dtb_krs = $("#dtb_krs").DataTable({
          <?php
          if (getFilter(array('filter_krs' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_krs' => 'input_search'));?>"
                },
            <?php
          }
          ?>              
          "lengthMenu": [[10,50,100, 200, 300, -1], [10,50,100, 200, 300, "All"]],
           'bProcessing': true,
            'bServerSide': true,
             "order": [],
          'columnDefs': [ {
            'targets': [0,-1,4],
              'orderable': false,
              'searchable': false
            },

             ],
            'ajax':{
              url :'<?=base_admin();?>modul/rencana_studi/krs_dosen/rencana_studi_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.mulai_smt = $("#mulai_smt").val();
                    d.mulai_smt_end = $("#mulai_smt_end").val();
                    d.disetujui = $("#disetujui").val();
                    d.status_krs = $("#status_krs").val();
                    //d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_krs.ajax.reload();
});

$("#dtb_krs_filter").on('click','.reset-button-datatable',function(){
    dtb_krs
    .search( '' )
    .draw();
    $('.bulk-check').prop("checked",0);
    $('#aksi_top_krs').hide();
  });

  $("#jur_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/kelas_jadwal/get_kurikulum_filter.php",
                        data : {program_studi:this.value,periode:$("#sem_filter").val()},
                        success : function(data) {
                            $("#kurikulum_filter").html(data);
                            $("#kurikulum_filter").trigger("chosen:updated");
                            $("#matkul_filter").html('<option value="all">Semua</option>');
                            $("#matkul_filter").trigger("chosen:updated");
                        }
      });
    });
  $("#sem_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/kelas_jadwal/get_kurikulum_filter.php",
                        data : {periode:this.value,program_studi:$("#jur_filter").val()},
                        success : function(data) {
                            $("#kurikulum_filter").html(data);
                            $("#kurikulum_filter").trigger("chosen:updated");
                            $("#matkul_filter").html('<option value="all">Semua</option>');
                            $("#matkul_filter").trigger("chosen:updated");
                        }
      });

    });
$("#kurikulum_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/kelas_jadwal/get_matkul_filter.php",
                        data : {kur_id:this.value,periode:$("#sem_filter").val()},
                        success : function(data) {
                            $("#matkul_filter").html(data);
                            $("#matkul_filter").trigger("chosen:updated");
                        }
      });

});
//modal jadwal
$(".table").on('click','.edit-jadwal',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/jadwal/modal_jadwal.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_kelas_jadwal").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_kelas_jadwal').modal({ keyboard: false,backdrop:'static' });

    });
//modal peserta kelas
$(".table").on('click','.peserta-kelas',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/peserta_kelas/peserta_kelas_modal_view.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_peserta_kelas").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_peserta_kelas').modal({ keyboard: false,backdrop:'static' });

    });
//modal cetak single
    $(".table").on('click','.cetak',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/cetak/cetak_modal_single.php",
            type : "post",
            data : {kelas_id:id},
            success: function(data) {
                $("#isi_modal_cetak").html(data);
                $("#loadnya").hide();
          }
        });
    $('#modal_cetak_single').modal({ keyboard: false,backdrop:'static' });
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

          sem_filter: {
          required: true,
          //minlength: 2
          },

        },
         messages: {

          jur_filter: {
          required: "Untuk Cetak Data Silakan Pilih Program Studi",
          //minlength: "Your username must consist of at least 2 characters"
          },

        }
    });
});

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
      var table_select = $('#dtb_krs tbody tr.selected');
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
          $('#dtb_krs tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_krs tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_krs tbody tr .check-selected', function(event) {
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
                  dtb_krs.draw(false);
            },
            //async:false
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
});
</script>
            