<!-- Content Header (Page header) -->
              <section class="content-header">
                  <h1>Nilai Ref</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>nilai-ref">Nilai Ref</a>
                        </li>
                        <li class="active">Edit Nilai Ref</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Nilai Ref</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                              </div>
                          </div>


                      <div class="box-body">
                          <form id="edit_nilai_ref" method="post" class="form-horizontal" action="<?=base_admin();?>modul/nilai_ref/nilai_ref_action.php?act=up">
                            
              <div class="form-group">
                <label for="Bobot" class="control-label col-lg-2">Bobot </label>
                <div class="col-lg-10">
                  <input type="text" name="bobot" value="<?=$data_edit->bobot;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Nilai Huruf" class="control-label col-lg-2">Nilai Huruf </label>
                <div class="col-lg-10">
                  <input type="text" name="nilai_huruf" value="<?=$data_edit->nilai_huruf;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
          <div class="form-group">
              <label for="Batas Bawah" class="control-label col-lg-2">Batas Bawah </label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="batas_bawah" value="<?=$data_edit->batas_bawah;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Batas Atas" class="control-label col-lg-2">Batas Atas </label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="batas_atas" value="<?=$data_edit->batas_atas;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          <div class="form-group">
                        <label for="Prodi" class="control-label col-lg-2">Prodi </label>
                        <div class="col-lg-10">
              <select name="prodi_id" data-placeholder="Pilih Prodi..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php foreach ($db->fetch_all("jurusan") as $isi) {

                  if ($data_edit->prodi_id==$isi->kode_jur) {
                    echo "<option value='$isi->kode_jur' selected>$isi->nama_jur</option>";
                  } else {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Tanggal Mulai Efektif" class="control-label col-lg-2">Tanggal Mulai Efektif </label>
              <div class="col-lg-10">
                <input type="text" id="tgl1" data-rule-date="true" name="tgl_mulai" value="<?=$data_edit->tgl_mulai;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Tanggal Selesai Efektif" class="control-label col-lg-2">Tanggal Selesai Efektif </label>
              <div class="col-lg-10">
                <input type="text" id="tgl2" data-rule-date="true" name="tgl_selesai" value="<?=$data_edit->tgl_selesai;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
                            <input type="hidden" name="id" value="<?=$data_edit->nilai_id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                                <a href="<?=base_index();?>nilai-ref" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                                
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->

<script type="text/javascript">
    $(document).ready(function() {
    
      $("#tgl1").datepicker( {
          format: "yyyy-mm-dd",
      });
      $("#tgl2").datepicker( {
          format: "yyyy-mm-dd",
      });
    
    $("#edit_nilai_ref").validate({
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
                data: $("#edit_nilai_ref").serialize(),
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
