<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Jadwal Kuliah</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>jadwal-kuliah">Jadwal Kuliah</a></li>
                        <li class="active">Detail Jadwal Kuliah</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Jadwal Kuliah</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
                        
                 <div class="form-group">
                <label>Multiple</label>
                <select class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a State" style="width: 100%;" tabindex="-1" aria-hidden="true">
                  <option>Alabama</option>
                  <option>Alaska</option>
                  <option>California</option>
                  <option>Delaware</option>
                  <option>Tennessee</option>
                  <option>Texas</option>
                  <option>Washington</option>
                </select><span class="select2 select2-container select2-container--default select2-container--below select2-container--focus" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-selection__choice" title="Alabama"><span class="select2-selection__choice__remove" role="presentation">×</span>Alabama</li><li class="select2-selection__choice" title="Alaska"><span class="select2-selection__choice__remove" role="presentation">×</span>Alaska</li><li class="select2-selection__choice" title="California"><span class="select2-selection__choice__remove" role="presentation">×</span>California</li><li class="select2-selection__choice" title="Tennessee"><span class="select2-selection__choice__remove" role="presentation">×</span>Tennessee</li><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
              </div>   
              <div class="form-group">
                <label for="Kelas" class="control-label col-lg-2">Kelas</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->kelas_id;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              
              <div class="form-group">
                <label for="Hari" class="control-label col-lg-2">Hari</label>
                <div class="col-lg-10">
                  <input type="text" disabled="" value="<?=$data_edit->hari;?>" class="form-control">
                </div>
              </div><!-- /.form-group -->
              <div class="form-group">
                        <label for="Ruang" class="control-label col-lg-2">Ruang</label>
                        <div class="col-lg-10">
              <?php foreach ($db->fetch_all("ruang_ref") as $isi) {
                  if ($data_edit->ruang_id==$isi->ruang_id) {

                    echo "<input disabled class='form-control' type='text' value='$isi->nm_ruang'>";
                  }
               } ?>
              </div>
                      </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Jam Mulai" class="control-label col-lg-2">Jam Mulai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jam_mulai;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
          <div class="form-group">
              <label for="Jam Selesai" class="control-label col-lg-2">Jam Selesai</label>
              <div class="col-lg-10">
                <input type="text" disabled="" value="<?=$data_edit->jam_selesai;?>" class="form-control">
              </div>
          </div><!-- /.form-group -->
          
                        
                      </form>
                      <a href="<?=base_index();?>jadwal-kuliah" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
