<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Pendaftaran PPL Mahasiswa
    </h1>
        <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>pendaftaran-ppl">Pendaftaran Ppl</a></li>
        <li class="active">Pendaftaran Ppl Mahasiswa</li>
    </ol>
</section>

<!-- Main content -->
<section class="content-body">
  <div class="container-fluid">
    <div class="box-body table-responsive">
<?php
    $nim = $_SESSION['username'];
    $check = $db->check_exist('ppl',array('nim'=>$nim));
    
    $batas_sks = $db->fetch_all('batas_sks');
    foreach ($batas_sks as $ky) {
      if($ky->ket == 'ppl'){
        $sks = $ky->jlm_sks; 
      }
    }
    $check_sks = $db->query("SELECT SUM(sks) as total_sks FROM krs_detail left JOIN krs ON krs_detail.id_krs=krs.krs_id WHERE krs_detail.nim='$nim' and krs_detail.batal='0' ");
    foreach ($check_sks as $key) {
      $total_sks = $key->total_sks;
    }

    if($sks <= $total_sks){
      //cek apakah data sudah ada atau belum
      if($check != false){
        $data = $db->query("select p.judul_kp,p.nama_perusahaan,p.nim,m.nama,f.nama_resmi,j.nama_jur,p.id from ppl p inner join mahasiswa m on p.nim=m.nim inner join fakultas f on p.kode_fak=f.kode_fak inner join jurusan j on p.kode_jurusan=j.kode_jur where m.nim=?",array("cm.nim" => $nim));
        foreach ($data as $dt){
?>
        <div class="alert alert-info" role="alert">
          <article>
            <strong>Perhatian!</strong> Pastikan data yang sudah anda masukan sudah benar
          </article>
        </div>
        <div class="panel panel-success panel-xs">
          <div class="panel-heading">
            <h2 align="center">Anda Telah Mengajukan Permohonan</h2>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table" border="0" width="100%" margin="5px">
                <tr>
                  <div class="form-group">
                    <td>
                      <a data-id='<?=$dt->id?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
                    </td>
                  </div>
                </tr>
                <tr>
                  <div class="form-group">
                    <td>
                      <label for="nim" class="label-control col-lg-1">Nim</label>
                      <div class="col-lg-11">
                        <input class="form-control" type="text" name="nim" value="<?=$dt->nim?>" readonly>
                      </div>
                    </td>
                  </div>
                </tr>
                <tr>
                  <div class="form-group">
                    <td>
                      <label for="nama" class="label-control col-lg-1">Nama</label>
                      <div class="col-lg-11">
                        <input class="form-control" type="text" name="nama" value="<?=$dt->nama?>" readonly>
                      </div>
                    <td>
                  </div>
                </tr>
                <tr>
                  <td>
                    <label for="jurusan" class="label-control col-lg-1">Judul</label>
                    <div class="col-lg-11">
                      <input class="form-control" type="text" name="jurusan" value="<?=$dt->judul_kp?>" readonly>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="Perusahaan" class="label-control col-lg-1">Perusahaan</label>
                    <div class="col-lg-11">
                      <input class="form-control" type="text" name="nama_perusahaan" value="<?=$dt->nama_perusahaan?>" readonly>
                      </textarea>
                    </div>
                  </td>
                </tr>           
              </table>
            </div>
          </div>
        </div>
<?php
        }
      } else{
          $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
          inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $nim));
          foreach ($data as $dt) {
?>
        <form id="edit_pendaftaran_ppl" method="post" enctype="multipart/form-data" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_action.php?act=in_mhs">
                    
          <div class="form-group">
            <label for="nim" class="control-label col-lg-2">NIM</label>
            <div class="col-lg-6">
              <input id="nim" type="text" name="nim" class="form-control" value="<?=$nim?>" readonly>
            </div>
          </div>
          <div class="form-group">
            <label for="kode_fakultas" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <select name="kode_fak" class="form-control" tabindex="2" readonly>
                  <option name="kode_fak" value="<?=$dt->kode_fak;?>"><?=$dt->nama_resmi;?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="kode_jur" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <select name="kode_jur" class="form-control" tabindex="2" readonly>
                  <option name="kode_jur" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="judul_kp" class="control-label col-lg-2">Judul PPL <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input type="text" name="judul_kp" placeholder="Judul PPL" class="form-control" required>
            </div>
          </div><!-- /.form-group -->
                
          <div class="form-group">
            <label for="nama_perusahaan" class="control-label col-lg-2">Tempat PPL <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <input type="text" name="nama_perusahaan" placeholder="Nama Perusahaan" class="form-control" required>
            </div>
          </div><!-- /.form-group -->
                
          <div class="form-group">
              <label for="alamat_perusahaan" class="control-label col-lg-2">Alamat Tempat PPL <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="alamat_perusahaan"></textarea>
              </div>
          </div><!-- /.form-group -->
            
          <div class="form-group">
            <div class="col-lg-12">
              <div class="modal-footer"> <button type="submit" class="btn btn-primary">Daftar PPL</button>
              </div>
            </div>
          </div><!-- /.form-group -->

        </form>
<?php
      }
    } 
  }else {
?>
        <div class="alert alert-danger" role="alert">
          <article>
            <strong>Perhatian!</strong> Jumlah sks anda tidak mencukup batas minimal <strong><?=$sks?> sks </strong>, sedangkan sks anda baru mencarapai <strong><?=$total_sks?> sks</strong>
          </article>
        </div>
<?php      
    }
?>
    </div><!-- /.box-body -->
  </div>

    <!-- Modal Tambah Data -->
    <div class="modal" id="modal_pendaftaran_ppl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edit Pendaftaran Ppl</h4> </div> <div class="modal-body" id="isi_pendaftaran_ppl"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

</section><!-- /.content -->

<script type="text/javascript">
    $("#nim").on('input',function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/list_cuti_mahasiswa/get_fakultas_jurusan.php",
          data : {nim:this.value},
          success : function(data) {
              $("#form_civitas").html(data);
              $("#form_civitas").trigger("chosen:updated");

          }
      });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_ppl/pendaftaran_ppl_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_ppl").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_ppl').modal({ keyboard: false,backdrop:'static' });

    });

    $(document).ready(function() {
    
    $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
        allow_single_deselect: true
    });



    //trigger validation onchange
    $('select').on('change', function() {
        $(this).valid();
    });
    //hidden validate because we use chosen select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#tgl1").datepicker({
      format: "yyyy-mm-dd",
    });
    
    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
    });

    $("#edit_pendaftaran_ppl").validate({
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
            
          nim: {
          required: true,
          //minlength: 2
          },
        
          jenis_keluar: {
          required: true,
          //minlength: 2
          },
        
          tgl_keluar: {
          required: true,
          //minlength: 2
          }
        
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jenis_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          tgl_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          }
        
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_pendaftaran_ppl").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_ppl').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
                          location.reload();
                        });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        //$(".errorna").fadeIn();
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, 
                        function() {
                          location.reload();
                        });
                    }
                }
            });
        }
    });
});
</script>