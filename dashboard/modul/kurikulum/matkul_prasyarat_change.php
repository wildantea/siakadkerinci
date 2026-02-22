<?php
include "../../inc/config.php";
$prodi = $db->fetch_custom_single("select matkul.id_matkul,matkul.nama_mk,matkul.kode_mk,nama_kurikulum,jurusan.kode_jur,jurusan.nama_jur,jenjang_pendidikan.jenjang,
semester_ref.id_semester,
concat(semester_ref.tahun,'/',semester_ref.tahun+1,' ',jns_semester) as tahun_akademik from
jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
inner join kurikulum on jurusan.kode_jur=kurikulum.kode_jur
inner join matkul on kurikulum.kur_id=matkul.kur_id
inner join semester_ref on kurikulum.sem_id=semester_ref.id_semester
inner join jenis_semester on semester_ref.id_jns_semester=jenis_semester.id_jns_semester
where matkul.id_matkul=?",array('id_matkul' =>$_POST['id_data']))
?>
          <div class="box-body table-responsive">
           <div class="row">
             <div class="col-lg-6">
                        <table id="list_mk" class="table table-bordered table-striped display responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                  <th colspan="3" style="background: #b2d8df;">Daftar Matakuliah </th>
                                </tr>
                                <tr>
                                  <th>Kode MK</th>
                                  <th>Nama Matkul</th>
                                  <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
</div>
             <div class="col-lg-6">
                        <table id="dtb_syarat" class="table table-bordered table-striped display responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                  <th colspan="4" style="background: #b2d8df;">Syarat Matakuliah <?=$prodi->kode_mk.' - '.$prodi->nama_mk;?></th>
                                </tr>
                                <tr>
                                  <th>Kode MK</th>
                                  <th>Nama Matkul</th>
                                  <th><a data-html="true" data-placement="left" data-toggle='tooltip' title="L: Matakuliah prasyarat harus sudah lulus.<br>S: Matakuliah prasyarat boleh diambil bersamaan.">Syarat</a></th>
                                  <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
           </div>
</div>
<style type="text/css">
  .modal { overflow: auto !important; }
</style>
            </div><!-- /.box-body -->
    <div class="modal" id="modal_kec" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"><h4 class="modal-title">Tambahkan Sebagai Syarat</h4> </div> <div class="modal-body" id="isi_kec"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
<div class="modal modal-danger" id="ucing_hapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang['confirm'];?></h4> </div> <div class="modal-body"> <p> <i class="fa fa-info-circle fa-2x" style=" vertical-align: middle;margin-right:5px"></i> <span> <?php echo $lang['delete_confirm'];?></span></p> </div> <div class="modal-footer"> <button type="button" id="delete" class="btn btn-danger">Delete</button> <button type="button" class="btn btn-default close_syarat">Cancel</button> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div><!-- /.modal -->
<?php
 $edit = "<a data-id='+aData[indek]+' data-toggle=\"tooltip\" title=\"Tambahkan Ke Prasyarat\" class=\"btn btn-primary edit_data \"><i class=\"fa fa-plus\"></i></a>";
  $hapus = "<button data-id='+aData[indek]+' data-uri=".base_admin()."modul/kurikulum/syarat_action.php".' class="btn btn-danger hapus_dtb " data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>';
 ?>
<script type="text/javascript">


    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kurikulum/syarat_edit.php",
            type : "post",
            data : {id_mat:<?=$_POST['id_data'];?>,id_mat_syarat:id},
            success: function(data) {
                $("#isi_kec").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_kec').modal({ keyboard: false,backdrop:'static' });

    });


  var tables = $('#list_mk').DataTable({

         "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$edit;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
          "lengthMenu": [[5, 10,20,30], [5, 10,20,30]],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [2],
              'orderable': false,
              'searchable': false
            }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/kurikulum/mat_syarat_data_list.php',
            type: 'post',  // method  , by default get
                data: function ( d ) {

                      d.kur_id = "<?=$_POST['kur_id'];?>";
                      d.id_mat = "<?=$_POST['id_data'];?>";
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },

  });

 var table = $("#dtb_syarat").DataTable({
       "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<?=$hapus;?>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
          "searching":false,
          "paging": false,
          "lengthMenu": [[5, 10,20,30], [5, 10,20,30]],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [2],
              'orderable': false,
              'searchable': false
            }
             ],

    
            'ajax':{
              url :'<?=base_admin();?>modul/kurikulum/mat_syarat_data.php',
            type: 'post',  // method  , by default get
                data: function ( d ) {

                      d.id_mat = "<?=$_POST['id_data'];?>";
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },

 });

 $(".close_syarat").on("click",function(){
  $("#ucing_hapus").modal( 'hide' ).data( 'bs.modal', null );
});

  $(".table").on('click','.hapus_dtb',function(event) {

    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);

    uri = currentBtn.attr('data-uri');
    id = currentBtn.attr('data-id');
    dtb = currentBtn.attr('data-dtb');
    id_mk = <?=$prodi->id_matkul;?>;


    $('#ucing_hapus')
        .modal({ keyboard: false })
        .one('click', '#delete', function (e) {

                $.ajax({
          type: "POST",
          url: uri+"?act=delete&id="+id+"&id_mk="+id_mk,
          success: function(data){
             tables.draw(false);
             table.draw(false);

          }
          });
          $('#ucing_hapus').modal('hide');

        });



  });

</script>