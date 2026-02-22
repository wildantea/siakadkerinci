<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Nilai Per Kelas
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>nilai-per-kelas">Nilai Per Kelas</a></li>
                        <li class="active">Nilai Per Kelas List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                if ($db2->userCan("import")) {
                                      ?>
                                      <a class="btn btn-primary import-nilai"><i class="fa fa-cloud-upload"></i> Import Nilai</a>
                                      <?php
                                  }
                                  ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box box-primary">
   <div class="box-header with-border">
              <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
            </div>
            <div class="box-body filter-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/nilai_per_kelas/download_data.php" target="_blank">
            <?php
            if (hasFakultas()) {
              ?>
              <div class="form-group">
                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                <div class="col-lg-5">
                  <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                  <?php
                  loopingFakultas('filter_nilai');
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
                                $session_filter_fakultas = getFilter(array('filter_nilai' => 'fakultas'));
                                loopingProdi('filter_nilai',$session_filter_fakultas);
                                ?>
                      </select>
                    </div>

                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="periode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <?php 
                          loopingSemester('filter_nilai');
                          ?>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Matakuliah</label>
                        <div class="col-lg-5">
                        <select id="matkul_filter" name="matakuliah" data-placeholder="Semua Semester ..." class="form-control chzn-select" tabindex="2">
                         <option value="all">Semua</option>
                          <?php
                              $sem_id = getFilter(array('filter_nilai' => 'semester'));
                              $prodi = getFilter(array('filter_nilai' => 'prodi'));
                              if ($sem_id=="") {
                                $sem_id = getSemesterAktif();
                              }
                              if ($prodi!="") {
                                $filter_session_matkul= getFilter(array('filter_nilai' => 'matakuliah'));
                                $data = $db2->query("SELECT tb_data_matakuliah.id_matkul,kode_mk,semester,nama_mk FROM tb_data_matakuliah
                                                    INNER JOIN tb_data_kelas USING(id_matkul)
                                                    where tb_data_kelas.sem_id=?
                                                    GROUP BY tb_data_kelas.id_matkul
                                                    ORDER by nama_mk asc,semester asc",array('sem_id' => $sem_id));
                                foreach ($data as $dt) {
                                  if ($dt->id_matkul==$filter_session_matkul) {
                                    echo "<option value='$dt->id_matkul' selected>$dt->kode_mk - $dt->nama_mk SMT $dt->semester</option>";

                                  } else {
                                    echo "<option value='$dt->id_matkul'>$dt->kode_mk - $dt->nama_mk SMT $dt->semester</option>";
                                  }
                                  
                                }
                              }
                          ?>
                        </select>
                      </div>
                    </div>

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Lihat Berdasarkan</label>
                        <div class="col-lg-5">
                        <select id="lihat_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="kelas">Lihat Per Kelas</option>
                          <option value="mahasiswa">Lihat Per Nilai Mahasiswa</option>
                            </select>
                          </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Status Penilaian</label>
                        <div class="col-lg-5">
                        <select id="status_penilaian" name="status_penilaian" data-placeholder="Pilih Status Nilai ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <option value="sudah">Sudah</option>
                          <option value="belum">Belum</option>
                          <option value="diumumkan">Diumumkan</option>
                          <option value="dikunci">Dikunci</option>
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


                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                           <?php
                            resetFilterButton('filter_nilai');
                            ?>
                          <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button>
                        </div>
                      </div>
                  
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">
                                   <button id="bulk_delete" style="display: none;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>

                        <table id="dtb_nilai_permahasiswa" class="table table-bordered table-striped display nowrap" width="100%" style="display: none">
                            <thead>
                                <tr>
                                   <th rowspan="2" style='padding-right:7px;width: 4%' class='dt-center'><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '> <input type='checkbox' class='group-checkable bulk-check'> <span></span></label></th>
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Periode</th>
                                  <th colspan="3" class="dt-center">Nilai</th>
                                  <th rowspan="2">Program Studi</th>
                                  <th rowspan="2">Action</th>
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
                                  <th rowspan="2">Matakuliah</th>
                                  <th rowspan="2">Kelas</th>
                                  <th rowspan="2">Dosen</th>
                                  <th rowspan="2">Peserta</th>
                                  <th colspan="5" class="dt-center">Status Nilai</th>
                                  <th rowspan="2">Program Studi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Sudah</th>
                                  <th>Belum</th>
                                  <th>Diumumkan</th>
                                  <th>Dikunci</th>
                                  <th>Komponen</th>
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
  $hapus="";
   $ubah_status = '';
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."nilai-per-kelas/add/'+data+' class=\"btn btn-primary btn-sm \" data-toggle=\"tooltip\" title=\"Input Nilai\"><i class=\"fa fa-pencil\"></i> Input</a>";
 }



 $ubah_status = '<div class="btn-group" data-toggle="tooltip" data-title="Ubah Status Nilai"><button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Status <i class="fa fa-angle-down"></i></button><ul class="dropdown-menu aksi-table" role="menu"><li><a data-id="+data+" data-status="+status+" data-toggle="tooltip" title="+status_label+ Nilai" class="umumkan"><i class="fa fa-bullhorn"></i>+status_label+</a></li><li role="separator" class="divider"></li><li><a data-id="+data+" data-toggle="tooltip" title="+status_kunci+" class="kunci"><i class="fa fa-lock"></i>+status_kunci</a></li></ul></div>';

 if ($db2->userCan("delete")) {
     $hapus = "<button data-id='+data+' data-uri=".base_admin()."modul/nilai_per_kelas/nilai_per_kelas_action.php".' class="btn btn-danger delete-nilai btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_nilai_permahasiswa"><i class="fa fa-trash"></i></button>';
 }
        ?>

    </section><!-- /.content -->

<div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" style="font-size:30px">&nbsp;</span></button> <h4 class="modal-title title-import">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
        <script type="text/javascript">
   $("#nim").change(function(){
    if (this.value=='nim') {
      $("#value_nim").show();
    } else {
      $("#value_nim").hide();
      $("#value_nim").val('');
    }

  });   

   $("#lihat_filter").change(function(){
    if (this.value=='mahasiswa') {
      $(".show-nim").show();
    } else {
      $(".show-nim").hide();
      $("#value_nim").val('');
    }

  });   

  $(".import-nilai").click(function() {
          $('.title-import').html('Import Nilai (Hanya untuk Nilai Lampau)');
          $.ajax({
            url : "<?=base_admin();?>modul/nilai_per_kelas/import/import_nilai.php",
            type : "GET",
            success: function(data) {
              $("#isi_import_data").html(data);
            }
          });

          $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

        });
      var dtb_nilai_per_kelas = $("#dtb_nilai_per_kelas").DataTable({

           'bProcessing': true,
            'bServerSide': true,
            "order": [],
      
            'ajax':{
              url :'<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.matakuliah = $("#matkul_filter").val();
                    d.status_penilaian = $("#status_penilaian").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

    //modal jadwal
$(".table").on('click','.umumkan',function(event) {
    $("#loadnya").show();
     var currentBtn = $(this);
      id = currentBtn.attr('data-id');
      status = currentBtn.attr('data-status');
      var notif = 'Apakah Yakin akan meng-umumkan nilai kelas ini ?';
    $.confirm({
        title: 'Konfirmasi',
        content: notif,
         theme: 'modern',
        buttons: {
            confirm: function () {
            $.ajax({
              url: "<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=publish",
              dataType: "json",
              type : "post",
              data : {id:id,status:status},
              error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
              },
              success: function(responseText) {
                $("#loadnya").hide();
                console.log(responseText);
                $.each(responseText, function(index) {
                  console.log(responseText[index].status);
                  if (responseText[index].status=="die") {
                    $("#informasi").modal("show");
                  } else if(responseText[index].status=="good") {
                    dtb_nilai_per_kelas.draw(false);
                  }
                });
              }

              });
            },
            cancel: function () {
              $("#loadnya").hide();
            }
        }
    });

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
$(".table").on('click','.kunci',function(event) {
    $("#loadnya").show();
     var currentBtn = $(this);
      id = currentBtn.attr('data-id');
      status = currentBtn.attr('data-status');
      if (status=='1') {
        var notif = 'Apakah Yakin akan mengunci nilai kelas ini ?';
      } else {
        var notif = 'Apakah Yakin akan membuka kunci nilai kelas ini ?';
      }
      
    $.confirm({
        title: 'Konfirmasi',
        content: notif,
         theme: 'modern',
        buttons: {
            confirm: function () {
            $.ajax({
              url: "<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=kunci",
              dataType: "json",
              type : "post",
              data : {id:id,status:status},
              error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
              },
              success: function(responseText) {
                $("#loadnya").hide();
                console.log(responseText);
                $.each(responseText, function(index) {
                  console.log(responseText[index].status);
                  if (responseText[index].status=="die") {
                    $("#informasi").modal("show");
                  } else if(responseText[index].status=="good") {
                    dtb_nilai_per_kelas.draw(false);
                  }
                });
              }

              });
            },
            cancel: function () {
              $("#loadnya").hide();
            }
        }
    });

    });

$("#dtb_nilai_per_kelas_filter").on("click",".reset-button-datatable",function(){
    dtb_nilai_per_kelas
    .search( "" )
    .draw();
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
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "width" : "4%",
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$hapus;?>';
               }
            },
              {
             "targets": [0],
              "orderable": false,
              "searchable": false
            }
      
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/nilai_per_kelas/hapus_nilai/data_permahasiswa.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.matakuliah = $("#matkul_filter").val();
                    d.nim = $("#nim").val();
                    d.value_nim = $("#value_nim").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);


            }
          },
              "drawCallback": function( settings ) {
                var api = this.api();
              $("#dtb_nilai_permahasiswa_filter").on("click",".reset-button-datatable",function(){
                   api
                  .search( "" )
                  .draw();
              });
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


  $("#jur_filter").change(function(){
                        $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/nilai_per_kelas/get_matkul.php",
                        data : {program_studi:this.value,periode:$("#sem_filter").val()},
                        success : function(data) {
                            $("#matkul_filter").html(data);
                            $("#matkul_filter").trigger("chosen:updated");
                        }
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

   $(".table").on('click','.delete-nilai',function(event) {
  
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    dtb_var = currentBtn.attr('data-variable');


    $('#modal-confirm-delete')
        .modal({ keyboard: false })
        .one('click', '#delete', function (e) {

        $.ajax({
          type: "POST",
          dataType: 'json',
          error: function(data ) { 
              $('#loadnya').hide();
              console.log(data); 
              $('.selected-data').html('');
              $('#bulk_delete').hide();
             $('.isi_warning_delete').html(data.responseText);
             $('.error_data_delete').fadeIn();
             $('html, body').animate({
                scrollTop: ($('.error_data_delete').first().offset().top)
            },500);
          },
          url: uri+"?act=delete",
          data : {id:id},
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
                            $('.selected-data').html('');
                            $('#bulk_delete').hide();
                            $('.error_data_delete').hide();
                            $("#line_"+id).fadeOut("slow");
                             dtb_nilai_permahasiswa.draw();
                          }
                    });
                }
          });
          $('#modal-confirm-delete').modal('hide');

        });
  });

$('.bulk-check').on('click',function() { // bulk checked
      var status = this.checked;
      if (status) {
        select_deselect('select');
      } else {
        select_deselect('unselect');
      }
      $('.check-selected').each( function() {
        $(this).prop('checked',status);
      });
      check_selected();
});



  $(document).on('click', '#dtb_nilai_permahasiswa tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          check_selected();
      }
  });

  function check_selected() {
      var table_select = $('#dtb_nilai_permahasiswa tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.delete-nilai').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      if (array_data_delete.length>0) {
        $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
        $('#bulk_delete').show();
      } else {
        $('.selected-data').text('');
        $('.bulk-check').prop('checked',false);
        $('#bulk_delete').hide();
      }
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_nilai_permahasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_nilai_permahasiswa tbody tr').removeClass('DTTT_selected selected')
      }
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_nilai_permahasiswa );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#modal-confirm-delete').modal({ keyboard: false }).one('click', '#delete', function (e) {
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
            url: '<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=del_massal',
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
                               dtb_nilai_permahasiswa.draw();
                          }
                    });
                }
            //async:false
        });

        $('#modal-confirm-delete').modal('hide');

    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }

</script>
            