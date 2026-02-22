<div class="box box-success">
  <div class="box-header ">
    <h3 class="box-title">Dosen Pengajar</h3>
  </div>
  <div class="box-body">
    <table id="dtb_dosen_pengajar" class="table table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
         <tr>
          <th>NIP</th>
          <th>Nama</th>                   
          <th>Jadwal</th>
          <th>Ruang</th>
         </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>
  </div>
</div>


<script type="text/javascript">
        var dtb_dosen_pengajar = $("#dtb_dosen_pengajar").DataTable({
           'bProcessing': true,
           "info": false,
           'paging' : false,
            'bServerSide': true,
            'searching' : false,
            'ordering' : false,
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/pengajar/tab_pengajar_data.php',
              type: 'post',  // method  , by default get
              data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id;?>;
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
</script>

<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title action-title-rps">RPS</h3>
  </div>
  <div class="box-body">
    <div id="isi_edit_rps">
<?php
//check if rps is exist
$nips = $db2->fetchCustomSingle("select group_concat(id_dosen) as nip from dosen_kelas where id_kelas='$kelas_id'");
$check_exist = $db2->query("select * from rps_file where semester=? and id_matkul=? and nip in($nips->nip)",array('semester' => $kelas_data->sem_id,'id_matkul' => $kelas_data->id_matkul));
if ($check_exist->rowCount()<1) {
  include "upload_rps.php";
}
?>
</div>    

     <table id="dtb_file" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Tanggal Upload</th>
                                  <th>Pengupload</th>
                                  <th>File</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
 <?php
  $edit ="";
  $del="";
    $edit = "<a data-id='+data+'  class=\"btn btn-primary edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
        ?>    


        <script type="text/javascript">
    
    $("#dtb_file").on('click','.edit_data',function(event) {
            event.preventDefault();
            var currentBtn = $(this);
            id = currentBtn.attr('data-id');
            $("#loadnya").show();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/rps/edit_rps.php",
              type : "POST",
              data : {id_rps:id},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_edit_rps").html(data);
                  $("#isi_edit_rps").slideDown();
                  $('.action-title-rps').html('<b>Edit RPS</b>');
              }
           });
    });

      var dtb_file = $("#dtb_file").DataTable({
              
           'order' : [[1,'asc']],
           'paging' : false,
           'searching' : false,
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$edit;?>';
               }
            },
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/rps/data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id?>;
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });$("#dtb_file_filter").on("click",".reset-button-datatable",function(){
    dtb_file
    .search( "" )
    .draw();
  });
</script>

  </div>
</div>

<div class="box box-success">
  <div class="box-header with-border">
    <h3 class="box-title action-title-rps">Materi Kuliah</h3>
  </div>
  <div class="box-body">
    <div id="isi_edit_rps">
<?php
//check if materi is exist
/*$check_exist = $db2->query("select * from rps_materi_kuliah where semester=? and id_matkul=? and id_kelas=? ",array('semester' => $kelas->sem_id,'id_matkul' => $kelas->id_matkul,'id_kelas' => $kelas_id));
if ($check_exist->rowCount()<1) {
  include "upload_materi.php";
}*/
 include "upload_materi.php";
?>
</div>    

     <table id="dtb_materi" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                  <th>Pertemuan</th>
                                  <th>Materi</th>
                                  <th>Link Materi/Bukti Ajar</th>
                                  <th>Tgl Upload</th>
                                  <th>Uploader</th>
                                  <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
 <?php
  $edit ="";
  $del="";
    $edit = "<a data-id='+data+'  class=\"btn btn-primary edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a>";
        ?>    


        <script type="text/javascript">
    

    $("#dtb_materi").on('click','.edit_data',function(event) {
       // $("#loadnya").show();
         event.preventDefault();
            var currentBtn = $(this);
            id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/pengajar/edit_materi.php",
            type : "post",
            data : {id_materi:id},
            success: function(data) {
                $("#input_mahasiswa_absen").html(data);
                $(".modal-title-absen").html("Edit Materi");
                $("#loadnya").hide();
          }
        });

    $('#modal_input_absen').modal({ keyboard: false,backdrop:'static' });

    });



      var dtb_materi = $("#dtb_materi").DataTable({
              
           'order' : [[1,'asc']],
           'paging' : false,
           'searching' : false,
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
              
            {
            "targets": [-1],
              "orderable": false,
              "searchable": false,
              "className": "all",
              "render": function(data, type, full, meta){
                return '<?=$edit;?>';
               }
            },
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/rps/materi_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id?>;
            },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });$("#dtb_materi_filter").on("click",".reset-button-datatable",function(){
    dtb_file
    .search( "" )
    .draw();
  });
</script>

  </div>
</div>


                      