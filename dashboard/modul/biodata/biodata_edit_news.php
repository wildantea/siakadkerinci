<style type="text/css">
  .help-block {
    color: #dd4b39;
}
.isi_abstract > p {
  font-size: 15px;
}

.auto-center {
  margin-left: auto;
  margin-right: auto;
  display: block;
}
 .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
  .nav-tabs-custom {
    border: 1px solid #337ab7;
  }
    
 .nav-tabs > li > a {
    position: relative;
    padding-left: 30px; /* Space for the circle */
  }
  
.nav-tabs-custom>.nav-tabs>li {
    border-top: 0px solid transparent;
    margin-bottom: -2px;
    margin-right: 5px;
}
  .nav-tabs > li > a::before {
    content: attr(data-number);
    position: absolute;
    left: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    background-color: #27a65a; /* Number circle color */
    color: white;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    line-height: 20px;
    border-radius: 50%;
    display: inline-block;
  }

  /* Active tab styling */
  .nav-tabs > li.active > a, 
  .nav-tabs > li.active > a:hover, 
  .nav-tabs > li.active > a:focus {
    background-color: #3d8dbc !important;
    color: white !important;
  }

  /* Hover effect for inactive tabs */
  .nav-tabs > li:not(.active) > a:hover {
    background-color: rgba(61, 141, 188, 0.2); /* Light effect */
    border-radius: 5px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px;
    padding-left:0;
}
.select2-container .select2-selection--single {
    height: 34px;
}
</style>

        <section class="content-header">
            <h1>Biodata</h1>
            <ol class="breadcrumb">
                <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?=base_index();?>biodata">Biodata</a></li>
                <li class="active">Edit Biodata</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
<div class="row">
<div class="col-md-12">
 <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Edit Biodata</h3>
                        </div>
                        <div class="box-body">
                            <?php
                            if ($data_edit->is_submit_biodata=='N') {
                                ?>
<div class="callout callout-info">
  <h4>📢 Pemutakhiran Data Mahasiswa</h4>
  <p>
    Kepada seluruh mahasiswa,<br><br>
    Dalam rangka menjaga keakuratan data akademik dan administratif, kami mengimbau kepada seluruh mahasiswa untuk melakukan <strong>pemutakhiran dan kelengkapan data pribadi</strong> pada sistem akademik (SIAKAD).
  </p>
</div>
                                <?php
                            }
                            ?>

                            <form id="edit_biodata" method="post" class="form-horizontal" action="<?=base_admin();?>modul/biodata/save_biodata.php">
                                   <div class="callout callout-success">
                                            <h4>Informasi</h4>
                                            <p>- Silakan isi data diri Anda sesuai dengan <strong>KTP</strong> atau <strong>Kartu Keluarga (KK)</strong>.<br>
                                            - Pastikan seluruh kolom yang diberi tanda bintang merah <span style="color:#FF0000">*</span> diisi dengan benar.<br>
                                        - Selain data wajib anda disarankan untuk mengisi data lainya yang tersedia.</p>
                                        </div>

                                    <div class="callout callout-info">
                                            <h4>Pernyataan</h4>
                                            <p>- Klik di Kirim Data di Tab Pernyataan jika data anda sudah benar - benar sesuai dan lengkap</strong></p>
                                        </div>

                                <!-- Tabs -->
                                 <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs"  id="myTab">
                                    <?php
                                    $path_act = uri_segment(3);
                                    $array_url = array('alamat','orang_tua','wali','dokumen','pernyataan');
                                    ?>

                                    <li class="<?=($path_act=='datadiri')?'active':($path_act=='')?'active':(!in_array($path_act,$array_url))?'active':'';?>"  data-number="1"><a href="<?=base_index();?>biodata/edit/datadiri" data-number="1">Data Diri</a></li>

                                    <li class="<?=($path_act=='alamat')?'active':'';?>"><a href="<?=base_index();?>biodata/edit/alamat"  data-number="2">Alamat</a></li>
                                    <li class="<?=($path_act=='orangtua')?'active':'';?>"><a href="<?=base_index();?>biodata/edit/orangtua" data-number="3">Orang Tua</a></li>
                                     <li class="<?=($path_act=='dokumen')?'active':'';?>"><a href="<?=base_index();?>biodata/edit/dokumen"  data-number="4">Dokumen</a></li>
                                     <?php
                                      if (checkBiodataAllStatus($_SESSION['username'])) {
                                        ?>
                                         <li class="<?=($path_act=='pernyataan')?'active':'';?>"><a href="<?=base_index();?>biodata/edit/pernyataan"  data-number="5">PERNYATAAN</a></li>
                                         <?php
                                      }
                                     ?>
                                </ul>
                                <div class="tab-content">
                                    <!-- Data Diri Tab -->
                                    <div class="tab-pane active">
                                        <?php
                                            switch ($path_act) {
                                                case 'datadiri':
                                                    include "data_diri_new.php";
                                                    break;
                                                case 'alamat':
                                                    include "alamat.php";
                                                    break;
                                                case 'orangtua':
                                                    include "orangtua.php";
                                                    break;
                                                /*case 'wali':
                                                    include "wali.php";
                                                    break;*/
                                                case 'dokumen':
                                                    include "upload.php";
                                                    break;
                                                case 'pernyataan':
                                                    include "pernyataan.php";
                                                    break;
                                                default:
                                                    include "data_diri_new.php";
                                                    break;
                                            }
                                            
                                            ?>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?=$data_edit->mhs_id;?>">
                               
                              </div>
                            </form>
                        </div>
                    </div>
</div>
</div>
        </section>
<script type="text/javascript">

    $(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    $('#myTab a[href="#'+hash[1]+'"]').tab("show");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);
  } 
   
});  

$(document).ready(function() {
    // Initialize datepicker
    $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        viewMode: 'years',
        changeMonth: true,
        changeYear: true,
    }).on("change", function() {
        $(":input", this).valid();
    });



    // Initialize Select2
    $("#kecamatan").select2({
        ajax: {
            url: '<?=base_admin();?>modul/mahasiswa/get_kecamatan.php',
            dataType: 'json'
        },
        placeholder: "Cari Kecamatan/Kabupaten",
        width: "100%",
    });

    // Province and Kabupaten change handlers
    $("#provinsi_provinsi").change(function() {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/biodata/get_kabupaten.php",
            data: { provinsi: this.value },
            success: function(data) {
                $("#kabupaten_kabupaten").html(data);
                $("#kabupaten_kabupaten").trigger("chosen:updated");
            }
        });
    });

    $("#kabupaten_kabupaten").change(function() {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/biodata/get_kec.php",
            data: { id_kab: this.value },
            success: function(data) {
                $("#id_kec_tea").html(data);
                $("#id_kec_tea").trigger("chosen:updated");
            }
        });
    });

    // KPS toggle
    $("#a_terima_kps").change(function() {
        $('#no_kps').prop('readonly', this.value !== '1');
    });

    // Trigger validation on change
    $('select').on('change', function() {
        $(this).valid();
    });

    // Ignore hidden fields for validation except select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    // Form validation
   // Form validation
$("#edit_biodata").validate({

    errorClass: "help-block",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass("has-success").addClass("has-error");
        if ($(element).hasClass("chzn-select") || $(element).hasClass("select2")) {
            $("#" + $(element).attr("id") + "_chosen").addClass("has-error");
        }
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".form-group").removeClass("has-error").addClass("has-success");
        if ($(element).hasClass("chzn-select") || $(element).hasClass("select2")) {
            $("#" + $(element).attr("id") + "_chosen").removeClass("has-error");
        }
    },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("select2-hidden-accessible")) {
               element.parent().append(error);
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }  else if (element.hasClass("dosen-ke")) {
                  error.appendTo('.error-dosen');
            }
            else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
    focusInvalid: false, // Keep this false as we'll handle focus and scroll manually
    invalidHandler: function(event, validator) { // 'validator' object gives access to errors
        // Get the first invalid element
        var errors = validator.numberOfInvalids();
        if (errors) {
            var firstInvalidElement = $(validator.errorList[0].element);

            // Set focus to the first invalid element
            firstInvalidElement.focus();

            // Smoothly scroll the page to the first invalid element
            // Adjust the 'offset' value if you want more space above the element
            $('html, body').animate({
                scrollTop: firstInvalidElement.offset().top - 80 // Scroll to 80px above the element
            }, 500); // Animation speed in milliseconds
        }
    },
    rules: {
        nama: { required: true },
        nik: { required: true, minlength: 16, maxlength: 16 },
        kewarganegaraan: { required: true },
        id_jalur_masuk: { required: true },
        tmpt_lahir: { required: true },
        id_agama: { required: true },
        jln: { required: true },
        kode_pos: { required: true, maxlength: 5 },
        no_hp: { required: true },
        email: { required: true, email: true },
        nm_ibu_kandung: { required: true },
        id_jenis_sekolah: { required: true },
        nama_asal_sekolah: { required: true }
    },
    messages: {
        nama: { required: "Wajib di Isi" },
        nik: { required: "Wajib di Isi", minlength: "Must be 16 digits", maxlength: "Must be 16 digits" },
        kewarganegaraan: { required: "Wajib di Isi" },
        id_jalur_masuk: { required: "Wajib di Isi" },
        tmpt_lahir: { required: "Wajib di Isi" },
        id_agama: { required: "Wajib di Isi" },
        jln: { required: "Wajib di Isi" },
        kode_pos: { required: "Wajib di Isi" },
        no_hp: { required: "Wajib di Isi" },
        email: { required: "Wajib di Isi", email: "Please enter a valid email address" },
        nm_ibu_kandung: { required: "Wajib di Isi" },
        id_jenis_sekolah: { required: "Wajib di Isi" },
        nama_asal_sekolah: { required: "Wajib di Isi" }
    },
    submitHandler: function(form) {
        $("#loadnya").show();
        $(form).ajaxSubmit({
            type: "post",
            url: $(form).attr("action"),
            data: $("#edit_biodata").serialize(),
            success: function(data) {
                console.log(data);
                $("#loadnya").hide();
                if (data == "good") {
                    $(".notif_top_up").fadeIn(1000).fadeOut(1000, function() {
                       window.location='<?=base_index();?>biodata/view';
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