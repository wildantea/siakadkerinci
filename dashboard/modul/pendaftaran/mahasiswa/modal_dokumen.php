<?php
include "../../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak,tb_data_pendaftaran.ket_ajuan_ulang, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.*,   
(select group_concat(nip_dosen_pembimbing separator '#')
 from tb_data_pendaftaran_pembimbing where tb_data_pendaftaran_pembimbing.id_pendaftaran=tb_data_pendaftaran.id_pendaftaran order by pembimbing_ke asc) as pembimbing
from view_simple_mhs
INNER JOIN tb_data_pendaftaran USING(nim)
INNER join tb_data_pendaftaran_jenis_pengaturan USING(id_jenis_pendaftaran_setting)
inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
WHERE tb_data_pendaftaran.id_pendaftaran=?",array('id_pendaftaran' => $_POST['id_pendaftaran']));


echo $db2->getErrorMessage();
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
          <td colspan="3"><?=$mhs_data->nama;?> </td>
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

  if ($mhs_data->ada_pembimbing=='Y') {
        $nama_dosen_pembimbing = array_map('trim', explode('#', $mhs_data->pembimbing));
        foreach ($nama_dosen_pembimbing as $nomor => $dosen) {
          $nama_dosen = $db->fetch_single_row("view_nama_gelar_dosen","nip",$dosen);
          ?>
           <tr>
          <td class="info2"><strong>Pembimbing <?=($nomor+1);?></strong></td>
          <td colspan="3"><?=$nama_dosen->nama_gelar;?> </td>
          </tr>
          <?php
        }
        
  }

  if ($mhs_data->status==2) {
    ?>
          <tr>
          <td class="info2 text-red"><strong>ALASAH DITOLAK</strong></td>
          <td colspan="3"><?=$mhs_data->ket_ditolak;?> </td>
        </tr>
    <?php
  }
        ?>

      </tbody>

    </table>

<?php

if ($mhs_data->bukti!='Y') {
  ?>
  <?php
?>
<h4>Dokumen Syarat Pendaftaran <?=$mhs_data->nama_jenis_pendaftaran;?></h4>
  <table id="dtb_syarat" class="table table-bordered table-striped display responsive nowrap" width="100%">
      <thead>
          <tr>
            <th>Nama Dokumen</th>
            <th>File</th>
            <th>Act</th>
          </tr>
      </thead>
      <tbody>
      </tbody>
  </table>

<script type="text/javascript">
  var dtb_syarat = $("#dtb_syarat").DataTable({
              
        searching : false,
        "paging": false,
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
            },
      
              
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/pendaftaran/mahasiswa/modal_data.php',
            type: 'post',  // method  , by default get
            data : {id_jenis_bukti : "<?=$mhs_data->bukti;?>",nama_directory : "<?=$mhs_data->nama_directory;?>",id_jenis_pendaftaran_setting:<?=$mhs_data->id_jenis_pendaftaran_setting;?>,id_pendaftaran:<?=$_POST['id_pendaftaran'];?>},
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "initComplete": function () {
                var api = this.api();
                api.$("a.bukti-dokumen").fancybox({
                  autoSize : false,
                  fitToView: false, // 
                  maxWidth : '90%',
                   mouseWheel : false,
                  loop : false,
                  helpers : {
                    title : {
                      type: 'outside'
                    },
                    thumbs  : {
                      width : 50,
                      height  : 50
                    }
                  }
                });
/*              api.$('td').click( function () {
                  api.search( this.innerHTML ).draw();
              } );*/
          }
        });
</script>

<?php
}

?>

<?php
if ($mhs_data->status==2) {
  ?>
 <form method="post" class="form-horizontal" id="change_status" action="<?=base_admin();?>modul/pendaftaran/pendaftaran_action.php?act=ajukan_ulang">

              <div class="form-group">
                <label for="nim" class="control-label col-lg-3">Keterangan Ajuan Ulang</label>
                <div class="col-lg-9">
                  <input type="text" name="ket_ajuan_ulang" class="form-control" value="<?=$mhs_data->ket_ajuan_ulang;?>">
                </div>
              </div><!-- /.form-group -->
              <input type="hidden" name="id" value="<?=$mhs_data->id_pendaftaran;?>">
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ajukan Ulang</button>

                  </div>
                </div>
              </div>
</form>
 <?php
}
?>

<div class="modal right fade" id="modal_edit_dokumen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"><button type="button" class="close close-edit-dokumen" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Ganti Dokumen</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

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
