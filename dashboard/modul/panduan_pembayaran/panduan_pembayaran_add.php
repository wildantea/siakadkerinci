<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Panduan Pembayaran</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>panduan-pembayaran">Panduan Pembayaran</a>
            </li>
            <li class="active">Add Panduan Pembayaran</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Panduan Pembayaran</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_panduan_pembayaran" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/panduan_pembayaran/panduan_pembayaran_action.php?act=in">
                      <div class="form-group">
                        <label for="Bank" class="control-label col-lg-2">Bank <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="id_bank" name="id_bank" data-placeholder="Pilih Bank ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("keu_bank") as $isi) {
                  echo "<option value='$isi->kode_bank'>$isi->nama_singkat</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <label for="Judul" class="control-label col-lg-2">Judul <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="judul" placeholder="Judul" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Isi Panduan" class="control-label col-lg-2">Isi Panduan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea id="editbox" name="isi_panduan" class="editbox"></textarea>
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Urutan" class="control-label col-lg-2">Urutan <span style="color:#FF0000">*</span></label>
              <div class="col-lg-1">
                <input type="text" data-rule-number="true" name="urutan" placeholder="Urutan" class="form-control" required>
              </div>
          </div><!-- /.form-group -->
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
             <a href="<?=base_index();?>panduan-pembayaran" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
      
    $("#input_panduan_pembayaran").validate({
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
            
          id_bank: {
          required: true,
          //minlength: 2
          },
        
          judul: {
          required: true,
          //minlength: 2
          },
        
          isi_panduan: {
          required: true,
          //minlength: 2
          },
        
          urutan: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          id_bank: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          judul: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          isi_panduan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          urutan: {
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
