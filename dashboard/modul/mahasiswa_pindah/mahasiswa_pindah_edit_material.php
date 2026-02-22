<!-- Content Header (Page header) -->
  <link rel="stylesheet" href="<?= base_url() ?>dashboard/assets/css/jquery-ui.css"> 
<style type="text/css">
     .ui-autocomplete { 
  z-index:2147483647;
}
</style>

                <section class="content-header">
                    <h1>
                        Mahasiswa Pindah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>mahasiswa-pindah">Mahasiswa Pindah</a></li>
                        <li class="active">Mahasiswa Pindah List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                <h3 class="text-center">Konversi Nilai </h3> 
                            </div><!-- /.box-header -->
                            <div class="box-body table-responsive">
                            <div class="box box-primary">
                              <div class="box-header with-border">
                                <h3 class="box-title">Profil Mahasiswa</h3>
                              </div>
                              <!-- /.box-header -->
                              <div class="box-body">
                                <strong><i class="fa fa-users margin-r-5"></i> Nama

                                <p class="text-muted">
                                 <?= $mhs->nama_mhs ?>
                                </p>
                                </strong>

                                <hr>

                                <strong><i class="fa fa-map-marker margin-r-5"></i> Kampus Lama | Kampus Baru

                                <p class="text-muted"><?= $mhs->kampus_lama." | ".$mhs->kampus_baru ?></p>
                                </strong>

                                <hr>

                                <strong><i class="fa fa-map-marker margin-r-5"></i> Jurusan Lama | Jurusan Baru

                                <p class="text-muted"><?= $mhs->jurusan_lama." | ".$mhs->nama_jur ?></p> 
                                </strong>

                                <hr>
                                

                              </div>
                              <!-- /.box-body -->
                            </div>
                            <div class="alert alert-warning fade in error_data_delete" style="display:none">
                              <button type="button" class="close hide_alert_notif">&times;</button>
                              <i class="icon fa fa-warning"></i> <span class="isi_warning_delete"></span>
                            </div>
                      <div class="col-md-12">
                       
                         <?php
                              $qm = $db->query("select nim_lama as nim,jenis_pindah from mhs_pindah where id='".uri_segment(3)."' ");
                              foreach ($qm as $vv) {
                                if ($vv->jenis_pindah=='internal') {
                                  ?>
                                  <table id="dtb_example" class="table table-bordered table-striped">
                                          <thead>
                                              <tr> 
                                                <th class="text-center">No</th> 
                                                <th class="text-center">Kode Lama </th>
                                                <th class="text-center">Matkul Lama</th>
                                                <th class="text-center">SKS</th>
                                                <th class="text-center">Nilai Angka</th>
                                                <th class="text-center">Nilai Huruf</th>
                                                <th class="text-center">Bobot</th>
                                                <th class="text-center">Matkul Baru</th> 
                                               
                                          
                                          </thead>
                                          <tbody>
                                          <?php
                                          $qq = $db->query("select k.bobot,k.nilai_huruf,k.nilai_angka, m.id_matkul, m.kode_mk,m.nama_mk,m.total_sks as sks from krs_detail k join matkul m on m.id_matkul=k.kode_mk  where k.nim='$vv->nim' ");
                                          foreach ($qq as $v) {
                                             
                                              $qx = $db->query("select id from konversi_matkul where id_pindah='".uri_segment(3)."' and kode_lama='$v->kode_mk' ");
                                              if ($qx->rowCount()>0) {
                                                $data = array('id_pindah' => uri_segment(3) ,
                                                           'kode_lama' => $v->kode_mk,
                                                           'nama_mk'  => $v->nama_mk,
                                                           'sks' => $v->sks,
                                                           'bobot' => $v->bobot,
                                                           'nilai_angka' => $v->nilai_angka,
                                                           'nilai' => $v->nilai_huruf,
                                                           'date_updated' => date("Y-m-d H:i:s") );
                                                //print_r($data);
                                                foreach ($qx as $kx) {
                                                  $db->update("konversi_matkul",$data,"id",$kx->id);
                                                }
                                              }else{ 
                                                $data = array('id_pindah' => uri_segment(3) ,
                                                           'kode_lama' => $v->kode_mk,
                                                           'nama_mk'  => $v->nama_mk,
                                                           'sks' => $v->sks,
                                                           'bobot' => $v->bobot, 
                                                            'nilai_angka' => $v->nilai_angka,
                                                           'nilai' => $v->nilai_huruf,
                                                           'date_created' => date("Y-m-d H:i:s"),
                                                           'date_updated' => date("Y-m-d H:i:s") );
                                                $db->insert("konversi_matkul",$data);
                                              }
                                          } 
                                          $qq = $db->query("select k.*,m.kode_mk,m.nama_mk as nama_matkul from konversi_matkul k 
                                           left join matkul m on m.id_matkul=k.kode_baru where id_pindah='".uri_segment(3)."' ");
                                          $no=1;
                                          foreach ($qq as $v) {
                                            $qs = $db->fetch_all("view_semester");
                                            $sem = "<select class='form-control' onchange='set_semester(this.value,\"$v->id\")' name='sem_$no'><option value=''>Pilih Semester</option> ";
                                            foreach ($qs as $ks) {
                                              if ($ks->id_semester==$v->semester) {
                                                 $sem.="<option value='$ks->id_semester' selected>$ks->tahun_akademik</option>";
                                              }else{
                                                 $sem.="<option value='$ks->id_semester'>$ks->tahun_akademik</option>";
                                              }
                                             
                                            }
                                            $sem .="</select>";
                                            $val_kode = "";
                                            if ($v->kode_baru!='') {
                                             $val_kode = "value='$v->kode_mk - $v->nama_matkul'"; 
                                            }
                                           
                                            echo "<tr>
                                            <td>$no</td>
                                            <td>$v->kode_lama</td>
                                            <td>$v->nama_mk</td>
                                            <td class='text-center'>$v->sks</td> 
                                            <td class='text-center'>$v->nilai_angka</td> 
                                            <td class='text-center'>$v->nilai</td> 
                                            <td class='text-center'>$v->bobot</td> 
                                            <td><input type='text' id='baris_$v->id' onkeyup='cek_matkul(\"$v->id\")' class='form-control cari_matkul' $val_kode  placeholder='Input Kode, Mata Kuliah'></td>
                                            
                                            </tr>";
                                            $no++;
                                          }
                                          ?>
                                          </tbody>
                                   </table>
                                  <?php
                                }else{
                                  ?>
                                   <table id="dtb_example" class="table table-bordered table-striped">
                                          <thead>
                                              <tr> 
                                                <th class="text-center" style="width: 100px"></th> 
                                                <th class="text-center">Kode Lama </th>
                                                <th class="text-center">Matkul Lama</th>
                                                <th class="text-center" style="width: 100px">SKS</th>
                                                <th class="text-center" style="width: 100px">Nilai Angka</th>
                                                <th class="text-center" style="width: 150px">Nilai</th>
                                                <th class="text-center" style="width: 300px">Matkul Baru</th> 
                                              
                                              </tr>
                                          </thead>
                                          <tbody id="isi_baris">
                                  <?php
                                   $qq = $db->query("select k.*,m.kode_mk,m.nama_mk as nama_matkul from konversi_matkul k 
                                           left join matkul m on m.id_matkul=k.kode_baru where id_pindah='".uri_segment(3)."' ");
                                          $no=1;
                                    if ($qq->rowCount()==0) {
                                      $data_kon = array('id_pindah' => uri_segment(3) , 
                                                        'date_created' => date("Y-m-d H:i:s"),
                                                        'date_updated' => date("Y-m-d H:i:s"));
                                      $db->insert("konversi_matkul",$data_kon);
                                      $id_konversi = $db->last_insert_id();

                                      $qs = $db->fetch_all("view_semester");
                                            $sem = "<select class='form-control' onchange='set_semester(this.value,1)' name='sem_$no'><option value=''>Pilih Semester</option> ";
                                            foreach ($qs as $ks) {                                            
                                                 $sem.="<option value='$ks->id_semester'>$ks->tahun_akademik</option>";
                                               }
                                     ?>
                                    
                                            <tr id="baris_matkul_<?= $id_konversi ?>">
                                              <td>
                                                <button class="btn btn-success" onclick="plus_baris('<?= $id_konversi ?>')"><i class="fa fa-plus"></i></button>
                                                <button class="btn btn-danger" onclick="minus_baris('<?= $id_konversi ?>')"><i class="fa fa-minus"></i></button>
                                              </td>
                                              <td>
                                                <input type="text" name="kode_lama[]" onkeyup="simpan_data('kode_lama','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="matkul_lama[]" onkeyup="simpan_data('nama_mk','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="sks_lama[]" onkeyup="simpan_data('sks','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="nilai_angka[]" onkeyup="simpan_data('nilai_angka','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" name="nilai_lama[]" onkeyup="simpan_data('nilai','<?= $id_konversi ?>',this.value)" class="form-control">
                                              </td>
                                              <td><input type='text' id="baris_<?= $id_konversi ?>"  onkeyup='cek_matkul("<?= $id_konversi ?>")' class='form-control cari_matkul' placeholder='Input Kode, Mata Kuliah'></td>
                                             
                                            </tr>
                                        
                                     <?php
                                    }else{
                                      
                                          $qq = $db->query("select k.*,m.kode_mk,m.nama_mk as nama_matkul from konversi_matkul k 
                                           left join matkul m on m.id_matkul=k.kode_baru where id_pindah='".uri_segment(3)."' ");
                                          $no=1;
                                          foreach ($qq as $v) {
                                            $qs = $db->fetch_all("view_semester");
                                            $sem = "<select class='form-control' onchange='set_semester(this.value,\"$v->id\")' name='sem_$no'><option value=''>Pilih Semester</option> ";
                                            foreach ($qs as $ks) {
                                              if ($ks->id_semester==$v->semester) {
                                                 $sem.="<option value='$ks->id_semester' selected>$ks->tahun_akademik</option>";
                                              }else{
                                                 $sem.="<option value='$ks->id_semester'>$ks->tahun_akademik</option>";
                                              }
                                             
                                            }
                                            $sem .="</select>";
                                            $val_kode = "";
                                            if ($v->kode_baru!='') {
                                             $val_kode = "value='$v->kode_mk - $v->nama_matkul'"; 
                                            }
                                            ?>
                                            <tr id="baris_matkul_<?= $v->id ?>">
                                              <td>
                                                <button class="btn btn-success" onclick="plus_baris('<?= uri_segment(3) ?>')"><i class="fa fa-plus"></i></button>
                                                <button class="btn btn-danger" onclick="minus_baris('<?= $v->id ?>')"><i class="fa fa-minus"></i></button>
                                              </td>
                                              <td>
                                                <input type="text" value="<?= $v->kode_lama ?>" name="kode_lama[]" onkeyup="simpan_data('kode_lama','<?= $v->id ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" value="<?= $v->nama_mk ?>" name="matkul_lama[]" onkeyup="simpan_data('nama_mk','<?= $v->id ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" value="<?= $v->sks ?>"  name="sks_lama[]" onkeyup="simpan_data('sks','<?= $v->id ?>',this.value)" class="form-control">
                                              </td>
                                              <td>
                                                <input type="text" value="<?= $v->nilai_angka ?>" data-id="<?=$v->id;?>" name="nilai_angka[]" onkeyup="simpan_data('nilai_angka','<?= $v->id ?>',this.value)" class="form-control nilai_angka">
                                              </td>
                                              <td>
                                                  <select class="form-control nilai_huruf" onchange="simpan_data('nilai','<?= $id_konversi ?>',this.value)">
                                                  <option value="">Pilih Nilai</option>
                                                <?php
                                                $skala_nilai = $db->query("select * from skala_nilai where kode_jurusan=? group by nilai_huruf",array('kode_jurusan' => $mhs->jurusan_baru));
                                                foreach ($skala_nilai as $kn) {
                                                  if ($kn->nilai_huruf==$v->nilai) {
                                                    echo "<option value='".$kn->nilai_huruf."#".$kn->nilai_indeks."' selected>".$kn->nilai_huruf." (".$kn->nilai_indeks.")"."</option>";
                                                  }else{
                                                     echo "<option value='".$kn->nilai_huruf."#".$kn->nilai_indeks."'>".$kn->nilai_huruf." (".$kn->nilai_indeks.")"."</option>";
                                                  }
                                                }
                                                ?> 
                                                </select>
                                              <!--   <input type="text" value="<?= $v->nilai ?>"  name="nilai_lama[]" onkeyup="simpan_data('nilai','<?= $v->id ?>',this.value)" class="form-control"> -->
                                              </td>
                                              <td><input type='text' id="baris_<?= $v->id ?>" <?= $val_kode ?> onkeyup='cek_matkul("<?= $v->id ?>")' class='form-control cari_matkul' placeholder='Input Kode, Mata Kuliah'></td>
                                              
                                              </tr>
                                            <?php
                                            $no++;
                                          }
                                   }
                                   ?>
                                     </tbody>
                                        </table>
                                  <?php
                                }
                              }
                              
                         ?>
                         
                         </div>

                  <!--     <div class="col-md-6">
                         <table class="table table-bordered table-striped"> 
                                          <thead>
                                              <tr>
                                                <th>No</th>
                                                <th>Kode </th>
                                                <th>Matkul</th>
                                                <th>SKS</th>
                                                <th>Aksi</th>
                                          
                                          </thead>
                                          <tbody>
                                          </tbody>
                            </table>
                         </div> -->
                       
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
                $edit = "<a data-id='+aData[indek]+'  class=\"edit_data \" data-toggle=\"tooltip\" title=\"Edit\"><i class=\"fa fa-pencil\"></i> Edit Data</a>";
                $matkul = "<a data-id='+aData[indek]+'  class=\"konversi_matkul \" data-toggle=\"tooltip\" title=\"Konversi Matkul\"><i class=\"fa fa-book\"></i> Konversi Matkul</a>"; 
              } else { 
                  $edit ="";
              }
            if ($role_act['del_act']=='Y') {
                $del = "<a data-id='+aData[indek]+' data-uri=".base_admin()."modul/mahasiswa_pindah/mahasiswa_pindah_action.php".' class="hapus_dtb_notif" data-toggle="tooltip" title="Hapus" data-variable="dtb_mahasiswa_pindah"><i class="fa fa-trash"></i> Hapus</a>'; 
            } else {
                $del="";
            }
                             }
            }

        ?>

    <div class="modal" id="modal_mahasiswa_pindah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Mahasiswa Pindah</h4> </div> <div class="modal-body" id="isi_mahasiswa_pindah"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>
    
    </section><!-- /.content -->
     <script src="<?= base_url() ?>dashboard/assets/js/jquery-ui.js"></script> 

        <script type="text/javascript">

$("#isi_baris").on("keyup", ".nilai_angka", function() {
    var btn = $(this);
    var jurusan = "<?=$mhs->jurusan_baru;?>";
    var angkatan = "<?=$mhs->angkatan_baru;?>";
    var id = btn.data("id");
    var nilai_huruf = $(this).closest('td').siblings().find('.nilai_huruf');

    $.ajax({
        type: "post",
        url: "<?=base_admin();?>modul/mahasiswa_pindah/get_nilai_huruf.php",
        data: {
            nilai: this.value,
            kode_jurusan: jurusan,
            angkatan: angkatan,
            id : id
        },
        success: function(data) {
            nilai_huruf.html(data);
        }
    });
});



          function simpan_data(ket,id,val){
            $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=simpan_data",
              type : "POST",
              data:{
                 id : id,
                 ket : ket,
                 val : val
              }, 
              success: function(data) {
                  //$("#isi_baris").append(data);
              }
          });
          }

          function plus_baris(id){
             $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=add_baris",
              type : "POST",
              data:{
                 id : id
              }, 
              success: function(data) {
                  $("#isi_baris").append(data);
              }
          });
            // $('#isi_baris').html($button);
           }  

           function minus_baris(id){
             $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=minus_baris",
              type : "POST",
              data:{
                 id : id
              }, 
              success: function(data) {
                  $("#baris_matkul_"+id).remove();
              }
          });
           
           }
      
      $("#add_mahasiswa_pindah").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_add.php",
              type : "GET",
              success: function(data) {
                  $("#isi_mahasiswa_pindah").html(data);
              }
          });

      $('#modal_mahasiswa_pindah').modal({ keyboard: false,backdrop:'static',show:true });

    });
    
      
    $(".table").on('click','.edit_data',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_edit.php",
            type : "post",
            data : {id_data:id},
            success: function(data) {
                $("#isi_mahasiswa_pindah").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_mahasiswa_pindah').modal({ keyboard: false,backdrop:'static' });

    });

     $(".table").on('click','.konversi_matkul',function(event) {
      
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        document.location="<?= base_url() ?>dashboard/index.php/mahasiswa-pindah/konversi_matkul/"+id;

    });
    
      var dtb_mahasiswa_pindah = $("#dtb_mahasiswa_pindah").DataTable({
           "fnCreatedRow": function( nRow, aData, iDataIndex ) {
            var indek = aData.length-1;
            $('td:eq('+indek+')', nRow).html('<div class="btn-group"> <button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Actions <i class="fa fa-angle-down"></i> </button> <ul class="dropdown-menu aksi-table" role="menu"><li><?=$edit;?></li><li><?= $del; ?></li><li><?= $matkul ?></li></ul></div>');
              $(nRow).attr('id', 'line_'+aData[indek]);
              },
              "dom": "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-6'l><'col-sm-6'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",

              buttons: [
              {
                 extend: 'collection',
                 text: 'Export Data',
                 buttons: [ 'pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5' ],

              }
              ],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [ {
            'targets': [10],
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
              url :'<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_data.php',
            type: 'post',  // method  , by default get
            error: function (xhr, error, thrown) {
            console.log(xhr);

            }
          },
        });

  $('#dtb_mahasiswa_pindah').on('draw.dt', function() {
          init_selected()
      });

      $('#select_all').on('click', function() {
          select_deselect('select')
      });
      $('#deselect_all').on('click', function() {
          select_deselect('unselect')
  });



  $(document).on('click', '#dtb_mahasiswa_pindah tbody tr td', function(event) {
      var btn = $(this).find('button');
      if (btn.length == 0) {
          $(this).parents('tr').toggleClass('DTTT_selected selected');
          var selected = check_selected();
          init_selected();

      }
  });



  function init_selected() {
      var selected = check_selected();
      var btn_hide = $('#select_all, #deselect_all, #bulk_delete, .selected-data');
      if (selected.length > 0) {
          btn_hide.show()
      } else {
          btn_hide.hide()
      }
  }


  function check_selected() {
      var table_select = $('#dtb_mahasiswa_pindah tbody tr.selected');
      var array_data_delete = [];
      table_select.each(function() {
          var check_data = $(this).find('.hapus_dtb_notif').attr('data-id');
          if (typeof check_data != 'undefined') {
              array_data_delete.push(check_data)
          }
      });
      $('.selected-data').text(array_data_delete.length + ' <?=$lang["selected_data"];?>');
      return array_data_delete
  }


  function select_deselect(type) {
      if (type == 'select') {
          $('#dtb_mahasiswa_pindah tbody tr').addClass('DTTT_selected selected')
      } else {
          $('#dtb_mahasiswa_pindah tbody tr').removeClass('DTTT_selected selected')
      }
      init_selected()
  }




/* Add a click handler for the delete row */
  $('#bulk_delete').click( function() {
    var anSelected = fnGetSelected( dtb_mahasiswa_pindah );
    var data_array_id = check_selected();
    var all_ids = data_array_id.toString();
    $('#ucing').modal({ keyboard: false }).one('click', '#delete', function (e) {
        $('#loadnya').show();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=base_admin();?>modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=del_massal',
            data: {data_ids:all_ids},
               success: function(responseText) {
                  $('#loadnya').hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $('#informasi').modal('show');
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data_delete').hide();
                               $('#loadnya').hide();
                               $(anSelected).remove();
                               dtb_mahasiswa_pindah.draw();
                          } else {
                             $('.isi_warning_delete').text(responseText[index].error_message);
                             $('.error_data_delete').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data_delete').first().offset().top)
                            },500);
                          }
                    });
                }
            //async:false
        });

        $('#ucing').modal('hide');

    });

  });

  /* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
      return oTableLocal.$('tr.selected');
  }

  function set_semester(val,id) {
     $.ajax({
               type : 'POST',
               data : {
                  id : id,
                  val : val 
               },
                url : "<?= base_url() ?>dashboard/modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=simpan_semester_konversi",
                              success:function(data){

           } 
      });
  }

  function cek_matkul(id) {  
    
                      $('#baris_'+id).autocomplete({
                        source: function (request, response) {
                          $.ajax({
                            url: "<?= base_url() ?>dashboard/modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=cari_matkul",
                            data: { term: request.term },
                            type : 'POST',
                            dataType: "json",
                            success: function (data) {

                              response($.map(data, function (item) {
                                return {
                                  id_mk: item.id_matkul,
                                  kode_mk: item.kode_mk,
                                  nama_mk : item.nama_mk,
                                  nama_jur : item.nama_jur 
                                };
                              }))
                            }
                          })
                        },
                        select: function (event, ui) {
                            $('#baris_'+id).val(ui.item.kode_mk+" - "+ui.item.nama_mk); 
                            $.ajax({
                              type : 'POST',
                              data : {
                                id:id,
                                kode_mk : ui.item.id_mk 
                              },
                              url : "<?= base_url() ?>dashboard/modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=simpan_matkul_konversi",
                              success:function(data){

                              }
                            });

                                               return false;
                         }
                                           }).data("ui-autocomplete")._renderItem = function (ul, item) {
                        var inner_html = '<a><div class="list_item_container" style="height:40px">' + item.kode_mk + '-' +item.nama_mk+ '<br>Jurusan '+item.nama_jur+'</div></a>';
                        return $("<li></li>")
                        .data("ui-autocomplete-item", item)
                        .append(inner_html)
                        .appendTo(ul);
                       };
  }

  function cek_matkul2(id) {  
    
                      $('#baris_'+id).autocomplete({
                        source: function (request, response) {
                          $.ajax({
                            url: "<?= base_url() ?>dashboard/modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=cari_matkul",
                            data: { term: request.term },
                            type : 'POST',
                            dataType: "json",
                            success: function (data) {

                              response($.map(data, function (item) {
                                return {
                                  id_mk: item.id_matkul,
                                  kode_mk: item.kode_mk,
                                  nama_mk : item.nama_mk,
                                  nama_jur : item.nama_jur 
                                };
                              }))
                            }
                          })
                        },
                        select: function (event, ui) {
                            $('#baris_'+id).val(ui.item.kode_mk+" - "+ui.item.nama_mk); 
                            $.ajax({
                              type : 'POST',
                              data : {
                                id:id,
                                kode_mk : ui.item.id_mk 
                              },
                              url : "<?= base_url() ?>dashboard/modul/mahasiswa_pindah/mahasiswa_pindah_action.php?act=simpan_matkul_konversi",
                              success:function(data){

                              }
                            });

                                               return false;
                         }
                                           }).data("ui-autocomplete")._renderItem = function (ul, item) {
                        var inner_html = '<a><div class="list_item_container" style="height:40px">' + item.kode_mk + '-' +item.nama_mk+ '<br>Jurusan '+item.nama_jur+'</div></a>';
                        return $("<li></li>")
                        .data("ui-autocomplete-item", item)
                        .append(inner_html)
                        .appendTo(ul);
                       };
  }
  $(document).ready(function(){
  });
</script>
            