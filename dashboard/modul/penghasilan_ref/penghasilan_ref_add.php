<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Penghasilan Ref</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>penghasilan-ref">Penghasilan Ref</a>
            </li>
            <li class="active">Add Penghasilan Ref</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Penghasilan Ref</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_penghasilan_ref" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/penghasilan_ref/penghasilan_ref_action.php?act=in">
                      
              <div class="form-group">
                <label for="Penghasilan" class="control-label col-lg-2">Penghasilan</label>
                <div class="col-lg-10">
                  <input type="text" name="penghasilan" placeholder="Penghasilan" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Batas Bawah" class="control-label col-lg-2">Batas Bawah</label>
                <div class="col-lg-10">
                  <input type="text" name="batas_bawah" placeholder="Batas Bawah" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Batas Atas" class="control-label col-lg-2">Batas Atas</label>
                <div class="col-lg-10">
                  <input type="text" name="batas_atas" placeholder="Batas Atas" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <input type="submit" class="btn btn-primary " value="submit">
                </div>
              </div><!-- /.form-group -->

            </form>

            <a href="<?=base_index();?>penghasilan-ref" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
     
    
    
    $("#input_penghasilan_ref").validate({
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
            
          penghasilan: {
          required: true,
          //minlength: 2
          },
        
          batas_bawah: {
          required: true,
          //minlength: 2
          },
        
          batas_atas: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          penghasilan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          batas_bawah: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          batas_atas: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_penghasilan_ref").serialize(),
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
