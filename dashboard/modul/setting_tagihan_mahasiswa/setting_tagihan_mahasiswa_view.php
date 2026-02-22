<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Setting Tagihan Mahasiswa
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>setting-tagihan-mahasiswa">Setting Tagihan Mahasiswa</a></li>
                        <li class="active">Setting Tagihan Mahasiswa List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <?php
                                  foreach ($db->fetch_all("sys_menu") as $isi) {
                                      if (uri_segment(1)==$isi->url) {
                                          if ($role_act["insert_act"]=="Y") {
                                      ?>
                                      <a id="add_setting_tagihan_mahasiswa" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                       <a class="btn btn-primary" id="import_mat"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                      <?php
                                          }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
<div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/setting_tagihan_mahasiswa/download_data.php">
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Periode Tagihan</label>
                        <div class="col-lg-5">
                        <select id="berlaku_angkatan" name="periode" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
<?php 
 $angkatan_exist = $db->query("select periode from keu_tagihan_mahasiswa group by periode
order by periode desc");
$periode_bayar_aktif = $db->fetch_custom_single("select periode_bayar from periode_pembayaran where is_active='Y'");

foreach ($angkatan_exist as $ak) {
  if ($periode_bayar_aktif->periode_bayar==$ak->periode) {
    echo "<option value='$ak->periode' selected>".ganjil_genap($ak->periode)."</option>";
  } else {
    echo "<option value='$ak->periode'>".ganjil_genap($ak->periode)."</option>";
  }
  
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
                    <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Fakultas</label>
                        <div class="col-lg-5">
                        <select id="fakultas_filter" name="fakultas" data-placeholder="Pilih Fakultas ..." class="form-control chzn-select" tabindex="2">
 <?php
                                loopingFakultas();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="kode_prodi" name="kode_prodi" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
 <?php
                                looping_prodi();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Angkatan Mahasiswa</label>
                        <div class="col-lg-5">
                        <select id="mulai_smt" name="mulai_smt" data-placeholder="Pilih Angkatan ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
 <?php
 $angkatan_exist = $db->query("select mulai_smt from mahasiswa inner join keu_tagihan_mahasiswa using(nim)
  group by mulai_smt
order by mulai_smt desc");

foreach ($angkatan_exist as $ak) {
    echo "<option value='$ak->mulai_smt'>".getAngkatan($ak->mulai_smt)."</option>";
  
}
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Pembayaran</label>
                        <div class="col-lg-5">
                        <select id="kode_pembayaran" name="kode_pembayaran" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select kjp.kode_pembayaran,kjp.nama_pembayaran from keu_jenis_pembayaran kjp
inner join keu_jenis_tagihan kjt on kjp.kode_pembayaran=kjt.kode_pembayaran
inner join keu_tagihan kt on kjt.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
group by kjp.kode_pembayaran");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_pembayaran'>$ak->nama_pembayaran</option>";
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Jenis Tagihan</label>
                        <div class="col-lg-5">
                        <select id="kode_tagihan" name="kode_tagihan" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php 
 $angkatan_exist = $db->query("select kt.kode_tagihan,kj.nama_tagihan from keu_jenis_tagihan kj 
inner join keu_tagihan kt on kj.kode_tagihan=kt.kode_tagihan
inner join keu_tagihan_mahasiswa ktm on kt.id=ktm.id_tagihan_prodi
group by kj.kode_tagihan");

foreach ($angkatan_exist as $ak) {
  echo "<option value='$ak->kode_tagihan'>$ak->nama_tagihan</option>";
} ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Status Tagihan</label>
                        <div class="col-lg-5">
                        <select id="status_tagihan" name="status_tagihan" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
<!--                         <option value="1">Aktif</option>
                        <option value="2">Non-Aktif</option> -->
                        <option value="3">Lunas</option>
                        <option value="5">Belum Bayar</option>
                        <option value="4">Gratis</option>
                    </select>
                </div>
                      </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button>
                      </div><!-- /.form-group -->
                    </div>
                </form>
              </div>
          </div>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
<div class="row" id="aksi_top_krs" style="display: none">
    <div class="col-sm-4" style="margin-bottom: 10px;">
      <div class="input-group input-group-sm">
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
          </span>
       <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
       <option value="tanggal" data-aksi="ubah_tanggal">Ubah Tanggal Massal</option>
      <option value="delete" data-aksi="del_massal">Hapus Tagihan</option>

    </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
          </span>
    </div>
    </div>
</div>
                        <table id="dtb_setting_tagihan_mahasiswa" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                 <th rowspan="2" style="padding-right:7px;width: 3%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
                                  <th rowspan="2">NIM</th>
                                  <th rowspan="2">Nama</th>
                                  <th rowspan="2">Angkatan</th>
                                  <th rowspan="2">Jenis</th>
                                  <th colspan="4" class="dt-center">Tagihan</th>
                                  <th rowspan="2">Periode</th>
                                  <th colspan="3" class="dt-center">Batas Tanggal Bayar</th>
                                  <th rowspan="2">Prodi</th>
                                  <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                  <th>Awal</th>
                                  <th>Potongan</th>
                                  <th>Total</th>
                                  <th>Lunas?</th>
                                  <th>Awal</th>
                                  <th>Akhir</th>
                                  <th>Aktif</th>
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
              $edit = "<a data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Edit Data\" class=\"btn btn-primary btn-sm edit_data data_selected_id\"><i class=\"fa fa-pencil\"></i></a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-toggle="tooltip" title="Hapus" data-variable="dtb_setting_tagihan_mahasiswa"><i class="fa fa-trash"></i></button>';
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Setting Tagihan Mahasiswa</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <div class="modal" id="modal_import_mat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel Kelulusan Mahasiswa</h4> </div> <div class="modal-body" id="isi_import_mat"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>  
<div class="modal" id="modal-confirm-action" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang['confirm'];?></h4> </div> <div class="modal-body"> <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span> Apakah Anda Yakin</span></p> </div> <div class="modal-footer"><button type="button" id="confirm-action" class="btn btn-primary">Ya Yakin</button> <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button></div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->

        <script type="text/javascript">
     $(document).ready(function() {     
            $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    });
      $("#add_setting_tagihan_mahasiswa").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_setting_tagihan_mahasiswa").html(data);
              }
          });

      $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static',show:true });

    });

    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_setting_tagihan_mahasiswa").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });

    });
    
});

dtb_setting_tagihan_mahasiswa = $("#dtb_setting_tagihan_mahasiswa").DataTable({
       "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              destroy : true,
              "lengthMenu": [[10,100, 200,500,1000, 5000], [10,100, 200,500,1000, 5000]],
           'bProcessing': true,
            'bServerSide': true,
            'order': [],
           'columnDefs': [ {
            'targets': [3,-1],
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
              url :'<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
              d.kode_prodi = $("#kode_prodi").val();
              d.mulai_smt = $("#mulai_smt").val();
              d.fakultas = $("#fakultas_filter").val();
              d.periode = $("#berlaku_angkatan").val();
              d.kode_tagihan = $("#kode_tagihan").val();
             d.kode_pembayaran = $("#kode_pembayaran").val();
             d.status_tagihan = $("#status_tagihan").val();
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          }
      });
//filter
$('#filter').on('click', function() {
  dtb_setting_tagihan_mahasiswa.ajax.reload();
});

$("#fakultas_filter").change(function(){
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/get_data_umum/get_prodi.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#kode_prodi").html(data);
        $("#kode_prodi").trigger("chosen:updated");

        }
    });
    //periode load
/*    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/get_periode_tagihan.php",
    data : {id_fakultas:this.value},
    success : function(data) {
        $("#berlaku_angkatan").html(data);
        $("#berlaku_angkatan").trigger("chosen:updated");

        }
    });*/
});
$("#kode_prodi").change(function(){
    //periode load
    $.ajax({
    type : "post",
    url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/get_periode_tagihan.php",
    data : {id_fakultas:$('#fakultas_filter').val(),kode_jur:this.value},
    success : function(data) {
        $("#berlaku_angkatan").html(data);
        $("#berlaku_angkatan").trigger("chosen:updated");

        }
    });
});

$(".bulk-check").on('click',function() { // bulk checked
          var status = this.checked;
          if (status) {
            select_deselect('select');
          } else {
            select_deselect('unselect');
          }
          
          $(".check-selected").each( function() {
            $(this).prop("checked",status);
          });
        });

  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#aksi_top_krs');
      if (selected.length > 0) {
          btn_hide.show()
      } else {
          btn_hide.hide()
      }
  }

    function check_selected() {
      var table_select = $('#dtb_setting_tagihan_mahasiswa tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.data_selected_id').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' Data Terpilih');
      return array_data_delete
  }

  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_setting_tagihan_mahasiswa tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_setting_tagihan_mahasiswa tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_setting_tagihan_mahasiswa tbody tr .check-selected', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          console.log(selected);
          init_selected();

      }
  });

/* Add a click handler for the delete row */
  $('.submit-proses').click( function(event) {
    $("#loadnya").show();
    event.stopPropagation();
    event.preventDefault();
    event.stopImmediatePropagation();
    //var anSelected = fnGetSelected( dtb_setting_tagihan_mahasiswa );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    //var aksi = $(':selected', $(this)).data('aksi');
    var aksi = $("#aksi_krs option:selected").attr('data-aksi');
    if (aksi=='ubah_tanggal') {
            $.ajax({
                url : "<?=base_admin();?>modul/setting_tagihan_mahasiswa/edit_tanggal_massal.php",
                type : "post",
                data : {all_id:all_ids},
                success: function(data) {
                    $("#isi_setting_tagihan_mahasiswa").html(data);
                    $("#loadnya").hide();
              }
            });
            $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });
        } else {
          $("#loadnya").hide();
          $('#modal-confirm-action').modal({ keyboard: false }).one('click', '#confirm-action', function (e) {
            e.stopImmediatePropagation()
            $.ajax({
                type: 'POST',
                url: '<?=base_admin();?>modul/setting_tagihan_mahasiswa/setting_tagihan_mahasiswa_action.php?act='+aksi,
                data: {aksi:$("#aksi_krs").val(), data_ids:all_ids},
                success: function(result) {
                   $('#loadnya').hide();
                      $(".bulk-check").prop("checked",0);
                      select_deselect('unselect');
                      dtb_setting_tagihan_mahasiswa.draw(false);
                },
                //async:false
            });
              $('#modal-confirm-action').modal('hide').data( 'bs.modal', null );;
          });
        }
  });

</script>