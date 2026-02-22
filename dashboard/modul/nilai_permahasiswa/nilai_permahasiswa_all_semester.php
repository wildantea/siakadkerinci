<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Manage Nilai Permahasiswa</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>nilai-permahasiswa">Nilai Permahasiswa</a></li>
    <li class="active">Nilai Permahasiswa List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
  <style type="text/css">
    .info-siswa {
      width: 30%;
    }
  </style>
    <div class="col-xs-12">
      <div class="box">
         <div class="box-body">    
            <table class="table table-bordered ">
              <?php
              ?>
              <tbody>
                <tr>
                    <td class="info-siswa">NIM </td>
                    <td>  <?=$data_mhs->nim;?></td>
                </tr>
                <tr>
                  <td >Nama </td>
                  <td>  <?=$data_mhs->nama;?></td>
                </tr>
                <tr>
                  <td >Angkatan </td>
                  <td>  <?=$data_mhs->angkatan;?></td>
                </tr>
                <tr>
                  <td >Program Studi </td>
                  <td>  <?=$data_mhs->jurusan;?></td>
                </tr>
            </tbody></table>
                <?php               
                   include 'tabel_nilai_persemester.php';
                  ?>
              </div>
   
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->

    </section><!-- /.content -->
<script type="text/javascript">
    $("#form_cari_mahasiswa").submit(function(){
    $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/nilai_permahasiswa/nilai_permahasiswa_action.php?act=cari_mhs',
            data: {data_ids:all_ids},
            success: function(result) {
               $('#loadnya').hide();
             // $("#hasil").html(result);
            },
            //async:false
        });
    return false;
  });
  
</script>