
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Manage Pengelolahan Sesi WIsuda
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pengelolahan-sesi-wisuda">Pengelolahan Sesi WIsuda</a></li>
                        <li class="active">Pengelolahan Sesi WIsuda List</li>
                    </ol>
                </section>

                <!-- Main content -->

<!-- Main content -->
<section class="content-body">
  <div class="container-fluid">
    <div class="box-body">
<?php
    $nim = $_SESSION['username'];
    $tgl=date('Y-m-d');
    $check_ta = $db->check_exist('tugas_akhir',array('nim'=>$nim,'status_ta' => 3));
    $check_wisuda = $db->check_exist('detail_wisuda',array('nim'=>$nim));
    //$check_priode = $db->query("select * from kelola_wisuda where tanggal=<'$tgl'");
    if($check_ta > 0 AND $check_wisuda > 0){
      $data = $db->query("select *,ta.status_ta, kw.id_wisuda,ta.nim,m.nama,f.nama_resmi,j.nama_jur,ta.id_ta 
        from detail_wisuda kw inner join mahasiswa m on m.nim=kw.nim
        inner join kelola_wisuda wk on wk.id_wisuda=kw.id_wisuda 
        inner join tugas_akhir ta on ta.nim=kw.nim 
        inner join fakultas f on ta.kode_fak=f.kode_fak 
        inner join jurusan j on ta.kode_jurusan=j.kode_jur where kw.nim=?",array("kw.nim" => $nim));
      foreach ($data as $dt){
?>
      <div class="alert alert-info" role="alert">
        <article>
          <strong>Perhatian!</strong> Pastikan data yang sudah anda masukan sudah benar
        </article>
      </div>
      <div class="panel panel-success panel-xs">
        <div class="panel-heading">
          <h2 align="center">Anda Telah Terdaftar</h2>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table" border="0" width="100%" margin="5px">
              <tr>
                <div class="form-group">
                  <td>
                    <a data-id='<?=$dt->id_detail?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
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
              <tr>
                <td>
                  <label for="jurusan" class="label-control col-lg-1">Priode</label>
                  <div class="col-lg-11">
                    <input class="form-control" type="text" name="jurusan" value="<?=$dt->priode?>" readonly>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="jurusan" class="label-control col-lg-1">Tanggal</label>
                  <div class="col-lg-11">
                    <input class="form-control" type="text" name="jurusan" value="<?=$dt->tanggal?>" readonly>
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
      $check = $db->check_exist('tugas_akhir',array('nim'=>$nim,"status_ta" => 3));
     
      if($check > 0){
        $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
        inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $nim));
        foreach ($data as $dt) {
?>
      <form id="input_pengelolahan_sesi_wisuda" class="form-horizontal" action="<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_action.php?act=in_mhs" method="post">
        <div class="form-group">
          <label for="nim" class="control-label col-lg-2">NIM</label>
          <div class="col-lg-6">
            <input id="nim" type="text" name="nim" class="form-control" value="<?=$nim?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_fakultas" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
          <div class="col-lg-6">
            <select name="kode_fak" class="form-control chzn-select" tabindex="2" readonly>
                <option name="kode_fak" value="<?=$dt->kode_fak;?>"><?=$dt->nama_resmi;?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="kode_jurusan" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
          <div class="col-lg-6">
            <select name="kode_jurusan" class="form-control chzn-select" tabindex="2" readonly>
                <option name="kode_jurusan" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-6">
              <select name="id_wisuda" data-placeholder="Pilih priode..." class="form-control chzn-select" tabindex="2" required>
               <option value="all">Semua</option>
               <?php foreach ($db->query("select * from kelola_wisuda kw where kw.tgl_awal <= NOW() AND kw.tgl_akhir >= NOW()") as $isi) {
                  echo "<option value='$isi->id_wisuda'>$isi->priode</option>";
               } ?>
              </select>
          </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <div class="col-lg-2 col-lg-offset-6">
            <button type="submit" class="btn btn-primary form-control">Daftar</button>
          </div>
        </div>
      </form>
<?php
      }
    } else{
?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Perhatian!</strong> Anda belum lulus tugas akhir.
        </article>
      </div>
<?php      
    }
  }
?>
    </div><!-- /.box-body -->
  </div>

    <div class="modal" id="modal_wisuda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pengelolahan Sesi WIsuda</h4> </div> <div class="modal-body" id="isi_wisuda"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->

<script type="text/javascript">
  $("#nim").on('input',function(){

        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/get_jurusan_fakultas.php",
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
          url : "<?=base_admin();?>modul/pengelolahan_sesi_wisuda/pengelolahan_sesi_wisuda_edit.php",
          type : "post",
          data : {id_data:id},
          success: function(data) {
              $("#isi_wisuda").html(data);
              $("#loadnya").hide();
        }
      });

    $('#modal_wisuda').modal({ keyboard: false,backdrop:'static' });

  });
  

$(document).ready(function() {

  //chosen select
  $(".chzn-select").chosen();
  $(".chzn-select-deselect").chosen({
      allow_single_deselect: true
  });



  //trigger validation onchange
  $('select').on('change', function() {
      $(this).valid();
  });
        
    $("#tgl1").datepicker( {
        format: "yyyy-mm-dd",
    });
    
    $("#input_pengelolahan_sesi_wisuda").validate({
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
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_pengelolahan_sesi_wisuda").serialize(),
                success: function(data) {
                    $("#loadnya").hide();
                    if(data == "good") {
                      $(".notif_top").fadeIn(1000);
                      $(".notif_top").fadeOut(1000, function() {
                        location.reload();
                      });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
            