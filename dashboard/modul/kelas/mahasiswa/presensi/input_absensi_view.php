<?php
session_start();
include "../../../inc/config.php";
//check if pertemuan exist
$kelas_id = $_POST['kelas_id'];
$check_pertemuan = $db2->checkExist("tb_data_kelas_absensi",array('id_pertemuan' => $_POST['pertemuan']));
$pertemuan = $db2->checkExist("tb_data_kelas_pertemuan",array('id_pertemuan' => $_POST['pertemuan']));


$kelas_data = $db2->fetchCustomSingle("SELECT kelas_id,kls_nama,id_matkul,kode_mk,sem_id,nama_mk,sks as total_sks,kode_jur,jurusan as nama_jurusan,
(select count(id_krs_detail) from krs_detail where disetujui='1' and id_kelas=view_nama_kelas.kelas_id) as approved_krs
#(select count(id_krs_detail) from krs_detail where disetujui='0' and id_kelas=view_nama_kelas.kelas_id) as pending_krs 
from view_nama_kelas where kelas_id=?",array('kelas_id' => $kelas_id));
$dosen_pertemuan = explode("#",$pertemuan->getData()->nip_dosen);
$nip = sprintf("'%s'", implode("','", $dosen_pertemuan ) );

$counter = 1;
foreach ($db2->query("select nip,nama_gelar from view_jadwal_dosen_kelas
inner join view_nama_gelar_dosen on view_jadwal_dosen_kelas.id_dosen=view_nama_gelar_dosen.nip where id_kelas=? order by dosen_ke asc",array("id_kelas" => $kelas_data->kelas_id)) as $isi) {
  $dosen_data[] = '- '.$isi->nama_gelar;
  $counter++;
}
$nama_dosen = trim(implode("<br>", $dosen_data));

$nip_dosen = getUser()->username;

$dosen_name = $db2->fetchSingleRow("view_nama_gelar_dosen","nip",$nip_dosen);

$fotos = $db2->fetchSingleRow("sys_users","username",$nip_dosen);

$foto_dosen = $fotos->foto_user;


//check if pernah absen
$pernah_absen = $db2->fetchCustomSingle("SELECT kehadiran_dosen FROM tb_data_kelas_pertemuan WHERE JSON_SEARCH(kehadiran_dosen, 'one', '".$nip_dosen."', NULL, '$[*].nip') IS NOT NULL and id_pertemuan=?",array('id_pertemuan' => $_POST['pertemuan']));

$tanggal_absen = '';
if ($pernah_absen->kehadiran_dosen!="") {
  $kehadiran = json_decode($pernah_absen->kehadiran_dosen,true);
    // 2. Check if nip already exists, if so update
    foreach ($kehadiran as &$dosen) {
            $tanggal_absen = '<span class="btn btn-info btn-sm"><i class="fa fa-clock-o"></i> '.tgl_time($dosen['tanggal_absen']).'</span>';
            $found = true;
            break;
        }

  $status_absen = '<span class="btn btn-success btn-sm"><i class="fa fa-check"></i> Sudah</span>';
  $has_absen = 1;
} else {
  $status_absen = '<span class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Belum</span>';
  $has_absen = 0;
}



?>
<style type="text/css">
  #presensi > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
    padding:2px;
  }
 .help-block {
    color: #dd4b39;
}
.mt-checkbox {
  margin-bottom:0
}
#presensi.dataTable {
  border-color: #9e9595;
}
.widget-user .widget-user-header {
    height: 104px;
}
</style>

<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->kode_mk;?> - <?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama ;?></td>
        </tr>
        <tr>
        <td class="info2"><strong>Dosen</strong></td>
          <td><?=$nama_dosen;?></td>
          <td class="info2" ><strong>Pertemuan</strong></td>
          <td><?=$pertemuan->getData()->pertemuan;?></td>
         
        </tr>
      </tbody></table>
<div class="alert alert-success success-absensi" style="display:none">
          Absensi Berhasil
        </div>
<!-- <div class="row">
  <div class="col-md-12">
          <div class="box box-widget widget-user-2">
            <div class="widget-user-header bg-aqua-active">
              <div class="widget-user-image">
                <img class="img-circle" src="<?=base_url();?>upload/back_profil_foto/<?=$foto_dosen;?>" alt="User Avatar">
              </div>
              <h3 class="widget-user-username"><?=$dosen_name->nama_gelar;?></h3>
              <h5 class="widget-user-desc"><button class="btn btn-default" id="absen_dosen" data-toggle="tooltip" data-title="Klik disini untuk melakukan absensi pertemuan <?=$pertemuan->getData()->pertemuan;?>">
        <img src="<?=base_admin();?>assets/finger.png" style="width:30px">
        Klik disini untuk Absensi Dosen
      </button></h5>
            </div>
                       <div class="box-footer" style="padding-top:0">
              <div class="row">
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                      <h5 class="description-header">Status Absensi Dosen</h5>
                    <span class="description-text status-absen"><?=$status_absen;?></span>
                  </div>
                </div>
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                    <h5 class="description-header">Tanggal Absensi</h5>
                    <span class="description-text tanggal-absen"><?=$tanggal_absen?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</div> -->

<div class="alert alert-danger error_data_absen" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning_absen"></span>
        </div>
<form id="input_absensi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/pertemuan/pertemuan_action.php?act=in_absen">     
       <input type="hidden" name="id_pertemuan" value="<?=$_POST['pertemuan'];?>">
<table id="presensi" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr class="bg-blue">
                                  <th>No</th>
                                  <th>NIM</th>
                                  <th>Nama</th>
                                  <th>Angkatan</th>
                                  
                                  <?php
                                  if ($check_pertemuan) {
                                   echo "<th>Tgl Input</th>";
                                   ?>
                                   <th class='all' style="padding-right:10px"><label class='mt-checkbox mt-checkbox-single' >Status <input type='checkbox' class='group-checkable bulk-check'> <span></span></label> </th>
                                   <?php
                                  } else {
                                    ?>
                                    <th class='all' style="padding-right:10px"><label class='mt-checkbox mt-checkbox-single '>Status <input type='checkbox' class='group-checkable bulk-check' > <span></span></label> </th>
                                    <?php
                                  }
                                  ?>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

               <div class="form-group" style="border-top: 1px solid #eee;padding-top: 5px;">
              <label for="Pengajar" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10" style="text-align: right;">
                <button type="button" class="btn btn-default close-absensi" ><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data" ><i class="fa fa-save"></i> Simpan Presensi</button>
                </div>
              </div><!-- /.form-group -->
                        </form>

<script>

    $("#absen_dosen").click(function() {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/presensi/input_absen_dosen.php",
            type : "post",
            data : {nip:"<?=$nip_dosen;?>",pert:<?=$_POST['pertemuan'];?>},
            success: function(data) {
              $("#loadnya").hide();
              console.log(data);
              $(".success-absensi").fadeIn(1000);
              $(".success-absensi").fadeOut(1000, function() {
              });
          }
        });
    });

$('.close-absensi').click(function(){
    $('#modal_input_absen').modal('hide');
    $('#modal_peserta_kelas').focus(); // Or focus a button inside it
});



     var presensi = $("#presensi").DataTable({
              'dom' : "",
              'ordering' : false,
              'bProcessing': true,
               'bServerSide': true,
               paging: false,
               
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
                 "searchable": false,
                 "class" : "all"
               }
                ],
         
               'ajax':{
                 url :'<?=base_admin();?>modul/kelas/presensi/input_absensi_data.php',
               type: 'post',  // method  , by default get
               data: function ( d ) {
                       //d.fakultas = $("#fakultas_filter").val();
                       d.id_pertemuan = <?=$_POST['pertemuan'];?>;
                       d.kelas_id = <?=$_POST['kelas_id'];?>;
                     },
               error: function (xhr, error, thrown) {
               console.log(xhr);
               }
             },
             "createdRow": function( row, data, dataIndex){
              val = $(row).find(":selected").val();
              if (val=='Ijin') {
                $(row).css("background-color", "#bcfffc");
              } else if(val=='Sakit') {
                $(row).css("background-color", "#ffffae");
              } else if(val=='Alpa') {
                $(row).css("background-color", "#ffb4b4");
              }
              //var oData = row.rows('.selected').data();
            }
           });

$("#presensi").on('change','.absen-val',function(event) {
event.preventDefault();
  val = this.value;
  console.log(val);
    if (val=='Ijin') {
      $(this).parents('tr').css("background-color", "#bcfffc");
    } else if(val=='Sakit') {
      $(this).parents('tr').css("background-color", "#ffffae");
    } else if(val=='Alpa') {
      $(this).parents('tr').css("background-color", "#ffb4b4");
    } else {
      $(this).parents('tr').css("background-color", "#f9f9f9");
    }
})
$('.bulk-check').click(function(){
  var status = this.checked;
  if (status) {
    presensi.$('select').val('Hadir');
    presensi.$('select').parents('tr').css("background-color", "#f9f9f9");
    $("#input_absensi").valid();
  } else {
    presensi.$('select').val('');
    $("#input_absensi").valid();
  } 
  
});

$("#input_absensi").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } if (element.hasClass("absen-val")) {
              element.parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },    
    submitHandler: function(form) {
            $("#loadnya").show();
           // var val_absen = presensi.$('select').serialize();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                //data : {val_absen},
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning_absen").html(data.responseText);
                  $(".error_data_absen").focus()
                  $(".error_data_absen").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning_absen").text(responseText[index].error_message);
                             $(".error_data_absen").focus()
                             $(".error_data_absen").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".error_data_absen").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                $('#modal_input_absen').modal('hide');
                                dtb_presensi.draw();
                                dtb_approved_krs.draw();
                            });
                          }
                    });
                }

            });
        }
    });
</script>