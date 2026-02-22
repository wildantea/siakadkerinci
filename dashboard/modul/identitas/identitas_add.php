<?php
$query = $db->query("select MAX(id_identitas) as new_id FROM identitas");
foreach ($query as $key) {
  $nilai = $key->new_id;
}

$id=$nilai + 1;
?>
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Identitas</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>identitas">Identitas</a>
            </li>
            <li class="active">Add Identitas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Identitas</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_identitas" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/identitas/identitas_action.php?act=in">
                      
              <div class="form-group">
                <label for="ID" class="control-label col-lg-2">ID <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="id_identitas" placeholder="ID" class="form-control" value="<?=$id?>" readonly>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Keterangan" class="control-label col-lg-2">Keterangan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="ket" placeholder="Keterangan" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Isi" class="control-label col-lg-2">Isi <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea id="editbox" name="isi" class="editbox"></textarea>
              </div>
          </div><!-- /.form-group -->
          
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">

             <a href="<?=base_index();?>identitas" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
     
    
    
    $("#input_identitas").validate({
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
            
          id_identitas: {
          required: true,
          //minlength: 2
          },
        
          ket: {
          required: true,
          //minlength: 2
          },
        
          isi: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          id_identitas: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          ket: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          isi: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#input_identitas").serialize(),
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
