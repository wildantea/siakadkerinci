<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Syarat PPL</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>syarat-ppl">Syarat PPL</a>
            </li>
            <li class="active">Add Syarat PPL</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Syarat PPL</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_syarat_ppl" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/syarat_ppl/syarat_ppl_action.php?act=in">
                      
              <div class="form-group">
                <label for="Syarat SKS" class="control-label col-lg-2">Syarat SKS <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="syarat_sks" placeholder="Syarat SKS" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Syarat Semester" class="control-label col-lg-2">Syarat Semester <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="syarat_semester" placeholder="Syarat Semester" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
               <div class="form-group">
                <label for="Syarat Semester" class="control-label col-lg-2">Kondisi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <label><input type="radio" name="kondisi" value="1" style="position: relative;top: 2px"> Wajib keduanya </label><label> &nbsp;&nbsp;<input type="radio" name="kondisi" value="2" style="position: relative;top: 2px"> Salah satu </label>
                </div>
              </div>
              <div class="form-group">
                        <label for="Kode Jurusan" class="control-label col-lg-2">Kode Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
            <select  id="kode_jur" name="kode_jur" data-placeholder="Pilih Kode Jurusan ..." class="form-control chzn-select" tabindex="2" required>
               <option value=""></option>
               <?php foreach ($db->fetch_all("jurusan") as $isi) {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
               } ?>
              </select>
            </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="Kode Jurusan" class="control-label col-lg-2">Matkul Pra Syarat <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                            <select  id="matkul_prasyarat" name="matkul_prasyarat[]"  class="form-control js-example-basic-multiple" multiple >
                            <option value="">pilih matkul</option>
                            
                            </select> 
                        </div>
                      </div>

                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
             <a href="<?=base_index();?>syarat-ppl" class="btn btn-default "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                 <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
           
                </div>
              </div><!-- /.form-group -->

            </form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

      $('.js-example-basic-multiple').select2({ 
                minimumInputLength: 2,
                tags: [],
                ajax: {
                    url: '<?= base_url() ?>dashboard/modul/syarat_ppl/syarat_ppl_action.php?act=get_matkul',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            term: term,
                            kode_jur : $("#kode_jur").val()
                        };
                    },
                    results: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.completeName,
                                    slug: item.slug,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
     
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
      
    $("#input_syarat_ppl").validate({
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
            
          syarat_sks: {
          required: true,
          //minlength: 2
          },
        
          syarat_semester: {
          required: true,
          //minlength: 2
          },
        
          kode_jur: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          syarat_sks: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          syarat_semester: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          kode_jur: {
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
