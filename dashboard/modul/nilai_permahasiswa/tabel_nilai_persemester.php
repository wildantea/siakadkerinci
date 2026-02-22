<style>
  .modal-lg {
    width: 90%;
    max-width: 90%;
  }
</style>
<div class="box-header">
  <div class="box-group" id="accordion">
    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
    <?php
    $qq = $db->query("select k.nim,k.id_krs_detail, k.id_semester, js.jns_semester,js.nm_singkat, s.tahun, s.id_semester,a.*, fungsi_jml_sks_diambil(k.nim,k.id_semester) as sks_diambil
 from krs_detail k left join semester_ref s on s.id_semester=k.id_semester left join akm a on (a.sem_id=s.id_semester and a.mhs_nim=k.nim) 
 left join jenis_semester js on js.id_jns_semester=s.id_jns_semester  where k.nim='" . de(uri_segment(3)) . "'  group by k.id_semester          order by s.id_semester asc");
    $i = 1;
    foreach ($qq as $k) {
      ?>
      <div class="panel box box-primary">
        <a style="font-size: 17px" data-toggle="collapse" data-parent="#accordion" href="#<?= $i ?>" class="collapsed"
          aria-expanded="false">
          <div class="box-header with-border">
            <h4 class="box-title" style="width: 100%;float:left;display:inline-block;">

              <?php
              if ($k->id_semester == '10') {
                echo "Matkul $k->jns_semester";

              } else {

                echo "Semester :  " . $k->tahun . "/" . ($k->tahun + 1) . " $k->jns_semester";
              }
              ?>





            </h4>
            <i class="fa fa-get-pocket" style="float: right;position: relative;top: -25px"></i>
          </div>
        </a>
        <div id="<?= $i ?>" class="panel-collapse collapse" aria-expanded="true" style="">
          <div class="box-body">
            <div class="callout">
              <table>
                <tr>
                  <td style="width: 100px ;font-size: 15px">Total SKS</td>
                  <td>: <b style="font-size: 15px"><?= $k->sks_diambil ?></b></td>
                </tr>
                <tr>
                  <td style="font-size: 15px">IP</td>
                  <td>: <b style="font-size: 15px"><?= $k->ip ?></b></td>
                </tr>
              </table>
            </div>


            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style='text-align:center'>No</th>
                  <th style='text-align:center'>Kode MK</th>
                  <th style='text-align:center'>Nama MK</th>
                  <th style='text-align:center'>SKS</th>
                  <th style='text-align:center'>Nilai Angka</th>
                  <th style='text-align:center'>Nilai Huruf</th>
                  <th style='text-align:center'>Action</th>
                  <th style='text-align:center'>Pengubah</th>
                </tr>
              </thead>
              <?php
              $noo = 1;
              $qq = $db->query("select nilai_angka,k.id_krs_detail, k.sks, k.id_krs_detail,m.nama_mk,m.kode_mk,tgl_perubahan_nilai,pengubah_nilai,
                        k.bobot,k.nilai_huruf, k.tgl_perubahan, k.pengubah from krs_detail k
                        join matkul m on m.id_matkul=k.kode_mk where k.id_semester='$k->id_semester'
                        and k.nim='" . de(uri_segment(3)) . "' group by k.kode_mk ");
              foreach ($qq as $kr) {

                echo " <tr>
                                <td>$noo</td>
                                <td>$kr->kode_mk</td>
                                <td>$kr->nama_mk</td>
                                <td style='text-align:center'>$kr->sks</td>
                                <td style='text-align:center' id='bobot-$kr->id_krs_detail'>$kr->nilai_angka</td>
                                <td style='text-align:center' id='nilai-$kr->id_krs_detail'>$kr->nilai_huruf</td>
                                <td style='text-align:center'>";
                if ($role_act["up_act"] == "Y") {
                  //echo " <a onclick='update_nilai($kr->id_krs_detail)' class='btn btn-success' id='$kr->id_krs_detail'><i class='fa fa-edit'></i> Update Nilai</a>";
                  echo " <a class='edit_nilai btn btn-success' data-id='$kr->id_krs_detail' id='$kr->id_krs_detail'><i class='fa fa-edit'></i> Update Nilai</a>";
                }

                echo " </td>
                                <td style='text-align:center' id='pengubah-$kr->id_krs_detail'>$kr->tgl_perubahan_nilai<br>$kr->pengubah_nilai</td>

                            </tr>";
                $noo++;
              }
              ?>


              <tbody>
              </tbody>
            </table>

          </div>
        </div>
      </div>
      <?php
      $i++;
    }
    ?>

  </div>
</div><!-- /.box-header -->

<div class="modal" id="modal_nilai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
        <h4 class="modal-title">Update Nilai</h4>
      </div>
      <div class="modal-body" id="isi_data_nilai"> </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>



<script type="text/javascript">

  function set_rule(checkbox) {
    if (checkbox.checked == true) {
      $(".komponen_nilai").prop('required', true);
    } else {
      $(".komponen_nilai").prop('required', false);
    }
  }

  $(".table").on('click', '.edit_nilai', function (event) {
    $("#loadnya").show();
    event.preventDefault();
    var currentBtn = $(this);

    id = currentBtn.attr('data-id');

    $.ajax({
      url: '<?= base_admin(); ?>modul/nilai_permahasiswa/edit_nilai_with_komponen.php',
      type: "post",
      data: { nilai_id: id },
      success: function (data) {
        $("#isi_data_nilai").html(data);
        $("#loadnya").hide();
      }
    });

    $('#modal_nilai').modal({ keyboard: false, backdrop: 'static' });

  });
</script>