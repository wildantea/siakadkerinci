<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Program Studi</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>program-studi">Program Studi</a>
            </li>
            <li class="active">Add Program Studi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Program Studi</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_program_studi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/program_studi/program_studi_action.php?act=in">
          
          <div class="form-group">
              <label for="Kode Jurusan Dikti" class="control-label col-lg-2">Kode Prodi Dikti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="kode_dikti" placeholder="Kode Jurusan Dikti" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Program Studi" class="control-label col-lg-2">Nama Program Studi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nama_jur" placeholder="Nama Program Studi" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Jenjang" class="control-label col-lg-2">Jenjang <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select name="id_jenjang" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2" required="">
               <option value=""></option>
               <?php foreach ($db->query("select * from jenjang_pendidikan where id_jenjang in(
20,21,22,23,30,35,40,90,91,99,32,37,31,36,41)") as $isi) {
                  echo "<option value='$isi->id_jenjang'>$isi->jenjang</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Fakultas" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span> </label>
                        <div class="col-lg-10">
            <select name="fak_kode" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("fakultas") as $isi) {
                  echo "<option value='$isi->kode_fak'>$isi->nama_resmi</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

            <div class="form-group">
                <label for="Status" class="control-label col-lg-2">Status <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select name="status" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" >     
<option value='A'>Aktif</option>

<option value='N'>Tidak Aktif</option>

                  </select>
                </div>
            </div><!-- /.form-group -->
            

              
              <div class="form-group">
                <label for="Nama Inggris" class="control-label col-lg-2">Nama Inggris </label>
                <div class="col-lg-10">
                  <input type="text" name="nama_jur_asing" placeholder="Nama Inggris" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Sks Lulus" class="control-label col-lg-2">Sks Lulus </label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="sks_lulus" placeholder="Sks Lulus" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Website" class="control-label col-lg-2">Website </label>
                <div class="col-lg-10">
                  <input type="text" name="web" placeholder="Website" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email </label>
                <div class="col-lg-10">
                  <input type="text" name="email" placeholder="Email" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Telp" class="control-label col-lg-2">Telp </label>
                <div class="col-lg-10">
                  <input type="text" name="telp" placeholder="Telp" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tanggal Berdiri" class="control-label col-lg-2">Tanggal Berdiri </label>
                <div class="col-lg-10">
                  <input type="text" id="tgl2" name="tgl_berdiri" placeholder="Tanggal Berdiri" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="SK Penyelenggaraan" class="control-label col-lg-2">SK Penyelenggaraan </label>
                <div class="col-lg-10">
                  <input type="text" name="no_sk_dikti" placeholder="SK Penyelenggaraan" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal SK" class="control-label col-lg-2">Tanggal SK </label>
              <div class="col-lg-10">
                <input type="text" id="tgl1" data-rule-date="true" name="tgl_sk_dikti" placeholder="Tanggal SK" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Ka Prodi" class="control-label col-lg-2">Ketua Program Prodi </label>
                        <div class="col-lg-10">
            <select name="ketua_jurusan" data-placeholder="Pilih Ka Prodi ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("dosen") as $isi) {
                  echo "<option value='$isi->id_dosen'>$isi->nama_dosen</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                               <a href="<?=base_index();?>program-studi" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>

           
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
     
    
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
    
    $("#input_program_studi").validate({
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
            
          kode_jur: {
          required: true,
          //minlength: 2
          },
        
          kode_dikti: {
          required: true,
          //minlength: 2
          },
        
          nama_jur: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          kode_jur: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_dikti: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_jur: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_program_studi").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
                                window.history.back();
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
