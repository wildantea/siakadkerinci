 <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/plugins/iCheck/all.css">
<style type="text/css">
  .red{
    color : red;
  }
</style>
<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Kelas</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>kelas">Kelas</a>
            </li>
            <li class="active">Add Kelas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Add Kelas</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <form id="input_kelas" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/kelas/kelas_action.php?act=in">
              <input type="hidden" value="<?= de(uri_segment(4)) ?>" name="sem_id">
             <hr>
           <b>Info Kelas</b>
           <hr>       
              <div class="form-group">
                <label for="Nama Kelas" class="control-label col-lg-2">Nama Kelas *</label>
                <div class="col-lg-10">
                  <input type="text" name="kls_nama" required placeholder="Nama Kelas" class="form-control" >
                </div>
              </div>
              
            <div class="form-group">
                <label for="Kode Paralel" class="control-label col-lg-2">Kode Paralel *</label>
                <div class="col-lg-10">
                  <select name="kode_paralel" required data-placeholder="Pilih Kode Paralel ..." class="form-control chzn-select" tabindex="2" >
                    
                   <?php
                     $kl = $db->fetch_all("paralel_kelas_ref");
                     foreach ($kl as $kls) {
                       echo "<option value='$kls->kode_paralel'>$kls->nm_paralel</option>";
                     }
                   ?>
                 

                  </select>
                </div>
            </div>
            <div class="form-group">
              <label for="Mata Kuliah" class="control-label col-lg-2">Mata Kuliah *</label>
              <div class="col-lg-10">
                <select name="id_matkul" required data-placeholder="Pilih Mata Kuliah ..." class="form-control chzn-select" tabindex="2" >
                   <option value="" selected>-Pilih Matakuliah-</option>
                   <?php foreach ($db->query("select m.id_matkul,m.kode_mk,m.nama_mk,k.nama_kurikulum 
                                              from matkul m join kurikulum k on m.kur_id=k.kur_id
                                              where k.kode_jur='".de(uri_segment(3))."'") as $isi) {
                      echo "<option value='$isi->id_matkul'>$isi->kode_mk - $isi->nama_mk (Kurikulum : $isi->nama_kurikulum)</option>";
                   } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="Mata Kuliah Setara" class="control-label col-lg-2">Mata Kuliah Setara *</label>
              <div class="col-lg-10">
                <select name="id_matkul_setara" required data-placeholder="Pilih Mata Kuliah Setara ..." class="form-control chzn-select" tabindex="2" >
                  <option value="" selected>-Pilih Matakuliah-</option>
                    <?php foreach ($db->query("select m.id_matkul,m.kode_mk,m.nama_mk,k.nama_kurikulum 
                                          from matkul m join kurikulum k on m.kur_id=k.kur_id
                                          where k.kode_jur='".de(uri_segment(3))."'") as $isi) {
                    echo "<option value='$isi->id_matkul'>$isi->kode_mk - $isi->nama_mk (Kurikulum : $isi->nama_kurikulum)</option>";
                  } 
                 ?>
                </select>
              </div>
            </div>


          <div class="form-group">
              <label for="Peserta Maximal" class="control-label col-lg-2">Peserta Maximal *</label>
              <div class="col-lg-10">
                <input type="number" required data-rule-number="true" name="peserta_max" placeholder="Peserta Maximal" class="form-control" >
              </div>
          </div>
          
          <div class="form-group">
              <label for="Peserta Minimal" class="control-label col-lg-2">Peserta Minimal *</label>
              <div class="col-lg-10">
                <input type="number" required data-rule-number="true" name="peserta_min" placeholder="Peserta Minimal" class="form-control" >
              </div>
          </div>

          <div class="form-group">
              <label for="is_open" class="control-label col-lg-2">Open</label>
              <div class="col-lg-10">
                <input name="is_open" class="make-switch" type="checkbox" checked>
              </div>
          </div>
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
             
           </div>
     
          
         <!--  <div class="form-group">
              <label  class="control-label col-lg-2">Presensi *</label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="presensi" id="presensi" onchange="hitung(this.value, 'presensi')">
                <span class="input-group-addon">%</span>
              </div>
               
              </div>
          </div>
           <div class="form-group">
              <label  class="control-label col-lg-2">Terstruktur *</label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="terstruktur" id="terstruktur" onchange="hitung(this.value, 'terstruktur')">
                <span class="input-group-addon">%</span>
              </div>
               
              </div>
          </div>
          <div class="form-group">
              <label  class="control-label col-lg-2">Mandiri *</label>
              <div class="col-lg-10">
               <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="mandiri" id="mandiri" onchange="hitung(this.value,'mandiri')">
                <span class="input-group-addon">%</span>
              </div>
              </div>
          </div>
          <div class="form-group">
              <label  class="control-label col-lg-2">Lain-lain *</label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="lain_lain" id="lain-lain" onchange="hitung(this.value,'lain-lain')">
                <span class="input-group-addon">%</span>
              </div>
              </div>
          </div>

          <div class="form-group">
              <label  class="control-label col-lg-2">UTS *</label>
              <div class="col-lg-10">
               <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="uts" id="uts" onchange="hitung(this.value,'uts')">
                <span class="input-group-addon">%</span>
              </div>
              </div>
          </div>
          <div class="form-group">
              <label  class="control-label col-lg-2">UAS *</label>
              <div class="col-lg-10">
                <div class="input-group" style="width:100px">
                <input type="number" required class="form-control" name="uas" id="uas" onchange="hitung(this.value,'uas')">
                <span class="input-group-addon">%</span>
              </div>
              </div>
          </div>
          <div class="form-group">
              <label  class="control-label col-lg-2">Total</label>
              <div class="col-lg-10">
                <div class="input-group" style="float:left">
                 <span id="total">0 %</span>
              </div>
              </div>
          </div> -->
       
         
       
            <hr>
           <b style="text-aligen:center">Dosen Ajar</b>
           <hr>  
            <div class="form-group">
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Dosen</label>
                        <div class="col-lg-10">
              <a class="btn btn-success " style="cursor:pointer" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Tambah Dosen</a>
              <input type="hidden" name='jml_dosen' value='0' id='jml_dosen'>
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
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="form-group">
              <label for="Catatan" class="control-label col-lg-2">Catatan</label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="catatan" ></textarea>
              </div>
          </div>
          
                      
              <div class="form-group">
                <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-10">
                  <input type="submit" class="btn btn-primary " value="submit">
                </div>
              </div>

            </form>

            <a href="<?=base_index();?>kelas?jur=<?= uri_segment(3) ?>&sem=<?= uri_segment(4) ?>" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

          </div>
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
    var jml_dosen =0;
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
    
    $("#input_kelas").validate({
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
