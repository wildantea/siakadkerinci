<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Pengajuan Cuti Kuliah</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pengajuan-cuti-kuliah">Pengajuan Cuti Kuliah</a></li>
                        <li class="active">Detail Pengajuan Cuti Kuliah</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Pengajuan Cuti Kuliah</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
              <div class="form-group">
                <label for="NIM" class="control-label col-lg-2">NIM <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->nim;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
            <div class="form-group">
                <label for="Status Persetujuan" class="control-label col-lg-2">Status Persetujuan <span style="color:#FF0000">*</span></label>
                <div class="col-lg-10">
                <?php
                  $option = array(
'waiting' => 'Menunggu',

'approved' => 'Disetujui',

'rejected' => 'Ditolak',
);
                  foreach ($option as $isi => $val) {
                  if ($data_edit->status_acc==$isi) {

                    echo "<input disabled class='form-control' type='text' value='$val'>";
                  }
               } ?>
                  </div>
            </div><!-- /.form-group -->
            
          <div class="form-group">
              <label for="Alasan Cuti" class="control-label col-lg-2">Alasan Cuti <span style="color:#FF0000">*</span></label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="alasan_cuti" disabled="" required><?=$data_edit->alasan_cuti;?> </textarea>
              </div>
          </div><!-- /.form-group -->
          
              <div class="form-group">
                <label for="date_created" class="control-label col-lg-2">date_created </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->date_created;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="date_approved" class="control-label col-lg-2">date_approved </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->date_approved;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="date_updated" class="control-label col-lg-2">date_updated </label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->date_updated;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
                        
                      </form>
                      <a href="<?=base_index();?>pengajuan-cuti-kuliah" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
