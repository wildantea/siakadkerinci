<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Manage Rencana Studi</h1>
  <ol class="breadcrumb">
    <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?=base_index();?>rencana-studi">Rencana Studi</a></li>
    <li class="active">Rencana Studi List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">         
        <div class="box-header">
           <?php 
             $m = get_atribut_mhs($mhs_id);   
           ?>
          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Nama Mahasiswa</label>
              <div class="col-lg-10">
                 <b> <?= strtoupper($m->nama); ?> </b>
              </div>
          </div><!-- /.form-group --><br>
          <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">NIM</label>
              <div class="col-lg-10">
                  <?= $m->nim; ?>
              </div>
          </div><!-- /.form-group --><br>
           <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Jurusan/Prodi</label>
              <div class="col-lg-10">
                  <?= $m->nama_jur; ?>
              </div>
          </div><!-- /.form-group --><br>
           <div class="form-group">
              <label for="Tanggal Masuk " class="control-label col-lg-2">Angkatan</label>
              <div class="col-lg-10">
                  <?= $m->mulai_smt; ?>
              </div>
          </div><!-- /.form-group --><br>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="dtb_manual" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width:25px" align="center">No</th>          
                    <th>Semester</th>
                    <th>SKS di ambil</th>
                    <th>Jatah SKS</th>
                    <th>IP</th>
                    <th>IPK</th>               
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>          
                <?php
               // print_r($sem_aktif);
                $where = "";
                 if ($_SESSION['group_level']=='mahasiswa') {
                  $where = " and k.sem_id='$sem_aktif->sem_id' ";
                }
                $dtb=$db->query("select k.krs_id, sum(kr.sks) as tot_sks,s.id_semester,a.*,js.jns_semester,
                                sf.tahun from krs k join semester s on k.sem_id=s.sem_id
                                join krs_detail kr on kr.id_krs=k.krs_id
                                join akm a on a.mhs_nim=k.mhs_id and a.sem_id=s.id_semester
                                join semester_ref sf on sf.id_semester=s.id_semester
                                join jenis_semester js on js.id_jns_semester=sf.id_jns_semester
                                 where k.mhs_id='".$mhs_id."' $where group by k.krs_id
                                ");
              //  echo "string";
                $i=1;
                foreach ($dtb as $isi) {
                  ?>
                <tr id="line_<?=$isi->akm_id;?>">
                  <td align="center"><?=$i;?></td>        
                    <td><?=$isi->jns_semester." ".$isi->tahun."/".($isi->tahun+1); ?></td>
                    <td><?=$isi->tot_sks; ?></td>
                    <td><?=$isi->jatah_sks ?></td>
                    <td><?=$isi->ip; ?></td>
                    <td><?=$isi->ipk; ?></td>
                  <td>
                    <?php
                    if ($_SESSION['group_level']!='mahasiswa') {
                      $nim = "$mhs_id";
                    }else{
                       $nim="";
                    }
                    //echo '<a href="'.base_index().'rencana-studi/tambah/'.$isi->kur_id.'/'.$isi->periode.'/'.$mhs_id.'/'.$isi->sem_id.'" class="btn btn-success "><i class="fa fa-book"></i> Manage KRS</a> ';
                     echo '<a href="'.base_index().'rencana-studi/tambah/'.en($isi->akm_id).'?nim='.$nim.'" class="btn btn-success "><i class="fa fa-book"></i> Add/Revisi KRS</a>   <button onclick="lihat_matkul('.$isi->krs_id.')" class="btn btn-success"><i class="fa fa-eye"></i> Detail Mata Kuliah</button>';
                  ?>
                  </td>
                </tr>
                  <?php
                 $i++;
                }
                ?>
              </tbody>
            </table>
            </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->
    </section><!-- /.content -->
   <!-- Button trigger modal -->

<!-- Modal -->
<div class="modal modal-success fade in" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Mata Kuliah Yang Diambil <span id="semester"></span></h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function lihat_matkul(krs_id)
  {
     // alert(krs_id);
      //$("#loadnya").show();
      $.ajax({
                type: "POST",
                url: "<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=showMatkul",
                data: "krs_id="+krs_id+"&semester=<?=$isi->jns_semester." ".$isi->tahun."/".($isi->tahun+1); ?>",
                success: function(data) {
                 // alert(data);
                 //   $("#loadnya").hide();
                    console.log(data);
                    $(".modal-content").html(data);
                    $("#loadnya").hide();
                    $('#myModal').modal('toggle');
                    $('#myModal').modal('show');
                  //  $('#myModal').modal('hide');
                }
            });
  }
</script>