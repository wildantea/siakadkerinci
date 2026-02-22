<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       Input Nilai PPL
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>kukerta">PPL</a></li>
                        <li class="active">PPL List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                            <div class="box-body table-responsive">

<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/download_data.php">
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode PPL</label>
                        <div class="col-lg-5">
                        <select id="periode_filter" name="periode" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                              <option value="all">Semua</option>
<?php 
$periode = $db->query("select * from priode_ppl where id_priode in(select id_priode from v_dpl_ppl where nip='".$_SESSION['username']."' or nip2='".$_SESSION['username']."')");
echo $db->getErrorMessage();
$periode_aktif = 0;
foreach ($periode as $ak) {
  if ($ak->aktif=='1') {
    echo "<option value='$ak->id_priode' selected>".ganjil_genap($ak->priode)."</option>";
    $periode_aktif = $ak->id_priode;
  } else {
    echo "<option value='$ak->id_priode'>".ganjil_genap($ak->priode)."</option>";
  }
  
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                     <!--    <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button> -->
                      </div><!-- /.form-group -->
                    </div>
                </form>
              </div>
          </div>

 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>

                        <table id="dtb_kukerta" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Periode</th>
                                  <th>Lokasi</th>
                                  <th>Peserta</th>
                                  <th>Sudah di nilai</th>
                                  <th>Belum di nilai</th>
                                  <th>DPL</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
        <?php

            foreach ($db->fetch_all("sys_menu") as $isi) {

            //jika url = url dari table menu
            if (uri_segment(1)==$isi->url) {
              //check edit permission
              if ($role_act["up_act"]=="Y") {
                $edit = "<a data-id='+aData[indek]+' href=".base_index()."input-nilai-ppl/input_nilai/'+aData[indek]+' class=\"btn btn-primary btn-sm edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-book\"></i> Input Nilai</a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/input_nilai_ppl/input_nilai_ppl_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_kukerta"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    </section><!-- /.content -->

        <script type="text/javascript">
    
    $("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#prodi_filter").html(data);
        $("#prodi_filter").trigger("chosen:updated");

        }
    });

    $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_ppl/get_lokasi.php",
      data : {id_fakultas:this.value,id_periode:$("#periode_filter").val(),id_prodi:$("#prodi_filter").val()},
      success : function(data) {
        $("#lokasi_filter").html(data);
        $("#lokasi_filter").trigger("chosen:updated");
      }
    });

});


$("#prodi_filter").change(function(){
    //periode load
    $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_ppl/get_lokasi.php",
      data : {id_prodi:this.value,id_fakultas:$("#fakultas_filter").val(),id_periode:$("#periode_filter").val()},
      success : function(data) {
        $("#lokasi_filter").html(data);
        $("#lokasi_filter").trigger("chosen:updated");
      }
    });
});  


  $("#periode_filter").change(function(){

/*    $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_ppl/get_lokasi.php",
      data : {id_periode:this.value,id_prodi:$("#kode_prodi").val(),id_fakultas:$("#fakultas_filter").val()},
      success : function(data) {
        $("#id_lokasi").html(data);
        $("#id_lokasi").trigger("chosen:updated");
      }
    });
*/
    $.ajax({
      type : "post",
      url : "<?=base_admin();?>modul/pendaftaran_ppl/get_lokasi.php",
      data : {id_periode:this.value,id_fakultas:$("#fakultas_filter").val(),id_prodi:$("#prodi_filter").val()},
      success : function(data) {
        $("#lokasi_filter").html(data);
        $("#lokasi_filter").trigger("chosen:updated");
      }
    });


  });

      
      var dtb_kukerta = $("#dtb_kukerta").DataTable({
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/input_nilai_ppl/input_nilai_ppl_data_dosen.php',
            type: 'post',  // method  , by default get
                  data: function ( d ) {
        d.periode = $("#periode_filter").val();
      },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

//filter
$('#filter').on('click', function() {
  dtb_kukerta.ajax.reload();
});


</script>
            