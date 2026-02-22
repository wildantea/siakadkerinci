<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Daftar Tugas Akhir
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_index();?>tugas-akhir">Kelulusan</a></li>
        <li class="active">Daftar Tugas Akhir</li>
    </ol>
</section>

<!-- Main content -->
<section class="content-body">
  <div class="container-fluid">
    <div class="box-body table-responsive">
<?php
  $priode_muna = $db->query("select priode_muna from jadwal_muna kw where kw.batas_awal <= NOW() AND kw.batas_akhir >= NOW()");

  $pm = '';
  foreach ($priode_muna as $key) {
    $pm = $key->priode_muna;
  }
  if($pm == false){
?>
  <div class="alert alert-info" role="alert"><strong>Perhatian!</strong> Belum Memasuki Priode Munaqasah</div>
<?php
  } else{
    $nim = $_SESSION['username'];
    $check = $db->check_exist('tugas_akhir',array('nim'=>$nim));
    if($check > 0){
      $data = $db->query("select *,ta.id_ta,ta.nim,mhs.nama,j.nama_jur from tugas_akhir ta
      inner join mahasiswa mhs on ta.nim=mhs.nim inner join fakultas f on ta.kode_fak=f.kode_fak
      inner join jurusan j on ta.kode_jurusan=j.kode_jur where ta.nim=?",array("ta.nim" => $nim));
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
                    <a data-id='<?=$dt->id_ta?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
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
                  <label for="judul" class="label-control col-lg-1">Judul</label>
                  <div class="col-lg-11">
                    <textarea class="form-control" name="judul" height="300" readonly><?=$dt->judul_ta?></textarea>
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
      <form id="input_tugas_akhir" class="form-horizontal" action="<?=base_admin();?>modul/tugas_akhir/tugas_akhir_action.php?act=in_mhs" method="post">
        <div class="form-group">
          <label for="nim" class="control-label col-lg-1">NIM <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
            <input id="nim" type="text" name="nim" class="form-control" value="<?=$_SESSION['username']?>" readonly>
          </div>
        </div>
<?php
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
          <select name="kode_jurusan" class="form-control chzn-select" tabindex="2" readonly>
              <option name="kode_jurusan" value="<?=$dt->kode_jur;?>"><?=$dt->nama_jur;?></option>
          </select>
        </div>
      </div>
<?php
      }
 ?>

        <div id="form_civitas"></div>
        <div class="form-group">
          <label for="judul_ta" class="control-label col-lg-1">Judul</label>
          <div class="col-lg-11">
            <textarea type="text" name="judul_ta" class="form-control" rows="7"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="pembimbing_1" class="control-label col-lg-1">Pembimbing 1 <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
            <select name="pembimbing_1" data-placeholder="Pilih pembimbing_1 ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php
                $dosen="";
                 foreach ($db->fetch_all("dosen") as $isi) {
                    if($isi->nidn != NULL) {
                      echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                      $dosen="";
                    } else{
                      $dosen="NULL";
                    }
                 }

                 echo "<option value='$dosen'>$dosen</option>";
               ?>
              </select>
            </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <label for="pembimbing_2" class="control-label col-lg-1">Pembimbing 2 <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
          <select name="pembimbing_2" data-placeholder="Pilih pembimbing_2 ..." class="form-control chzn-select" tabindex="2" required>
             <option value=""></option>
             <?php
              $dosen="";
               foreach ($db->fetch_all("dosen") as $isi) {
                  if($isi->nidn != NULL) {
                    echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
                    $dosen="";
                  } else{
                    $dosen="NULL";
                  }
               }

               echo "<option value='$dosen'>$dosen</option>";
             ?>
            </select>
          </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <label for="priode" class="control-label col-lg-1">Priode <span style="color:#FF0000">*</span></label>
          <div class="col-lg-11">
              <select name="priode_muna" id="priode_muna" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
                 <option value=""></option>
                 <?php 
                 $sem = $db->query("select * from jadwal_muna join semester_ref s on s.id_semester= jadwal_muna.priode_muna join jenis_semester j on s.id_jns_semester=j.id_jns_semester where batas_awal <= NOW() AND batas_akhir >= NOW() order by s.id_semester desc");
                 foreach ($sem as $isi2) {
                   echo "<option name='priode_muna' value='".$enc->enc($isi2->id_muna)."' selected>$isi2->jns_semester $isi2->tahun/".($isi2->tahun+1)."</option>";
                  } 
                 ?>
            </select>
          </div>
        </div><!-- /.form-group -->
        <div class="form-group">
          <div class="col-lg-2 col-lg-offset-10">
            <button type="submit" class="btn btn-primary form-control">Daftar</button>
          </div>
        </div>
  </form>
<?php
    }
  }
?>
    </div><!-- /.box-body -->
  </div>

  <div class="modal" id="modal_tugas_akhir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Tugas Akhir</h4> </div> <div class="modal-body" id="isi_tugas_akhir"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

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

        $("#input_tugas_akhir").validate({
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
                    data: $("#input_tugas_akhir").serialize(),
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

    $("#nim").on('input',function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/tugas_akhir/get_fakultas_jurusan_mahasiswa.php",
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
            url : "<?=base_admin();?>modul/tugas_akhir/tugas_akhir_edit_mahasiswa.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_tugas_akhir").html(data);
                $("#loadnya").hide();
          }
        });

        $('#modal_tugas_akhir').modal({ keyboard: false,backdrop:'static' });

    });
</script>
