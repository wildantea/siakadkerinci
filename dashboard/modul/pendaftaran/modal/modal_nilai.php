<?php
include "../../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,mulai_smt,tb_data_pendaftaran.kode_jurusan,tb_data_pendaftaran.id_semester,tb_data_pendaftaran.ket_ditolak,ket_ajuan_ulang, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,jurusan as nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.* from view_simple_mhs_data 
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
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
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$mhs_data->nama_jurusan;?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Nama</strong></td>
          <td><?=$mhs_data->nama;?> </td>
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

      </tbody>

    </table>
 <form method="post" class="form-horizontal" id="change_status" action="<?=base_admin();?>modul/pendaftaran/modal/up_nilai.php">
  <table id="dtb_syarat" class="table table-bordered table-striped display responsive nowrap" width="100%">
      <thead>
          <tr>
            <th>Matakuliah</th>
          </tr>
      </thead>
      <tbody>
        <?php
        //matkul_syarat
        $matkul = $db2->fetchCustomSingle("select matkul.kode_mk,id_krs_detail,bobot,matkul.nama_mk,nama_kurikulum,nilai_angka,nilai_huruf from  matkul inner join krs_detail on matkul.id_matkul=krs_detail.kode_mk
inner join kurikulum using(kur_id)
where nim='$mhs_data->nim' and id_semester='$mhs_data->id_semester'");
        $bobot = $matkul->nilai_huruf."#".$matkul->bobot;
        ?>
        <th><?=$matkul->nama_kurikulum.' - '.$matkul->kode_mk.' - '.$matkul->nama_mk;?></th>
      </tbody>
  </table>
<input type="hidden" name="id_pendaftaran" value="<?=$mhs_data->id_pendaftaran;?>">
            <div class="form-group">
                <label for="nim" class="control-label col-lg-3">Nilai Angka</label>
                <div class="col-lg-2">
<input type="text" name="nilai_angka" value="<?=$matkul->nilai_angka;?>" onkeydown="return onlyNumber(event,this,true,false)" class="form-control nilai_angka" maxlength="5" size="5" onblur="set_nilai_huruf(this,<?=$mhs_data->kode_jurusan;?>)" required>
                </div>
              </div><!-- /.form-group -->
              <input type="hidden" name="id" value="<?=$matkul->id_krs_detail;?>">
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-3">Nilai Huruf</label>
                        <div class="col-lg-2">
        <select name='nilai_huruf' class='form-control nilai_huruf' required>
          <option value=''></option>
          <?php
$where_berlaku = "";
if ($mhs_data->mulai_smt>20202) {
      $where_berlaku = "and berlaku_angkatan='".$where_berlaku."'";
}
              $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? $where_berlaku",array('kode_jurusan' => $mhs_data->kode_jurusan));
    foreach ($skala_nilai as $skala) {
      $array_skala[$skala->nilai_huruf."#".$skala->nilai_indeks] = $skala->nilai_huruf." (".$skala->nilai_indeks.")";
    }
foreach ($array_skala as $id_skala => $skala_bobot) {
  if ($bobot==$id_skala) {
    echo "<option value='$id_skala' selected>$skala_bobot</option>";
  } else {
    echo "<option value='$id_skala'>$skala_bobot</option>";
  }
  
}
          ?>
              </select>
                        </div>
            </div>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>

                  </div>
                </div>
              </div><!-- /.form-group -->
</form>

<div class="callout callout-info">
                <h4>Keterangan :</h4>

               Silakan Input Nilai Akhir Hasil dari Seminar Mahasiswa</p>
              </div>

<script type="text/javascript">
function set_nilai_huruf(t,kode_jurusan) {
            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/nilai/get_nilai_huruf.php",
            data : {nilai:t.value,kode_jurusan:kode_jurusan,angkatan : <?=$mhs_data->mulai_smt;?>},
            success : function(data) {
                $('.nilai_huruf').html(data);
              }
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

    $("#change_status").validate({
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
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
            } else {
                error.insertAfter(element);
            }
        },
 rules: {
            
          penanggung_jawab: {
          required: true,
          //minlength: 2
          }
        },
         messages: {
            
          penanggung_jawab: {
          required: "Pilih Penanggung Jawab",
          //minlength: "Your username must consist of at least 2 characters"
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
                            $("#modal_pendaftaran").modal( 'hide' ).data( 'bs.modal', null );
                            $('.error_data').hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                              dtb_pendaftaran.draw(false);
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
