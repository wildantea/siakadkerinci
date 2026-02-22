<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Setting Menu</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>setting-menu">Setting Menu</a>
                        </li>
                        <li class="active">Edit Setting Menu</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Setting Menu</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_setting_menu" method="post" class="form-horizontal" action="<?=base_admin();?>modul/setting_menu/setting_menu_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nama Menu" class="control-label col-lg-2">Nama Menu <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="page_name" value="<?=$data_edit->page_name;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Icon" class="control-label col-lg-2">Icon </label>
                <div class="col-lg-10">
                  <input type="text" name="icon" value="<?=$data_edit->icon;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Menu" class="control-label col-lg-2">Jenis Menu </label>
                <div class="col-lg-10">
                    <select id="type_menu" name="type_menu" data-placeholder="Pilih Jenis Menu..." class="form-control chzn-select" tabindex="2" >
                      <option value=""></option>
                     <?php
                     $option = array(
'main' => 'Menu Parent',

'page' => 'Menu Halaman',
);
                     foreach ($option as $isi => $val) {

                        if ($data_edit->type_menu==$isi) {
                          echo "<option value='$data_edit->type_menu' selected>$val</option>";
                        } else {
                       echo "<option value='$isi'>$val</option>";
                          }
                     } ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->
            <div class="form-group">
                        <label for="Menu Parent" class="control-label col-lg-2">Menu Parent <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
              <select  id="parent" name="parent" data-placeholder="Pilih Menu Parent..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("sys_menu") as $isi) {

                  if ($data_edit->parent==$isi->page_name) {
                    echo "<option value='$isi->page_name' selected>$isi->id</option>";
                  } else {
                  echo "<option value='$isi->page_name'>$isi->id</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Urutan Menu" class="control-label col-lg-2">Urutan Menu <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="urutan_menu" value="<?=$data_edit->urutan_menu;?>" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
          
                            <input type="hidden" name="id" value="<?=$data_edit->id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <a href="<?=base_index();?>setting-menu" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_setting_menu").validate({
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
            
          page_name: {
          required: true,
          //minlength: 2
          },
        
          parent: {
          required: true,
          //minlength: 2
          },
        
          urutan_menu: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          page_name: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          parent: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          urutan_menu: {
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
