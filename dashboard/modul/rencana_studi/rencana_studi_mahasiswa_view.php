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
           <?php 
           $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$_SESSION['username']);
             //ip semester sebelumnya
             $ip_sebelumnya = $db->fetch_custom_single("select fungsi_get_ip_semester_sebelumnya(".$_SESSION['username'].",".get_sem_aktif().") as ip_sebelumnya");
             if ($ip_sebelumnya) {
               $ip_semester_lalu = $ip_sebelumnya->ip_sebelumnya;
             }
             $check_paket_semester = $db->fetch_single_row("data_paket_semester","id",1);
              $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks('".$_SESSION['username']."','".get_sem_aktif()."') as jatah_sks,fungsi_jml_sks_diambil('".$_SESSION['username']."','".get_sem_aktif()."') as diambil ");
              $jatah_sks = $data_jatah_sks->jatah_sks;
            if ($data_mhs->jenjang=='S2') {
               $jatah_sks = 24;
             }
              $dapat_paket = ""; 
             // if ($check_paket_semester) {
             //  if ($semester_mhs) {
             //    //semester paket
             //    $xpl_semester = explode(",", $check_paket_semester->semester_mhs);
             //    if (in_array($semester_mhs->semester,$xpl_semester)) {
             //      $jatah_sks = $check_paket_semester->jml_sks;
             //      $dapat_paket = "(Paket Semester)";
             //    }
             //  }
             // }
         //  print_r($_SESSION);
             $data_mhsx = $db->query("select `m`.`nim` AS `nim`,`m`.`nama` AS `nama`,`m`.`mulai_smt` AS `mulai_smt`,`m`.`jk` AS `jk`,`jd`.`id_jenis_daftar` AS `id_jenis_daftar`,`jd`.`nm_jns_daftar` AS `nm_jns_daftar`,ifnull(`jk`.`ket_keluar`,'Aktif') AS `jenis_keluar`,`view_semester`.`angkatan` AS `angkatan`,`m`.`mhs_id` AS `mhs_id`,`vpj`.`kode_jur` AS `jur_kode`,`view_dosen`.`nip` AS `nip_dosen_pa`,`view_dosen`.`dosen` AS `dosen_pa`,`vpj`.`jurusan` AS `jurusan` from (((((`mahasiswa` `m` join `view_prodi_jenjang` `vpj` on((`m`.`jur_kode` = `vpj`.`kode_jur`))) left join `jenis_keluar` `jk` on((`m`.`id_jns_keluar` = `jk`.`id_jns_keluar`))) left join `view_dosen` on((`m`.`dosen_pemb` = `view_dosen`.`nip`))) join `view_semester` on((`m`.`mulai_smt` = `view_semester`.`id_semester`))) join `jenis_daftar` `jd` on((`m`.`id_jns_daftar` = `jd`.`id_jenis_daftar`))) where nim='".$_SESSION['username']."' ");
            foreach($data_mhsx as $data_mhs){
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
          <td colspan="5">: <?= get_tahun_akademik(get_sem_aktif()) ?></td>
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
        <?php
        
            }
        ?>
        <div class="box-body table-responsive">
        
<?php
       //check if current date is periode krs or periode perbaikan krs
                $is_periode = check_current_periode('krs',get_sem_aktif(),$data_mhs->jur_kode);
               // dump($is_periode);
                $check_if_bayar = $db->fetch_custom_single("select fungsi_cek_pembayaran_periode(".get_sem_aktif().",".$data_mhs->jur_kode.",".$data_mhs->nim.") as is_bayar");
              //  print_r($check_if_bayar);
              /*  echo "select fungsi_cek_pembayaran_periode(".get_sem_aktif().",".$data_mhs->jur_kode.",".$data_mhs->nim.") as is_bayar";*/
              $affirmasi = afirmasi_krs($data_mhs->nim,get_sem_aktif());
              $qc = $db->query("SELECT * FROM keu_tagihan_mahasiswa m 
                WHERE m.nim='$data_mhs->nim'
                AND m.periode='".get_sem_aktif()."' ");
              $ada_tagihan = $qc->rowCount();
             // echo "$ada_tagihan";

 
         ?>
         

          <?php
                $is_periode_krs = "yes";
                if ($is_periode==false) { 
                  $is_periode_krs = "no";
                 ?>
               <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> Bukan Periode IRS
                </div>
                 <?php
                } elseif (($check_if_bayar->is_bayar=='0' || $ada_tagihan==0 ) && (!$affirmasi) ) {
                 // print_r($check_if_bayar);
                  ?>
                <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> Anda Belum Melakukan Pembayaran Di Semester Ini  
                </div>
                <?php
                }

            //     else {
               

               ?>

                 <div class="box-header">
                  <?php

$data_krs=$db->fetch_custom_single("select fungsi_get_jatah_sks($data_mhs->nim,".get_sem_aktif().") as jatah_sks,fungsi_jml_sks_diambil($data_mhs->nim,".get_sem_aktif().") as diambil,fungsi_get_krs_disetujui_mhs($data_mhs->nim,".get_sem_aktif().") as status_disetujui");
       //  if ($data_krs->diambil>0) {
           ?>
         
             <button id="cetak_krs" data-sem="<?=$enc->enc(get_sem_aktif());?>" data-nim="<?=$enc->enc($data_mhs->nim);?>" class="btn btn-info" style="float:left"><i class="fa fa-print"></i>&nbsp;Cetak IRS</button>
             <select id="sem" name="sem" class="form-control" style="width:150px">
               <option value="">-Pilih Semester-</option>
               <?php
               $q = $db->query("select s.tahun_akademik,s.id_semester from krs_detail k join view_semester s on s.id_semester=k.id_semester where k.nim='$data_mhs->nim'
                group by k.id_semester order by k.id_semester desc");
               foreach ($q as $k) {
                  echo "<option value='".en($k->id_semester)."'>$k->tahun_akademik</option> ";
               }
               ?>
             </select>
             
           <?php

        // }
        if ($data_krs->status_disetujui<1 && $is_periode_krs=='yes') {
         ?>
                  <a href="<?=base_index();?>rencana-studi/tambah" class="btn btn-primary "><i class="fa fa-plus"></i> Tambah IRS</a>
                  <?php
        }
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
<?php
            //    }


?>
         
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
              url :"<?=base_admin();?>modul/rencana_studi/rencana_data_mhs.php",
            type: 'post',  // method  , by default get
            data : {is_periode : "<?=$is_periode_krs;?>"},
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
       var sem = $("#sem").val();
       if (sem!='') {
         nim = currentBtn.data('nim');  
         window.open("<?= base_admin() ?>modul/rencana_studi/cetak_krs_mhs.php?sem="+sem+"&nim="+nim,'_blank');
       }else{
        alert("silahkan pilih semester");
       }
      
       
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