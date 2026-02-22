<?php
session_start();
include "../../inc/config.php";

?>
<h6>Proses ini akan menghapus jadwal dan dosen pengajar kelas</h6>
      <form method="GET" id="form_jadwal_gen" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas_jadwal/kelas_jadwal_action.php?act=reset-jadwal">
                      <div class="form-group">
                        <label for="Kabupaten" class="control-label col-lg-3">Program Studi</label>
                        <div class="col-lg-9">
                                      <select name="jur" id="jur" data-placeholder="Pilih Program Studi ..." class="form-control chzn-select" tabindex="2" method="post" required="">
                                        <?php
                                 $akses_prodi = get_akses_prodi();
                                $jurusan = $db->query("select * from view_prodi_jenjang $akses_prodi");
                                    if ($jurusan->rowCount()<1) {
                                      echo "<option value='' selected>Group User Ini Belum Punya Akses Prodi</option>";
                                  } else if ($jurusan->rowCount()==1) {
                                    foreach ($jurusan as $dt) {
                                      echo "<option value='".$dt->kode_jur."' selected>$dt->jurusan</option>";
                                    }
                                  } else {
                                    echo "<option value=''>Pilih Program Studi</option>";
                                    foreach ($jurusan as $dt) {
                                     echo "<option value='".$dt->kode_jur."'>$dt->jurusan</option>";
                                    }
                                  }
                                ?>
                                        </select>
            </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Kabupaten" class="control-label col-lg-3">Semester</label>
                        <div class="col-lg-9">
                                      <select name="sem" id="sem" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" method="post">
                                    <?php 
                            foreach ($db->fetch_all("view_semester") as $isi) {
                                if ($isi->aktif==1) {
                                    echo "<option value='".$isi->id_semester."' selected>$isi->tahun_akademik</option>";
                                    $aktif = $isi->tahun_akademik;
                                } else {
                                    echo "<option value='".$isi->id_semester."'>$isi->tahun_akademik</option>";
                                }

                             } 
                        ?>
                                        </select>
            </div>
                      </div><!-- /.form-group -->

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="submit" class="btn btn-danger"><i class="fa fa-close"></i> Reset Jadwal</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>

                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript">
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
      $("#form_jadwal_gen").validate({
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
                url : $(this).attr("action"),
                type : 'post',
                success: function(responseText) {
                    $("#loadnya").hide();
                    console.log(responseText);
                    $('#modal_reset').modal('hide');
                    dataTable_jadwal.draw();
                }

            });
        }

    });
</script>