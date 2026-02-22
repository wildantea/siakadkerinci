<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Bimbingan Pa</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>bimbingan-pa">Bimbingan Pa</a>
            </li>
            <li class="active">Add Bimbingan Pa</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Bimbingan Pa</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_bimbingan_pa" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/bimbingan_pa/bimbingan_pa_action.php?act=in">
                      
              <div class="form-group">
                <label for="nim" class="control-label col-lg-2">nim <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim" placeholder="nim" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="nip" class="control-label col-lg-2">nip <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nip" placeholder="nip" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Pertanyaan / Keluhan" class="control-label col-lg-2">Pertanyaan / Keluhan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="pertanyaan" placeholder="Pertanyaan / Keluhan" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Jawaban Saran" class="control-label col-lg-2">Jawaban Saran <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="jawaban" placeholder="Jawaban Saran" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Tanggal Konsultasi" class="control-label col-lg-2">Tanggal Konsultasi </label>
                <div class="col-lg-10">
                  <input type="text" name="tgl_tanya" placeholder="Tanggal Konsultasi" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="tgl_jawab" class="control-label col-lg-2">tgl_jawab </label>
                <div class="col-lg-10">
                  <input type="text" name="tgl_jawab" placeholder="tgl_jawab" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="id_semester" class="control-label col-lg-2">id_semester </label>
                <div class="col-lg-10">
                  <input type="text" name="id_semester" placeholder="id_semester" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="kategori_konsultasi" class="control-label col-lg-2">kategori_konsultasi </label>
                <div class="col-lg-10">
                  <input type="text" name="kategori_konsultasi" placeholder="kategori_konsultasi" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
             <a href="<?=base_index();?>bimbingan-pa" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
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
      
    $("#input_bimbingan_pa").validate({
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
            
          nim: {
          required: true,
          //minlength: 2
          },
        
          nip: {
          required: true,
          //minlength: 2
          },
        
          pertanyaan: {
          required: true,
          //minlength: 2
          },
        
          jawaban: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nip: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          pertanyaan: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          jawaban: {
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
