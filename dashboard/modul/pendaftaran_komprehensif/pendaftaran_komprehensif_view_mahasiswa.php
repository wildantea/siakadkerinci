<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Pendaftaran Komprehensif
    </h1>
        <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>pendaftaran-komprehensif">Pendaftaran Komprehensif</a></li>
        <li class="active">Pendaftaran Komprehensif</li>
    </ol>
</section>

<!-- Main content -->
<section class="content-body">
  <div class="container-fluid">
    <div class="box-body table-responsive">
<?php
    $nim = $_SESSION['username'];
    $check = $db->check_exist('kompre',array('nim'=>$nim));
    
    $batas_sks = $db->fetch_all('batas_sks');
    foreach ($batas_sks as $ky) {
      if($ky->ket == 'kompre'){ 
        $sks = $ky->jlm_sks;
      }
    }
    $check_sks = $db->query("SELECT SUM(sks) as total_sks FROM krs_detail WHERE nim='$nim' group by kode_mk");
    $total_sks =0; 
    foreach ($check_sks as $key) {
      $total_sks = $total_sks + $key->total_sks;
    }

    $priode_kompre = $db->query("select priode_kompre from jadwal_kompre kw where kw.batas_awal <= NOW() AND kw.batas_akhir >= NOW()");

    $pm = '';
    foreach ($priode_kompre as $ky) {
      $pm = $ky->priode_kompre;
    }

    if($sks <= $total_sks && $pm != false){
      //cek apakah data sudah ada atau belum
      if($check != false){
        $data = $db->query("select k.nim,m.nama,f.nama_resmi,j.nama_jur,k.id from kompre k
        inner join mahasiswa m on k.nim=m.nim
        inner join fakultas f on k.kode_fak=f.kode_fak 
        inner join jurusan j on k.kode_jurusan=j.kode_jur where m.nim=?",array("cm.nim" => $nim));
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
                  <div class="form-group">
                    <td>
                      <label for="fakultas" class="label-control col-lg-1">Fakultas</label>
                      <div class="col-lg-11">
                        <input class="form-control" type="text" name="fakultas" value="<?=$dt->nama_resmi?>" readonly>
                      </div>
                    </td>
                  </div>
                </tr>  
                <tr>
                  <div class="form-group">
                    <td>
                      <label for="jurusan" class="label-control col-lg-1">Jurusan</label>
                      <div class="col-lg-11">
                        <input class="form-control" type="text" name="jurusan" value="<?=$dt->nama_jur?>" readonly>
                      </div>
                    </td>
                  </div>
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
        <form id="edit_pendaftaran_ppl" method="post" enctype="multipart/form-data" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_action.php?act=in_mhs">
                    
          <div class="form-group">
            <label for="nim" class="control-label col-lg-2">NIM</label>
            <div class="col-lg-10">
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
            <label for="priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
                <select name="priode_kompre" data-placeholder="Pilih priode..." class="form-control chzn-select" tabindex="2" required>
                 <?php foreach ($db->query("select * from jadwal_kompre kw where kw.batas_awal <= NOW() AND kw.batas_akhir >= NOW()") as $isi) {
                    echo "<option value='$isi->id_kompre' selected>$isi->priode_kompre</option>";
                 } ?>
                </select>
            </div>
          </div><!-- /.form-group -->  

          <div class="form-group">
            <div class="col-lg-12">
              <div class="modal-footer"> <button type="submit" class="btn btn-primary">Daftar Komprehensif</button>
              </div>
            </div>
          </div><!-- /.form-group -->

        </form>
<?php
      }
    } 
  }else {
    if($pm == false){
?>
        <div class="alert alert-danger" role="alert">
          <article>
            <strong>Perhatian!</strong> Belum memasuki priode Komprehensif
          </article>
        </div>            
<?php
    }else{
?>
        <div class="alert alert-danger" role="alert">
          <article>
            <strong>Perhatian!</strong> Jumlah sks anda tidak mencukup batas minimal <strong><?=$sks?> sks untuk pendaftaran Komprehensif </strong>, sedangkan sks anda baru mencarapai <strong><?=$total_sks?> sks</strong>
          </article>
        </div>
<?php      
    }
  }
?>
    </div><!-- /.box-body -->
  </div>

    <!-- Modal Tambah Data -->
    <div class="modal" id="modal_pendaftaran_komprehensif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edit Pendaftaran Komprehensif</h4> </div> <div class="modal-body" id="isi_pendaftaran_komprehensif"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

</section><!-- /.content -->

<script type="text/javascript">
    $("#nim").on('input',function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_komprehensif/get_fakultas_jurusan.php",
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
            url : "<?=base_admin();?>modul/pendaftaran_komprehensif/pendaftaran_komprehensif_edit_mahasiswa.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_komprehensif").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_pendaftaran_komprehensif').modal({ keyboard: false,backdrop:'static' });

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