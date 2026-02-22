<!-- Content Header (Page header) -->
<style type="text/css">
  .warna {
    background: #f1f4f7;
    text-align: center;
}
.jml {
    background: #afd7ff;
}

</style>
<section class="content-header">
  <h1>Rencana Studi</h1>
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
          <a href="<?=base_index_new();?>rencana-studi" class="btn btn-primary" id="btn-simpan"><i class="fa fa-backward"></i> Kembali</a>
          <p>
           <?php 
             $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$nim);
             //check semester mahasiswa
             $semester_mhs = $db->fetch_custom_single("select (($sem-left(mulai_smt,4))*2)+right($sem,1)-(floor(right(mulai_smt,1)/2)) as semester from mahasiswa where nim=?",array('mhs_nim' => $data_mhs->nim));
              //print_r($semester_mhs);
             // echo "select (($sem-left(mulai_smt,4))*2)+right($sem,1)-(floor(right(mulai_smt,1)/2)) as semester from mahasiswa where nim='07.305.15' ";
             if ($semester_mhs->semester>16) {
               $qa = $db->query("select jatah_sks from akm where sem_id='$sem' and mhs_nim='$nim' ");
               if ($qa->rowCount()==0) {
                  $qcm = $db->query("select fungsi_get_jatah_sks('$data_mhs->nim',$sem) as jatah");
                  foreach ($qcm as $kcm) {
                    $jatah_sks = $kcm->jatah;
                  } 
               }else{
                   foreach ($qa as $ka) {
                     $jatah_sks = $ka->jatah_sks;
                   }
               }
              
               $dapat_paket = "";
               $ip_semester_lalu = "";
             }else{
                $check_paket_semester = $db->fetch_single_row("data_paket_semester","id",1);
                $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks(".$data_mhs->nim.",".$sem.") as jatah_sks,fungsi_jml_sks_diambil(".$data_mhs->nim.",".$sem.") as diambil ");
                $jatah_sks = $data_jatah_sks->jatah_sks;
                $dapat_paket = "";
               if ($check_paket_semester) {
                if ($semester_mhs) {
                  //semester paket
                  $xpl_semester = explode(",", $check_paket_semester->semester_mhs);
                  if (in_array($semester_mhs->semester,$xpl_semester)) {
                    $jatah_sks = $check_paket_semester->jml_sks;
                    $dapat_paket = "(Paket Semester)";
                  }
                } 
               }
                $ip_semester_lalu = "";
               //ip semester sebelumnya
               $ip_sebelumnya = $db->fetch_custom_single("select fungsi_get_ip_semester_sebelumnya(".$data_mhs->nim.",".$sem.") as ip_sebelumnya");
               if ($ip_sebelumnya) {
                 $ip_semester_lalu = $ip_sebelumnya->ip_sebelumnya;
               }
             }
             
            
           ?>
            <table class="table table-bordered table-striped">
      <tbody>
        <tr>
          <td width="20%">Nama</td>
          <td colspan="5">: <?=$data_mhs->nama;?></td>
        </tr>
        <tr>
          <td width="20%">NIM</td>
          <td colspan="5">: <?=$data_mhs->nim;?></td>
        </tr>
        <tr>
          <td width="20%">Angkatan</td>
          <td colspan="5">: <?=$data_mhs->angkatan;?></td>
        </tr>
        <tr>
          <td width="20%">Program Studi</td>
          <td colspan="5">: <?=$data_mhs->jurusan;?></td>
        </tr>
        <tr>
          <td width="20%">Dosen Pembimbing Akademik</td>
          <td colspan="5">: <?=$data_mhs->dosen_pa;?></td>
        </tr>
        <tr>
          <td width="20%">Periode Semester</td>
          <td colspan="5">: <?= get_tahun_akademik($sem) ?></td>
        </tr>
        <tr>
          <td width="10%">IP Semester Sebelumnya</td>
          <td colspan="5"> : <?= $ip_semester_lalu ?> </td>
        </tr>
        <tr>
          <td width="10%">Jatah SKS</td>
          <td colspan="5"> : <?= $jatah_sks." ".$dapat_paket?> </td>
        </tr>
            </tbody></table>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
       
<?php

                $data_krs=$db->fetch_custom_single("select fungsi_get_jatah_sks('$data_mhs->nim',".$sem.") as jatah_sks,fungsi_jml_sks_diambil('$data_mhs->nim',".$sem.") as diambil,fungsi_get_krs_disetujui_mhs('$data_mhs->nim',".$sem.") as status_disetujui
                                ");
                // echo "select fungsi_get_jatah_sks($data_mhs->nim,".$sem.") as jatah_sks,fungsi_jml_sks_diambil($data_mhs->nim,".$sem.") as diambil,fungsi_get_krs_disetujui_mhs($data_mhs->nim,".$sem.") as status_disetujui";

$is_periode = check_current_periode('krs',get_sem_aktif(),$data_mhs->jur_kode);
 $is_periode_krs = "yes";
    if ($is_periode==false) { 
      $is_periode_krs = "no";
    }
?>

        <div class="box-header">
<?php
$is_aktif_krs = 'no';
 if ($data_krs->status_disetujui<1 && $is_periode_krs=='yes') {
  $is_aktif_krs = 'yes';
         ?>
                  <a href="<?=base_index();?>rencana-studi/tambah/?n=<?=$enc->enc($nim);?>&s=<?=$enc->enc($sem);?>" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah IRS</a>
                  <?php
        }
        ?>

       
         <?php
       //  if ($data_krs->diambil>0) {
           ?>
             <button id="cetak_krs" data-sem="<?=$enc->enc($sem);?>" data-nim="<?=$enc->enc($data_mhs->nim);?>" class="btn btn-info"><i class="fa fa-print"></i>&nbsp;Cetak IRS</button>
           <?php

       //  }
         ?>
          </div><!-- /.box-header -->
   <table class="table table-bordered table-striped display responsive nowrap" id="dtb_rencana" style="width:100%">
              <thead>
                <tr>    
                    <th rowspan="2" style="width:5%">No</th>  
                    <th rowspan="2">Matakuliah</th>
                    <th rowspan="2">Kelas</th>
                    <th rowspan="2">SKS</th>
                    <th colspan="4" style="text-align: center;">Jadwal</th>
                    <th rowspan="2">#</th>
                </tr>
                <tr>
                    <th>Ruang</th>
                    <th>Hari</th>          
                    <th>Waktu</th>
                    <th>Dosen</th>
                </tr>
              </thead>
              <tbody>          
              </tbody>
              <tfoot>
                <tr>
                <th colspan="3"><span class="btn btn-info btn-xs">Jumlah SKS Diambil</span></th>
                <th></th>
                <th colspan="5"></th>
              </tr>
              <tr>
                <th colspan="3" class="dt-center all">Jatah SKS <span class="btn btn-info btn-xs jatah_sks"></span> Sisa Jatah SKS <span class="btn btn-danger btn-xs sisa_jatah"></span> <span class="status_irs"></span></th>
                 <th class="jatah"></th>
                <th colspan="5"></th>
              </tr>
              </tfoot>
            </table>

         
            </div><!-- /.box-body -->
            </div><!-- /.box -->
          </div>
        </div>
        </section><!-- /.content -->
    </section><!-- /.content -->
   <!-- Button trigger modal -->
<script type="text/javascript">
      $(document).ready(function() {
      var dtb_rencana = $("#dtb_rencana").DataTable({
            'order' : [1,'asc'],
            "lengthMenu": [[-1], ["All"]],
           'bProcessing': true,
            'bServerSide': true,
            
           'columnDefs': [
                {
            'width': '5%',
            'targets': 0,
            'orderable': false,
            'searchable': false,
            'className': 'dt-center'
          },
          {
            'className': 'all',
             'targets': [ 0,1 ]
          }

             ],

    
            'ajax':{
              url :"<?=base_admin();?>modul/rencana_studi/krs_dosen/detail_data.php",
            type: 'post',  // method  , by default get
            data: function ( d ) {
                    d.nim = '<?=$nim;?>';
                    d.sem = <?=$sem;?>;
                    d.is_aktif_krs = '<?=$is_aktif_krs;?>';
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
        $('.status_irs').html(json.status_irs);
        $('.jatah_sks').html(json.jatah_sks);
        $('.sisa_jatah').html(json.sisa_jatah);
         $(api.column(3).footer()).html('<span class="btn btn-info btn-xs">'+json.jumlah_sks+'</span>');
            }
        });
    $("#cetak_krs").click(function(){
       var currentBtn = $(this);
       sem = currentBtn.data('sem');
       nim = currentBtn.data('nim');
       window.open("<?= base_admin() ?>modul/rencana_studi/cetak_krs_mhs.php?sem="+sem+"&nim="+nim,'_blank');
      });

   $(".table").on('click','.hapus-krs',function(event) {
    event.stopPropagation();
    event.preventDefault();
    var currentBtn = $(this);
    id = currentBtn.attr('data-id');
    $.ajax({
      type: "POST",
      url: "<?=base_admin();?>modul/rencana_studi/rencana_studi_action.php?act=delete&id="+id,
      success: function(data){
            dtb_rencana.draw(false);
      }
      });
  });
});
</script>