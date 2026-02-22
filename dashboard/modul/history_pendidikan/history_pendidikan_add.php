<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                     Update History Pendidikan  Mahasiswa
                    </h1>
                           <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>aktivitas-kuliah-mahasiswa">History Pendidikan </a></li>
                        <li class="active">Import Update History Pendidikan  Mahasiswa</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="box box-solid box-primary">
                                 <div class="box-header">
                                    <h3 class="box-title">Import History Pendidikan Mahasiswa</h3>
                                    <div class="box-tools pull-right">
                                        <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
<div class="row">
  <div class="col-lg-2">&nbsp;</div>
  <div class="col-lg-10"><h5>Update History digunakan untuk update NIm yang sudah ada, misal menjadi mahasiswa pindahan.</h5></div>
</div>
                  <div class="box-body">
<div id="isi_import"></div>
                     <form id="input_import_data" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/history_pendidikan/history_pendidikan_action.php?act=import">
                     <div class="form-group">
                        <label for="nim" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                           <a href="<?=base_url();?>upload/sample/sample_history.xlsx" class="btn btn-success btn-flat"><i class="fa fa-cloud-download"></i> Download  Template Update History</a>
                        </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Upload Data Akm" class="control-label col-lg-2">Upload Data</label>
                        <div class="col-lg-10">
                          <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="input-group">
                              <div class="form-control uneditable-input span3" data-trigger="fileinput">
                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                              </div>
                              <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                                <input type="file" name="semester" required>
                              </span>
                              <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.form-group -->
                      
    <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-cloud-upload"></i> Upload Data</button>
                        </div>
                      </div><!-- /.form-group -->
                    </form>
<p>&nbsp;</p>
                  </div>
                  </div>
              </div>
</div>

                </section><!-- /.content -->
<script type="text/javascript">
$(document).ready(function(){
  //date 
   $("#input_import_data").validate({
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
               $('#loadnya').show();
                   $(form).ajaxSubmit({
                          type: "post",
                          url: $(this).attr('action'),
                          data: $("#input_import").serialize(),
                       //  enctype:  'multipart/form-data'
                        success: function(data){
                          $("#isi_import").html(data);
                          $('#loadnya').hide();
                     
                      }
                    });
            }

        }); 

}); 
</script>