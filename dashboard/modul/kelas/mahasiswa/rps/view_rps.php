<div class="box box-success">
<div class="box-header">
              <h3 class="box-title">RPS</h3>
            </div>
<div class="box-body">
                        <table id="dtb_file" class="table table-bordered table-striped display responsive nowrap">
                            <thead>
                                <tr>
                                  <th>Tanggal Upload</th>
                                  <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
</div>
</div>

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
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/mahasiswa/rps/data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$_POST['kelas_id']?>;
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

<div class="box box-success">
<div class="box-header">
              <h3 class="box-title">PRESENSI & MATERI</h3>
              
            </div>
<div class="box-body">
    <a href="<?=base_admin()?>modul/kelas/cetak/cetak_bap_mhs.php?ids=<?=$_POST['kelas_id'];?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"> Cetak Bukti Hadir</i></a>
<table id="dtb_presensi" class="table table-bordered table-striped display nowrap" width="100%">
                              <thead>
                                <tr>
                                  <th>Pertemuan</th>
                                  <th>Tanggal</th>
                                  <th>Waktu</th>
                                  <th>Pengajar</th>
                                  <th>Status</th>
                                  <th>Materi</th>
                                  <th>Link Materi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

</div>
</div>
<script type="text/javascript">
    $("#dtb_presensi").on('click','.edit_data',function(event) {
            event.preventDefault();
            var currentBtn = $(this);
            id = currentBtn.attr('data-id');
            $("#loadnya").show();
            //$(this).find('.fa').toggleClass('fa-plus fa-minus');
            $('.button-top').hide();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/presensi/pertemuan_edit.php",
              type : "POST",
              data : {id_pertemuan:id},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_tambah_pertemuan").html(data);
                  $("#isi_tambah_pertemuan").slideDown();
                  $('.action-title').html('<b>Edit Pertemuan</b>');
                  
              }
           });
    });

//modal jadwal
$("#dtb_presensi").on('click','.input-absen',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');
        kelas_id = currentBtn.attr('data-kelas');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/presensi/input_absensi_view.php",
            type : "post",
            data : {kelas_id:kelas_id,pertemuan:id},
            success: function(data) {
                $("#input_mahasiswa_absen").html(data);
                $(".modal-title-absen").html("Isi Presensi Mahasiswa");
                $("#loadnya").hide();
          }
        });

    $('#modal_input_absen').modal({ keyboard: false,backdrop:'static' });

    });


    $("#dtb_presensi").on('click','.input-materi',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');
        kelas_id = currentBtn.attr('data-kelas');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas/mahasiswa/presensi/input_materi.php",
            type : "post",
            data : {kelas_id:kelas_id,pertemuan:id},
            success: function(data) {
                $("#input_mahasiswa_absen").html(data);
                $(".modal-title-absen").html("Isi Materi Pertemuan");
                $("#loadnya").hide();
          }
        });

    $('#modal_input_absen').modal({ keyboard: false,backdrop:'static' });

    });

        $("#tambah_pertemuan").click(function() {
          if ($("#isi_tambah_pertemuan").is(':visible')) {
            $(this).find('.fa').toggleClass('fa-plus fa-minus');
            $("#isi_tambah_pertemuan").html('');
            $("#isi_tambah_pertemuan").slideUp();
          } else {
            $("#loadnya").show();
            console.log('hallo');
            $('.button-top').hide();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/pertemuan/pertemuan_add.php",
              type : "POST",
              data : {kelas_id:<?=$kelas_id;?>},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_tambah_pertemuan").html(data);
                  $("#isi_tambah_pertemuan").slideDown();
                  $('.action-title').html('<b>Tambah Pertemuan</b>');
                  
              }
           });
          }
    });


       var dtb_presensi = $("#dtb_presensi").DataTable({
        
        //"ordering" : false,
        searching : false,
        paging : false,
        scrollX: true,
           'bProcessing': true,
            'bServerSide': true,
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/mahasiswa/presensi/tab_presensi_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$_POST['kelas_id'];?>;
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });
</script>
            