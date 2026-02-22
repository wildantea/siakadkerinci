<div class="button-top">
<!-- <a id="tambah_pertemuan" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah Pertemuan</a> -->
<a id="generate_pertemuan" class="btn btn-success "><i class="fa fa-gear"></i> Generate Pertemuan</a>
</div>

<h4 class="box-title action-title"></h4>
<p>
<div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
<div id="isi_tambah_pertemuan" style="display: none;">
</div>                 

        <div class="row" id="aksi_top_krs" style="display: none">
                                   <form>
                                   <div class="col-sm-4" style="margin-bottom: 10px;">
                                     
                                     <div class="input-group input-group-sm">
                                         <span class="input-group-btn">
                                           <button type="button" class="btn btn-danger btn-flat selected-data">Terpilih</button>
                                         </span>
                                      <select class="form-control col-lg-3" name="aksi_krs" id="aksi_krs">
                                     <!-- <option value="dosen">Edit Dosen Pengajar</option>
                                     <option value="0">Batalkan Persetujuan KRS</option> -->
                                     <option value="hapus">Hapus</option>
                                   </select>
                                         <span class="input-group-btn">
                                           <button type="button" class="btn btn-success btn-flat submit-proses">Submit</button>
                                         </span>
                                   </div>
                                   </div>
</form>
                                 </div>
                        <table id="dtb_pertemuan" class="table table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                <th style='padding-right:1px;padding-left: 0px;' class='dt-center'>
<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline" style="margin-bottom: 15px;"> <input type="checkbox" class="group-checkable bulk-check"> <span></span></label>
     </th>
                                  <th>Pert</th>
                                  <th>Tanggal</th>
                                  <th>Waktu</th>
                                  <th>Ruang</th>
                                  <th>Jenis</th>
                                  <th>Pengajar</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

<script type="text/javascript">
  
        $("#generate_pertemuan").click(function() {
          if ($("#isi_tambah_pertemuan").is(':visible')) {
            $(this).find('.fa').toggleClass('fa-plus fa-minus');
            $("#isi_tambah_pertemuan").html('');
            $("#isi_tambah_pertemuan").slideUp();
          } else {
            $("#loadnya").show();
            console.log('hallo');
            $('.button-top').hide();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/pertemuan/generate.php",
              type : "POST",
              data : {kelas_id:<?=$kelas_id;?>},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_tambah_pertemuan").html(data);
                  $("#isi_tambah_pertemuan").slideDown();
                  $('.action-title').html('<b>Generate Pertemuan</b>');
                  
              }
           });
          }
    });
    $("#dtb_pertemuan").on('click','.edit_data',function(event) {
            event.preventDefault();
            var currentBtn = $(this);
            id = currentBtn.attr('data-id');
            $("#loadnya").show();
            //$(this).find('.fa').toggleClass('fa-plus fa-minus');
            $('.button-top').hide();
            $.ajax({
              url : "<?=base_admin();?>modul/kelas/pertemuan/pertemuan_edit.php",
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
              data : {kelas_id:<?=$kelas_id?>},
              success: function(data) {
                $("#loadnya").hide();
                  $("#isi_tambah_pertemuan").html(data);
                  $("#isi_tambah_pertemuan").slideDown();
                  $('.action-title').html('<b>Tambah Pertemuan</b>');
                  
              }
           });
          }
    });
       var dtb_pertemuan = $("#dtb_pertemuan").DataTable({
        'dom' : "",
        pageLength: -1,
        //"ordering": false,
           'bProcessing': true,
            'bServerSide': true,
            
         //disable order dan searching pada tombol aksi use "className":"none" for always responsive hide column
                 "columnDefs": [
                  {
             "targets": [0,-1],
             "width" : "2%",
              "orderable": false,
              "searchable": false,
              "class" : "all"
            },
                  {
             "targets": [1],
             "width" : "2%",
              "orderable": false,
              "searchable": false,
              "class" : "all"
            },
    
             ],
      
            'ajax':{
              url :'<?=base_admin();?>modul/kelas/pertemuan/tab_pertemuan_data.php',
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    //d.fakultas = $("#fakultas_filter").val();
                    d.kelas_id = <?=$kelas_id?>;
                  },
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

    $('#dtb_pertemuan tbody').on('click', '.check-selected-pertemuan', function () {
        //$(this).toggleClass('selected');
        $(this).parents('tr').toggleClass('DTTT_selected selected');
        var selected = check_selected();
        init_selected();
    });


$(".bulk-check").on('click',function() { // bulk checked
          var status = this.checked;
          if (status) {
            select_deselect('select');
          } else {
            select_deselect('unselect');
          }
          
          $(".check-selected-pertemuan").each( function() {
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
      var table_select = $('#dtb_pertemuan tbody tr.selected');
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
          $('#dtb_pertemuan tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_pertemuan tbody tr').removeClass('DTTT_selected selected')
      }
    init_selected()
  }


/* Add a click handler for the delete row */
  $('.submit-proses').click( function() {
    $("#loadnya").show();
      var data_array_id = check_selected();
      var all_ids = data_array_id.toString();
      var notif = 'Apakah anda yakin ?';
      if ($("#aksi_krs").val()=='hapus') {
         notif = 'Apakah Yakin akan Hapus pertemuan ini? Absensi dan Materi yang sudah diinput akan terhapus juga';
      }
    $.confirm({
        title: 'Konfirmasi',
        content: notif,
         theme: 'modern',
        buttons: {
            confirm: function () {
            $.ajax({
              url: "<?=base_admin();?>modul/kelas/pertemuan/pertemuan_action.php?act="+$("#aksi_krs").val(),
              dataType: "json",
              type : "post",
              data : {pertemuan_id:all_ids},
              error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
              },
              success: function(responseText) {
                $("#loadnya").hide();
                console.log(responseText);
                $.each(responseText, function(index) {
                  console.log(responseText[index].status);
                  if (responseText[index].status=="die") {
                    $("#informasi").modal("show");
                  } else if(responseText[index].status=="good") {
                    $(".bulk-check").prop('checked',false);
                    select_deselect('unselect');
                    dtb_pertemuan.draw(false);
                    dtb_presensi.draw(false);
                  }
                });
              }

              });
            },
            cancel: function () {
              $("#loadnya").hide();
            }
        }
    });

  });
</script>