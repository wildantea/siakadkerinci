<?php
session_start();
include "../../inc/config.php";
$data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",$_POST['id_data']);
if ($_POST['jenis']=='surat') {
  $template = $data_edit->isi_template_surat;
} elseif ($_POST['jenis']=='sk_penguji') {
  $template = $data_edit->isi_sk_penguji;
} elseif ($_POST['jenis']=='sk_pembimbing') {
  $template = $data_edit->isi_sk_pembimbing;
}
?>
<style>
  .center-ckeditor {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
 .copy-btn {
    background-color: #007bff;
    color: white;
    border: none;
    display: inline-flex;
        margin-top: 5px;
    padding: 5px 10px;
    cursor: pointer;
  }
  .center-ckeditor textarea {
    max-width: 100%;
  }

  </style>
   <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
           <form id="edit_template" method="post" class="form-horizontal" action="<?=base_admin();?>modul/pengaturan_pendaftaran/pengaturan_pendaftaran_action.php?act=up_template">

<div class="form-group">
 <div class="col-lg-12">
    <span class="copy-btn" data-type="text" data-text="{{nip}}"><i class="fa fa-copy"></i> NIP/Nomor Pegawai</span> 
    <span class="copy-btn" data-type="text" data-text="{{nama}}"><i class="fa fa-copy"></i> Nama Dosen/Pegawai</span> 
    <span class="copy-btn" data-type="text" data-text="{{nidn_dosen}}"><i class="fa fa-copy"></i> NIDN Dosen</span>
    <span class="copy-btn" data-type="text" data-text="{{nama_unit}}"><i class="fa fa-copy"></i> Unit</span>
    <span class="copy-btn" data-type="text" data-text="{{alamat_rumah}}"><i class="fa fa-copy"></i> Alamat Rumah</span>
    <span class="copy-btn" data-type="text" data-text="{{kota_kab_lahir}}"><i class="fa fa-copy"></i> Kota/Kab Tempat Lahir</span>
    <span class="copy-btn" data-type="text" data-text="{{tgl_lahir}}"><i class="fa fa-copy"></i> Tanggal Lahir</span>
    <span class="copy-btn" data-type="text" data-text="{{jenis_kelamin}}"><i class="fa fa-copy"></i> Jenis Kelamin</span>
    <span class="copy-btn" data-type="text" data-text="{{nama_unit_dt}}"><i class="fa fa-copy"></i> Unit DT(Dosen)</span>
    <span class="copy-btn" data-type="text" data-text="{{tahun}}"><i class="fa fa-copy"></i> Digit Tahun</span>
    <span class="copy-btn" data-type="text" data-text="{{bulan_romawi}}"><i class="fa fa-copy"></i> Bulan Romawi</span>
    <span class="copy-btn" data-type="text" data-text="{{bulan}}"><i class="fa fa-copy"></i> Digit Bulan</span>
    <span class="copy-btn" data-type="text" data-text="{{tgl}}"><i class="fa fa-copy"></i> Digit Tanggal</span>
    <span class="copy-btn" data-type="text" data-text="{{tgl_lengkap}}"><i class="fa fa-copy"></i> Tanggal Lengkap (22 Juli 2022 M)</span>
    <span class="copy-btn" data-type="text" data-text="{{tgl_lengkap_hijriah}}"><i class="fa fa-copy"></i> Tanggal Lengkap Hijriah (18 Muharram 1444 H)</span>
    <span class="copy-btn" data-type="text" data-text="{{thn_akademik}}"><i class="fa fa-copy"></i>Thn Akademik (<?=tahunAkademik();?>)</span>
    <span class="copy-btn" data-type="text" data-text="{{jenis_semester_akademik}}"><i class="fa fa-copy"></i>Jenis Semester Skrng (Genap,Ganjil)</span>
    <?php
    $no_surat = $db2->query("select * from tb_data_pendaftaran_no_surat where is_aktif='Y'");
    foreach ($no_surat as $num) {
      ?>
      <span class="copy-btn" data-type="text" data-text="{{<?=$num->kode_penomoran;?>}}"><i class="fa fa-copy"></i> <?=$num->nama_penomoran;?></span>
      <?php
    }
    ?>
    <?php
    $header = $db2->query("select * from tb_data_pendaftaran_header_surat");
    foreach ($header as $head) {
      ?>
      <span class="copy-btn" data-type="header" data-text="<?=$head->id_header;?>"><i class="fa fa-copy"></i> <?=$head->nama_header;?></span>
      <?php
    }
    //attribute
    if ($data_edit->has_attr=='Y') {
          $attr_value = json_decode($data_edit->attr_value);
          foreach ($attr_value as $attr) {
   ?>
   <span class="copy-btn" data-type="text" data-text="{{<?=$attr->attr_name;?>}}"><i class="fa fa-copy"></i> <?=$attr->attr_label;?></span>
      <?php
          }
   
    }
    ?>
    <span class="copy-btn" data-type="text" data-text="{{qr_code}}"><i class="fa fa-copy"></i>Qr Code</span>
         </div>
</div>
              <div class="form-group">
                <div class="col-lg-12 center-ckeditor">
                 <textarea id="editor1" name="isi_template_surat"><?=$template ;?></textarea>
                </div>
               
              </div><!-- /.form-group -->

              <input type="hidden" name="id" value="<?=$data_edit->id_jenis_pendaftaran_setting;?>">

              <div class="form-group">
                <div class="col-lg-12">
                  <div class="modal-footer"> 
                  <button type="submit" class="btn btn-primary save-data"><i class="fa fa-save"></i> <?php echo $lang["submit_button"];?></button>
                  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                  </div>
                </div>
              </div><!-- /.form-group -->


            </form>


<script type="text/javascript">
/* CKEDITOR.addCss(
      'body.document-editor, div.cke_editable { width: 595px;height:442px;} ');*/

 $(document).ready(function(){


            $('[data-toggle="tooltip"]').tooltip();
            
            $(".copy-btn").click(function() {
                const textToCopy = $(this).data("text");
                const type = $(this).data('type');
                navigator.clipboard.writeText(textToCopy);
                
                const originalTitle = $(this).html();
                $(this).attr("data-original-title", "Copied!");
                $(this).tooltip('show');
                if (type=='header') {
                    $.ajax({
                      type : "post",
                      url : "<?=base_admin();?>modul/pengaturan_pendaftaran/header_surat/get_header.php",
                      data : {id:textToCopy},
                      success : function(data) {
                         $('textarea#editor1').ckeditor().editor.insertHtml(data);
                          }
                    });
                } else {
                  $('textarea#editor1').ckeditor().editor.insertText(textToCopy);
                }
                const copyBtn = $(this);
                setTimeout(function() {
                    copyBtn.attr("data-original-title", originalTitle);
                    copyBtn.tooltip('hide');
                }, 1500);
            });
        });
   $("textarea#editor1" ).ckeditor({
      // Make the editing area bigger than default.
      height: '296mm',
      width: '310mm',

      // Allow pasting any content.
      allowedContent: true,

      // Fit toolbar buttons inside 3 rows.
      toolbarGroups: [{
          name: 'document',
          groups: ['mode', 'document', 'doctools']
        },
        {
          name: 'clipboard',
          groups: ['clipboard', 'undo']
        },
        {
          name: 'editing',
          groups: ['find', 'selection', 'spellchecker', 'editing']
        },
        {
          name: 'forms',
          groups: ['forms']
        },
        '/',
        {
          name: 'paragraph',
          groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
        },
        {
          name: 'links',
          groups: ['links']
        },
        {
          name: 'insert',
          groups: ['insert']
        },
        '/',
        {
          name: 'styles',
          groups: ['styles']
        },
        {
          name: 'basicstyles',
          groups: ['basicstyles', 'cleanup']
        },
        {
          name: 'colors',
          groups: ['colors']
        },
        {
          name: 'tools',
          groups: ['tools']
        },
        {
          name: 'others',
          groups: ['others']
        },
        {
          name: 'about',
          groups: ['about']
        }
      ],

      // Remove buttons irrelevant for pasting from external sources.
      removeButtons: 'ExportPdf,Form,Checkbox,Radio,TextField,Select,Textarea,Button,ImageButton,HiddenField,NewPage,CreateDiv,Flash,Iframe,About,ShowBlocks,Maximize',

      // An array of stylesheets to style the WYSIWYG area.
      // Note: it is recommended to keep your own styles in a separate file in order to make future updates painless.
      contentsCss: [
        '<?=base_admin();?>assets/plugins/ckeditor4/contents.css',
        '<?=base_admin();?>assets/plugins/ckeditor4/pasteword.css'
      ],
      fontSize_sizes : '8/8pt;9/9pt;10/10pt;11/11pt;12/12pt;14/14pt;15/15pt;16/16pt;18/18pt;20/20pt;22/22pt;24/24pt;26/26pt;28/28pt;36/36pt;48/48pt;72/72pt',
      enterMode : CKEDITOR.ENTER_BR,

      // This is optional, but will let us define multiple different styles for multiple editors using the same CSS file.
      bodyClass: 'document-editor'
    });




CKEDITOR.on("instanceReady", function(event) {
  event.editor.on("beforeCommandExec", function(event) {
    // Show the paste dialog for the paste buttons and right-click paste
    if (event.data.name == "paste") {
      event.editor._.forcePasteDialog = true;
    }
    // Don't show the paste dialog for Ctrl+Shift+V
    if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
      event.cancel();
    }
  })
});

      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });
$("#edit_template").validate({
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
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("waktu")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            }
            else if (element.hasClass("select2")) {
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

        

         submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
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
                            //$(".save-data").attr("disabled", "disabled");
                            //$('#modal_pendaftaran').modal('hide');
                            $(".error_data").hide();
                            $(".notif_top_up").fadeIn(1000);
                            $(".notif_top_up").fadeOut(1000);
                          }
                    });
                }

            });
        }
    });

</script>
