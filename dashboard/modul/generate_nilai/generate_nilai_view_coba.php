<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Generate Nilai
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>generate-nilai">Generate Nilai</a></li>
                        <li class="active">Generate Nilai List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
      <img id="loading-bar" style="width:50px;display: none;" src="<?=base_admin();?>assets/dist/img/loadnya.gif" class="ajax-loaders"/>
                              </div><!-- /.box-header -->


              
            <div class="box-header with-border">
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
            </div>
            <div class="box-body">

           <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/kelas_jadwal/cetak.php" target="_blank">


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
                                <label for="Semester" class="control-label col-lg-2">Program Studi
                                  </label>
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
                        <label for="Semester" class="control-label col-lg-2">Kelas</label>
                        <div class="col-lg-5">
                        <select id="kelas_filter" name="kelas_filter" data-placeholder="Pilih Kelas ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                        </select>
                      </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Tampilkan Nilai Kosong</span>
                      </div><!-- /.form-group -->
                    </form>
            </div>
            <!-- /.box-body -->
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">

                              <div id="hasil">
                                
                              </div>
<div class="row" id="aksi_top_krs" style="display: none">
    <div class="col-sm-4" style="margin-bottom: 10px;">
      <div class="input-group input-group-sm">
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
          </span>
       <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
       <option value="tanggal" data-aksi="ubah_tanggal">Generate Nilai</option>

    </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
          </span>
    </div>
    </div>
</div>
                               <table id="dtb_nilai_permahasiswa" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th rowspan="2" style="padding-right:7px;width: 3%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
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
                            
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>

    </section><!-- /.content -->


    <div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Generate Nilai</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

<div class="modal modal-warning" id="informasi-rekap" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title"><?=$lang['info'];?></h4> </div> <div class="modal-body"> <p id="isi_informasi_rekap">
<?=$lang['session_over'];?>
</p> </div> <div class="modal-footer"> <a href="<?=base_index();?>generate-nilai" class="btn btn-outline pull-left">Close</a> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div><!-- /.modal -->
        <script type="text/javascript">

    $("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/kelas_jadwal/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#jur_filter").html(data);
        $("#jur_filter").trigger("chosen:updated");
        $("#kelas_filter").html("<option value='all'>Semua</option>");
        $("#kelas_filter").trigger("chosen:updated");
        

        }
    });
});

var dtb_nilai_permahasiswa = $("#dtb_nilai_permahasiswa").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            lengthMenu: [10, 20, 50, 100, 200, 500,1000,1500],
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
              url :'<?=base_admin();?>modul/generate_nilai/generate_nilai_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jur_filter").val();
                    d.semester = $("#sem_filter").val();
                     d.kelas = $("#kelas_filter").val();
                    d.matakuliah = $("#matkul_filter").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);


            }
          }

        });


//filter
$('#filter').on('click', function() {
  dtb_nilai_permahasiswa.ajax.reload();
});

$("#jur_filter").change(function(){
    if ($("#jur_filter").val()!="" && $("#sem_filter").val()!="") {
        $.ajax({
              url : "<?=base_admin();?>modul/generate_nilai/get_matkul.php",
              type : "POST",
              data : {jur_filter:$("#jur_filter").val(),sem_filter:$("#sem_filter").val()},
              success: function(data) {
                  $("#matkul_filter").html(data);
                  $("#matkul_filter").trigger("chosen:updated");
                  $("#kelas_filter").html("<option value='all'>Semua</option>");
                  $("#kelas_filter").trigger("chosen:updated");
              }
          });
    }
});

$("#matkul_filter").change(function(){
        $.ajax({
              url : "<?=base_admin();?>modul/generate_nilai/get_kelas.php",
              type : "POST",
              data : {id_mat:this.value,jur_filter:$("#jur_filter").val(),sem_filter:$("#sem_filter").val()},
              success: function(data) {
                  $("#kelas_filter").html(data);
                  $("#kelas_filter").trigger("chosen:updated");
              }
          });
});

    

    function generate_nilai() {
      var sem_id = $("#sem_id").val();
      var kode_jur = $("#kode_jur").val();
       $.ajax({
              url : "<?=base_admin();?>modul/generate_nilai/generate_nilai_action.php?act=generate_nilai",
              type : "POST",
              data : {
                 sem_id : sem_id,
                 kode_jur : kode_jur
              },
              success: function(data) {
                $("#hasil").html(data);
              }
          });
    }

$('.rekap-now').on('click', function() {

    $('.rekap-now').prop("disabled", true);
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
            fakultas : $("#fakultas_filter").val(),
            jurusan : $("#jur_filter").val(),
            semester : $("#sem_filter").val(),
            id_matkul : $("#matkul_filter").val(),
            kelas : $("#kelas_filter").val()
          }
             $.ajax({
              url: "<?=base_admin();?>modul/generate_nilai/jumlah_gen.php",
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
          fakultas : $("#fakultas_filter").val(),
          jurusan : $("#jur_filter").val(),
          semester : $("#sem_filter").val(),
          id_matkul : $("#matkul_filter").val(),
          kelas : $("#kelas_filter").val(),
          id_data : 'end',
          random_number : random_number,
          offset : counters,
          total_data : total_data
        }
    } else {
      data = {
            fakultas : $("#fakultas_filter").val(),
            jurusan : $("#jur_filter").val(),
            semester : $("#sem_filter").val(),
            id_matkul : $("#matkul_filter").val(),
            kelas : $("#kelas_filter").val(),
            total_data : total_data,
            offset : counters,
            random_number : random_number
          }
    }



 
    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/generate_nilai/gen_nilai.php",
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
                  waktu = "Total Waktu Generate : "+millisToMinutesAndSeconds(end_time-start);
                  console.log(data[index]);
                  alert('Generate data Selesai');
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
      var table_select = $('#dtb_nilai_permahasiswa tbody tr.selected');
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
          $('#dtb_nilai_permahasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_nilai_permahasiswa tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_nilai_permahasiswa tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          console.log(selected);
          init_selected();

      }
  });

/* Add a click handler for the delete row */
  $('.submit-proses').click( function(event) {
    $("#loadnya").show();
    event.stopPropagation();
    event.preventDefault();
    event.stopImmediatePropagation();
    //var anSelected = fnGetSelected( dtb_nilai_permahasiswa );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    //var aksi = $(':selected', $(this)).data('aksi');
    var aksi = $("#aksi_krs option:selected").attr('data-aksi');
            $.ajax({
                url : "<?=base_admin();?>modul/generate_nilai/ubah_nilai.php",
                type : "post",
                data : {all_id:all_ids},
                success: function(data) {
                    $("#isi_setting_tagihan_mahasiswa").html(data);
                    $("#loadnya").hide();
              }
            });
            $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });
  });
</script>
            