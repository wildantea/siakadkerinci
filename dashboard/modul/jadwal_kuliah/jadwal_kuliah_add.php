<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Jadwal Kuliah</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>jadwal-kuliah">Jadwal Kuliah</a>
            </li>
            <li class="active">Add Jadwal Kuliah</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Jadwal Kuliah</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_jadwal_kuliah" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/jadwal_kuliah/jadwal_kuliah_action.php?act=in">
                      
              <div class="form-group">
                <label for="Kelas" class="control-label col-lg-2">Kelas</label>
                <div class="col-lg-10">
                  <input type="text" name="kelas_id" placeholder="Kelas" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Hari" class="control-label col-lg-2">Hari</label>
                <div class="col-lg-10">
                  <input type="text" name="hari" placeholder="Hari" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Ruang" class="control-label col-lg-2">Ruang</label>
                        <div class="col-lg-10">
            <select name="ruang_id" data-placeholder="Pilih Ruang ..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("ruang_ref") as $isi) {
                  echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Jam Mulai" class="control-label col-lg-2">Jam Mulai</label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="jam_mulai" placeholder="Jam Mulai" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Jam Selesai" class="control-label col-lg-2">Jam Selesai</label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="jam_selesai" placeholder="Jam Selesai" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <input type="submit" class="btn btn-primary " value="submit">
                </div>
              </div><!-- /.form-group -->

            </form>

            <a href="<?=base_index();?>jadwal-kuliah" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
     
    
    
    $("#input_jadwal_kuliah").validate({
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
                data: $("#input_jadwal_kuliah").serialize(),
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
