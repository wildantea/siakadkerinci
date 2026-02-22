<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Jadwal Menguji
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jadwal-menguji">Jadwal Menguji</a></li>
                        <li class="active">Jadwal Menguji List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body">
<?php
if (isset($_SESSION['filter_pendaftaran_jadwal']['show_filter'])) {
  if (getFilter(array('filter_pendaftaran_jadwal' => 'show_filter'))=='Y') {
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
  <div class="box box-primary">
                            <div class="box-header with-border">
         <h3 data-toggle="tooltip" data-title="show/hide filter" class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa <?=$fa_filter;?>'></i> Filter</h3>
        </div>
      <div class="box-body filter-body" <?=$display_filter;?>>
       <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/pendaftaran/download_data.php" target="_blank">

                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Akademik</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="sem_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <option value="all">Semua</option>
                          <?php 
                          $filter_session_sem_filter = getFilter(array('filter_pendaftaran_jadwal' => 'sem_filter'));

                          $jur_kode = aksesProdi('tb_data_pendaftaran.kode_jurusan');

                          $get_exist_periode = $db2->query("select id_semester,tahun_akademik from view_semester where id_semester in (select id_semester from tb_data_pendaftaran where tb_data_pendaftaran.status='1' $jur_kode)order by id_semester desc");
                          $index=0;
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

                      <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Jenis Sidang/Seminar</label>
                        <div class="col-lg-5">
                        <select id="jenis_pendaftaran" name="jenis_pendaftaran" data-placeholder="Pilih Pendaftaran ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                            <?php
                            $filter_session_jenis = getFilter(array('filter_pendaftaran_jadwal' => 'jenis_pendaftaran'));
                            foreach ($db2->query("SELECT tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_jenis_pendaftaran from tb_data_pendaftaran_jenis where id_jenis_pendaftaran in(
                              select id_jenis_pendaftaran from tb_data_pendaftaran_jenis_pengaturan 
                              INNER join tb_data_pendaftaran using(id_jenis_pendaftaran_setting)
                              where tb_data_pendaftaran.status='1' $jur_kode)") as $jenis_pendaftaran) {
                              if ($filter_session_jenis==$jenis_pendaftaran->id_jenis_pendaftaran) {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran' selected>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              } else {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran'>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              }
                            }
                            //echo $db2->getErrorMessage();
                            ?>
                        </select>
                      </div>
                    </div>
                    <input type="hidden" name="status" value="1">
                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                       <!--  <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download Data</button> -->
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
</div>



 <div class="alert alert-info fade in">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-info"></i> 
          Dibawah ini adalah jadwal menguji Bapa/Ibu 
        </div>
           </h4>
                <div class="btn-group" id="aksi-jadwal" style="display: none;margin-bottom: 10px">
                  <button type="button" class="btn btn-success">Pilih Aksi</button>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="aksi_jadwal" data-value="edit"><a href="#"><i class="fa fa-pencil"></i> Edit Masal Jadwal</a></li>
                    <li class="aksi_jadwal" data-value="hapus"><a href="#"><i class="fa fa-trash"></i> Hapus Jadwal</a></li>
                  </ul>
                </div>
                <div class="table-responsive">
                        <table id="dtb_jadwal_sidang" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                               <tr>
                                  <th style='padding-right:7px;' class='dt-center'><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline'> <input type='checkbox' class='group-checkable bulk-check'> <span></span></label></th>
                                  <th class="none">NIM</th>
                                  <th>Nama</th>
                                  <th>Pendaftaran</th>
                                  <th>Hari/Tanggal</th>
                                  <th>Jam</th>
                                  <th>Ruang</th>
                                  <th>Judul</th>
                                  <th>Nilai</th>
                                  <th>Program Studi</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>

    <div class="modal fade" id="modal_jadwal_sidang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Jadwal Sidang</h4> </div> <div class="modal-body" id="isi_jadwal_sidang"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal fade" id="modal_jadwal_sidang_massal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg" style="width:90%"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Edit Jadwal Sidang</h4> </div> <div class="modal-body" id="isi_jadwal_sidang_masssal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

        <script type="text/javascript">


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
                  data : {filter_name:'filter_pendaftaran_jadwal',show_filter : show},
                  success: function(data) {
                    console.log(data);
                  }
              });

        });
      $("#add_jadwal_sidang").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_jadwal_sidang").html(data);
              }
          });

      $('#modal_jadwal_sidang').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
          $(".table").on('click','.lihat-nilai',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Nilai Penguji");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/jadwal_sidang/lihat_nilai.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_jadwal_sidang").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_jadwal_sidang').modal({ keyboard: false,backdrop:'static' });

    });
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);
        $(".modal-title").html("Input Nilai Menguji");
        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/jadwal_menguji/input_nilai_ujian.php",
            type : "post",
            data : {id_pendaftaran:id},
            success: function(data) {
                $("#isi_jadwal_sidang").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_jadwal_sidang').modal({ keyboard: false,backdrop:'static' });

    });
      var dtb_jadwal_sidang = $("#dtb_jadwal_sidang").DataTable({
              
          <?php
          if (getFilter(array('filter_pendaftaran_jadwal' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_pendaftaran_jadwal' => 'input_search'));?>"
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
            }
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/jadwal_menguji/jadwal_menguji_data.php',
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
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_jadwal_sidang.ajax.reload();
});

$("#dtb_jadwal_sidang_filter").on('click','.reset-button-datatable',function(){
    dtb_jadwal_sidang
    .search( '' )
    .draw();
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
        dtb_jadwal_sidang.rows(':not(.parent)').nodes().to$().find('td:first-child').trigger('click');
      } else {
        select_deselect('unselect');
        dtb_jadwal_sidang.rows('.parent').nodes().to$().find('td:first-child').trigger('click');
      }
      $('.check-selected').each( function() {
        $(this).prop('checked',status);
      });
      check_selected();
});



  $(document).on('click', '#dtb_jadwal_sidang tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          check_selected();
      }
  });

  function check_selected() {
      var table_select = $('#dtb_jadwal_sidang tbody tr.selected');
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
          $('#dtb_jadwal_sidang tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_jadwal_sidang tbody tr').removeClass('DTTT_selected selected')
      }
  }




/* Add a click handler for the delete row */
  $('.aksi_jadwal').click( function() {
    var nilai_aksi = $(this).data('value');
    var anSelected = fnGetSelected( dtb_jadwal_sidang );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();

    if (nilai_aksi=='hapus') {
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
            url: '<?=base_admin();?>modul/jadwal_sidang/jadwal_sidang_action.php?act=del_massal',
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
                               dtb_jadwal_sidang.draw();
                          }
                    });
                }
            //async:false
        });

        $('#modal-confirm-delete').modal('hide');

    });

    } else if(nilai_aksi=='edit') {
        $("#loadnya").show();
        event.preventDefault();
        $(".modal-title").html("Edit Jadwal Sidang");
        $.ajax({
            url : "<?=base_admin();?>modul/jadwal_sidang/edit_massal.php",
            type : "post",
            data : {data_ids:all_ids},
            success: function(data) {
                $("#isi_jadwal_sidang_masssal").html(data);
                $("#loadnya").hide();
          }
        });

        $('#modal_jadwal_sidang_massal').modal({ keyboard: false,backdrop:'static' });
    }

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }
</script>
            