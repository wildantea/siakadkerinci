<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Aktivitas Kuliah Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>aktivitas-kuliah-mahasiswa">Aktivitas Kuliah Mahasiswa</a></li>
                        <li class="active">Aktivitas Kuliah Mahasiswa List</li>
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
                                      <a class="btn btn-primary rekap-nilai"><i class="fa fa-gear"></i> Rekap Nilai Akm</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
      <img id="loading-bar" style="width:50px;display: none;" src="<?=base_admin();?>assets/dist/img/loadnya.gif" class="ajax-loaders"/>
                              </div><!-- /.box-header -->

<div class="box-body">
              <div class="row" id="show_progress" style="display: none">
               <div class="col-md-11">
                        <div class="progress">
                          <div class="progress-bar progress-bar-striped" id="progressbar" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                            1%
                          </div>

                        </div>
                    </div>
                <div class='col-md-1' id="message">
                  <span class='current-count'>1</span>/<span class="total-count">13</span>
                </div>
              </div>
              <div class="alert alert-danger alert-dismissible" id="ada_error" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <span class="isi_error"></span>
              </div>

<div id="hasil_up" style="display:none"></div>
<form>
<div id="isi_drop_up" style="display:none">
<div class="row">
               <div class="form-group">
                        <label for="Semester" class="control-label col-lg-1">NIM</label>
                        <div class="col-lg-2" >
                        <select id="nim_rekap" name="nim_rekap" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" style="z-index: 1" required="">
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
              <input type="text" name="value_nim_rekap" id="value_nim_rekap" class="form-control" placeholder="Input NIM Mahasiswa" style="display: none">
              <div id="error_nim_rekap"></div>
            </div>
              
                      </div><!-- /.form-group -->
</div>
<p>
<div class="row" id="pilihan_semester" style="display:none">
               <div class="form-group">
                        <label for="Semester" class="control-label col-lg-1">Pilihan Semester</label>
                        <div class="col-lg-3" >
                        <select id="pilih_semester" name="pilih_semester" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" style="z-index: 1" required="">
                          <option value="all">Hitung Semua Periode (dari Awal semester mahasiswa)</option>
                          <option value="pilih">Pilih Periode Semester</option>
                        </select>
                </div>
                      </div><!-- /.form-group -->
</div>
<p>
<div class="row">
<div class="form-group" id="show_semester">
                        <label for="Jurusan" class="control-label col-lg-1">Semester</label>
                        <div class="col-lg-3">
                                 <select id="semester_rekap" name="semester_rekap" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2" required>
                          <option value="10">Semester Konversi</option>
                          <?php 
                          loopingSemesterForm();
                          ?>
              </select>
                          <div id="error_sem"></div>
                        </div>
                      </div><!-- /.form-group -->
</div>
<p>
<div class="row" id="show_prodi">
        <div class="form-group">
                        <label for="Semester" class="control-label col-lg-1">Prodi</label>
                        <div class="col-lg-3">
                        <select id="jur_rekap" name="jur_rekap" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="5">
                         <?php
                                loopingProdi();
                        ?>
             
              </select>

 </div>
  </div><!-- /.form-group -->
</div>
<p>

<div class="row">
<div class="form-group">
           <label for="Jurusan" class="control-label col-lg-1">&nbsp;</label>
            <div class="col-lg-3">
            <span class="btn btn-success btn-flat rekap-now">
<i class="fa fa-gear"></i> Hitung Akm / Rekap Nilai</span>
            </div>
</div>


</div>
<p>&nbsp;</p>
</div>
</form>
<div class="box box-primary">
   <div class="box-header with-border">
              <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
            </div>
            <div class="box-body filter-body">
           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/aktivitas_kuliah_mahasiswa/download_data.php" target="_blank">
             <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Filter NIM</label>
                        <div class="col-lg-2" >
                        <select id="nim" name="nim" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2" style="z-index: 1" required="">

<?php
$array_filter_nim = array(
                          'all' => 'Semua',
                          'nim' => 'NIM'
                        );
                          $filter_session_nim = getFilter(array('filter_akm' => 'nim'));
                          foreach ($array_filter_nim as $key => $value) {
                            if ($filter_session_nim==$key) {
                              echo "<option value='$key' selected>$value</option>";
                            } else {
                              echo "<option value='$key'>$value</option>";
                            }
                          }
                          ?>
              </select>
            </div>
            <?php
            $show_nim = "style='display:none'";
            $value_nim = "nim";
            if ($filter_session_nim !='all' and $filter_session_nim !='') {
              $show_nim = "style='display:block'";
              $value_nim = getFilter(array('filter_akm' => 'value_nim'));
            }
            ?>
            <div class="col-lg-3" style="padding-left: 0;">
              <input type="text" name="value_nim" id="value_nim" class="form-control" placeholder="Input NIM Mahasiswa" <?=$show_nim;?> required="" value='<?=$value_nim;?>'>
            </div>
              <div id="error_nim"></div>
                      </div><!-- /.form-group -->
            <?php
            if (hasFakultas()) {
              ?>
          <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                                <div class="col-lg-5">
                                <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                loopingFakultas('filter_akm');
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
                                $session_filter_fakultas = getFilter(array('filter_akm' => 'fakultas'));
                                loopingProdi('filter_akm',$session_filter_fakultas);
                                ?>
                      </select>
                    </div>

                              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode</label>
                        <div class="col-lg-5">
                        <select id="sem_filter" name="periode" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                          <?php 
                          loopingSemester('filter_akm');
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
                        <label for="Semester" class="control-label col-lg-2">Status Mahasiswa</label>
                        <div class="col-lg-3">
                        <select id="status_mahasiswa" name="status_mahasiswa" data-placeholder="Pilih Status ..." class="form-control" tabindex="2">
                          <option value="all">Semua</option>
                          <?php
                          $filter_session_status = getFilter(array('filter_akm' => 'status'));
                          $status_mahasiswa = $db->query("select * from tb_ref_status_mahasiswa where show_in_akm='Y'");
                           foreach ($status_mahasiswa as $status) {
                             if ($filter_session_status==$status->id_stat_mhs) {
                               echo "<option value='$status->id_stat_mhs' selected>$status->nm_stat_mhs</option>";
                             } else {
                               echo "<option value='$status->id_stat_mhs'>$status->nm_stat_mhs</option>";
                             }
                             
                           }
                          ?>
                        </select>
                      </div>
                    </div><!-- /.form-group -->
                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                           <?php
                            resetFilterButton('filter_akm');
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
                        <table id="dtb_aktivitas_kuliah_mahasiswa" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:13px;' class='dt-center'><label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '> <input type='checkbox' class='group-checkable bulk-check'> <span></span></label></th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  <th>SMT MHS</th>
                                  <th>Periode</th>
                                  <th>Status</th>
                                  <th>IPS</th>
                                  <th>IPK</th>
                                  <th>SKS Semester</th>
                                  <th>SKS Total</th>
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
<div class="modal modal-warning" id="informasi-rekap" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title"><?=$lang['info'];?></h4> </div> <div class="modal-body"> <p id="isi_informasi_rekap">
<?=$lang['session_over'];?>
</p> </div> <div class="modal-footer"> <a href="<?=base_index();?>aktivitas-kuliah-mahasiswa" class="btn btn-outline pull-left">Close</a> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div><!-- /.modal -->
 <?php
  $edit ="";
  $del="";

foreach ($db->fetch_all("sys_menu") as $isi) {
    if (uri_segment(1)==$isi->url) {
        if ($role_act["up_act"]=="Y") {
    $edit = "<a data-id='+data+' href=".base_index()."aktivitas-kuliah-mahasiswa/edit/'+data+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
        }
    }
}

foreach ($db->fetch_all("sys_menu") as $isi) {
    if (uri_segment(1)==$isi->url) {
        if ($role_act["del_act"]=="Y") {
     $del = "<button data-id='+data+' data-uri=".base_admin()."modul/aktivitas_kuliah_mahasiswa/aktivitas_kuliah_mahasiswa_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_aktivitas_kuliah_mahasiswa"><i class="fa fa-trash"></i></button>';
        }
    }
}
        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
$('.rekap-nilai').on('click', function() {

  $('#isi_drop_up').toggle();

});

  $("#nim").change(function(){
    if (this.value=='all') {
      $("#value_nim").hide();
    } else {
      $("#value_nim").show();
    }

  });

  $("#nim_rekap").change(function(){
    if (this.value=='all') {
      $("#value_nim_rekap").hide();
      $("#pilihan_semester").hide();
      $("#show_semester").show();
      //$("#value_nim_rekap").value('');
      $("#show_prodi").show();
      $("#nim_rekap").parent().parent().removeClass('has-error');
      $("#error_nim_rekap").html('');
    } else {
      $("#pilihan_semester").show();
      $("#show_semester").hide();
      $("#show_prodi").hide();
      $("#value_nim_rekap").show();
    }

  });

  $("#pilih_semester").change(function(){
    if (this.value=='pilih') {
      console.log('tes');
       $("#show_semester").show();
    } else {
       $("#show_semester").hide();
    }
  });


$("#value_nim_rekap").keypress(function() {
 if($(this).val().length > 1) {
        $("#error_nim_rekap").html('');
        $("#nim_rekap").parent().parent().removeClass('has-error');
    }

});

$(document).ready(function(){
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
      
      var dtb_aktivitas_kuliah_mahasiswa = $("#dtb_aktivitas_kuliah_mahasiswa").DataTable({
              
          <?php
          if (getFilter(array('filter_akm' => 'input_search'))!="") {
            ?>
                "search": {
                  "search": "<?=getFilter(array('filter_akm' => 'input_search'));?>"
                },
            <?php
          }
          ?>   

          // 'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
           // "ordering": false,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$del;?>';
               }
            },
      
              {
             "targets": [0],
             //"width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/aktivitas_kuliah_mahasiswa/aktivitas_kuliah_mahasiswa_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    <?php
                    if(hasFakultas()) {
                      ?>
                      d.fakultas = $("#fakultas_filter").val();
                      <?php
                    }
                    ?>
                    d.value_nim = $("#value_nim").val();
                    d.nim = $("#nim").val();
                    d.jurusan = $("#jur_filter").val();
                    d.periode = $("#sem_filter").val();
                    d.mulai_smt = $("#mulai_smt").val();
                    d.mulai_smt_end = $("#mulai_smt_end").val();
                    d.status_mahasiswa = $("#status_mahasiswa").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_aktivitas_kuliah_mahasiswa.ajax.reload();
});

$("#dtb_aktivitas_kuliah_mahasiswa_filter").on('click','.reset-button-datatable',function(){
    dtb_aktivitas_kuliah_mahasiswa
    .search( '' )
    .draw();
  });

$('.rekap-now').on('click', function() {
    if($("#value_nim_rekap").is(":visible") && $("#value_nim_rekap").val()=="") {
      $("#nim_rekap").parent().parent().addClass('has-error');
      $("#error_nim_rekap").html('<span for="nim" class="help-block">Silakan isi NIM</span>');
      return;
    } 

    $('.rekap-now').prop("disabled", true);
    $("#isi_drop_up").hide();
      var start_time = new Date().getTime();
      $("#loading-bar").show();
      $("#show_progress").show();
      total_data = parseInt(total_rekap());

      var bagi = Math.ceil(total_data/50);
      
      getData(bagi,total_data,start_time);

       //$("#loading-bar").hide();
    
});
function total_rekap() {
          var totaldata;
          var datas = {
            jur_rekap : $("#jur_rekap").val(),
            semester_rekap : $("#semester_rekap").val(),
            nim_rekap : $("#nim_rekap").val(),
            value_nim_rekap : $("#value_nim_rekap").val(),
            pilih_semester : $("#pilih_semester").val(),
          }
             $.ajax({
              url: "<?=base_admin();?>modul/aktivitas_kuliah_mahasiswa/jumlah_rekap.php",
              type : "post",
              dataType: 'json',
              'async':false,
              data : datas,
              success: function(data) {
                  totaldata = data.jumlah;
              }
            });

    return totaldata;
}

function millisToMinutesAndSeconds(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  var hrs = (s - mins) / 60;

  return (hrs < 1 ? '' : hrs+' Jam : ') + (mins < 1 ? '' : mins+' Menit : ') + secs + ' detik';
}

 function proses(percent){
    if(percent>1){
      
      $("#progressbar").css("width",percent+"%");
      $("#progressbar").html(percent+"%");
      } else {
        $("#progressbar").css("width",1+"%");
        $("#progressbar").html(1+"%");
      }
    } 

    var random_number = Math.floor(1000 + Math.random() * 9000);
    var counters = 0;
    var jumlah_error=0;
    var jumlah_sukses = 0;
    var persen = 0;
    var progress=50;
    var token = "";
    var error_msg = "";    
window.getData=function(bagi,total_data,start_time)
{
    var start = start_time;
  
    if ((bagi*50)==progress) {
      data = {
          jur_rekap : $("#jur_rekap").val(),
          semester_rekap : $("#semester_rekap").val(),
          nim_rekap : $("#nim_rekap").val(),
          value_nim_rekap : $("#value_nim_rekap").val(),
          pilih_semester : $("#pilih_semester").val(),
          id_data : 'end',
          random_number : random_number,
          offset : counters,
          total_data : total_data
        }
    } else {
      data = {
            jur_rekap : $("#jur_rekap").val(),
            semester_rekap : $("#semester_rekap").val(),
            nim_rekap : $("#nim_rekap").val(),
            value_nim_rekap : $("#value_nim_rekap").val(),
            pilih_semester : $("#pilih_semester").val(),
            total_data : total_data,
            offset : counters,
            random_number : random_number
          }
    }



 
    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/aktivitas_kuliah_mahasiswa/rekap_akm.php",
        //async:false,
        data : data,
        type : "post",
        dataType: 'json',
        success:function(data){
          $.each(data, function(index) {
            persen = ((progress/total_data)*100).toFixed(1);
            if (persen>100) {
              persen=100+ "%";
              progress = total_data;
            } else {
              persen=persen+ "%";
              progress = progress;
            }

         
            $(".current-count").html(progress);
            $(".total-count").html(total_data);
            persen = parseInt(persen);
            proses(persen);

              counters+=50;
              progress+=50;


              //console.log(data[index].offset);
              //jumlah_error+=parseInt(data[index].jumlah_error);
              //jumlah_sukses+=parseInt(data[index].jumlah_sukses);
               if (counters < total_data) {
                  getData(bagi,total_data,start);
                } else {
                  $("#loading-bar").hide();
                  var end_time = new Date().getTime();
                  waktu = "Total Waktu Rekap : "+millisToMinutesAndSeconds(end_time-start);
                  console.log(data[index]);
                  alert('Rekap data Selesai');
                  $("#isi_informasi_rekap").html(data[index].last_notif.concat(waktu));
                  $('#informasi-rekap').modal('show');
                  //console.log('done');
                } 
              });

        },
      error: function (xhr, ajaxOptions, thrownError) {
        alert('oops ada error');
        $("#loadnya").hide();
         $("#ada_error").show();
        $(".isi_error").html(xhr.responseText);
        $("#loading-bar").hide();
        $('.rekap-now').prop("disabled", false);
        
        }

    });
}
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



  $(document).on('click', '#dtb_aktivitas_kuliah_mahasiswa tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          check_selected();
      }
  });

  function check_selected() {
      var table_select = $('#dtb_aktivitas_kuliah_mahasiswa tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
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
          $('#dtb_aktivitas_kuliah_mahasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_aktivitas_kuliah_mahasiswa tbody tr').removeClass('DTTT_selected selected')
      }
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_aktivitas_kuliah_mahasiswa );
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
            url: '<?=base_admin();?>modul/aktivitas_kuliah_mahasiswa/aktivitas_kuliah_mahasiswa_action.php?act=del_massal',
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
                               dtb_aktivitas_kuliah_mahasiswa.draw();
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
            