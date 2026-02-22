<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Hasil Studi Mahasiswa</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>hasil-studi-mahasiswa">Hasil Studi Mahasiswa</a>
                        </li>
                        <li class="active">Edit Hasil Studi Mahasiswa</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Hasil Studi Mahasiswa</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                              </div>
                          </div>


                      <div class="box-body">
                          <form id="edit_hasil_studi_mahasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/hasil_studi_mahasiswa/hasil_studi_mahasiswa_action.php?act=up">
                            
              <div class="form-group">
                <label for="id_krs_detail" class="control-label col-lg-2">id_krs_detail </label>
                <div class="col-lg-10">
                  <input type="text" name="id_krs_detail" value="<?=$data_edit->id_krs_detail;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="sks" class="control-label col-lg-2">sks </label>
                <div class="col-lg-10">
                  <input type="text" name="sks" value="<?=$data_edit->sks;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="bobot" class="control-label col-lg-2">bobot </label>
                <div class="col-lg-10">
                  <input type="text" name="bobot" value="<?=$data_edit->bobot;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nilai_huruf" class="control-label col-lg-2">nilai_huruf </label>
                <div class="col-lg-10">
                  <input type="text" name="nilai_huruf" value="<?=$data_edit->nilai_huruf;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                            <input type="hidden" name="id" value="<?=$data_edit->id_krs_detail;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                <a href="<?=base_index();?>hasil-studi-mahasiswa" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                                
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
    
    
    $("#edit_hasil_studi_mahasiswa").validate({
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
                data: $("#edit_hasil_studi_mahasiswa").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
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
