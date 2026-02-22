                                        <div class="callout callout-info">
                                        <h4>Dokumen</h4>
                                    </div>
                                        <?php

                                        if(!empty(checkUploadDokumen($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Mohon lengkapi Dokumen berikut!</h4>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkUploadDokumen($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                        <?php
                                    }
                                    ?>
                                    </div>
                                        

          <div class="box-body table-responsive no-padding">
                                    <table id="dtb_file_upload" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                  <th>Jenis File</th>
                                  <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $data_file_label = $db->query("select * from tb_data_file_label  where is_show='Y'");

                              foreach ($data_file_label as $label) {
                                $is_exist = $db2->checkExist('tb_data_file',array('id_file_label' => $label->id_file_label,'nim' => $_SESSION['username']));
                                    $class_s1 = $jenj;
                                    $required = '';
                                    $wajib = '';
                                     if ($label->is_required=='Y') {
                                      $required = 'required';
                                      $wajib = '<span style="color:#FF0000">*</span>';
                                      }
                                    
                                if (!$is_exist) {
                                 
                                  ?>
                                <tr>
                                  <th><?=$label->nama_label;?> <?=$wajib;?></th>
                                  <th class="th_<?=$label->id_file_label;?> file-data"><span class='btn btn-primary add_file' data-id="<?=$label->id_file_label;?>" data-toggle='tooltip' data-title="Klik untuk Tambah File"><i class='fa fa-plus'></i> Upload File </span> <input type="text" style="width:0;border:none;float:right;position: fixed; left: -10000000px;" id="file_<?=$label->id_file_label;?>" name="file_<?=$label->id_file_label;?>" data-msg-required="Silakan Tambah <?=$label->nama_label;?>" value="" <?=$required;?>>
                                     <p class="help-block" style="color:#1dc0ef"><?=$label->helper;?></p>
                                  </th>
                                  <?php
                                } else {
                                  $check_ext = $db->fetch_single_row("tb_data_file_extention","type",$is_exist->getData()->type_file);
                                   $icon = base_admin()."modul/biodata/document.png";
                                  if ($check_ext->file_type=='image') {
                                    $icon = base_admin()."modul/biodata/gambar.png";
                                  }
                                  ?>
                                  <tr>
                                  <th><?=$label->nama_label;?> <?=$wajib;?></th>
                                  <th class="th_<?=$label->id_file_label;?>"><a target="_blank" data-toggle="tooltip" data-title="Lihat File" href="<?=$is_exist->getData()->link_file;?>"><img style="cursor:pointer" src="<?=$icon?>" width="30"></a> <a class="btn btn-primary ubah-file" data-toggle="tooltip" data-title="Klik untuk merubah File" data-id="<?=$is_exist->getData()->id_file;?>"><i class="fa fa-pencil"></i> Edit</a>
                                  </th>
                                </tr>
                                  <?php
                                }
                                ?>

                                <?php
                              }
                              ?>
                            </tbody>
                        </table>

          </div>
    <div class="modal" id="modal_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Riwayat Pendidikan</h4> </div> <div class="modal-body" id="isi_modal"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<script> 
$(".add_file").click(function() {
  $("#loadnya").show();
          $('.modal-title').html('Upload File');
          $.ajax({
              url : "<?=base_admin();?>modul/biodata/berkas_file_add.php",
              type : "POST",
              data : {id_label : $(this).data('id')},
              success: function(data) {
                  $("#isi_modal").html(data);
                  $("#loadnya").hide();
              }
          });

      $('#modal_data').modal({ keyboard: false,backdrop:'static',show:true });

});

$("#dtb_file_upload").on('click','.ubah-file',function(event) {
        $("#loadnya").show();
        event.preventDefault();
          $('.modal-title').html('Ubah File');
          $.ajax({
              url : "<?=base_admin();?>modul/biodata/berkas_file_edit.php",
              type : "POST",
              data : {id_file : $(this).data('id')},
              success: function(data) {
                  $("#isi_modal").html(data);
                   $("#loadnya").hide();
              }
          });
      $('#modal_data').modal({ keyboard: false,backdrop:'static',show:true });
});

</script>