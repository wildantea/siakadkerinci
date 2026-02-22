<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pendaftaran
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran">Pendaftaran</a></li>
                        <li class="active">Pendaftaran List</li>
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
                                      <a id="add_pendaftaran" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
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
                              <div class="box box-primary">
                            <div class="box-header with-border">
<?php
if (isset($_SESSION['filter_pendaftaran']['show_filter'])) {
  if (getFilter(array('filter_pendaftaran' => 'show_filter'))=='Y') {
    $fa_filter = "fa-minus";
    $display_filter = 'style="display:block"';
  } else {
    $fa_filter = "fa-plus";
    $display_filter = 'style="display:none"';
  }
} else {
    $fa_filter = "fa-minus";
    $display_filter = 'style="display:block"';
}
?>
          <h3 data-toggle="tooltip" data-title="show/hide filter" class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa <?=$fa_filter;?>'></i> Filter</h3>
        </div>
        <div class="box-body filter-body" <?=$display_filter?>>
       <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/pendaftaran/download_data.php" target="_blank">

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Akademik</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <?php 
                          $filter_session_sem_filter = getFilter(array('filter_pendaftaran' => 'sem_filter'));

                          $jur_kode = aksesProdi('tb_data_pendaftaran.kode_jurusan');

                          $get_exist_periode = $db2->query("select id_semester,tahun_akademik from view_semester where id_semester in (select id_semester from tb_data_pendaftaran where 1=1 $jur_kode)
                            order by id_semester desc");
                          $index = 0;
                          foreach ($get_exist_periode as $periode) {
                            if ($filter_session_sem_filter==$periode->id_semester) {
                              echo "<option value='$periode->id_semester' selected>$periode->tahun_akademik</option>";
                            } else {
                              if( $index==0 ) { 
                                echo "<option value='$periode->id_semester' selected>$periode->tahun_akademik</option>";
                              } else {
                                echo "<option value='$periode->id_semester'>$periode->tahun_akademik</option>";
                              }
                            }
                            $index++;
                          }
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_pendaftaran');
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
                                <select id="jur_filter" name="jurusan" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingProdi('filter_pendaftaran',getFilter(array('filter_pendaftaran' => 'fakultas')));
                                ?>
                      </select>
                    </div>
                              </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Bulan Daftar</label>
                        <div class="col-lg-5">
                        <select id="periode" name="periode" data-placeholder="Pilih Periode ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <?php
                            $filter_session_periode = getFilter(array('filter_pendaftaran' => 'periode'));
                            foreach ($db2->query("SELECT EXTRACT( YEAR_MONTH FROM `date_created` ) as periode,month(date_created) as bulan,year(date_created) as tahun from tb_data_pendaftaran where 1=1 $jur_kode group by EXTRACT( YEAR_MONTH FROM `date_created` ) order by EXTRACT( YEAR_MONTH FROM `date_created` ) desc") as $periode) {

                              if ($filter_session_periode==$periode->periode) {
                                echo "<option value='$periode->periode' selected>".getBulan($periode->bulan)." ".date($periode->tahun)."</option>";
                              } else {
                                if (date('Ym')==$periode->periode) {
                                  echo "<option value='$periode->periode' selected>".getBulan($periode->bulan)." ".date($periode->tahun)."</option>";
                                } else {
                                  echo "<option value='$periode->periode'>".getBulan($periode->bulan)." ".date($periode->tahun)."</option>";
                                }
                                
                              }
                            }
                            ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Jenis Pendaftaran</label>
                        <div class="col-lg-5">
                        <select id="jenis_pendaftaran" name="jenis_pendaftaran" data-placeholder="Pilih Pendaftaran ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                            <?php
                            $filter_session_jenis = getFilter(array('filter_pendaftaran' => 'jenis_pendaftaran'));
                            foreach ($db2->query("SELECT tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_jenis_pendaftaran from tb_data_pendaftaran_jenis where id_jenis_pendaftaran in(
                              select id_jenis_pendaftaran from tb_data_pendaftaran_jenis_pengaturan 
                              INNER join tb_data_pendaftaran using(id_jenis_pendaftaran_setting)
                              where 1=1 $jur_kode)") as $jenis_pendaftaran) {
                              if ($filter_session_jenis==$jenis_pendaftaran->id_jenis_pendaftaran) {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran' selected>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              } else {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran'>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              }
                            }
                            ?>
                        </select>
                      </div>
                    </div>
                      <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Status</label>
                        <div class="col-lg-5">
                        <select id="status" name="status" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                            <?php
                            $filter_session_status = getFilter(array('filter_pendaftaran' => 'status'));
                            $status_query = $db2->query("select distinct(status) from tb_data_pendaftaran where status in(0,1,2,3,4,5) $jur_kode");
                            $array_status = array();
                            foreach ($status_query as $qr_status) {
                              $array_status[] = $qr_status->status;
                            }

                            $status_array = array('1' => 'Sudah Acc','0' => 'Belum Acc','2' => 'Ditolak', '3' => 'Tidak Lulus','4' => 'Selesai/Lulus','5' => 'Diajukan Ulang');
                              foreach ($status_array as $key => $status) {
                                $key = (string)$key;
                                if (in_array($key, $array_status)) {
                                      if($filter_session_status==$key){
                                       echo "<option value='$key' selected>$status</option>";
                                     } else {
                                       echo "<option value='$key'>$status</option>";
                                     }
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
                           <?php
                            resetFilterButton('filter_pendaftaran');
                            ?>
                        <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
          </div>

 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
                <div class="btn-group" id="aksi-jadwal" style="display: none;margin-bottom: 10px">
                  <span class="selected-data" style="float: left;padding-top: 7px;padding-right: 5px;"></span>
                  <button type="button" class="btn btn-success">Pilih Aksi</button>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="aksi_jadwal" data-value="edit"><a href="#"><i class="fa fa-pencil"></i> Edit Masal Status</a></li>
                    <li class="aksi_jadwal" data-value="hapus"><a href="#"><i class="fa fa-trash"></i> Hapus Data</a></li>
                  </ul>
                </div>
                        <table id="dtb_pendaftaran" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:7px;' class='dt-center'><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline'> <input type='checkbox' class='group-checkable bulk-check'> <span></span></label></th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Jenis Pendaftaran</th>
                                  <th>Tanggal Daftar</th>
                                  <th>Status</th>
                                  <th>Periode</th>
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
 <?php
  $edit ="";
  $del="";
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+'  class=\"btn btn-xs btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/pendaftaran/pendaftaran_action.php".' class="btn btn-xs btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_pendaftaran"><i class="fa fa-trash"></i></button>';
    
 }
        ?>

    <div class="modal" id="modal_pendaftaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="glyphicon glyphicon-remove"></i></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran</h4> </div> <div class="modal-body" id="isi_pendaftaran"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
            <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->

        <script type="text/javascript">
      $(document).ready(function(){
$.fn.modal.Constructor.prototype.enforceFocus = function() {};

        $("#import_data").click(function() {
          $.ajax({
              url : "<?php echo base_admin();?>modul/pendaftaran/import_data.php",
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
              var show = 'N';
            } else {
              $(this).find('.fa').toggleClass('fa-plus fa-minus');
              $(".filter-body").slideDown();
              var show = 'Y';
            }
              $.ajax({
                  url : "<?=base_admin();?>modul/pendaftaran/filter_session.php",
                  type : "POST",
                  data : {filter_name:'filter_pendaftaran',show_filter : show},
                  success: function(data) {
                    console.log(data);
                  }
              });

        });
      });




      $("#add_pendaftaran").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/pendaftaran/pendaftaran_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_pendaftaran").html(data);
              }
          });

      $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
    $(".table").on('click','.status-daftar',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Detail Pendaftaran Mahasiswa");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/modal/modal_view.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.edit-nilai',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Detail Pendaftaran Mahasiswa");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/modal/modal_nilai.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Edit Pendaftaran");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/pendaftaran_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_pendaftaran = $("#dtb_pendaftaran").DataTable({
          <?php
          if (getFilter(array('filter_pendaftaran' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_pendaftaran' => 'input_search'));?>"
                },
            <?php
          }
          ?>    
         // "order" : [[1,"asc"]],   
           
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
            },
            {
            "targets": [-1],
              "orderable": false,
              "width" : "10%",
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$edit;?> <?=$del;?> <a class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Download Berkas" href="<?=base_admin();?>modul/pendaftaran/down_berkas.php?id='+data+'"><i class="fa fa-download"></i></a>';
               }
            },
      
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran/pendaftaran_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                    d.sem_filter = $("#sem_filter").val();
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#periode").val();
                    d.jenis_pendaftaran = $("#jenis_pendaftaran").val();
                    d.status = $("#status").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/pendaftaran/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jurusan").html(data);
        $("#jurusan").trigger("chosen:updated");

        }
    });
});

//filter
$('#filter').on('click', function() {
  dtb_pendaftaran.ajax.reload();
});

$("#dtb_pendaftaran_filter").on('click','.reset-button-datatable',function(){
    dtb_pendaftaran
    .search( '' )
    .draw();
  });

$(document).ready(function(){
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
       $.validator.addMethod("myFunc", function(val) {
        if(val=='all'){
          return false;
        } else {
          return true;
        }
      }, "Untuk Download Silakan Pilih Jenis Pendaftaran");
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

          jenis_pendaftaran: {
            myFunc:true
          //minlength: 2
          }

        },
         messages: {

          jenis_pendaftaran: {
          required: "Untuk Download Silakan Pilih Jenis Pendaftaran",
          //minlength: "Your username must consist of at least 2 characters"
          },

        }
    });
});
$('.bulk-check').on('click',function() { // bulk checked
      var status = this.checked;
      if (status) {
        select_deselect('select');
        dtb_pendaftaran.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
      } else {
        select_deselect('unselect');
        dtb_pendaftaran.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
      }
      $('.check-selected').each( function() {
        $(this).prop('checked',status);
      });
      check_selected();
});



  $(document).on('click', '#dtb_pendaftaran tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          check_selected();
      }
  });

  function check_selected() {
      var table_select = $('#dtb_pendaftaran tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      if (array_data_delete.length>0) {
        $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
        $('#aksi-jadwal').show();
      } else {
        $('.selected-data').text('');
        $('.bulk-check').prop('checked',false);
        $('#aksi-jadwal').hide();
      }
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_pendaftaran tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_pendaftaran tbody tr').removeClass('DTTT_selected selected')
      }
  }




/* Add a click handler for the delete row */
  $('.aksi_jadwal').click( function() {
    var nilai_aksi = $(this).data('value');
    var anSelected = fnGetSelected( dtb_pendaftaran );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();

    if (nilai_aksi=='hapus') {
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            error: function(data ) {
                $('#loadnya').hide();
                console.log(data);
               $('.isi_warning_delete').html(data.responseText);
               $('.error_data_delete').fadeIn();
               $('html, body').animate({
                  scrollTop: ($('.error_data_delete').first().offset().top)
              },500);
            },
            url: '<?=base_admin();?>modul/pendaftaran/pendaftaran_action.php?act=del_massal',
            data: {data_ids:all_ids},
               success: function(responseText) {
                  $('#loadnya').hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $('#informasi').modal('show');
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                               $('.selected-data').text('');
                               $('.bulk-check').prop('checked',false);
                               $('#bulk_delete').hide();
                               $('#loadnya').hide();
                               $(anSelected).remove();
                               dtb_pendaftaran.draw();
                          }
                    });
                }
            //async:false
        });

        $('#ucing').modal('hide');

    });

    } else if(nilai_aksi=='edit') {
        $("#loadnya").show();
        event.preventDefault();
        $(".modal-title").html("Edit status Massal");
        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/edit_status.php",
            type : "post",
            data : {data_ids:all_ids},
            success: function(data) {
                $("#isi_pendaftaran").html(data);
                $("#loadnya").hide();
          }
        });

        $('#modal_pendaftaran').modal({ keyboard: false,backdrop:'static' });
    }

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
</script>
            