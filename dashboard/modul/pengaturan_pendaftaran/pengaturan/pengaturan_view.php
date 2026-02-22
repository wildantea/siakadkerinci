                         <div class="row">
                           <div class="col-xs-12">
                         <div class="box">
                                <div class="box-header">
                                <?php
                                if ($db2->userCan("insert")) {
                                      ?>
                                      <a href="<?=base_index();?>pengaturan-pendaftaran/create" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                  }
                                ?>
                            </div><!-- /.box-header -->
  <div class="box box-primary">
                            <div class="box-header with-border">
          <h3 class="box-title show-filter" data-toggle="tooltip" data-title="Tampilkan/Sembunyikan Filter"><i class='fa fa-minus'></i> Filter</h3>
        </div>
        <div class="box-body ">
       <form class="form-horizontal" id="filter_kelas_form" method="post" action="<?=base_admin();?>modul/pendaftaran/download_data.php" target="_blank">

        <div class="form-group">
                                <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                                <div class="col-lg-5">
                                <select id="jurusan" name="jurusan" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" required="">
                                <?php
                                $filter_session_prodi = getFilter(array('filter_pengaturan_pendaftaran' => 'prodi'));
                                $prodi_unit = $db2->query("select * from view_prodi_unit where kode_jur in(select kode_jur from tb_data_pendaftaran_jenis_pengaturan_prodi)");
                                echo '<option value="all">Semua</option>';
                                foreach ($prodi_unit as $pr_unit) {
                                  if ($filter_session_prodi==$pr_unit->kode_jur) {
                                    echo '<option value="'.$pr_unit->kode_jur.'" selected>'.$pr_unit->nama_unit.'</option>';
                                  } else {
                                    echo '<option value="'.$pr_unit->kode_jur.'">'.$pr_unit->nama_unit.'</option>';
                                  } 
                                }
                                ?>
                      </select>
                    </div>

                      </div><!-- /.form-group -->

                      <div class="form-group">
                      <label for="Semester" class="control-label col-lg-2">Jenis Pendaftaran</label>
                        <div class="col-lg-5">
                        <select id="jenis_pendaftaran" name="jenis_pendaftaran" data-placeholder="Pilih Pendaftaran ..." class="form-control chzn-select" tabindex="2">
                          <option value="all">Semua</option>
                            <?php
                          //get default akses prodi 
                          $akses_prodi = getAksesProdi();
                          if ($akses_prodi) {
                            $jur_kode = "tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur in(".$akses_prodi.")";
                          } else {
                            //jika tidak group tidak punya akses prodi, set in 0
                            $jur_kode = "tb_data_pendaftaran_jenis_pengaturan_prodi.kode_jur in(0)";
                          }

                            $filter_session_jenis = getFilter(array('filter_pengaturan_pendaftaran' => 'jenis_pendaftaran'));
                            foreach ($db2->query("SELECT tb_data_pendaftaran_jenis.id_jenis_pendaftaran,nama_jenis_pendaftaran,kode_jur
                                                 from tb_data_pendaftaran_jenis_pengaturan
                                                 inner join tb_data_pendaftaran_jenis USING(id_jenis_pendaftaran)
                                                 inner join tb_data_pendaftaran_jenis_pengaturan_prodi using(id_jenis_pendaftaran_setting) where $jur_kode group by tb_data_pendaftaran_jenis.id_jenis_pendaftaran") as $jenis_pendaftaran) {
                              if ($filter_session_jenis==$jenis_pendaftaran->id_jenis_pendaftaran) {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran' selected>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              } else {
                                echo "<option value='$jenis_pendaftaran->id_jenis_pendaftaran'>$jenis_pendaftaran->nama_jenis_pendaftaran</option>";
                              }
                            }
                            ?>
                        </select>
                      </div>
                    </div>

                      <!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                           <?php
                            resetFilterButton('filter_pengaturan_pendaftaran');
                            ?>
                      </div><!-- /.form-group -->
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
          </div>
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 10px">
                                   <button id="bulk_delete" style="display: none;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> <?php echo $lang["delete_selected"];?></button> <span class="selected-data"></span>
                            </div>
                            </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
        <div class="table-responsive">
                        <table id="dtb_pengaturan_pendaftaran" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th style='padding-right:0;' class='dt-center'>#</th>
                                  <th>Nama Pendaftaran</th>
                                  <th>Diperuntukan</th>
                                  <th>Jadwal</th>
                                  <!-- <th>Bukti</th> -->
                                  <th>Judul</th>
                                  <th>Pembimbing</th>
                                  <th>Penguji</th>
                                  <th>Program Studi</th>
                                  <th>Aktif</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                        </div>
                           </div>
                         </div>
 <?php
  $edit ="";
  $del="";
    $surat="";
 if ($db2->userCan("update")) {
    $edit = "<a data-id='+data+' href=".base_index()."pengaturan-pendaftaran/edit/'+full[10]+'/'+data+' class=\"btn btn-primary btn-sm \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
 $surat = "<a data-id='+data+'  class=\"btn btn-sm btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit Template Surat\"><i class=\"fa fa-envelope\"></i></a>";
      
 }
  if ($db2->userCan("delete")) {
    
    $del = "<button data-id='+data+' data-uri=".base_admin()."modul/pengaturan_pendaftaran/pengaturan_pendaftaran_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Delete" data-variable="dtb_pengaturan_pendaftaran"><i class="fa fa-trash"></i></button>';
    
 }
        ?>
    <div class="modal" id="modal_pendaftaran" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg modal-abs"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="glyphicon glyphicon-remove"></i></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Pendaftaran</h4> </div> <div class="modal-body" id="isi_pendaftaran"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

 <script type="text/javascript">
      

      var dtb_pengaturan_pendaftaran = $("#dtb_pengaturan_pendaftaran").DataTable({
              
           'order' : [[1,'asc']],
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                //console.log(full[9]);
                 return '<?=$edit;?> <?=$del;?>';
                /*if (full[9]=='Y') {
                    return '<?=$edit;?> <?=$del;?> <?=$surat;?>';
                } else {
                    return '<?=$edit;?> <?=$del;?>';
                }*/
                
               }
            },
      
              {
             "targets": [0],
             "width" : "5%",
              "orderable": false,
              "searchable": false,
              "class" : "dt-center"
            }
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/pengaturan_pendaftaran/pengaturan_pendaftaran_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jurusan").val();
                    d.jenis_pendaftaran = $("#jenis_pendaftaran").val();
                    d.input_search = $('.dataTables_filter input').val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
//filter
$('#filter').on('click', function() {
  dtb_pengaturan_pendaftaran.ajax.reload();
});

$("#dtb_pengaturan_pendaftaran_filter").on('click','.reset-button-datatable',function(){
    dtb_pengaturan_pendaftaran
    .search( '' )
    .draw();
  });
</script>