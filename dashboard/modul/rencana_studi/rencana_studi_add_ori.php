<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Rencana Studi</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>rencana-studi">Rencana Studi</a>
            </li>
            <li class="active">Add Rencana Studi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Rencana Studi</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_rencana_studi" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=in">
                      <div class="form-group">
                        <label for="Mahasiswa" class="control-label col-lg-2">Mahasiswa</label>
                        <div class="col-lg-10">
            <select name="mhs_id" data-placeholder="Pilih Mahasiswa ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("mahasiswa") as $isi) {
                  echo "<option value='$isi->mhs_id'>$isi->nama</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Semester</label>
                        <div class="col-lg-10">
            <select name="sem_id" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("semester") as $isi) {
                  echo "<option value='$isi->sem_id'>$isi->sem_id</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Mata Kuliah" class="control-label col-lg-2">Mata Kuliah</label>
                        <div class="col-lg-10">
            <select name="id_matkul" data-placeholder="Pilih Mata Kuliah ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("matkul") as $isi) {
                  echo "<option value='$isi->id_matkul'>$isi->nama_mk</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Nilai" class="control-label col-lg-2">Nilai</label>
                        <div class="col-lg-10">
            <select name="nilai_id" data-placeholder="Pilih Nilai ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("nilai_ref") as $isi) {
                  echo "<option value='$isi->nilai_id'>$isi->nilai_huruf</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Disetujui" class="control-label col-lg-2">Disetujui</label>
              <div class="col-lg-10">
                <input name="di_setujui" class="make-switch" type="checkbox" checked>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="Pengubah" class="control-label col-lg-2">Pengubah</label>
                <div class="col-lg-10">
                  <input type="text" name="pengubah" placeholder="Pengubah" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Tanggal Perubahan" class="control-label col-lg-2">Tanggal Perubahan</label>
              <div class="col-lg-10">
                <input type="text" id="tgl1" data-rule-date="true" name="tgl_perubahan" placeholder="Tanggal Perubahan" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <input type="submit" class="btn btn-primary " value="submit">
                </div>
              </div><!-- /.form-group -->

            </form>

            <a href="<?=base_index();?>rencana-studi" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

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
    
    $("#input_rencana_studi").validate({
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
                data: $("#input_rencana_studi").serialize(),
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
