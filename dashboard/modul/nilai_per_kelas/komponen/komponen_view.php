<?php
include "../../../inc/config.php";
$kelas_data = $db2->fetchCustomSingle("SELECT tdk.kelas_id,tdk.kls_nama,ada_komponen,komponen,vmk.kode_mk,tdk.sem_id,vmk.nama_mk,vmk.total_sks,vmk.kode_jur,vmk.kode_jur, vmk.nama_jurusan from kelas tdk
INNER JOIN view_matakuliah_kurikulum vmk using(id_matkul) where kelas_id=?",array('kelas_id' => $_POST['kelas_id']));
if ($kelas_data->ada_komponen=='Y') {
  $komponen = 1;
} else {
  $komponen = 0;
}
?>
<table id="tabelku" class="table table-bordered table-striped" cellspacing="0" width="100%">
        <tbody><tr>
          <td class="info2" width="20%"><strong>Program Studi</strong></td>
          <td width="30%"><?=$kelas_data->nama_jurusan;?></td>
          <td class="info2" width="20%"><strong>Periode</strong></td>
          <td><?=getPeriode($kelas_data->sem_id);?></td>
        </tr>
        <tr>
          <td class="info2"><strong>Matakuliah</strong></td>
          <td><?=$kelas_data->nama_mk;?> (<?=$kelas_data->total_sks;?> sks) </td>
          <td class="info2"><strong>Kelas</strong></td>
          <td><?=$kelas_data->kls_nama;?></td>
        </tr>
      </tbody></table>
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
      <form method="post" class="form-horizontal" id="input_komponen" action="<?=base_admin();?>modul/nilai_per_kelas/nilai_per_kelas_action.php?act=input_komponen">
        <input type="hidden" name="kelas_id" value="<?=$kelas_data->kelas_id;?>">
            <div class="form-group">
                        <label for="Mata Kuliah Setara" class="control-label col-lg-2">Komponen</label>
                        <div class="col-lg-8">
<!--                 <select name="unsur_nilai" id="unsur_nilai" class="form-control">
                    <option value="">Pilih Komponen Nilai</option>
                <?php
                foreach ($db2->query("select * from tb_ref_komponen_nilai") as $item) {
                    echo "<option value='$item->id'>$item->nama_komponen</option>";
                }
                $last_id = $item->id+1;
                ?>                
                </select> -->
              <table class="table">
                <thead>
                     <tr>
                      <th>Nama Komponen</th>
                      <th>Prosentase</th>
                      <!-- <th>Del</th> -->
                     </tr>
                </thead>
                <tbody id="isi_komponen">
<?php 
$prosentase = 0;
if ($komponen) {
$komponen = json_decode($kelas_data->komponen);
   foreach ($komponen as $key) {
    if (is_array($key)) {
        foreach ($key as $val) {
            if($val->id==1) {
              ?>
 <tr class='komponen_list_<?=$val->id;?>'><input type='hidden' name='id_komponen[]' value='<?=$val->id;?>'> <td><input type='text' required name='nama_komponen[]' value='<?=$val->nama_komponen;?>' class='form-control' readonly></td> <td><div class='input-group' style='width:100px'> <input type='text' class='form-control prosentase' required onkeypress='return isNumberKey(event)' name='prosentase[]' onblur='findTotal()' value='<?=$val->value_komponen;?>'> <span class='input-group-addon'> % </span> </div></td>
    <!--  <td>
   <span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span>
</td> 
 -->
</tr>
              <?php
            } else {
                 $readonly = '';
                if ($val->id==5 or $val->id==6) {
                    $readonly = 'readonly';
                }
            ?>
 <tr class='komponen_list_<?=$val->id;?>'>
    <input type='hidden' name='id_komponen[]' value='<?=$val->id;?>'> <td><input type='text' required name='nama_komponen[]' value='<?=$val->nama_komponen;?>' class='form-control' readonly></td> <td><div class='input-group' style='width:100px'> <input type='text' class='form-control prosentase' required onkeypress='return isNumberKey(event)' <?=$readonly?> name='prosentase[]' onblur='findTotal()' value='<?=$val->value_komponen;?>'> <span class='input-group-addon'> % </span> </div></td>
    <!--     <td>
            <span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span>
        </td>  -->
</tr>
            <?php
        }
        }
    }
   }
   $prosentase = $komponen->total_prosentase;

 } 

 ?>
 </tbody>
                <footer>
                    <th style="text-align: right;vertical-align: middle;">Total Prosentase</th>
                    <th>
                        <div class='input-group' style='width:100px'> <input type='text' readonly class='form-control' id="total_prosentase" required  name='total_prosentase' value="<?=$prosentase?>"> <span class='input-group-addon'> % </span> </div>
                    </th>
                    <th>
                       <!--  <span class='btn btn-primary add_item_nilai btn-sm' data-toggle="tooltip" data-title="Tambah Komponen Lain"><i class='fa fa-plus'></i></span> -->
                    </th>
                </footer>
              </table>
            </div>
          </div> 
              <div class="form-group">
                <div class="col-lg-10">
                  <div class="modal-footer"> 
                  <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Komponen</button>
                <button type="button" class="btn btn-sdefault" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>

                  </div>
                </div>
              </div><!-- /.form-group -->

      </form>
<script type="text/javascript" src="<?=base_admin();?>/assets/plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script type="text/javascript">

$("#unsur_nilai").change(function(){
    id = $(this).val();
    var readon = '';
    if (id==1) {
      var readon = 'readonly';
    }
    txt = this.options[this.selectedIndex].text;
    if ($('.komponen_list_'+id).length<1) {
        $("#isi_komponen").append("<tr class='komponen_list_"+id+"'><input type='hidden' name='id_komponen[]' value='"+id+"'> <td><input type='text' required name='nama_komponen[]' value='"+txt+"' class='form-control' "+readon+"></td> <td><div class='input-group' style='width:100px'> <input type='text' class='form-control prosentase' required onkeypress='return isNumberKey(event)' name='prosentase[]' onblur='findTotal()' value='10'> <span class='input-group-addon'> % </span> </div><td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td> </tr>"); 
        findTotal();
    }
});
var rowNum = <?=$last_id;?>;
$(".add_item_nilai").click(function(){
    rowNum++;
    $("#isi_komponen").append("<tr class='komponen_list_"+rowNum+"'><input type='hidden' name='id_komponen[]' value='"+rowNum+"'> <td><input type='text' required name='nama_komponen[]' value='isi Nama Komponen' class='form-control'></td> <td><div class='input-group' style='width:100px'> <input type='text' class='form-control prosentase' required  onkeypress='return isNumberKey(event)' name='prosentase[]' onblur='findTotal()' value='10'> <span class='input-group-addon'> % </span> </div><td><span class='btn btn-danger hapus_komponen_dosen btn-sm'><i class='fa fa-minus'></i></span></td> </tr>"); 
    findTotal();
});


function findTotal(){
    var arr = document.getElementsByClassName('prosentase');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
        console.log(tot);
    }
    document.getElementById('total_prosentase').value = tot;
}

$('.clockpicker').clockpicker();
  $(".chzn-select").chosen();
  //Timepicker
  $(".time_mulai").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });
  //Timepicker
  $(".time_akhir").timepicker({
    showInputs: false,
    showSeconds:false,
    showMeridian:false,
    minuteStep: 15,
    maxHours:24,
  });


    $("#add_dosen").on('click',function(event) {
        $("#loadnya").show();
        event.preventDefault();
        var currentBtn = $(this);

        id = currentBtn.attr('data-id');

        $.ajax({
            url : "<?=base_admin();?>modul/kelas_jadwal/modal_list_dosen.php",
            type : "post",
            success: function(data) {
                $("#isi_dosen").html(data);
                $("#loadnya").hide();
          }
        });

    $('#modal_list_dosen').modal({ keyboard: false,backdrop:'static' });

    });

$('.close_main').click(function() {
    location.reload();
});

    function pilih_dosen(id_dosen){
        // $('#loadnya').show();
        $.ajax({
            type: 'POST',
            url: '<?=base_admin();?>modul/kelas_jadwal/komponen_tambah_dosen.php',
            data: 'id_dosen='+id_dosen,
            success: function(result) {
              // $('#loadnya').hide();
              $("#modal_list_dosen").modal( 'hide' ).data( 'bs.modal', null );
              $("#dosen_ajar").append(result);
            },
            //async:false
        });
    }

$(document).on("click", ".hapus_komponen_dosen", function() {
      $(this).parent().parent().remove(); 
      findTotal();
});

          //trigger validation onchange
      $('select').on('change', function() {
          $(this).valid();
      });
      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    $("#input_komponen").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            } else if (element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: 'json',
                type : 'post',
                error: function(data ) { 
                  $("#loadnya").hide();
                  //console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn();
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=='die') {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=='error') {
                             $('.isi_warning').text(responseText[index].error_message);
                             $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          } else if(responseText[index].status=='good') {
                            $('.error_data').hide();
                            $(".notif_top").fadeIn(1000);
                            $(".notif_top").fadeOut(1000, function() {
                              $("#modal_komponen_penilaian").modal( 'hide' ).data( 'bs.modal', null );
                                location.reload();
                            });
                          } else {
                             console.log(responseText);
                             $('.isi_warning').text(responseText[index].error_message);
                               $('.error_data').fadeIn();
                             $('html, body').animate({
                                scrollTop: ($('.error_data').first().offset().top)
                            },500);
                          }
                    });
                }

            });
        }
    });
</script>
