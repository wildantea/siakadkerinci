<?php
include "../../inc/config.php";
$data_edit = $db->fetch_single_row("beasiswa_mhs","id_beasiswamhs",$_POST['id_data']);
?>
  <style type="text/css"> .datepicker {z-index: 1200 !important; } </style>
            <form id="edit_pendaftaran_beasiswa" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pendaftaran_beasiswa/pendaftaran_beasiswa_action.php?act=up">
                            
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="nim_beasiswamhs" value="<?=$data_edit->nim_beasiswamhs;?>" class="form-control" required readonly>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="nama" class="control-label col-lg-2">Nama <span style="color: #FF0000">*</span></label>
                <div class="col-lg-10">
<?php
  $data = $db->query("select * from mahasiswa where nim='".$data_edit->nim_beasiswamhs."'");
  foreach ($data as $dt) {
?>
                  <input type="text" name="nama" value="<?=$dt->nama?>" class="form-control" readonly>
<?php
  }
?>
                </div>
              </div>
              <div class="form-group">
                <label for="IPK" class="control-label col-lg-2">IPK <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" name="ipk_beasiswamhs" value="<?=$data_edit->ipk_beasiswamhs;?>" class="form-control" required readonly>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Jenis Beasiswa" class="control-label col-lg-2">Jenis Beasiswa <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select id="id_beasiswajns" name="id_beasiswajns" data-placeholder="Pilih Jenis Beasiswa ..." class="form-control chzn-select" tabindex="2" required>
                     <option value=""></option>
                     <?php foreach ($db->fetch_all("beasiswa_jenis") as $isi) {

                        if ($data_edit->id_beasiswajns==$isi->id_beasiswajns) {
                          echo "<option value='$isi->id_beasiswajns' selected>$isi->jenis_beasiswajns</option>";
                        } else {
                        echo "<option value='$isi->id_beasiswajns'>$isi->jenis_beasiswajns</option>";
                          }
                     } ?>
                    </select>
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                <label for="Beasiswa" class="control-label col-lg-2">Beasiswa </label>
                <div class="col-lg-10">
                  <select id="id_beasiswa" name="id_beasiswa" data-placeholder="Pilih Beasiswa ..." class="form-control chzn-select" tabindex="2" >            
                  </select>
                </div>
              </div><!-- /.form-group -->   
             
              <input type="hidden" name="id" value="<?=$data_edit->id_beasiswamhs;?>">

              <div class="form-group">
                  <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                  <div class="col-lg-4">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-step-backward"></span> <?php echo $lang["cancel_button"];?></button>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-floppy-o"></span> <?php echo $lang["submit_button"];?></button>
                  </div>
              </div><!-- /.form-group -->
            </form>

<script type="text/javascript">
    $(document).ready(function() {
    
      //chosen select
      $(".chzn-select").chosen();
      $(".chzn-select-deselect").chosen({
          allow_single_deselect: true
      });
      
      $("#id_beasiswajns").change(function(){

            $.ajax({
            type : "post",
            url : "<?=base_admin();?>modul/pendaftaran_beasiswa/get_beasiswa_filter.php",
            data : {jenisbeasiswa:this.value},
            success : function(data) {
                $("#id_beasiswa").html(data);
                $("#id_beasiswa").trigger("chosen:updated");

            }
        });

      });        
    
    
      //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
      
    $("#edit_pendaftaran_beasiswa").validate({
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
            
          nim_beasiswamhs: {
          required: true,
          //minlength: 2
          },
        
          id_beasiswajns: {
          required: true,
          //minlength: 2
          },
        
          ipk_beasiswamhs: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim_beasiswamhs: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          id_beasiswajns: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
          ipk_beasiswamhs: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
    
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(this).attr("action"),
                data: $("#edit_pendaftaran_beasiswa").serialize(),
                success: function(data) {
                    $('#modal_pendaftaran_beasiswa').modal('hide');
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000);
                        $(".notif_top_up").fadeOut(1000, function() {
                               location.reload();
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
