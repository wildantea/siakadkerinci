<?php
include "../../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,jurusan as nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.*,tanggal_ujian,jam_mulai,jam_selesai,tempat,id_ruang,
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
            $ruangan = $db->fetch_custom_single("select * from view_ruang_prodi");
            $tempat = getRuang($mhs_data->id_ruang);
          }
          ?>
          <td colspan="3"><?=$ruangan->nm_gedung.' - Ruang : '.$ruangan->nm_ruang;?> </td>
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
          ?>
           <tr>
          <td class="info2"><strong>Penguji <?=($index+1);?></strong></td>
          <td colspan="3"><?=$dosen_uji;?> </td>
          </tr>
          <?php
        }
        ?>
         
        <?php
        }
          ?>
      </tbody>
</table>

  <!-- Add fancyBox - thumbnail helper (this is optional) -->
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.css?v=2.1.7" />
  <script type="text/javascript" src="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.js?v=2.1.7"></script>

<script type="text/javascript">

$("#status").change(function(){
  console.log(this.value);
  if (this.value==2) {
    $('.alasan-ditolak').show();
    $(".isi-ditolak").prop('required',true);
  } else {
    $('.alasan-ditolak').hide();
    $(".isi-ditolak").prop('required',false);
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
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                              $("#modal_pendaftaran").modal( 'hide' ).data( 'bs.modal', null );
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
