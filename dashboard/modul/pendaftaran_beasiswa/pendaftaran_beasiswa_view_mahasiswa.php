<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Daftar Pendaftaran Beasiswa
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>pendaftaran-beasiswa">Kelulusan</a></li>
        <li class="active">Daftar Pendaftaran Beasiswa</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
<?php
  $priode_beasiswa = $db->query("select priode_beasiswa from beasiswa kw where kw.batas_awal <= NOW() AND kw.batas_akhir >= NOW()");

  $pm = '';
  foreach ($priode_beasiswa as $key) {
    $pm = $key->priode_beasiswa;
  }
  if($pm == false){
?>
  <div class="alert alert-info" role="alert"><strong>Perhatian!</strong> Belum Memasuki Masa Beasiswa</div>
<?php
  } else{
    $nim = $_SESSION['username'];
    $check = $db->check_exist('beasiswa_mhs',array('nim_beasiswamhs'=>$nim));
    if($check > 0){
      $data = $db->query("select *,bm.id_beasiswamhs,bm.nim_beasiswamhs,mhs.nama,j.nama_jur,b.nama_beasiswa from beasiswa_mhs bm 
      inner join beasiswa b on bm.id_beasiswa=b.id_beasiswa
      inner join mahasiswa mhs on bm.nim_beasiswamhs=mhs.nim 
      inner join fakultas f on bm.kode_fak=f.kode_fak
      inner join jurusan j on bm.kode_jur=j.kode_jur where bm.nim_beasiswamhs=?",array("bm.nim_beasiswamhs" => $nim));
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
                    <a data-id='<?=$dt->id_beasiswamhs?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
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
                  <label for="judul" class="label-control col-lg-1">Beasiswa</label>
                  <div class="col-lg-11">
                    <textarea class="form-control" name="judul" height="300" readonly><?=$dt->nama_beasiswa?></textarea>
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
?>
      <form id="input_pendaftaran_beasiswa" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=in_mhs" method="post">
        <div class="form-group">
          <label for="nim" class="control-label col-lg-1">NIM <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
            <input id="nim" type="text" name="nim_beasiswamhs" class="form-control" value="<?=$_SESSION['username']?>" readonly>
          </div>
        </div>
<?php
      $akm = $db->query("select * from akm am inner join mahasiswa m on am.mhs_nim=m.nim where mhs_nim=".$_SESSION['username']." order by mhs_nim desc limit 1");
      foreach ($akm as $am) {
?>
      <div class="form-group">
        <label for="nama" class="control-label col-lg-1">Nama <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <input id="nama" type="text" name="nama" class="form-control" value="<?=$am->nama?>" readonly>
        </div>
      </div>
      <div class="form-group">
        <label for="ipk" class="control-label col-lg-1">IPK <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <input id="ipk_beasiswamhs" type="text" name="ipk_beasiswamhs" class="form-control" value="<?=$am->ipk?>" readonly>
        </div>
      </div>
<?php
      }
      $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
      inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $_SESSION['username']));
      foreach ($data as $dt) {
?>
      <div class="form-group">
        <label for="kode_fakultas" class="control-label col-lg-1">Fakultas <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <select name="kode_fak" class="form-control chzn-select" tabindex="2" readonly>
              <option name="kode_fak" value="<?=$dt->kode_fak;?>"><?=$dt->nama_resmi;?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="kode_jurusan" class="control-label col-lg-1">Jurusan <span style="color:#FF0000">*</span></label>
        <div class="col-lg-11">
          <select name="kode_jur" class="form-control chzn-select" tabindex="2" readonly>
              <option name="kode_jur" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
          </select>
        </div>
      </div>
<?php
      }
 ?>

        <div id="form_civitas"></div>
        <div class="form-group">
          <label for="Priode Beasiswa" class="control-label col-lg-1">Priode Beasiswa</label>
          <div class="col-lg-11">
              <select name="priode_beasiswa" id="sem" data-placeholder="Pilih Priode Beasiswa ..." class="form-control chzn-select" tabindex="2" >
                 <option value=""></option>
                 <?php 
                   $sem = $db->query("select * from semester_ref s join jenis_semester j 
                    on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
                    foreach ($sem as $isi2) {
                      if ($data_edit->priode_beasiswa == $isi2->id_semester) {
                       echo "<option value='".$enc->enc($isi2->id_semester)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                      }else{
                        echo "<option value='".$enc->enc($isi2->id_semester)."'>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                      }
                    
                 } ?>
                </select>
          </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <label for="Jenis Beasiswa" class="control-label col-lg-1">Jenis Beasiswa <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
            <select id="id_beasiswajns" name="id_beasiswajns" data-placeholder="Pilih Jenis Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {
                  echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
               } ?>
              </select>
          </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <label for="Beasiswa" class="control-label col-lg-1">Beasiswa </label>
          <div class="col-lg-11">
            <select id="id_beasiswa" name="id_beasiswa" data-placeholder="Pilih Beasiswa ..." class="form-control chzn-select" tabindex="2" >            
            </select>
          </div>
        </div><!-- /.form-group -->   
        <div class="form-group">
          <div class="col-lg-1 col-lg-offset-11">
            <button type="submit" class="btn btn-primary form-control">Daftar</button>
          </div>
        </div>
        <div class="form-group">
            <label for="tags" class="control-label col-lg-2">&nbsp;</label>
            <div class="col-lg-4">
              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
              <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> Daftar </button>
            </div>
        </div><!-- /.form-group -->

  </form>
<?php
    }
  }
?>

  <div class="modal" id="modal_pendaftaran_beasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran Beasiswa</h4> </div> <div class="modal-body" id="isi_pendaftaran_beasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

</section><!-- /.content -->

<script type="text/javascript">
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
          //hidden validate because we use chosen select
          $.validator.setDefaults({ ignore: ":hidden:not(select)" });

        $("#input_pendaftaran_beasiswa").validate({
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

              kode_fak: {
              required: true,
              //minlength: 2
              },

              kode_jurusan: {
              required: true,
              //minlength: 2
              },

              nim: {
              required: true,
              //minlength: 2
              },

              judul_ta: {
              required: true,
              },
              pembimbing_1: {
              required: true,
              },
              pembimbing_2: {
                required: true,
              }
            },
             messages: {

              kode_fak: {
              required: "This field is required",
              //minlength: "Your username must consist of at least 2 characters"
              },

              kode_jurusan: {
              required: "This field is required",
              //minlength: "Your username must consist of at least 2 characters"
              },

              nim: {
              required: "This field is required",
              //minlength: "Your username must consist of at least 2 characters"
              },

              judul_ta: {
               required: "This field is required",
              //minlength: "Your username must consist of at least 2 characters" 
              },

              pembimbing_1: {
                required: "This field is required",
              },

              pembimbing_2: {
                required: "This field is required",
              }
            },

            submitHandler: function(form) {
                $("#loadnya").show();
                $(form).ajaxSubmit({
                    type: "post",
                    url: $(this).attr("action"),
                    data: $("#input_pendaftaran_beasiswa").serialize(),
                    success: function(data) {
                        if (data == "good") {
                          location.reload();
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

    $("#id_beasiswajns").change(function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/pendaftaran_beasiswa/get_beasiswa_filter.php",
          data : {jenisbeasiswa:this.value},
          success : function(data) {
              $("#id_beasiswa").html(data);
              $("#id_beasiswa").trigger("chosen:updated");

          }
      });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_edit_mahasiswa.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_pendaftaran_beasiswa").html(data);
                $("#loadnya").hide();
          }
        });

        $('#modal_pendaftaran_beasiswa').modal({ keyboard: false,backdrop:'static' });

    });
</script>
