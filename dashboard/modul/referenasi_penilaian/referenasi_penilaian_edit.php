<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Referenasi Penilaian</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>referenasi-penilaian">Referenasi Penilaian</a>
                        </li>
                        <li class="active">Edit Referenasi Penilaian</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Referenasi Penilaian</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                              </div>
                          </div>


                      <div class="box-body">
                          <form id="edit_referenasi_penilaian" method="post" class="form-horizontal" action="<?=base_admin();?>modul/referenasi_penilaian/referenasi_penilaian_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nama Komponen" class="control-label col-lg-2">Nama Komponen </label>
                <div class="col-lg-10">
                  <input type="text" name="nama_komponen" value="<?=$data_edit->nama_komponen;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Wajib" class="control-label col-lg-2">Wajib </label>
                <div class="col-lg-10">
                <?php if ($data_edit->wajib=="1") {
                ?>
                  <input name="wajib" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="wajib" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            
            <div class="form-group">
                <label for="Tampil" class="control-label col-lg-2">Tampil </label>
                <div class="col-lg-10">
                <?php if ($data_edit->isShow=="1") {
                ?>
                  <input name="isShow" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="isShow" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            
                            <input type="hidden" name="id" value="<?=$data_edit->id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                <a href="<?=base_index();?>referenasi-penilaian" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                                
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
    
    
    $("#edit_referenasi_penilaian").validate({
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
                data: $("#edit_referenasi_penilaian").serialize(),
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
