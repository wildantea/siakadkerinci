<?php
session_start();
include "../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,jurusan as nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.*,tanggal_ujian,jam_mulai,jam_selesai,tempat,id_ruang,id_jadwal_ujian,view_simple_mhs_data.jur_kode,view_simple_mhs_data.mulai_smt,tb_data_pendaftaran.id_semester,
  (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_penguji on view_nama_gelar_dosen.nip=tb_data_pendaftaran_penguji.nip_dosen where tb_data_pendaftaran_penguji.id_jadwal_ujian=tb_data_pendaftaran_jadwal_ujian.id_jadwal_ujian order by penguji_ke asc
) as penguji,
    (select group_concat(nama_gelar separator '#')
 from view_nama_gelar_dosen inner join tb_data_pendaftaran_pembimbing on view_nama_gelar_dosen.nip=tb_data_pendaftaran_pembimbing.nip_dosen_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc) as pembimbing
from view_simple_mhs_data 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
inner join tb_data_pendaftaran_jadwal_ujian using(id_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_pendaftaran']));

?>
<link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/clockpicker/bootstrap-clockpicker.min.css">
<style type="text/css">
    .clockpicker-span-hours,.clockpicker-span-minutes {
    font-size: 24px;
  }

.modal.left .modal-dialog,
  .modal.right .modal-dialog {
    top: 0;
    bottom:0;
    position:fixed;
    overflow-y:scroll;
    overflow-x:hidden;
    margin: auto;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }
/*Right*/
  .modal.right.fade .modal-dialog {
    right: -320px;
    -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
       -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
         -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
  }
  
  .modal.right.fade.in .modal-dialog {
    right: 0;
  }
</style>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody>
        <tr>
          <td class="info2" width="20%"><strong>NIM</strong></td>
          <td width="30%"><?=$mhs_data->nim;?></td>
          <td class="info2" width="20%"><strong>Angkatan</strong></td>
          <td width="30%"><?=$mhs_data->angkatan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td><?=$mhs_data->nama;?> </td>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td><?=$mhs_data->nama_jurusan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Jenis Sidang</strong></td>
          <td colspan="3"><?=$mhs_data->nama_jenis_pendaftaran;?> </td>
        </tr>
        <?php
        if ($mhs_data->ada_judul=='Y') {
          ?>
          <tr>
          <td class="info2"><strong>Judul</strong></td>
          <td colspan="3"><?=$mhs_data->judul;?> </td>
        </tr>
          <?php
        }
        ?>
        <?php
  if ($mhs_data->ada_jadwal=='Y') {
      ?>
          <tr>
          <td class="info2"><strong>Tanggal Ujian</strong></td>
          <td colspan="3"><?=tgl_indo($mhs_data->tanggal_ujian);?> </td>
          </tr>
          <tr>
         <!--  <td class="info2"><strong>Waktu</strong></td>
          <td colspan="3"><?=$mhs_data->jam_mulai.' s/d '.$mhs_data->jam_selesai;?> </td>
          </tr> -->
          <tr>
          <td class="info2"><strong>Tempat</strong></td>
          <?php
          if ($mhs_data->tempat=='Daring') {
            $tempat = "Daring";
          } else {
            $tempat = getRuang($mhs_data->id_ruang);
          }
          ?>
          <td colspan="3"><?=$tempat;?> </td>
          </tr>
      <?php

  }

  if ($mhs_data->ada_pembimbing=='Y') {
        $nama_dosen_pembimbing = array_map('trim', explode('#', $mhs_data->pembimbing));
        foreach ($nama_dosen_pembimbing as $nomor => $dosen) {
          ?>
           <tr>
          <td class="info2"><strong>Pembimbing <?=($nomor+1);?></strong></td>
          <td colspan="3"><?=$dosen;?> </td>
          </tr>
          <?php
        }
        
  }

       if ($mhs_data->ada_penguji=='Y') {
        $nama_dosen = array_map('trim', explode('#', $mhs_data->penguji));
        foreach ($nama_dosen as $index => $dosen_uji) {
          $dosen_penguji[] = ($index+1).'. '.$dosen_uji;
          if ($index+1==1) {
            $jabatan = 'Ketua';
          } else {
            $jabatan = 'Anggota';
          }
          ?>
           <tr>
          <td class="info2"><strong><?=$jabatan;?> Penguji</strong></td>
          <td colspan="3"><?=$dosen_uji;?> </td>
          </tr>
          <?php
        }
        ?>
         
        <?php
        }


//get nilai
$nilai = $db2->fetchCustomSingle("select * from tb_data_pendaftaran_penguji where id_jadwal_ujian=? and nip_dosen=?",array(
'id_jadwal_ujian' => $mhs_data->id_jadwal_ujian,
'nip_dosen' => $_SESSION['username'])
);
$huruf = "";
if ($nilai->nilai_ujian!="") {
  $kode_jurusan= $mhs_data->jur_kode;
  $bobot=$nilai->nilai_ujian;
  $berlaku_angkatan=$mhs_data->mulai_smt;
  $where_berlaku = "";
  if ($berlaku_angkatan>=20202) {
        $where_berlaku = "and berlaku_angkatan='".$berlaku_angkatan."'"; 
  } else{
        $where_berlaku = "and berlaku_angkatan is null"; 
  }
  //echo "select * from skala_nilai where kode_jurusan=? $where_berlaku";
  $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku and $bobot >= bobot_nilai_min and $bobot <= bobot_nilai_maks",array('kode_jurusan' => $kode_jurusan));
  //echo "select * from skala_nilai where kode_jurusan=? $where_berlaku"; 
         if ($skala_nilai->rowCount()>0) {
              foreach ($skala_nilai as $skala) {
                  $huruf =  $skala->nilai_huruf;
              }
         } else {
          $huruf = "Skala Nilai Belum dibuat,Hubungi Admin";
         }
}
          ?>
      </tbody>
</table>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
    <form id="edit_nilai" method="post" class="form-horizontal" action="<?=base_admin();?>modul/jadwal_menguji/jadwal_menguji_action.php?act=input_nilai">

        
              <div class="form-group">
              <label for="Tanggal Sidang" class="control-label col-lg-2">Nilai Angka<span style="color:#FF0000">*</span></label>
              <div class="col-lg-3">
                <input type="text" name="nilai_ujian" value="<?=$nilai->nilai_ujian;?>" class="form-control nilai_angka" maxlength="5" size="5" required="">
              </div>
          </div><!-- /.form-group -->

          <input type="hidden" name="id" value="<?=$nilai->id_pendaftaran_penguji;?>">
          <input type="hidden" name="id_jadwal" value="<?=$nilai->id_jadwal_ujian;?>">
          <input type="hidden" name="nim" value="<?=$mhs_data->nim;?>">
          <input type="hidden" name="id_jenis_pendaftaran_setting" value="<?=$mhs_data->id_jenis_pendaftaran_setting;?>">
          <input type="hidden" name="id_semester" value="<?=$mhs_data->id_semester;?>">

              <div class="form-group">
              <label for="Tanggal Sidang" class="control-label col-lg-2">Nilai Huruf</label>
              <div class="col-lg-9">
                <input type="text" autocomplete="off" class="form-control nilai_huruf" value="<?=$huruf;?>" name="nilai_huruf" readonly />
                <span class="text-warning">Nilai huruf otomatis terisi berdasarkan nilai angka yang diisi</span>
              </div>
          </div><!-- /.form-group -->

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> Simpan Nilai</button>
                  </div>
                </div>
              </div><!-- /.form-group -->
</form>
<style type="text/css">
    .invalid {
    border: 2px solid red;
  }
</style>

<script type="text/javascript">
   $(document).ready(function() {
    const inputElement = $('.nilai_angka');
    const outputElement = $('.nilai_huruf');

    inputElement.on('input', function() {
      const value = $(this).val().trim();
      const cleanedValue = value.replace(/[^0-9.]/g, '');
      const isValid = /^[+-]?\d+(\.\d+)?$/.test(cleanedValue);

      $(this).val(cleanedValue);
      $(this).toggleClass('invalid', !isValid);

      if (isValid) {
        makeAjaxRequest(cleanedValue);
      } else {
        outputElement.val('');
      }
    });

    function makeAjaxRequest(value) {
      const url = "<?=base_admin();?>modul/jadwal_menguji/nilai_huruf.php";

      $.ajax({
        url: url,
        data : {nilai:value,kode_jurusan:<?=$mhs_data->jur_kode;?>,angkatan : <?=$mhs_data->mulai_smt;?>},
        type: 'POST',
        success: function(response) {
          outputElement.val(response);
        },
        error: function() {
          console.error('AJAX request failed');
        }
      });
    }
  });



$('.close_main').click(function() {
    location.reload();
});


$(document).on("click", ".edit-bukti", function() {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran/mahasiswa/edit_dokumen.php",
            type : "post",
            data :{id_bukti : id},
            success: function(data) {
                $("#isi_dosen").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_edit_dokumen').modal({ keyboard: false,backdrop:'static' });
});

          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });


$("#edit_nilai").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
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
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
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
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                            $('#modal_jadwal_sidang').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                dtb_jadwal_sidang.draw();
                            });
                          }
                    });
                }

            });
        }
    });
</script>
