<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("dosen","id_dosen",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="edit_dosen" method="post" class="form-horizontal" action="<?=base_admin();?>modul/dosen/dosen_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nomor Identitas (NIP, dll)" class="control-label col-lg-3">Nomor Indup Pegawai (NIP) <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nip" value="<?=$data_edit->nip;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <input type="hidden" name="old_nip" value="<?=$data_edit->nip;?>">
              
              <div class="form-group">
  <label for="Nomor Induk Dosen Nasional (NIDN)" class="control-label col-lg-3">NIDN/NIDK/NUP </label>
  <div class="col-lg-9">
    <input type="text" name="nidn" value="<?=$data_edit->nidn;?>" class="form-control" >
  </div>
</div>
              
              <div class="form-group">
                <label for="Nama Lengkap dan Gelar" class="control-label col-lg-3">Nama Lengkap dan Gelar <span style="color:#FF0000">*</span></label>
                <div class="col-lg-9">
                  <input type="text" name="nama_dosen" value="<?=$data_edit->nama_dosen;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Lengkap dan Gelar" class="control-label col-lg-3">Email</label>
                <div class="col-lg-9">
                  <input type="text" name="email" value="<?=$data_edit->email;?>" class="form-control" data-rule-email="true">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Nama Lengkap dan Gelar" class="control-label col-lg-3">No HP</label>
                <div class="col-lg-9">
                  <input type="text" name="no_hp" value="<?=$data_edit->no_hp;?>" class="form-control" onkeypress="return isNumberKey(event)">
                </div>
              </div><!-- /.form-group -->

              

              <div class="form-group">
                <label for="Program Studi" class="control-label col-lg-3">Jenis Dosen <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-9">
                  <select  id="id_jenis_dosen" name="id_jenis_dosen" data-placeholder="Pilih Jenis Dosen..." class="form-control chzn-select" tabindex="2" required>
                  <?php foreach ($db->fetch_all("jenis_dosen") as $isi) {
                    if ($data_edit->id_jenis_dosen==$isi->id_jenis_dosen) {
                      echo "<option value='$isi->id_jenis_dosen' selected>$isi->nama_jenis</option>";
                    } else {
                      echo "<option value='$isi->id_jenis_dosen'>$isi->nama_jenis</option>";
                    }
                    } ?>
                  </select>
          </div>
                      </div><!-- /.form-group -->


              <div class="form-group">
                        <label for="Program Studi" class="control-label col-lg-3">Program Studi <span style="color:#FF0000">*</span></label>
                        <div class="col-lg-9">
              <select  id="kode_jur" name="kode_jur" data-placeholder="Pilih Program Studi..." class="form-control chzn-select" tabindex="2" required>
               <?php foreach ($db->fetch_all("view_prodi_jenjang") as $isi) {

                  if ($data_edit->kode_jur==$isi->kode_jur) {
                    echo "<option value='$isi->kode_jur' selected>$isi->jurusan</option>";
                  } else {
                  echo "<option value='$isi->kode_jur'>$isi->jurusan</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->

     <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-3">Rumpun</label>
                    <div class="col-lg-9">
                    <select id="rumpun_edit" name="rumpun" data-placeholder="Pilih Rumpun Dosen ..." class="form-control chzn-select" tabindex="2">
                      <option value="all">Pilih Rumpun</option>
                    <?php
$show = "";
$not_hide = 'style="display: none"';
if ($data_edit->kode_rumpun!="") {
  $not_hide = '';
  $show = "yes";
  $id_rumpun = $db->fetch_custom_single("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where id_level='1' and drd.kode in(select dw.id_induk
from  data_rumpun_dosen dw 
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where dw.id_level='2' and dwc.kode in($data_edit->kode_rumpun))");

                    $prodi = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd where id_level='1' ");
                    foreach ($prodi as $pr) {
                      if ($id_rumpun->kode==$pr->kode) {
                          echo "<option value='$pr->kode' selected>$pr->nama_rumpun</option>";
                      } else {
                          echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                      }
                    
                    }
} else {
                      $prodi = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd where id_level='1' ");
                    foreach ($prodi as $pr) {
                      echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                    
                    }
}

                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group show_sub" <?=$not_hide;?>>
                    <label for="Program studi" class="control-label col-lg-3">Sub Rumpun</label>
                    <div class="col-lg-9">
                    <select id="sub_rumpun_edit" name="sub_rumpun" data-placeholder="Pilih Sub Rumpun Dosen ..." class="form-control chzn-select" tabindex="2">
                      <?php
if ($show!="") {
  
$id_sub_rumpun = $db->fetch_custom_single("select dw.kode,dw.nama_rumpun
from  data_rumpun_dosen dw 
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where dw.id_level='2' and dwc.kode in($data_edit->kode_rumpun)");

                    $sub_rumpun = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where id_level='2' and id_induk='$id_rumpun->kode'");
                    foreach ($sub_rumpun as $pr) {
                      if ($id_rumpun->kode==$pr->kode) {
                          echo "<option value='$pr->kode' selected>$pr->nama_rumpun</option>";
                      } else {
                          echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                      }
                    
                    }
}
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group show_bidang" <?=$not_hide;?>>
                    <label for="Program studi" class="control-label col-lg-3">Bidang Ilmu</label>
                    <div class="col-lg-9">
                    <select id="bidang_ilmu_edit" name="bidang_ilmu" data-placeholder="Pilih Bidang Ilmu ..." class="form-control chzn-select" tabindex="2">
                       <?php
if ($show!="") {
                    $bidang = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where kode='$data_edit->kode_rumpun'");
                    foreach ($bidang as $pr) {
                       echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                    }
}
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->


            <div class="form-group">
                <label for="Aktif" class="control-label col-lg-3">Aktif </label>
                <div class="col-lg-9">
                <?php if ($data_edit->aktif=="Y") {
                ?>
                  <input name="aktif" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="aktif" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            
              <input type="hidden" name="id" value="<?=$data_edit->id_dosen;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> <?php echo $lang["cancel_button"];?></button>
                  </div>
                </div>
              </div><!-- /.form-group -->

            </form>

<script type="text/javascript">
    $(document).ready(function() {

$('#rumpun_edit').on('change', function() {
 $("#sub_rumpun_edit").chosen();
 $(".show_sub").fadeIn();
  if ($(this).val()!='all') {
        $("#sub_rumpun_edit").prop('required',true);
        $("#sub_rumpun_edit").trigger('chosen:updated');
        $.ajax({
      url : "<?=base_admin();?>modul/dosen/get_sub_rumpun.php",
      type: "post",
      data : {rumpun: $(this).val()},
      success:function(data) {
       // console.log(data);
        $("#sub_rumpun_edit").html(data);
        $("#sub_rumpun_edit").trigger('chosen:updated');
        $(".show_bidang").fadeOut();
        $("#bidang_ilmu_edit").html('');
        $("#bidang_ilmu_edit").trigger('chosen:updated');

      }
    });
  } else {

        $("#sub_rumpun_edit").prop('required',false);
        $("#sub_rumpun_edit").trigger('chosen:updated');
        $("#bidang_ilmu_edit").prop('required',false);
        $("#bidang_ilmu_edit").trigger('chosen:updated');
     $(".show_sub").fadeOut();
     $(".show_bidang").fadeOut();
  }

 });
$('#sub_rumpun_edit').on('change', function() {
 $("#bidang_ilmu_edit").chosen();
 $(".show_bidang").fadeIn();
  if ($(this).val()!='all') {
        $("#bidang_ilmu_edit").prop('required',true);
        $("#bidang_ilmu_edit").trigger('chosen:updated');
        $.ajax({
      url : "<?=base_admin();?>modul/dosen/get_bidang_ilmu.php",
      type: "post",
      data : {sub_rumpun: $(this).val()},
      success:function(data) {
       // console.log(data);
        $("#bidang_ilmu_edit").html(data);

        $("#bidang_ilmu_edit").trigger('chosen:updated');
      }
    });
  } else {
     $(".show_bidang").fadeOut();
        $("#bidang_ilmu_edit").prop('required',false);
        $("#bidang_ilmu_edit").trigger('chosen:updated');
  }

 });
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
      
    $("#edit_dosen").validate({
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
            
          nip: {
          required: true,
          //minlength: 2
          },
        
          nama_dosen: {
          required: true,
          //minlength: 2
          },
        
          kode_jur: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nip: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          nama_dosen: {
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
                            $('#modal_dosen').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000, function() {
                                 dtb_dosen.draw(false);
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
