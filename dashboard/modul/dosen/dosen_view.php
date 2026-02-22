<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dosen
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>dosen">Dosen</a></li>
                        <li class="active">Dosen List</li>
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
                                      <a id="add_dosen" class="btn btn-primary "><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
                                      <?php
                                          }
                                        if ($role_act["import_act"]=="Y") {
                                          ?>
                                          <a class="btn btn-primary" id="import_data"><i class="fa fa-cloud-upload"></i> Import Excel</a>
                                          <?php
                                        }
                                      }
                                  }
                                ?>
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">

<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body">
           <form class="form-horizontal" id="filter_form" method="post" action="<?=base_admin();?>modul/dosen/aksi.php" target="_blank">
                   
              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Program studi</label>
                    <div class="col-lg-5">
                    <select id="jur_filter" name="kode_jur" data-placeholder="Pilih Program studi ..." class="form-control chzn-select" tabindex="2">
                      <option value="all">Semua</option>
                    <?php
                    $prodi = $db->query("select * from view_prodi_jenjang");
                    foreach ($prodi as $pr) {
                      echo "<option value='$pr->kode_jur'>$pr->jurusan</option>";
                    }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Status" class="control-label col-lg-2">Status</label>
                    <div class="col-lg-5">
                    <select id="aktif" name="aktif" data-placeholder="Pilih Status ..." class="form-control chzn-select" tabindex="2" required="">
                   <option value="all">Semua</option>
                   <option value="Y">Aktif</option>
                   <option value="N">Tidak Aktif</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->


              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Jenis Dosen</label>
                    <div class="col-lg-5">
                    <select id="id_jenis_dosen" name="id_jenis_dosen" data-placeholder="Pilih Jenis Dosen ..." class="form-control chzn-select" tabindex="2">
                      <option value="all">Semua</option>
                    <?php
                    $jns_dosen = $db->query("select * from jenis_dosen");
                    foreach ($jns_dosen as $j_dosen) {
                      echo "<option value='$j_dosen->id_jenis_dosen'>$j_dosen->nama_jenis</option>";
                    }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->

              <div class="form-group">
                    <label for="Program studi" class="control-label col-lg-2">Rumpun</label>
                    <div class="col-lg-5">
                    <select id="rumpun" name="rumpun" data-placeholder="Pilih Rumpun Dosen ..." class="form-control chzn-select" tabindex="2">
                      <option value="all">Semua</option>
                    <?php
                    $prodi = $db->query("select drd.kode,drd.nama_rumpun from data_rumpun_dosen drd 
where id_level='1' and drd.kode in(select dw.id_induk
from  data_rumpun_dosen dw 
inner join data_rumpun_dosen dwc on dw.kode=dwc.id_induk
where dw.id_level='2' and dwc.kode in(select kode_rumpun from dosen where kode_rumpun is not null group by kode_rumpun))");
                    foreach ($prodi as $pr) {
                      echo "<option value='$pr->kode'>$pr->nama_rumpun</option>";
                    }
                    ?>
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group show_sub" style="display: none">
                    <label for="Program studi" class="control-label col-lg-2">Sub Rumpun</label>
                    <div class="col-lg-5">
                    <select id="sub_rumpun" name="sub_rumpun" data-placeholder="Pilih Sub Rumpun Dosen ..." class="form-control chzn-select" tabindex="2">
                    </select>
                    </div>
              </div><!-- /.form-group -->
              <div class="form-group show_bidang" style="display: none">
                    <label for="Program studi" class="control-label col-lg-2">Bidang Ilmu</label>
                    <div class="col-lg-5">
                    <select id="bidang_ilmu" name="bidang_ilmu" data-placeholder="Pilih Bidang Ilmu ..." class="form-control chzn-select" tabindex="2">
                      <option value="all">Semua</option>
                    </select>
                    </div>
              </div><!-- /.form-group -->
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                  <div class="btn-group">
                  <button type="button" class="btn btn-info"><i class="fa fa-external-link-square"></i> Aksi</button>
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li class="cetak-data"><button type="submit" name="jenis" value="download" class="btn cetak-data"><i class="fa fa-cloud-download"></i> Download Dosen</li>
                    <li class="cetak-data"><button type="submit" name="jenis" value="cetak" class="btn cetak-data"><i class="fa fa-print"></i> Cetak Akun Dosen</li>
                  </ul>
                </div>
                      </div><!-- /.form-group -->
                    </div>
                    </form>
            </div>
            <!-- /.box-body -->
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
       <option value="tanggal" data-aksi="ubah_tanggal">Ubah Status Aktif</option>
       <option value="jenis" data-aksi="ubah_jenis">Ubah Jenis Dosen</option>

    </select>
          <span class="input-group-btn">
            <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
          </span>
    </div>
    </div>
</div>
                        <table id="dtb_dosen" class="table table-bordered table-striped display nowrap" width="100%">
                            <thead>
                                <tr>
                                   <th style="padding-right:7px;width: 3%"><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline "> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label></th>
                                  <th>NIP</th>
                                  <th>NIDN/NIDK/NUP</th>
                                  <th>Nama Lengkap</th>
                                  <th>Email</th>
                                  <th>No Hp</th>
                                  <th>Status</th>
                                  <th>Jenis Dosen</th>
                                  <th>Program Studi</th>
                                  <th>Bidang Ilmu</th>
                                  <th>Mhs Bimbingan</th>
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
                $edit = "<a data-id='+data+'  class=\"edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> Edit</a>";
                 $reset = "<a data-id='+data+' data-toggle=\"tooltip\" title=\"Reset Password\"  class=\"reset_pass\"><i class=\"fa fa-undo\"></i> Reset</a>";
              } else {
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<a data-id='+data+' data-uri=".base_admin()."modul/dosen/dosen_action.php".' class="hapus_dtb_notif" data-toggle="tooltip" title="Hapus" data-variable="dtb_dosen"><i class="fa fa-trash"></i> Hapus</a>';
            } else {
                $del="";
            }
                             }
            }

        ?>

        <div class="modal" id="modal_setting_tagihan_mahasiswa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title">Edit Status Aktif Dosen</h4> </div> <div class="modal-body" id="isi_setting_tagihan_mahasiswa"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

    <div class="modal" id="modal_reset_pass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Reset Password</h4> </div> <div class="modal-body" id="isi_reset_pass"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    <div class="modal" id="modal_dosen" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Dosen</h4> </div> <div class="modal-body" id="isi_dosen"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
        <div class="modal" id="modal_import_data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button> <h4 class="modal-title">Import Excel </h4> </div> <div class="modal-body" id="isi_import_data"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    </section><!-- /.content -->

        <script type="text/javascript">
$('#jur_filter').on('change', function() {
 $("#rumpun").chosen();
        $.ajax({
      url : "<?=base_admin();?>modul/dosen/get_rumpun.php",
      type: "post",
      data : {prodi: $(this).val()},
      success:function(data) {
       // console.log(data);
        $("#rumpun").html(data);
        $("#rumpun").trigger('chosen:updated');
        $("#sub_rumpun").html('');
        $("#sub_rumpun").trigger('chosen:updated');
        $("#bidang_ilmu").html('');
        $("#bidang_ilmu").trigger('chosen:updated');
        $(".show_sub").fadeOut();
        $(".show_bidang").fadeOut();

      }
    });

 });

$('#rumpun').on('change', function() {
 $("#sub_rumpun").chosen();
 $(".show_sub").fadeIn();
  if ($(this).val()!='all') {
        $.ajax({
      url : "<?=base_admin();?>modul/dosen/get_sub_rumpun_filter.php",
      type: "post",
      data : {rumpun: $(this).val()},
      success:function(data) {
       // console.log(data);
        $("#sub_rumpun").html(data);
        $("#sub_rumpun").trigger('chosen:updated');
        $(".show_bidang").fadeOut();
        $("#bidang_ilmu").html('');
        $("#bidang_ilmu").trigger('chosen:updated');

      }
    });
  } else {
     $(".show_sub").fadeOut();
     $(".show_bidang").fadeOut();
  }

 });
$('#sub_rumpun').on('change', function() {
 $("#bidang_ilmu").chosen();
 $(".show_bidang").fadeIn();
  if ($(this).val()!='all') {
        $.ajax({
      url : "<?=base_admin();?>modul/dosen/get_bidang_ilmu_filter.php",
      type: "post",
      data : {sub_rumpun: $(this).val()},
      success:function(data) {
       // console.log(data);
        $("#bidang_ilmu").html(data);
        $("#bidang_ilmu").trigger('chosen:updated');
      }
    });
  } else {
     $(".show_bidang").fadeOut();
  }

 });
        $("#import_data").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/dosen/import_data.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_data").html(data);
              }
          });

      $('#modal_import_data').modal({ keyboard: false,backdrop:'static',show:true });

    });
  $(".table").on('click','.reset_pass',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/dosen/reset_password.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_reset_pass").html(data);
                $('#modal_reset_pass').modal({ keyboard: false,backdrop:'static' });
                $("#loadnya").hide();
          }
        });



    });
      $("#add_dosen").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/dosen/dosen_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_dosen").html(data);
              }
          });
      $('#modal_dosen').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/dosen/dosen_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_dosen").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_dosen').modal({ keyboard: false,backdrop:'static' });

    });
    
      var dtb_dosen = $("#dtb_dosen").DataTable({

            "order": [[1,'asc']],  
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false,
              'className': 'all',
              "render": function(data, type, full, meta){
                return '<div class="dropup"><div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$reset;?></li><li><?=$edit;?></li><li><?=$del;?></li></ul></div></div>';
               }
            },
            {
            'targets': [4,5,8],
              'className': 'none'
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
              url :'<?=base_admin();?>modul/dosen/dosen_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });


 $('#filter').on('click', function() {
  dtb_dosen = $("#dtb_dosen").DataTable({
            "order": [[1,'asc']],  
            destroy : true,
               'bProcessing': true,
            'bServerSide': true,
            'scrollX' : true,
           'columnDefs': [ {
            'targets': [-1],
              'orderable': false,
              'searchable': false,
              'className': 'all',
              "render": function(data, type, full, meta){
                return '<div class="dropup"><div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$reset;?></li><li><?=$edit;?></li><li><?=$del;?></li></ul></div></div>';
               }
            },
            {
            'targets': [4,5,8],
              'className': 'none'
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
              url :'<?=base_admin();?>modul/dosen/dosen_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                //filter variable datatable
                d.kode_jur = $("#jur_filter").val();
                d.aktif = $("#aktif").val();
                d.rumpun = $("#rumpun").val();
                d.sub_rumpun = $("#sub_rumpun").val();
                d.bidang_ilmu = $("#bidang_ilmu").val();
                d.id_jenis_dosen = $("#id_jenis_dosen").val();
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
});


//bulk
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
      var table_select = $('#dtb_dosen tbody tr.selected');
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
          $('#dtb_dosen tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_dosen tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }
  $(document).on('click', '#dtb_dosen tbody tr .check-selected', function(event) {
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
    //var anSelected = fnGetSelected( dtb_dosen );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    //var aksi = $(':selected', $(this)).data('aksi');
    var aksi = $("#aksi_krs option:selected").attr('data-aksi');
    if (aksi=='ubah_tanggal') {
            $.ajax({
                url : "<?=base_admin();?>modul/dosen/edit_status.php",
                type : "post",
                data : {all_id:all_ids},
                success: function(data) {
                    $("#isi_setting_tagihan_mahasiswa").html(data);
                    $("#loadnya").hide();
              }
            });
            $('#modal_setting_tagihan_mahasiswa').modal({ keyboard: false,backdrop:'static' });
        } else if(aksi=='ubah_jenis') {
            $.ajax({
                url : "<?=base_admin();?>modul/dosen/edit_jenis.php",
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
                      dtb_dosen.draw(false);
                },
                //async:false
            });
              $('#modal-confirm-action').modal('hide').data( 'bs.modal', null );;
          });
        }
  });

</script>
            