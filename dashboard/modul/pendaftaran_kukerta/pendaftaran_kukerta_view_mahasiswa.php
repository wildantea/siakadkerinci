<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Pendaftaran Kukerta
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>pendaftaran-kukerta">Pendaftaran Kukerta</a></li>
    <li class="active">Pendaftaran Kukerta</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
   <div class="box">
    <div class="box-body table-responsive">
      <?php
     // print_r($_SESSION);
      $nim = $_SESSION['username'];
      $check = $db->check_exist('kkn',array('nim'=>$nim));

      $batas_sks = $db->fetch_all('batas_sks');
      foreach ($batas_sks as $ky) {
        if($ky->ket == 'kukerta'){
          $sks = $ky->jlm_sks;
        }
      }
      // $check_sks = $db->query("SELECT SUM(sks) as total_sks FROM krs_detail  WHERE nim='$nim'");
      // foreach ($check_sks as $key) {
      //   $total_sks = $key->total_sks;
      // }

      $mhs_pindahan    = cek_mhs_pindahan($nim);
      $status_semester = cek_status_semester($nim);
      $total_sks       = get_total_sks($nim);
      $sudah_ambil_kukerta = cek_sudah_ambil_mk_kukerta($nim);
      $data_mhsx = $db->query("select `m`.`nim` AS `nim`,`m`.`nama` AS `nama`,`m`.`mulai_smt` AS `mulai_smt`,`m`.`jk` AS `jk`,`jd`.`id_jenis_daftar` AS `id_jenis_daftar`,`jd`.`nm_jns_daftar` AS `nm_jns_daftar`,ifnull(`jk`.`ket_keluar`,'Aktif') AS `jenis_keluar`,`view_semester`.`angkatan` AS `angkatan`,`m`.`mhs_id` AS `mhs_id`,`vpj`.`kode_jur` AS `jur_kode`,`view_dosen`.`nip` AS `nip_dosen_pa`,`view_dosen`.`dosen` AS `dosen_pa`,`vpj`.`jurusan` AS `jurusan` from (((((`mahasiswa` `m` join `view_prodi_jenjang` `vpj` on((`m`.`jur_kode` = `vpj`.`kode_jur`))) left join `jenis_keluar` `jk` on((`m`.`id_jns_keluar` = `jk`.`id_jns_keluar`))) left join `view_dosen` on((`m`.`dosen_pemb` = `view_dosen`.`nip`))) join `view_semester` on((`m`.`mulai_smt` = `view_semester`.`id_semester`))) join `jenis_daftar` `jd` on((`m`.`id_jns_daftar` = `jd`.`id_jenis_daftar`))) where nim='".$_SESSION['username']."' ");
        foreach($data_mhsx as $data_mhs){
               $check_if_bayar = $db->fetch_custom_single("select fungsi_cek_pembayaran_periode(".get_sem_aktif_kkn().",".$data_mhs->jur_kode.",".$data_mhs->nim.") as is_bayar");
                $affirmasi = afirmasi_krs($data_mhs->nim,get_sem_aktif_kkn());
               $qc = $db->query("SELECT * FROM keu_tagihan_mahasiswa m 
                WHERE m.nim='$data_mhs->nim'
                AND m.periode='".get_sem_aktif_kkn()."' "); 
                $ada_tagihan = $qc->rowCount();
               //echo "$check_if_bayar->is_bayar , $ada_tagihan , ".var_dump($affirmasi);
             
              if (($check_if_bayar->is_bayar=='0' || $ada_tagihan==0 ) && (!$affirmasi) ){
                 $sudah_bayar = 0;
              }else{
                 $sudah_bayar = 1;
              }
        }
       // echo " = $sudah_bayar";
    //  echo "$status_semester";
     // if($sks <= $total_sks){
      // $validasi = array(
      //             "semester"
      //  );
       // echo "$status_semester , $sudah_bayar, $total_sks, $sudah_ambil_kukerta";
      if (($status_semester>=6 || $mhs_pindahan ) && $sudah_bayar=='1' && $total_sks>=80 && $sudah_ambil_kukerta) {


      //cek apakah data sudah ada atau belum
        if($check != false){
          $data = $db->query("select pk.priode,lk.nama_lokasi,p.nim,m.nama,f.nama_resmi,j.nama_jur,p.id_kkn from kkn p
 left join mahasiswa m on p.nim=m.nim left join fakultas f on p.kode_fak=f.kode_fak left join jurusan j on p.kode_jur=j.kode_jur 
left join priode_kkn pk on p.id_priode=pk.id_priode left join lokasi_kkn lk on p.id_lokasi=lk.id_lokasi where m.nim=?",array("cm.nim" => $nim));
          // echo "select pk.priode,lk.nama_lokasi,p.nim,m.nama,f.nama_resmi,j.nama_jur,p.id_kkn from kkn p 
          // left join mahasiswa m on p.nim=m.nim inner join fakultas f on p.kode_fak=f.kode_fak inner join jurusan j 
          // on p.kode_jur=j.kode_jur inner join priode_kkn pk on p.id_priode=pk.id_priode inner join lokasi_kkn lk 
          // on p.id_lokasi=lk.id_lokasi where m.nim='$nim' ";
          foreach ($data as $dt){
            ?>
            <div class="alert alert-info" role="alert">
              <article>
                <strong>Perhatian!</strong> Pastikan data yang sudah anda masukan sudah benar
              </article>
            </div>
            <div class="panel panel-success panel-xs">
              <div class="panel-heading">
                <h2 align="center">Anda Telah Mengajukan Permohonan</h2>
              </div>

              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table" border="0" width="100%" margin="5px">
                    <tr>
                      <div class="form-group">
                        <td>
                          <a data-id='<?=$dt->id_kkn?>'  class="btn btn-default edit_data"><i class="fa fa-pencil"> Edit</i></a>
                          <a target="_BLANK" href="<?= base_url() ?>dashboard/modul/pendaftaran_kukerta/cetak_surat_pernyataan.php?n=<?= en($dt->nim) ?>" class="btn btn-primary"><i class="fa fa-print"> Cetak Surat Pernyataan</i></a>
                        </td>
                      </div>
                    </tr>
                    <tr>
                      <div class="form-group">
                        <td>
                          <label for="nim" class="label-control col-lg-1">Nim</label>
                          <div class="col-lg-11">
                            <input class="form-control" type="text" name="nim" value="<?=$dt->nim?>" readonly>
                          </div>
                        </td>
                      </div>
                    </tr>
                    <tr>
                      <div class="form-group">
                        <td>
                          <label for="nama" class="label-control col-lg-1">Nama</label>
                          <div class="col-lg-11">
                            <input class="form-control" type="text" name="nama" value="<?=$dt->nama?>" readonly>
                          </div>
                          <td>
                          </div>
                        </tr>
                        <tr>
                          <td>
                            <label for="jurusan" class="label-control col-lg-1">Priode</label>
                            <div class="col-lg-11">
                              <input class="form-control" type="text" name="id_priode" value="<?=$dt->priode?>" readonly>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label for="Perusahaan" class="label-control col-lg-1">Lokasi</label>
                            <div class="col-lg-11">
                              <input class="form-control" type="text" name="id_lokasi" value="<?=$dt->nama_lokasi?>" readonly>
                            </textarea>
                          </div>
                        </td>
                      </tr>           
                    </table>
                  </div>
                </div>
              </div>
              <?php
            }
          } else{
           // echo "string";
            $data = $db->query("select * from mahasiswa m inner join jurusan j on m.jur_kode=j.kode_jur
              inner join fakultas f on f.kode_fak=j.fak_kode where nim=?",array("nim" => $nim));
            foreach ($data as $dt) { 
              ?>
              <form id="edit_pendaftaran_kukerta" method="post" enctype="multipart/form-data" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_action.php?act=in_mhs">
                <input type="hidden" name="kode_jur" value="<?= $dt->kode_jur ?>">
                <input type="hidden" name="kode_fak" value="<?= $dt->kode_fak ?>">
                <div class="form-group">
                  <label for="nim" class="control-label col-lg-2">NIM</label>
                  <div class="col-lg-6">
                    <input id="nim" type="text" name="nim" class="form-control" value="<?=$nim?>" readonly>
                  </div>
                </div>
                <div class="form-group"> 
                    <label for="Lokasi" class="control-label col-lg-2">Lokasi <span style="color:#FF0000">*</span></label>
                    <div class="col-lg-10">
                      <select id="id_lokasi" name="id_lokasi" data-placeholder="Pilih Lokasi Kukerta ..." class="form-control chzn-select" tabindex="2" required="">
                       <option value="">Pilih Lokasi</option>
                       <?php
                       foreach ($db->query("select l.* from lokasi_kkn l left join priode_kkn p on p.id_priode=l.id_periode
                        where p.aktif='1'  ") as $isi) {
                        echo "<option value='$isi->id_lokasi'>$isi->nama_lokasi</option>";
                      } ?>
                    </select> 
                  </div>
                </div>
               <div class="form-group" style="display: none">
                <label for="Priode" class="control-label col-lg-2">Priode <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <select id="id_priode" name="id_priode" data-placeholder="Pilih Priode Kukerta ..." class="form-control chzn-select" tabindex="2">
                   <option value="all">Semua</option>
                   <?php
                   foreach ($db->fetch_all("priode_kkn") as $isi) {
                    if ($isi->aktif=='1') {
                    //  $id_aktif = $isi->id_priode;
                      echo "<option value='$isi->id_priode' selected>$isi->priode</option>";
                    }
                    
                  } ?>
                </select>
              </div>
            </div> 
                <div class="form-group">
                  <label for="kode_fakultas" class="control-label col-lg-2">Fakultas <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-10">
                    <input type="text"  value="<?=$dt->nama_resmi;?>" class="form-control" readonly>

                  </div>
                </div>
                <div class="form-group">
                  <label for="kode_jur" class="control-label col-lg-2">Jurusan <span style="color:#FF0000">*</span></label>
                  <div class="col-lg-10">
                   <input type="text"value="<?= $dt->nama_jur;?>" class="form-control" readonly>

                 </div>
               </div>


              
             <div class="form-group">
                <label for="Priode" class="control-label col-lg-2">Priode Pendaftaran <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <?php
                  $qp = $db->query("select * from priode_kkn where aktif='1' ");
                  foreach ($qp as $kp) {
                     echo "<input type='text' class='form-control' value='".tgl_indo($kp->tgl_awal)." s/d ".tgl_indo($kp->tgl_akhir)."' readonly>"; 
                  }
                  ?>
              </div>
            </div><!-- /.form-group -->

                        
          <div class="form-group">
            <div class="col-lg-12">
              <div class="modal-footer"> <button type="submit" class="btn btn-primary">Daftar Kukerta</button>
              </div>
            </div>
          </div><!-- /.form-group -->

        </form>
        <?php
      }
    } 
  }else { 
    // if ($status_semester>=6 && $sudah_bayar=='1' && $total_sks>80) {
    ?>
    <h3 class="alert alert-warning">Anda Belum Bisa Mendaftar Kukerta, karena memiliki catatan sebagai berikut :</h3>
    <?php
    if (($status_semester<6) &&  !$mhs_pindahan) {
       ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Status semester anda sekarang adalah semester <?= $status_semester ?>, Kukerta bisa di ambil minimal semester 6 </strong>
        </article>
      </div>
      <?php     
    }

    if ($sudah_bayar=='0') {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Anda belum melakukan pembayaran SPP pada semester ini</strong>
        </article>
      </div>
      <?php    
    }

    if ($total_sks<80) {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Total SKS yang sudah anda ambil <?= $total_sks ?>, sedangkan syarat minimum mengambil Kukerta adalah 80 SKS </strong>
        </article>
      </div>
      <?php    
    } 

    if (!$sudah_ambil_kukerta) {
      ?>
      <div class="alert alert-danger" role="alert">
        <article>
          <strong>Anda belum mengambil Mata Kuliah Kukerta </strong>
        </article>
      </div>
      <?php    
    } 
    
  }
  ?>
</div><!-- /.box-body -->
</div>
</div>
</td>
<!-- Modal Tambah Data -->
<div class="modal" id="modal_pendaftaran_kukerta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Edit Pendaftaran Ppl</h4> </div> <div class="modal-body" id="isi_pendaftaran_kukerta"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<div id="modal_kukerta" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Pendaftaran Gagal</h4>
      </div>
      <div class="modal-body" id="respon_kukerta">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</section><!-- /.content -->

<script type="text/javascript">
  $("#nim").on('input',function(){

    $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/list_cuti_mahasiswa/get_fakultas_jurusan.php",
      data : {nim:this.value},
      success : function(data) {
        $("#form_civitas").html(data);
        $("#form_civitas").trigger("chosen:updated");

      }
    });

  });

  $(".table").on('click','.edit_data',function(event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');

    $.ajax({
      url : "<?=base_admin();?>modul/pendaftaran_kukerta/pendaftaran_kukerta_edit.php",
      type : "post",
      data : {id_data:id},
      success: function(data) {
        $("#isi_pendaftaran_kukerta").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_pendaftaran_kukerta').modal({ keyboard: false,backdrop:'static' });

  });

  $(document).ready(function() {

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

    $("#tgl1").datepicker({
      format: "yyyy-mm-dd",
    });
    
    $("#tgl2").datepicker({
      format: "yyyy-mm-dd",
    });

    $("#edit_pendaftaran_kukerta").validate({
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
        
        jenis_keluar: {
          required: true,
          //minlength: 2
        },
        
        tgl_keluar: {
          required: true,
          //minlength: 2
        }
        
        
      },
      messages: {

        nim: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        jenis_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        },
        
        tgl_keluar: {
          required: "This field is required",
          //minlength: "Your username must consist of at least 2 characters"
        }
        
        
      },

      submitHandler: function(form) {
        $("#loadnya").show(); 
        $(form).ajaxSubmit({
          type: "POST", 
          url: $(this).attr("action"),
          data: $("#edit_pendaftaran_kukerta").serialize(),
          success: function(data) {
            $('#modal_pendaftaran_kukerta').modal('hide');
            $("#loadnya").hide();
            if (data == "good") {
              $(".notif_top").fadeIn(1000);
              $(".notif_top").fadeOut(1000, function() {
                location.reload();
              });
            }  
            // else if (data == "die") {
            //   $("#isi_informasi").html(data);
            //   $("#informasi").modal("show");
            // } 
            else {
              $("#modal_pendaftaran_kukerta").modal("hide");
              $("#respon_kukerta").html(data);
              $("#modal_kukerta").modal("show");
                        //$(".errorna").fadeIn();
                        // $(".notif_top").fadeIn(1000);
                        // $(".notif_top").fadeOut(1000, 
                        //   function() {
                        //    // location.reload();
                        //   });
                      }
                    }
                  });
      }
    });
  });
</script>