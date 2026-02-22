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
                        <li class="active">Edit Syarat PPL</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Syarat PPL</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-pencil"></i></button>
                              </div>
                          </div>
                      <div class="box-body">
                       <div class="alert alert-danger error_data" style="display:none">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <span class="isi_warning"></span>
                      </div>
                          <form id="edit_syarat_ppl" method="post" class="form-horizontal" action="<?=base_admin();?>modul/syarat_ppl/syarat_ppl_action.php?act=up">
                            
              <div class="form-group">
                <label for="Syarat SKS" class="control-label col-lg-2">Syarat SKS <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="syarat_sks" value="<?=$data_edit->syarat_sks;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Syarat Semester" class="control-label col-lg-2">Syarat Semester <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="syarat_semester" value="<?=$data_edit->syarat_semester;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
               <div class="form-group">
                <label for="Syarat Semester" class="control-label col-lg-2">Kondisi <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <label><input type="radio" name="kondisi" value="1" style="position: relative;top: 2px" <?php if ($data_edit->kondisi=='1') {
                     echo "checked";
                  } ?>> Wajib keduanya </label><label> &nbsp;&nbsp;<input type="radio" name="kondisi" value="2" style="position: relative;top: 2px" <?php if ($data_edit->kondisi=='2')  {
                    echo "checked";
                  } ?>> Salah satu </label>
                </div>
              </div>
              <div class="form-group">
                        <label for="Kode Jurusan" class="control-label col-lg-2">Kode Jurusan <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                            <select  id="kode_jur" name="kode_jur" data-placeholder="Pilih Kode Jurusan..." class="form-control chzn-select" tabindex="2" required>
                             <option value=""></option>
                             <?php foreach ($db->fetch_all("jurusan") as $isi) {

                                if ($data_edit->kode_jur==$isi->kode_jur) {
                                  echo "<option value='$isi->kode_jur' selected>$isi->nama_jur</option>";
                                } else {
                                echo "<option value='$isi->kode_jur'>$isi->nama_jur</option>";
                                  }
                             } ?>
                            </select>
                        </div>
                      </div><!-- /.form-group -->
                        <div class="form-group">
                        <label for="Kode Jurusan" class="control-label col-lg-2">Matkul Pra Syarat <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-10">
                            <select  id="matkul_prasyarat" name="matkul_prasyarat[]"  class="form-control js-example-basic-multiple" multiple >
                            <option value="">pilih matkul</option>
                            <?php
                             $q = $db->query("select m.kode_mk,m.nama_mk,m.id_matkul from matkul m join kurikulum k on k.kur_id=m.kur_id  where  k.kode_jur='$data_edit->kode_jur'  "); 
                            if ($q->rowCount()>0) {
                              foreach ($q as $k) {
                                 echo "<option value='$k->id_matkul'>$k->kode_mk $k->nama_mk</option>";
                              }
                             
                            }
                            ?>
                            </select> 
                        </div>
                      </div>

                            <input type="hidden" name="id" value="<?=$data_edit->id;?>">
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

 
      <?php
      //echo "select s.*,m.nama_mk,m.kode_mk from syarat_ppl_matkul s join matkul m on m.id_matkul=s.id_matkul where s.id_syarat='$data_edit->id' "; 
      $q = $db->query("select s.*,m.nama_mk,m.kode_mk from syarat_ppl_matkul s join matkul m on m.id_matkul=s.id_matkul where s.id_syarat='$data_edit->id' "); 
      if ($q->rowCount()>0) {
        $res = array(); 
        foreach ($q as $k) {
          // $data['id']   = $k->id_matkul;
          // $data['text'] = $k->kode_mk." ".$k->nama_mk;
          //$data[] = $k->kode_mk." ".$k->nama_mk;
          $res[]        = $k->id_matkul;
        }
        ?>   
        var defaultData = <?= json_encode($res) ?>;
        $('.js-example-basic-multiple').select2().val(defaultData).trigger('change');
        <?php
      }
      ?>

     
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_syarat_ppl").validate({
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
