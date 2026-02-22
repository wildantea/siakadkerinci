<!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Pengaturan Pendaftaran</h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li>
              <a href="<?=base_index();?>pengaturan-pendaftaran">Pengaturan Pendaftaran</a>
            </li>
            <li class="active"><?php echo $lang["add_button"];?> Pengaturan Pendaftaran</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title"><?php echo $lang["add_button"];?> Pengaturan Pendaftaran</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="box-body">
           <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
            <form id="input_pengaturan_pendaftaran" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/pengaturan_pendaftaran/pengaturan_pendaftaran_action.php?act=in">
          
<div class="form-group">
              <label for="Untuk Program Studi" class="control-label col-lg-2">Ditujukan untuk</label>
                        <div class="col-lg-10">
              <select  id="ditujukan" class="form-control chzn-select" tabindex="2" required>
                <option value="">Pilih Tujuan</option>
               <option value="mahasiswa">Mahasiswa</option>
               <option value="dopeg">Dosen/Pegawai</option>
              </select>
          </div>

</div>
</form>

          </div>
        </div>
      </div>
    </div>

    </section><!-- /.content -->

<script type="text/javascript">
$(document).ready(function () {
  $("#ditujukan").change(function(){
    if (this.value=='mahasiswa') {
      var url_content = "<?=base_admin();?>modul/pengaturan_pendaftaran/get_pengaturan_mhs_add.php";
      document.location="<?=base_index();?>pengaturan-pendaftaran/create/mahasiswa";
    } else {
      document.location="<?=base_index();?>pengaturan-pendaftaran/create/dopeg";
    }

 $.ajax({
   url : url_content,
   type : "post",
   success : function(data) {
     $('.form-content').html(data);
   }
 });

  });

});
</script>