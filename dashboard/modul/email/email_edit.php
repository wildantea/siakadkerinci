<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Email</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>email">Email</a>
                        </li>
                        <li class="active"><?php echo $lang["edit"];?> Email</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title"><?php echo $lang["edit"];?> Email</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_email" method="post" class="form-horizontal" action="<?=base_admin();?>modul/email/email_action.php?act=up">
                            
              <div class="form-group">
                <label for="Email" class="control-label col-lg-2">Email <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="email" value="<?=$data_edit->email;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Login" class="control-label col-lg-2">Login </label>
                <div class="col-lg-10">
                <?php if ($data_edit->login=="Y") {
                ?>
                  <input name="login" data-on-text="On" data-off-text="Off" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="login" data-on-text="On" data-off-text="Off" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            
              <div class="form-group">
                <label for="client_id" class="control-label col-lg-2">client_id <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="client_id" value="<?=$data_edit->client_id;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="client_secret" class="control-label col-lg-2">client_secret <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="client_secret" value="<?=$data_edit->client_secret;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="redirect_url" class="control-label col-lg-2">redirect_url <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="redirect_url" value="<?=$data_edit->redirect_url;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
            <div class="form-group">
                <label for="Login" class="control-label col-lg-2">Active </label>
                <div class="col-lg-10">
                <?php if ($data_edit->aktif=="Y") {
                ?>
                  <input name="aktif" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="aktif" data-on-text="Yes" data-off-text="No" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
          
                            <input type="hidden" name="id" value="<?=$data_edit->id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <a href="<?=base_index();?>email" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["cancel_button"];?></a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
    
      $.each($(".make-switch"), function () {
            $(this).bootstrapSwitch({
            onText: $(this).data("onText"),
            offText: $(this).data("offText"),
            onColor: $(this).data("onColor"),
            offColor: $(this).data("offColor"),
            size: $(this).data("size"),
            labelText: $(this).data("labelText")
            });
          });
        
    
    
    $("#edit_email").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
        rules: {
            
          email: {
          required: true,
          //minlength: 2
          },
        
          client_id: {
          required: true,
          //minlength: 2
          },
        
          client_secret: {
          required: true,
          //minlength: 2
          },
        
          redirect_url: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          email: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          client_id: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          client_secret: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          redirect_url: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    

        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                    window.history.back();
                            });
                          } else {
                             console.log(responseText);
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          }
                    });
                }

            });
        }
    });
});
</script>
