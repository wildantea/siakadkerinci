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
            <li class="active">Add Setting Menu</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Setting Menu</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_setting_menu" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/setting_menu/setting_menu_action.php?act=in">
                      
              <div class="form-group">
                <label for="Nama Menu" class="control-label col-lg-2">Nama Menu <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="page_name" placeholder="Nama Menu" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Icon" class="control-label col-lg-2">Icon </label>
                <div class="col-lg-10">
                   <input type="text"  name="icon" placeholder="fa-camera-retro" class="form-control">
                        <a target="_blank" href="<?=base_index();?>setting-menu/icon">Referensi Icon (new window)</a>
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Jenis Menu" class="control-label col-lg-2">Jenis Menu </label>
                <div class="col-lg-10">
                  <select name="type_menu" id="type_menu" data-placeholder="Pilih Jenis Menu ..." class="form-control chzn-select" tabindex="2" >
                    
<option value='main'>Menu Parent</option>

<option value='page'>Menu Halaman</option>

                  </select>
                </div>
            </div><!-- /.form-group -->

                         <div class="form-group" id="parent">
                        <label class="control-label col-lg-2">Menu Parent</label>
                        <div class="col-lg-10">
                          <select data-placeholder="Pilih Modul" name="parent" class="form-control chzn-select" tabindex="2">
                            <option value="0">None</option>
                            <?php foreach ($db->query('select * from sys_menu where is_menu_pendaftaran="Y" and (url="" or url is null)') as $isi) {
                              echo "<option value='$isi->id'>$isi->page_name</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>


          <div class="form-group">
              <label for="Urutan Menu" class="control-label col-lg-2">Urutan Menu <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="urutan_menu" placeholder="Urutan Menu" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
          
                      
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
    </div>

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
      
    $("#input_setting_menu").validate({
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
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
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
