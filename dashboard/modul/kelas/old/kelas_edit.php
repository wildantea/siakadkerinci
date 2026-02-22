<!-- Content Header (Page header) -->
 <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/plugins/iCheck/all.css">
      <style type="text/css">
        .red{
          color : red;
        }
      </style>
              <section class="content-header">
                  <h1>Kelas</h1>
                    <ol class="breadcrumb">
                        <li>
                        <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
                        </li>
                        <li>
                        <a href="<?=base_index();?>kelas">Kelas</a>
                        </li>
                        <li class="active">Edit Kelas</li>
                    </ol>
              </section>

              <!-- Main content -->
              <section class="content">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="box box-solid box-primary">
                          <div class="box-header">
                              <h3 class="box-title">Edit Kelas</h3>
                              <div class="box-tools pull-right">
                                  <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                              </div>
                          </div>


                      <div class="box-body">
                          <form id="edit_kelas" method="post" class="form-horizontal" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=up">
                            
              <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Nama Kelas</label>
                <div class="col-lg-10">
                  <input type="text" name="kls_nama" value="<?=$data_edit->kls_nama;?>" class="form-control" >
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Kode Paralel" class="control-label col-lg-2">Kode Paralel</label>
                <div class="col-lg-10">
                    <select name="kode_paralel" data-placeholder="Pilih Kode Paralel..." class="form-control chzn-select" tabindex="2" >
                      <option value=""></option>
                     <?php
                     $kl = $db->fetch_all("paralel_kelas_ref");
                     foreach ($kl as $kls) {
                      if ($kls->kode_paralel==$data_edit->kode_paralel) {
                          echo "<option value='$kls->kode_paralel' selected>$kls->nm_paralel</option>";
                      }else{
                          echo "<option value='$kls->kode_paralel'>$kls->nm_paralel</option>";
                      }
                     
                     }
                   ?>
                    </select>
                  </div>
            </div><!-- /.form-group -->
            <div class="form-group">
                        <label for="Mata Kuliah" class="control-label col-lg-2">Mata Kuliah</label>
                        <div class="col-lg-10">
              <select name="id_matkul" data-placeholder="Pilih Mata Kuliah..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php 
             foreach ($db->query("select m.id_matkul,m.kode_mk,m.nama_mk,k.nama_kurikulum 
                                          from matkul m join kurikulum k on m.kur_id=k.kur_id
                                          where k.kode_jur='".de(uri_segment(4))."'") as $isi) {

                  if ($data_edit->id_matkul==$isi->id_matkul) {
                    echo "<option value='$isi->id_matkul' selected>$isi->nama_mk</option>";
                  } else {
                  echo "<option value='$isi->id_matkul'>$isi->nama_mk</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Mata Kuliah Setara <?= de(uri_segment(4)) ?></label>
                        <div class="col-lg-10">
              <select name="id_matkul_setara" data-placeholder="Pilih Mata Kuliah Setara..." class="form-control chzn-select" tabindex="2" >
               <option value=""></option>
               <?php  foreach ($db->query("select m.id_matkul,m.kode_mk,m.nama_mk,k.nama_kurikulum 
                                          from matkul m join kurikulum k on m.kur_id=k.kur_id
                                          where k.kode_jur='".de(uri_segment(4))."'") as $isi) {


                  if ($data_edit->id_matkul_setara==$isi->id_matkul) {
                    echo "<option value='$isi->id_matkul' selected>$isi->nama_mk</option>";
                  } else {
                  echo "<option value='$isi->id_matkul'>$isi->nama_mk</option>";
                    }
               } ?>
              </select>
          </div>
                      </div><!-- /.form-group -->
          <input type="hidden" name="sem_id" value="<?= $data_edit->sem_id ?>"> 


          <div class="form-group">
              <label for="Peserta Maximal" class="control-label col-lg-2">Peserta Maximal</label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="peserta_max" value="<?=$data_edit->peserta_max;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Peserta Minimal" class="control-label col-lg-2">Peserta Minimal</label>
              <div class="col-lg-10">
                <input type="text" data-rule-number="true" name="peserta_min" value="<?=$data_edit->peserta_min;?>" class="form-control" >
              </div>
          </div><!-- /.form-group -->
          
            <div class="form-group">
                <label for="is_open" class="control-label col-lg-2">is_open</label>
                <div class="col-lg-10">
                <?php if ($data_edit->is_open=="Y") {
                ?>
                  <input name="is_open" class="make-switch" type="checkbox" checked>
                <?php
              } else {
                ?>
                  <input name="is_open" class="make-switch" type="checkbox">
                <?php
              }?>

                </div>
            </div><!-- /.form-group -->
            
          <hr>
           <b>Komponen Penilaian</b>
           <hr>  
                 <div class="form-group">
              <label  class="control-label col-lg-2"></label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                   <a class="btn btn-success" data-toggle="modal" data-target="#modalKomponen"><i class="fa fa-plus"></i> Tambah Komponen</a>
                </div>               
              </div>
            </div>
           <div id="list_komponen">
              <?php
              foreach ($db->query("select k.*,kp.id as id_kls_komponen,kp.id_kelas,kp.id_komponen,kp.nilai
                                   from komponen_nilai k join kelas_penilaian kp on k.id=kp.id_komponen
                                   where kp.id_kelas='$id_kelas' ") as $k) {
               ?>
                <div class="form-group" id="komponen-<?= $k->id_komponen ?>">
                  <label  class="control-label col-lg-2"><?= $k->nama_komponen ?></label>
                  <div class="col-lg-10">
                    <div class="input-group" style="width:150px">
                    <input type="number" value="<?= $k->nilai ?>" class="form-control" name="komponen_<?= $k->id_komponen ?>" id="komponen_<?= $k->id_komponen ?>" >
                    <span class="input-group-addon">%</span>
                    <button onclick="hapus_komponen(<?= $k->id_komponen ?>)"  class='btn btn-primary ' style='float:right;position:relative;right:-3px' ><i class='fa fa-minus'></i></button>
                  </div>               
                  </div>
                </div><!-- /.form-group -->
               <?php
              }
           ?>
          </div>   
       
       
         
          
          <div class="form-group">
              <label for="is_open" class="control-label col-lg-2">Open</label>
              <div class="col-lg-10">
                <input name="is_open" class="make-switch" type="checkbox" checked>
              </div>
          </div><!-- /.form-group -->
            <hr>
           <b style="text-aligen:center">Dosen Ajar</b>
           <hr>  
            <div class="form-group">
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Dosen</label>
                        <div class="col-lg-10">
              <a class="btn btn-success " style="cursor:pointer" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Tambah Dosen</a>
              <?php
               $qd = $db->query("select dk.dosen_ke, d.nip,d.nama_dosen,d.gelar_depan,d.gelar_belakang,
                  j.nama_jur from dosen_kelas dk 
                      join dosen d on dk.id_dosen=d.nip
                      join jurusan j on j.kode_jur=d.kode_jur
                      where dk.id_kelas='".$data_edit->kelas_id."'");
               $jml_dosen =  $qd->rowCount();
              ?>
              <input type="hidden" name='jml_dosen' value='<?= $jml_dosen ?>' id='jml_dosen'>
              <table class="table">
                <thead>
                     <tr>
                      <th>NIP</th>
                      <th>Nama</th>
                      <th>Jurusan</th>
                      <th>Dosen Ke</th>                    
                      <th>Input Nilai Online</th>
                     </tr>
                </thead>
                <tbody id="dosen_ajar">
                <?php
               
                $i=1;
                foreach ($qd as $k) {
                echo "<tr id='dosen_".$i."'>                     
                      <td> <input type='hidden' name='dosen_".$i."' value='$k->nip'>$k->nip</td>
                      <td>$k->nama_dosen</td>
                      <td>$k->nama_jur</td>  
                      <td><input type='text' style='width:100px' value='$k->dosen_ke' name='dosen_input_".$i."'><td>                  
                      <td><input type='checkbox' value='Y' name='dapat_input_".$i."' checked>
                      <button onclick='hapus_dosen(".$i.")' type='submit' class='btn btn-primary ' style='float:right' ><i class='fa fa-minus'></i></button></td>
                     </tr>";
                     $i++;
                    } 
                ?>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="form-group">
              <label for="Catatan" class="control-label col-lg-2">Catatan</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="catatan" ><?= $data_edit->catatan ?></textarea>
              </div>
          </div><!-- /.form-group -->
          
          
                            <input type="hidden" name="id" value="<?=$data_edit->kelas_id;?>">
                            <div class="form-group">
                                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                                <div class="col-lg-10">
                                  <input type="submit" class="btn btn-primary " value="submit">
                                </div>
                            </div><!-- /.form-group -->
                          </form>
                          <a href="<?=base_index();?>kelas" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                      </div>
                  </div>
              </div>
              </section><!-- /.content -->
               <div id="myModal" class="modal fade" role="dialog" >
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih Dosen</h4>
                                  </div>
                                  <div class="modal-body">
                                     <table id="dtb_manual" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th></th>                                            
                                            <th>NIP</th>
                                            <th>Nama</th>   
                                            <th>Jurusan</th>                                        
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $q= $db->query("select d.id_dosen, d.nip,d.nama_dosen,j.nama_jur from dosen d 
                                                    join jurusan j on d.kode_jur=j.kode_jur ");
                                        foreach ($q as $k) {
                                         echo "
                                            <tr>
                                            <th><button class='btn btn-success' onclick='pilih_dosen($k->id_dosen)'><i class='fa fa-plus'></i> Pilih Dosen</button></th>                                            
                                            <th>$k->nip</th>
                                            <th>$k->nama_dosen</th>    
                                            <th>$k->nama_jur</th>                                       
                                          </tr>
                                             ";
                                        }
                                        ?>
                                      </tbody>
                                  </table>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                  </div>
                                </div>

                              </div>
                            </div>
 <div id="modalKomponen" class="modal fade" role="dialog" >
                              <div class="modal-dialog" style="width:70%">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Pilih Komponen Penilaian</h4>
                                  </div>
                                  <div class="modal-body">
                                     <table id="dtb_manual" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                            <th style="width: 20px"></th>                                            
                                            <th>Nama Komponen</th>                                     
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $q= $db->query("select * from komponen_nilai where isShow='1' ");
                                        foreach ($q as $kpp) {
                                         echo "
                                            <tr>
                                            <th><input type='checkbox' name='komponen[]' value='$kpp->id==$kpp->nama_komponen' class='minimal data-komponen'></th>                                            
                                            <th>$kpp->nama_komponen</th>                                 
                                          </tr>
                                             ";
                                        }
                                        ?>
                                      </tbody>
                                  </table>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-success" id="btn-komponen" data-dismiss="modal">Pilih Komponen</button>
                                  </div>
                                </div>

                              </div>
                            </div>

<script src="<?= base_url() ?>dashboard/assets/plugins/iCheck/icheck.min.js"></script> 
<script type="text/javascript">
    var tot = 0;
    var nilai =[];
    nilai["a-terstruktur"] = 0;
    nilai["a-lain-lain"] = 0;
    nilai["a-mandiri"] =0;
    nilai["a-uts"] =0;
    nilai["a-uas"] =0;
    nilai["a-presensi"] = 0;
    var jml_dosen =$("#jml_dosen").val();
    function hitung(jml,ket){
      
       nilai["a-"+ket]=parseInt(jml);
       tot = parseInt(nilai["a-terstruktur"]) + parseInt(nilai["a-lain-lain"]) + parseInt(nilai["a-mandiri"]) + parseInt(nilai["a-uts"]) + parseInt(nilai["a-uas"]) + parseInt(nilai["a-presensi"]);
       
       if (tot<=100) {
            $("#total").removeClass("red");
           $("#total").html(tot+" %");
       }else{
         $("#total").addClass("red");
         $("#total").html(tot+" % * MELEBIHI PRESENTASE");
         $("#"+ket).val('');
         $("#"+ket).focus();
         tot = tot - parseInt(jml);
       }
      
    }
    function pilih_dosen(nip){
        // $('#loadnya').show();
        jml_dosen++;
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas/add_dosen.php',
            data: 'nip='+nip+'&jml_dosen='+jml_dosen,
            success: function(result) {
              // $('#loadnya').hide();
              $('#myModal').modal('hide');
              $("#dosen_ajar").append(result);
              $("#jml_dosen").val(jml_dosen);
            },
            //async:false
        });
    }


    function hapus_dosen(id){
      $("#dosen_"+id).remove();
    }

    function hapus_komponen(id) {
      $("#komponen-"+id).remove();
      //setInterval(function(){ $("#komponen-"+id).remove(); }, 3000);
      
    }

    $(function () {
     $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
   });

    $(document).ready(function() {
     
    $('#btn-komponen').on('click', function () {
       //var arr = [];
      // $("#list_komponen").html('');
       $('.data-komponen:checked').each(function () {
          var ru = $(this).val().split("==");
          if(($(this).prop("checked") == true ) && ($("#"+ru[0]).length==0)){
               $("#komponen-"+ru[0]).remove();
               $("#list_komponen").append(" <div class='form-group' id='komponen-"+ru[0]+"'><label  class='control-label col-lg-2'>"+ru[1]+"</label><div class='col-lg-10'><div class='input-group' style='width:100px'><input type='number' class='form-control' style='width:100px' name='komponen_"+ru[0]+"' id='komponen_"+ru[0]+"' ><span class='input-group-addon'>%</span><button onclick='hapus_komponen(\""+ru[0]+"\")'  class='btn btn-primary ' style='float:right;position:relative;right:-3px' ><i class='fa fa-minus'></i></button></div></div></div>");  
            }          
       });
     });
    
    $("#edit_kelas").validate({
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
                data: $("#input_kelas").serialize(),
                success: function(data) {
                    console.log(data);
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top").fadeIn(1000);
                        $(".notif_top").fadeOut(1000, function() {
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