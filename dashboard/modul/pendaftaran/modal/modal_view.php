<?php
include "../../../inc/config.php";
$mhs_data = $db2->fetchCustomSingle("SELECT tb_data_pendaftaran.id_pendaftaran,tb_data_pendaftaran.ket_ditolak,ket_ajuan_ulang, tb_data_pendaftaran.status,tb_data_pendaftaran.nim,nama,judul,jurusan as nama_jurusan,angkatan,tb_data_pendaftaran_jenis.nama_jenis_pendaftaran,nama_directory,tb_data_pendaftaran_jenis_pengaturan.* from view_simple_mhs_data 
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
 <form method="post" class="form-horizontal" id="change_status" action="<?=base_admin();?>modul/pendaftaran/pendaftaran_action.php?act=change_status">
<?php
if ($mhs_data->bukti!='') {
  ?>
  <?php
?>
<h4>Dokumen Syarat Pendaftaran <?=$mhs_data->nama_jenis_pendaftaran;?></h4>
  <table id="dtb_syarat" class="table table-bordered table-striped display responsive nowrap" width="100%">
      <thead>
          <tr>
            <th>Nama Dokumen</th>
            <th>File</th>
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
              url :'<?=base_admin();?>modul/pendaftaran/modal/modal_data.php',
            type: 'post',  // method  , by default get
            data : {id_jenis_bukti : "<?=$mhs_data->bukti;?>",nama_directory : "<?=$mhs_data->nama_directory;?>",nim:"<?=$mhs_data->nim;?>",id_jenis_pendaftaran_setting:"<?=$mhs_data->id_jenis_pendaftaran_setting;?>",id_pendaftaran:"<?=$_POST['id_pendaftaran'];?>"},
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
          "initComplete": function () {
                var api = this.api();
                api.$("a.bukti-dokumen").fancybox({
                  autoSize : false,
                  width : 1208,
                  loop : false,
                  mouseWheel : false,
                  preload   : false,

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
     
<input type="hidden" name="id_pendaftaran" value="<?=$mhs_data->id_pendaftaran;?>">
            
            <div class="form-group">
                        <label for="Ruangan" class="control-label col-lg-3">Status</label>
                        <div class="col-lg-9">
        <select name="status" id="status_pendaftaran" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="" >
          <?php
          $array_status = array(1 => 'Sudah Acc',0 => 'Belum Acc',2 => 'Ditolak', 3 => 'Tidak Lulus',4 => 'Selesai/Lulus',5 => 'Diajukan Ulang');
          foreach ($array_status as $status => $label) {
            if ($mhs_data->status==$status) {
              echo "<option value='$status' selected>$label</option>";
            } else {
              echo "<option value='$status'>$label</option>";
            }
            
          }
          ?>
              </select>
                        </div>
            </div>
              <div class="form-group alasan-ditolak" <?=($mhs_data->status==2 or $mhs_data->status==5)?'':'style="display: none"';?>>
                <label for="nim" class="control-label col-lg-3">Alasan Ditolak</label>
                <div class="col-lg-9">
                  <input type="text" name="ket_ditolak" placeholder="Isi kenapa ditolak, sehingga mahasiswa bisa memperbaikti" class="form-control isi-ditolak" value="<?=$mhs_data->ket_ditolak;?>">
                </div>
              </div><!-- /.form-group -->
              <?php
              if ($mhs_data->status==5) {
                ?>
              <div class="form-group">
                <label for="nim" class="control-label col-lg-3">Keterangan Ajuan Ulang oleh Mahasiswa</label>
                <div class="col-lg-9">
                  <input type="text" name="ket_ajuan_ulang" class="form-control" value="<?=$mhs_data->ket_ajuan_ulang;?>" >
                </div>
              </div><!-- /.form-group -->
              <?php
              }
              ?>
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
                <h4>Keterangan Status :</h4>

                <p>1. Sudah Acc : Gunakan status ini jika pendaftaran sudah memenuhi persyaratan<br>
                  2. Belum Acc : Status Default data pendaftaran Mahasiswa<br>
                  3. Tolak : Tolak Pendaftaran Mahasiswa jika ada kekurangan/salah syarat dll, sertakan keterangan admin untuk diketahui mahasiswa<br>
                  4 : Tidak Lulus : Gunakan status ini jika mahasiswa gagal / tidak lulus sidang/seminar <br>
                5 : Selesai/Lulus : Gunakan status ini jika pendaftaran telah selesai atau mahasiswa Lulus dalam Sidang / Seminar</p>
              </div>

<div class="modal right fade" id="modal_list_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><button type="button" class="close close-dosen-pengajar" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Pilih Dosen Pengajar</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

  <!-- Add fancyBox - thumbnail helper (this is optional) -->
  <link rel="stylesheet" type="text/css" href="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.css?v=2.1.7" />
  <script type="text/javascript" src="<?=base_admin();?>assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.js?v=2.1.7"></script>

<script type="text/javascript">



$("#status_pendaftaran").change(function(){
  console.log(this.value);
  if (this.value==2) {
    $('.alasan-ditolak').show();
    $(".isi-ditolak").prop('required',true);
  } else {
    $('.alasan-ditolak').hide();
    $(".isi-ditolak").prop('required',false);
  }

});

    $("#add_dosen").on('click',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/jadwal/modal_dosen_pengajar.php",
            type : "post",
            success: function(data) {
                $("#isi_dosen").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_list_dosen').modal({ keyboard: false,backdrop:'static' });

    });

    $(document).ready(function() {

      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });

      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });


$(".close-dosen-pengajar").on("click",function(){
  $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
});
});

$('.close_main').click(function() {
    location.reload();
});


    function pilih_dosen(id_dosen){
        // $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas_jadwal/jadwal/jadwal_action.php?act=pilih_dosen',
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
