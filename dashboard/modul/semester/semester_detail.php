<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Semester Berlaku</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>semester">Semester</a></li>
                        <li class="active">Detail Semester Berlaku</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Semester Berlaku</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                    <div class="box-body">
                      <form class="form-horizontal">
<div class="form-group">
                <label for="Tahun" class="control-label col-lg-3">Periode Tahun Akademik</label>
                <div class="col-lg-3">
                  <input type="text" name="tahun" readonly="" value="<?=$data_edit->tahun_akademik;?>" class="form-control" required>
                </div>
              </div><!-- /.form-group -->

          <div class="form-group">
              <label for="Aktif" class="control-label col-lg-3">Aktif</label>
              <div class="col-lg-5">
                <?php if ($data_edit->aktif=="1") {
                ?>
                  <input name="is_aktif" class="make-switch" type="checkbox" checked data-on-text="Aktif" data-off-text="Tidak" readonly="">
                <?php
              } else {
                ?>
                  <input name="is_aktif" class="make-switch" type="checkbox" data-on-text="Aktif" data-off-text="Tidak" readonly="">
                <?php
              }?>
              </div>
          </div><!-- /.form-group -->


                    <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai</label>
              <div class="col-lg-3">
                <div class='input-group date'>
                    <input type='text' class="form-control" name="tgl_mulai" readonly="" value="<?=tgl_indo($data_edit->tgl_mulai);?>" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' >
                    <input type='text' class="form-control" name="tgl_selesai" value="<?=tgl_indo($data_edit->tgl_selesai);?>" readonly=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

                <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai KRS</label>
              <div class="col-lg-3">
                <div class='input-group date' >
                    <input type='text' class="form-control" name="tgl_mulai_krs" value="<?=tgl_indo($data_edit->tgl_mulai_krs);?>" readonly=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' >
                    <input type='text' class="form-control" name="tgl_selesai_krs" value="<?=tgl_indo($data_edit->tgl_selesai_krs);?>" readonly=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

     <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Perbaikan KRS</label>
              <div class="col-lg-3">
                <div class='input-group date'>
                    <input type='text' class="form-control" name="tgl_mulai_pkrs" value="<?=tgl_indo($data_edit->tgl_mulai_pkrs);?>" readonly/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' >
                    <input type='text' class="form-control" value="<?=tgl_indo($data_edit->tgl_selesai_pkrs);?>" readonly />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->

     <div class="form-group">
              <label for="Tanggal Mulai" class="control-label col-lg-3">Tanggal Mulai Input Nilai</label>
              <div class="col-lg-3">
                <div class='input-group date' >
                    <input type='text' class="form-control" name="tgl_mulai_input_nilai" value="<?=tgl_indo($data_edit->tgl_mulai_input_nilai);?>" readonly="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
               <div class="col-lg-1" style="font-weight:bold;padding-left: 0;padding-right: 0;width: 20px;padding-top: 5px;">
                        S/d</div>
              <div class="col-lg-3">
              <div class='input-group date' >
                    <input type='text' class="form-control" name="tgl_selesai_input_nilai" value="<?=tgl_indo($data_edit->tgl_selesai_input_nilai);?>" readonly=""/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
              </div>
          </div><!-- /.form-group -->
                        <div class="form-group">
                <label for="tags" class="control-label col-lg-3">&nbsp;</label>
                <div class="col-lg-4">
                  <a href="<?=base_index();?>semester" class="btn btn-success "><i class="fa fa-step-backward"></i> <?php echo $lang["back_button"];?></a>
                </div>
              </div><!-- /.form-group -->
                      </form>
                      

                        </div>
                      </div>
                    </div>
                </div>

                </section><!-- /.content -->
