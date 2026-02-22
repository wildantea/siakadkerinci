<?php
include "../../inc/config.php";
$kelas_data = $db->fetch_single_row("view_nama_kelas","kelas_id",$_POST['kelas_id']);
$semester = $db->fetch_single_row("view_semester","id_semester",$kelas_data->sem_id);
$matkul = $db->fetch_single_row("matkul",'id_matkul',$kelas_data->id_matkul);
$jmlsks_mk=$matkul->sks_tm+$matkul->sks_prak+$matkul->sks_prak_lap+$matkul->sks_sim;
$check_exist_jadwal = $db->check_exist('jadwal_kuliah',array('kelas_id' => $kelas_data->kelas_id));
if ($check_exist_jadwal) {
  $data_edit = $db->fetch_single_row("jadwal_kuliah","kelas_id",$kelas_data->kelas_id);
  $jadwal = 1;
} else {
  $jadwal = 0;
}
$check_exist_dosen_kelas = $db->check_exist('view_jadwal_dosen_kelas',array('id_kelas' => $kelas_data->kelas_id));
if ($check_exist_dosen_kelas) {
  $dosen = 1;
} else {
  $dosen = 0;
}
?>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody><tr>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$kelas_data->jurusan;?></td>
          <td class="info2" width="20%"><strong>Periode</strong></td>
          <td><?=$semester->tahun_akademik;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->nm_matkul;?> (<?=$jmlsks_mk;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->nama_kelas;?></td>
        </tr>
      </tbody></table>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form method="post" class="form-horizontal" id="input_jadwal" action="<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=input_jadwal">
<input type="hidden" name="kelas_id" value="<?=$_POST['kelas_id'];?>">
<input type="hidden" name="sem_id" value="<?=$kelas_data->sem_id;?>">
            
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-2">Gedung</label>
                        <div class="col-lg-9">
        <select name="gedung_id" id="gedung_id" data-placeholder="Pilih Gedung ..." class="form-control chzn-select" tabindex="2" required="" >
               <option value="">Pilih Gedung</option>
               <?php 
                $gedung = $db->query("select gedung_ref.gedung_id,gedung_ref.nm_gedung from gedung_ref
inner join ruang_ref on gedung_ref.gedung_id=ruang_ref.gedung_id
inner join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where ruang_ref_prodi.kode_jur='$kelas_data->kode_jur' and gedung_ref.is_aktif='Y'
group by gedung_ref.gedung_id");
                if ($jadwal) {
                 
                  foreach ($gedung as $isi) {
                    $selected_gedung_id = $db->fetch_single_row("ruang_ref","ruang_id",$data_edit->ruang_id);
                  if ($selected_gedung_id->gedung_id==$isi->gedung_id) {
                    echo "<option value='$isi->gedung_id' selected>$isi->nm_gedung</option>";
                  } else {
                    echo "<option value='$isi->gedung_id'>$isi->nm_gedung</option>";
                  }

                  }
              } else {
                  foreach ($gedung as $isi) {
                   echo "<option value='$isi->gedung_id'>$isi->nm_gedung</option>";
                  }
              }

 ?>
              </select>
                        </div>
            </div>

            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-2">Ruangan</label>
                        <div class="col-lg-9">
            <select name="ruang_id" id="ruang_id" data-placeholder="Pilih Ruangan ..." class="form-control chzn-select" tabindex="2" required="" >
               <option value="">Pilih Ruangan</option>
               <?php 
                if ($jadwal) {
$ruangan = $db->query("select * from ruang_ref where gedung_id=? and is_aktif='Y'",array('gedung_id' => $selected_gedung_id->gedung_id));
                  foreach ($ruangan as $isi) {
                  if ($data_edit->ruang_id==$isi->ruang_id) {
                    echo "<option value='$isi->ruang_id' selected>$isi->nm_ruang</option>";
                  } else {
                    echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
                  }

                  }
              } 

 ?>
              </select>
                        </div>
            </div>
                                           <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Hari</label>
                        <div class="col-lg-3">
                        <select id="hari" name="hari" data-placeholder="Pilih Hari ..." class="form-control" tabindex="2" required="">
                          <option value="">Pilih Hari</option>
                          <?php
                          $array_hari = array(
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                            'minggu' => 'Minggu'
                          );
                          if ($jadwal) {
                            foreach ($array_hari as $h => $hari) {
                              if ($data_edit->hari==$h or $data_edit->hari==$hari) {
                                echo "<option value='$h' selected>$hari</option>";
                              } else {
                                echo "<option value='$h'>$hari</option>";
                              }
                            
                            }
                          } else {
                            foreach ($array_hari as $h => $hari) {
                              echo "<option value='$h'>$hari</option>";
                            }
                          }

                          ?>
                        </select>


 </div>
                      </div><!-- /.form-group -->
                <div class="form-group">
                        <label for="Jam Mulai" class="control-label col-lg-2">Jam Mulai</label>
                        <div class="col-lg-3">
                        <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
                            <input type="text" class="form-control" <?=$jadwal?'value="'.$data_edit->jam_mulai.'"':"";?>  name="jam_mulai" autocomplete="off"  required="">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        </div>
                        <div class="col-lg-1" style="padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d
                        </div>
                                  <div class="col-lg-3">
                        <div class="input-group clockpicker" data-placement="top" data-align="top" data-autoclose="true">
                            <input type="text" autocomplete="off" class="form-control" <?=$jadwal?'value="'.$data_edit->jam_selesai.'"':"";?> name="jam_selesai" required="">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        </div>
                      </div><!-- /.form-group -->
     <hr>
         
            <div class="form-group">
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Dosen Ajar</label>
                        <div class="col-lg-10">
              <a class="btn btn-success " style="cursor:pointer" id="add_dosen"><i class="fa fa-plus"></i> Tambah Dosen</a>
              <table class="table">
                <thead>
                     <tr>
                      <th>NIP</th>
                      <th>Nama</th>
                      <th>Jurusan</th>
                      <th>Dosen Ke</th>                    
                      <th>Rencana Pertemuan</th>
                      <th>Del</th>
                     </tr>
                </thead>
                <tbody id="dosen_ajar">
<?php 
if ($dosen) {
   $data_edit = $db->query("select view_dosen.*,view_jadwal_dosen_kelas.dosen_ke,view_jadwal_dosen_kelas.jml_tm_renc from view_dosen inner join view_jadwal_dosen_kelas
on view_dosen.nip=view_jadwal_dosen_kelas.id_dosen
where view_jadwal_dosen_kelas.id_kelas=?",array("id_kelas" => $kelas_data->kelas_id));
   foreach ($data_edit as $k) {
      echo "<tr class='komponen_list'>                     
                      <td>$k->nip <input type='hidden' name='dosen[]' value='$k->id_dosen'></td>
                      <td>$k->dosen</td>
                      <td>$k->jurusan_dosen</td>  
                      <td><input type='text' required style='width:100px' name='dosen_ke[]' value='$k->dosen_ke'></td>
                      <td><input type='text' required style='width:100px' name='jml_tm_renc[]' value='$k->jml_tm_renc'></td>                  
                      <td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td>
                     </tr>";
   }
 } 
 ?>
                </tbody>
              </table>
            </div>
          </div>
            <hr>  

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-sdefault" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>

                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">

$("#gedung_id").change(function(){
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas_jadwal/get_ruang_ref.php',
            data: {gedung_id :$(this).val(),kode_jur:<?=$kelas_data->kode_jur;?>},
            success: function(result) {
              $("#ruang_id").html(result);
              $("#ruang_id").trigger("chosen:updated");
            }
        });

});
$('.clockpicker').clockpicker();
  $(".chzn-select").chosen();
  //Timepicker
  $(".time_mulai").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });
  //Timepicker
  $(".time_akhir").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });


    $("#add_dosen").on('click',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_list_dosen.php",
            type : "post",
            success: function(data) {
                $("#isi_dosen").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_list_dosen').modal({ keyboard: false,backdrop:'static' });

    });

$('.close_main').click(function() {
    location.reload();
});

    function pilih_dosen(id_dosen){
        // $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas_jadwal/komponen_tambah_dosen.php',
            data: 'id_dosen='+id_dosen,
            success: function(result) {
              // $('#loadnya').hide();
              $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
              $("#dosen_ajar").append(result);
            },
            //async:false
        });
    }

$(document).on("click", ".hapus_komponen_dosen", function() {
      $(this).parent().parent().remove(); 
});

          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#input_jadwal").validate({
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
        
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: 'json',
                type : 'post',
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data').hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                              $("#modal_kelas_jadwal").modal( 'hide' ).data( 'bs.modal', null );
                                   dataTable_jadwal.draw(false);
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                               $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
</script>
