<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Kurikulum</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>kurikulum">Kurikulum</a></li>
    <li class="active">Kurikulum List</li>
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
          <a href="<?=base_index();?>kurikulum/tambah" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo $lang["add_button"];?></a>
          <?php
                  }
              }
          }
          ?>
          </div><!-- /.box-header -->
          <div class="box-body table-responsive">
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Filter</h3>
              </div>
            <div class="box-body">
              <div class="row">
                 <form class="form-horizontal" method="post" action="<?=base_admin();?>modul/kurikulum/download_data.php">
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Program Studi</label>
                        <div class="col-lg-5">
                        <select id="jurusan_filter" name="jurusan_filter" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2">
               <?php
                                looping_prodi();
                                ?>
              </select>

 </div>
                      </div><!-- /.form-group -->
  <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Mulai Berlaku</label>
                        <div class="col-lg-5">
                        <select id="jenjang" name="jenjang" data-placeholder="Pilih Jenjang ..." class="form-control chzn-select" tabindex="2">
                        <option value="all">Semua</option>
                                                      <?php
               foreach ($db->query("select semester_ref.id_semester,
concat(tahun,'/',tahun+1,' ',jns_semester) as tahun_akademik
 from semester_ref inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
 inner join kurikulum on semester_ref.id_semester=kurikulum.sem_id
group by kurikulum.sem_id
order by tahun desc") as $isi) {
                  echo "<option value='$isi->id_semester'>$isi->tahun_akademik</option>";
               } ?>
              </select>

 </div>
                      </div><!-- /.form-group -->

                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                          <span id="filter" class="btn btn-primary"><i class="fa fa-refresh"></i> Filter</span>
                        <!--   <button type="submit" class="btn btn-success"><i class="fa fa-cloud-download"></i> Download</button> -->
                     <!--       <span id="akun" class="btn btn-primary"><i class="fa fa-cloud-download"></i> Download Akun</span>
                      -->   </div>
                      </div><!-- /.form-group -->
                </form>
              </div>
          </div>
<style type="text/css">
  table.table thead th {
  vertical-align: middle;
  text-align:center;
}
</style>
 <div class="alert alert-warning fade in error_data_delete" style="display:none">
          <button type="button" class="close hide_alert_notif">&times;</button>
          <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
        </div>
            <table id="dtb_kurikulum" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width:25px;" rowspan="2">No</th>
                  <th rowspan="2">Nama Kurikulum</th>
                  <th rowspan="2">Program Studi</th>
                  <th rowspan="2">Berlaku</th>
                  <th colspan="3" style="text-align: center;">Jumlah SKS</th>
                  <th rowspan="2">Matkul</th>
                  <th rowspan="2">Action</th>
                </tr>
                <tr>
                  <th>Wajib</th>
                  <th>Pilihan</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
            </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->
    <?php

        $detail="<a title=\"Detail Mata Kuliah\" href=\"".base_index()."kurikulum/detail_mk/'+aData[indek]+'\" data-id='+aData[indek]+' class=\"btn edit_data btn-success btn-sm\" data-toggle=\"tooltip\" ><i class=\"fa fa-book\"></i></a>";
        $prasyarat="<a title=\"Mata Kuliah Prasyarat\" href=\"".base_index()."kurikulum/prasayarat/'+aData[indek]+'\" data-id='+aData[indek]+' class=\"btn edit_data btn-success btn-sm\" data-toggle=\"tooltip\" ><i class=\"fa fa-list-ol\"></i></a>";
        foreach ($db->fetch_all("sys_menu") as $isi) {

        //jika url = url dari table menu
        if (uri_segment(1)==$isi->url) {
          if ($role_act["up_act"]=="Y") {
            $edit = "<a href=\"".base_index()."kurikulum/edit/'+aData[indek]+'\" data-id=\"'+aData[indek]+'\" class=\"btn edit_data btn-primary btn-sm \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a> ";
          } else {
              $edit ="";
          }
        if ($role_act['del_act']=='Y') {
            $del = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/kurikulum/kurikulum_action.php".' class="btn btn-danger hapus_dtb_notif btn-sm" data-variable="dataTable_kur" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>';
        } else {
            $del="";
        }
                         }
        }

    ?>

    </section><!-- /.content -->

    <script type="text/javascript">

      var dataTable_kur = $("#dtb_kurikulum").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$detail;?> <?=$prasyarat;?> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            'order' : [1,'asc'],
           'bProcessing': true,
            'bServerSide': true,

           'columnDefs': [ {
            'targets': [0,5],
              'orderable': false,
              'searchable': false
            },
                {
            'width': '5%',
            'targets': 0,
            'orderable': true,
            'searchable': false,
            'className': 'dt-center'
          },
                {
            'width': '17%',
            'targets': 8,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          }
             ],


            'ajax':{
              url :"<?=base_admin();?>modul/kurikulum/kurikulum_data.php",
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
              "fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        console.log(json);
/*        $(api.column(2).footer()).html(json.jumlah);
        $(api.column(3).footer()).html(json.sks_kinerja);*/
       // $('.jml_kurang').html(json.sks_kurang);
         //$(api.column(2).footer(1)).html(json.sks_kurang);
            }
        });

$('#filter').on('click', function() {
  dataTable_kur = $("#dtb_kurikulum").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$detail;?> <?=$prasyarat;?> <?=$edit;?> <?=$del;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
            destroy : true,
           'bProcessing': true,
            'bServerSide': true,
                 "columnDefs": [ {
               "targets": [0,7],
              "orderable": false,
              "searchable": false

            } ],
            "ajax":{
             url :"<?=base_admin();?>modul/kurikulum/kurikulum_data.php",
            type: "post",  // method  , by default get
            data: function ( d ) {
                    d.fakultas = $("#fakultas_filter").val();
                    d.jurusan = $("#jurusan_filter").val();
                    d.sem_id = $("#jenjang").val();
                  },
          error: function (xhr, error, thrown) {
            console.log(xhr);
            }
          },
              "fnDrawCallback": function() {
        var api = this.api()
        var json = api.ajax.json();
        console.log(json);
/*        $(api.column(2).footer()).html(json.jumlah);
        $(api.column(3).footer()).html(json.sks_kinerja);*/
       // $('.jml_kurang').html(json.sks_kurang);
         //$(api.column(2).footer(1)).html(json.sks_kurang);
            }
        });

      });

    $("#fakultas_filter").change(function(){

          $.ajax({
          type : "post",
          url : "<?=base_admin();?>modul/program_studi/get_jurusan_filter.php",
          data : {fakultas:this.value},
          success : function(data) {
              $("#jurusan_filter").html(data);
              $("#jurusan_filter").trigger("chosen:updated");

          }
      });

    });
    </script>
