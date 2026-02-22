<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Daftar Cuti Mahasiswa
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>list-cuti-mahasiswa">Kemahasiswaan</a></li>
        <li class="active">Pendaftaran Cuti Mahasiswa</li>
    </ol>
</section>

<!-- Main content -->
<section class="content-body">
  <div class="container-fluid">
    <div class="box-body table-responsive">
<?php
    $nim = $_SESSION['username'];
    $check = $db->check_exist('cuti_mahasiswa',array('nim'=>$nim));
    $check_reg = $db->check_exist('mhs_registrasi',array('nim'=>$nim));
    
    $sem_akv = $db->query("SELECT id_semester FROM semester ORDER BY id_semester DESC LIMIT 1");
    foreach ($sem_akv as $akv) {
      $semester_aktiv = $akv->id_semester;
    }
    
    $sem_reg = $db->query("SELECT sem_id FROM mhs_registrasi WHERE nim='$nim' ORDER BY sem_id DESC LIMIT 1");
    foreach ($sem_reg as $reg) {
      $semester_registrasi = $reg->sem_id;
    }

    //cek apakah data sudah ada atau belum
    if($check != false AND $check_reg != false AND $semester_registrasi == $semester_aktiv){
      $data = $db->query("select *,cm.nim,m.nama,f.nama_resmi,j.nama_jur,jk.ket_keluar,cm.file_sk,cm.id_cuti from cuti_mahasiswa cm inner join mahasiswa m on cm.nim=m.nim 
        inner join jenis_keluar jk on cm.jenis_keluar=jk.id_jns_keluar 
        inner join fakultas f on cm.kode_fak=f.kode_fak 
        inner join jurusan j on cm.kode_jur=j.kode_jur where cm.nim=?",array("cm.nim" => $nim));
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
                    <a data-id='<?=$dt->id_cuti?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
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
                  <label for="jurusan" class="label-control col-lg-1">Jurusan</label>
                  <div class="col-lg-11">
                    <input class="form-control" type="text" name="jurusan" value="<?=$dt->nama_jur?>" readonly>
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
      <form id="input_list_cuti_mahasiswa" method="post" enctype="multipart/form-data" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/list_cuti_mahasiswa/list_cuti_mahasiswa_action.php?act=in_mhs">
                  
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
            <label for="jenis_keluar" class="control-label col-lg-2">Jenis Keluar <span style="color:#FF0000">*</span></label>
            <div class="col-lg-10">
              <select name="jenis_keluar" data-placeholder="Pilih Jenis Keluar ..." class="form-control" tabindex="2" required readonly>
                <option value=""></option>
                <?php
                  foreach ($db->fetch_all('jenis_keluar') as $isi) {
                    if($isi->id_jns_keluar == '9'){
                      echo "<option value='$isi->id_jns_keluar' selected>$isi->ket_keluar</option>";
                    }
                  }
                ?>
              </select>
            </div>
        </div><!-- /.form-group -->
          
        <div class="form-group">
          <div class="col-lg-12">
            <div class="modal-footer"> <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
            </div>
          </div>
        </div><!-- /.form-group -->

      </form>
<?php
    }
  }
?>
    </div><!-- /.box-body -->
  </div>

     <div class="modal" id="modal_list_cuti_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edist List Cuti Mahasiswa</h4> </div> <div class="modal-body" id="isi_list_cuti_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

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
            url : "<?=base_admin();?>modul/list_cuti_mahasiswa/list_cuti_mahasiswa_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_list_cuti_mahasiswa").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_list_cuti_mahasiswa').modal({ keyboard: false,backdrop:'static' });

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

    $("#input_list_cuti_mahasiswa").validate({
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
                data: $("#input_list_cuti_mahasiswa").serialize(),
                success: function(data) {
                    $('#modal_list_cuti_mahasiswa').modal('hide');
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