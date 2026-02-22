
<table id="dtb_presensi" class="table table-bordered table-striped display responsive nowrap" width="100%">
                              <thead>
                                <tr>
                                  <th>Pert</th>
                                  <th>Tanggal</th>
                                  <th>Waktu</th>
                                  <th>Pengajar</th>
                                  <th>Status</th>
                                   <th>Materi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>


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
        'dom' : "",
        pageLength: -1,
        //"ordering" : false,
        responsive: true,
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