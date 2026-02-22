<style type="text/css">
  .help-block {
    color: #dd4b39;
}
.isi_abstract > p {
  font-size: 15px;
}

.auto-center {
  margin-left: auto;
  margin-right: auto;
  display: block;
}
 .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
  .nav-tabs-custom {
    border: 1px solid #337ab7;
  }
    
 .nav-tabs > li > a {
    position: relative;
    padding-left: 30px; /* Space for the circle */
  }
  
.nav-tabs-custom>.nav-tabs>li {
    border-top: 0px solid transparent;
    margin-bottom: -2px;
    margin-right: 5px;
}
  .nav-tabs > li > a::before {
    content: attr(data-number);
    position: absolute;
    left: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    background-color: #27a65a; /* Number circle color */
    color: white;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    line-height: 20px;
    border-radius: 50%;
    display: inline-block;
  }

  /* Active tab styling */
  .nav-tabs > li.active > a, 
  .nav-tabs > li.active > a:hover, 
  .nav-tabs > li.active > a:focus {
    background-color: #3d8dbc !important;
    color: white !important;
  }

  /* Hover effect for inactive tabs */
  .nav-tabs > li:not(.active) > a:hover {
    background-color: rgba(61, 141, 188, 0.2); /* Light effect */
    border-radius: 5px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px;
}
.select2-container .select2-selection--single {
    height: 34px;
}
</style>

        <section class="content-header">
            <h1>Biodata</h1>
            <ol class="breadcrumb">
                <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?=base_index();?>biodata">Biodata</a></li>
                <li class="active">Edit Biodata</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
<div class="row">
<div class="col-md-4">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
<?php
$foto = $db->fetch_single_row("sys_users","username",$data_edit->nim);
$mhs = $db->fetch_single_row("view_simple_mhs","nim",$data_edit->nim);
$ip = $db->fetch_custom_single("select * from akm where mhs_nim='".$data_edit->nim."' order by sem_id desc");

?>
<span style="margin: auto auto;display: block;text-align: center;">
 <?php
$user = $db->fetch_single_row('sys_users','id',$_SESSION['id_user']);
                if ($user->is_photo_drived=='Y') {
                   ?>
                   <img src="<?=$user->foto_user?>=w300" class="img-thumbnail">
                   <?php
                } else {
                    ?>
                      <img src="<?=base_url();?>upload/back_profil_foto/<?=$foto->foto_user;?>" class="img-thumbnail">
                     <?php
                }
                ?>
             

</span>
              <h3 class="profile-username text-center"><?=ucwords(strtolower($data_edit->nama));?></h3>

              <p class="text-muted text-center"><?=$data_edit->group_level;?></p>

              <ul class="list-group list-group-unbordered">
                 <li class="list-group-item">
                  <b>Fakultas</b> <span class="pull-right"><?=$mhs->nama_fakultas;?></span>
                </li>
                 <li class="list-group-item">
                  <b>Prodi</b> <span class="pull-right"><?=$mhs->nama_jurusan;?></span>
                </li>
                 <li class="list-group-item">
                  <b>Angkatan</b> <span class="pull-right"><?=$mhs->angkatan;?></span>
                </li>
                 <li class="list-group-item">
                  <b>Email</b> <span class="pull-right"><?=$data_edit->email;?></span>
                </li>
                <li class="list-group-item">
                  <b>Dosen Pembimbing</b>
                  <?php foreach ($db->fetch_all("view_nama_gelar_dosen") as $isi) {
                    if ($data_edit->dosen_pemb == $isi->nip) {
                        echo '<span class="pull-right">'.$isi->nama_gelar.'</span>';
                    }
                } ?>

                  
                </li>

               
                <li class="list-group-item">
                  <b>IPK</b> <span class="pull-right btn btn-success btn-xs"><?=$ip->ipk;?></span>
                </li>
              </ul>
              <?php
              if (checkBiodataAllStatus($_SESSION['username'])) {
                     echo '<a href="'.base_index().'biodata/edit" class="btn btn-primary btn-block"><i class="fa fa-pencil-square-o"></i> Edit Biodata</a>';
              } elseif ($data_edit->is_submit_biodata=='N') {
                       echo '<a href="'.base_index().'biodata/edit" class="btn btn-primary btn-block"><i class="fa fa-pencil-square-o"></i> Edit Biodata</a>';
              }
             
              ?>
             </a> 
             <a href="<?=base_index();?>profil/editphoto" class="btn btn-primary btn-block"><i class="fa  fa-user"></i> Ubah Photo</a>
             <a href="<?=base_index();?>profil/change-passwords" class="btn btn-primary btn-block"><i class="fa  fa-unlock"></i> Ganti Password</a>
              
            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <!-- /.box -->
        </div>
<div class="col-md-8">
 <div class="box box-solid box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Biodata</h3>
                        </div>
                        <div class="box-body">
                            <form id="edit_biodata" method="post" class="form-horizontal" action="<?=base_admin();?>modul/biodata/biodata_action.php?act=up">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="NIM" class="control-label col-lg-4">NIM</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nim" readonly="" value="<?=$data_edit->nim;?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Lengkap" class="control-label col-lg-4">Nama Lengkap</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nama" value="<?=$data_edit->nama;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Nama Lengkap" class="control-label col-lg-4">Tanggal Lahir</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nama" value="<?=tgl_indo($data_edit->tgl_lahir);?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-md-6">
                                          <div class="form-group">
                                            <label for="Tempat Lahir" class="control-label col-lg-4">Tempat Lahir</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="tmpt_lahir" value="<?=$data_edit->tmpt_lahir;?>" class="form-control" readonly maxlength="32">
                                            </div>
                                        </div>
                                           <div class="form-group">
                                            <label for="Jenis Kelamin" class="control-label col-lg-4">Jenis Kelamin</label>
                                            <div class="col-lg-8">
                                                 <?php
                                                    $option = array(
                                                        'L' => 'Laki - Laki',
                                                        'P' => 'Perempuan',
                                                    );
                                                    ?>

                                                 <input type="text" name="tmpt_lahir" value="<?=$option[$data_edit->jk];?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <label for="Nama Lengkap" class="control-label col-lg-4">Nama Ibu</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nama" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                     </div>
                                </div>

                             <!--    <div class="callout callout-info">
                                            <h4>Klik Edit Biodata Untuk Memperbaharui Biodata Anda</h4>
                                        </div> -->

                                <!-- Tabs -->
                                 <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs" id="myTab">
                                    <li class="active"  data-number="1"><a href="#data_diri" data-toggle="tab" aria-expanded="true" data-number="1">Data Diri</a></li>

                                    <li><a href="#alamat" data-toggle="tab"  data-number="2">Alamat</a></li>
                                    <li><a href="#orang_tua" data-toggle="tab"  data-number="3">Orang Tua</a></li>
                                    <li><a href="#dokumen" data-toggle="tab"  data-number="4">Dokumen</a></li>
                                </ul>
                                <div class="tab-content">
                                    <!-- Data Diri Tab -->
                                    <div class="tab-pane active" id="data_diri">
                                           <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                            <label for="Nomor KTP" class="control-label col-lg-3">NIK KTP </label>
                                            <div class="col-lg-9">
                                                <input type="text" data-rule-number="true" name="nik" value="<?=$data_edit->nik;?>" class="form-control" readonly minlength="16" maxlength="16" onkeypress="return isNumberKey(event)" >
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Agama" class="control-label col-lg-3">Agama </label>
                                            <div class="col-lg-9">
                                                <?php foreach ($db->fetch_all("agama") as $isi) {
                                                        $agamas[$isi->id_agama] = $isi->nm_agama;
                                                    }
                                                 ?>
                                                  <input type="text" name="tmpt_lahir" value="<?=$agamas[$data_edit->id_agama];?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                               
                                         <div class="form-group">
                                            <label for="Kewarganegaraan" class="control-label col-lg-3">Warganegara </label>
                                            <div class="col-lg-9">
                                                    <?php foreach ($db->fetch_all("kewarganegaraan") as $isi) {
                                                        if ($data_edit->kewarganegaraan==$isi->kewarganegaraan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->nm_wil;?>" class="form-control" readonly>
                                                             <?php
                                                        }
                                                    } ?>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Penerima KPS" class="control-label col-lg-3">Terima kps?</label>
                                            <div class="col-lg-9">
                                                 <?php
                                                    $option = array('0' => 'Tidak', '1' => 'Iya');
                                                     ?>
                                                  <input type="text" name="tmpt_lahir" value="<?=$option[$data_edit->a_terima_kps];?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                            </div>
                                            <div class="col-lg-6">
                                                 <div class="form-group">
                                            <label for="NPWP" class="control-label col-lg-3">NPWP</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="npwp" value="<?=$data_edit->npwp;?>" class="form-control" onkeypress="return isNumberKey(event)" readonly>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label for="Status Pernikahan" class="control-label col-lg-3">Status </label>
                                            <div class="col-lg-9">
                                                <?php
                                                     $option = array(
                                                        'B' => 'Belum Menikah',
                                                        'M' => 'Menikah',
                                                        'D' => 'Duda/Janda'
                                                    );
                                                    ?>

                                                 <input type="text" name="tmpt_lahir" value="<?=$option[$data_edit->status_pernikahan];?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="NISN" class="control-label col-lg-3">NISN</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="nisn" data-rule-number="true" value="<?=$data_edit->nisn;?>" class="form-control" onkeypress="return isNumberKey(event)" maxlength="10" readonly>
                                            </div>
                                        </div>


                                         <?php
                                        if ($data_edit->a_terima_kps=='1') {
                                            ?>
                                             <div class="form-group">
                                            <label for="No KPS" class="control-label col-lg-3">No KPS</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="no_kps" id="no_kps" readonly="" value="<?=$data_edit->no_kps;?>" class="form-control">
                                            </div>
                                        </div>
                                            <?php
                                        }
                                        ?>
                                       
                                            </div>
                                        </div>

                                       <div class="form-group">
                                            <label for="Jalur Masuk" class="control-label col-lg-3">Jalur Masuk </label>
                                            <div class="col-lg-9">
                                                 <?php foreach ($db->fetch_all("jalur_masuk") as $isi) {
                                                      $has_wali = 0;
                                                        if ($data_edit->id_jalur_masuk==$isi->id_jalur_masuk) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->nm_jalur_masuk;?>" class="form-control" readonly>
                                                             <?php
                                                               $has_wali = 1;
                                                             break;
                                                        }
                                                    }  if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        } ?>
                                            </div>

                                        </div>
                                         <div class="form-group">
                                            <label for="Jenis Pendaftaran" class="control-label col-lg-3">Jenis Daftar</label>
                                            <div class="col-lg-9">
                                                <?php foreach ($db->fetch_all("jenis_daftar") as $isi) {
                                                        if ($data_edit->id_jns_daftar==$isi->id_jenis_daftar) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->nm_jns_daftar;?>" class="form-control" readonly>
                                                             <?php
                                                        }
                                                    } ?>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <label for="Asal Sekolah" class="control-label col-lg-3">Jenis Asal Sekolah</label>
                                            <div class="col-lg-9">
                                                  <?php foreach ($db->fetch_all("tb_ref_jenis_asal_sekolah") as $isi) {
                                                      $has_wali = 0;
                                                        if ($data_edit->id_jenis_sekolah==$isi->id_jenis_sekolah) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->nama_jenis_sekolah;?>" class="form-control" readonly>
                                                             <?php
                                                               $has_wali = 1;
                                                             break;
                                                        }
                                                    }  if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        } ?>
                                            </div>
                                        </div>

<?php
//jenjang 
$array_jenjang = array(35,31);


$jenjang = $db->fetch_custom_single('select id_jenjang from jurusan where kode_jur="'.$data_edit->jur_kode.'"');
if ($data_edit->id_jns_daftar!=1) {
  $show = "";
  
  $required = 'required=""';
  } elseif (in_array($jenjang->id_jenjang,$array_jenjang)) {
     $show = "";
  
  $required = 'required=""';
  } else {
  $show = "style='display:none'";
  $required = "";
  
}
?>
<div class="form-group" id="show_asal_pt" <?=$show?>>
                        <label for="Jenis Pendaftaran" class="control-label col-lg-3">Asal Perguruan Tinggi</label>
                        <div class="col-lg-9">
                        
                          <?php
                          if ($data_edit->id_pt_asal!="") {
                            $kampus = $db->fetch_single_row("satuan_pendidikan","id_sp",$data_edit->id_pt_asal);
                        
                          ?>
                          <input type="text" name="tmpt_lahir" value="<?=$kampus->nm_lemb;?>" class="form-control" readonly>
<?php
  }
  ?>
           
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group" id="show_asal_prodi" <?=$show?>>
                        <label for="Jenis Pendaftaran" class="control-label col-lg-3">Asal Program Studi</label>
                        <div class="col-lg-9">
                          <?php
                            $prodis = $db->query("SELECT concat(jenjang,' ',nm_lemb) as nama_jurusan,kode_prodi,id_sms from jenjang_pendidikan INNER join sms on id_jenjang=id_jenj_didik where id_sp=?",array('id_sp' => $data_edit->id_pt_asal));
                            foreach ($prodis as $prodi) {
                              if ($data_edit->id_prodi_asal==$prodi->id_sms) {
                                ?>
                                 <input type="text" name="tmpt_lahir" value="<?=$prodi->nama_jurusan;?>" class="form-control" readonly>

                                 <?php
                              }
                          }
                          ?>
                        </div>
                      </div><!-- /.form-group -->

<?php
//jenjang 
$jenjang = $db->fetch_custom_single('select id_jenjang from jurusan where kode_jur="'.$data_edit->jur_kode.'"');
if ($jenjang->id_jenjang=='30') {
    ?>

                                       <div class="form-group">
                                            <label for="Nama Asal Sekolah" class="control-label col-lg-3">NPSN - Nama Asal Sekolah/Lembaga</label>
                                            <div class="col-lg-9">
                                                <?php
$sekolah = "";
        $row = $db->fetch_custom_single("SELECT 
        s.npsn, 
        s.kelurahan,
        s.nama AS nama_sekolah, 
        kec.nama AS kecamatan, 
        kab.nama AS kabupaten, 
        prov.nama AS provinsi
    FROM sekolah s
    JOIN sekolah_kecamatan kec ON s.kecamatan_kode = kec.kode
    JOIN sekolah_kabupaten kab ON kec.kabupaten_kode = kab.kode
    JOIN sekolah_provinsi prov ON kab.province_kode = prov.kode
    WHERE 
        s.npsn= ? ", 
    array($data_edit->npsn));
    if ($row) {
         $sekolah = $row->npsn.' - '.$row->nama_sekolah . ' (' .$row->kelurahan.','. $row->kecamatan . ', ' . $row->kabupaten . ', ' . $row->provinsi . ')';
    }
    ?>
                                                <input type="text" name="nama_asal_sekolah" value="<?=$sekolah;?>" class="form-control" readonly>
                                            </div>
                                        </div>
<?php
}
?>                
                                        
                                       
                                       
                                        
                                        
                                    </div>
                                    <!-- Alamat Tab -->
                                    <div class="tab-pane" id="alamat">
                                         <div class="row">
                                            <div class="col-lg-12">
                                         <div class="form-group">
                                            <label for="Kecamatan" class="control-label col-lg-2">Kecamatan </label>
                                            <div class="col-lg-10">
                                                    <?php
                                                    if ($data_edit->id_wil == '999999') {
                                                        ?>
                                                          <input type="text" name="tmpt_lahir" value="Tidak ada" class="form-control" readonly>
                                                          <?php
                                                    } else {
                                                        foreach ($db->query("
                                                            SELECT dwc.id_wil, CONCAT(dwc.nm_wil, ' - ', dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
                                                            FROM data_wilayah
                                                            LEFT JOIN data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
                                                            LEFT JOIN data_wilayah dwc ON dw.id_wil = dwc.id_induk_wilayah
                                                            WHERE data_wilayah.id_level_wil = '1' AND dwc.id_wil='$data_edit->id_wil'
                                                            UNION ALL
                                                            SELECT dw.id_wil, CONCAT(dw.nm_wil, ' - ', data_wilayah.nm_wil) AS wil 
                                                            FROM data_wilayah
                                                            LEFT JOIN data_wilayah dw ON data_wilayah.id_wil = dw.id_induk_wilayah
                                                            WHERE data_wilayah.id_level_wil = '1' AND dw.id_wil='$data_edit->id_wil'
                                                        ") as $isi) {
                                                            ?>
                                                              <input type="text" name="tmpt_lahir" value="<?=$isi->wil;?>" class="form-control" readonly>
                                                              <?php
                                                        }
                                                    } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jalan" class="control-label col-lg-2">Alamat Jalan </label>
                                            <div class="col-lg-10">
                                                <input type="text" name="jln" value="<?=$data_edit->jln;?>" class="form-control" readonly maxlength="80">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                            <label for="Dusun" class="control-label col-lg-3">Dusun</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="nm_dsn" value="<?=$data_edit->nm_dsn;?>" class="form-control" maxlength="60" readonly>
                                            </div>
                                            </div>

                                          <div class="form-group">
                                            <label for="Kelurahan" class="control-label col-lg-3">Desa/Kelurahan</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="ds_kel" value="<?=$data_edit->ds_kel;?>" class="form-control" readonly>
                                            </div>
                                        </div>      
                                        <div class="form-group">
                                            <label for="Jenis Tinggal" class="control-label col-lg-3">Jns Tinggal </label>
                                            <div class="col-lg-9">
                                                <?php foreach ($db->fetch_all("jenis_tinggal") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_jns_tinggal==$isi->id_jns_tinggal) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->jenis_tinggal;?>" class="form-control" readonly>
                                                             <?php
                                                                $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                    if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }  ?>
                                            </div>
                                        </div>

                                        
                                         <div class="form-group">
                                            <label for="No Handphone" class="control-label col-lg-3">No HP </label>
                                            <div class="col-lg-9">
                                                <input type="text" name="no_hp" value="<?=$data_edit->no_hp;?>" class="form-control" readonly onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="No Telepon Rumah" class="control-label col-lg-3">Telp Rumah</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="no_tel_rmh" value="<?=$data_edit->no_tel_rmh;?>" class="form-control" onkeypress="return isNumberKey(event)" minlength="9" readonly>
                                            </div>
                                        </div>
                                         </div>
                                    <div class="col-lg-6">
                                          <div class="form-group">
                                            <label for="RT" class="control-label col-lg-3">RT</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="rt" value="<?=$data_edit->rt;?>" class="form-control" readonly data-rule-number="true" maxlength="2" onkeypress="return isNumberKey(event)">
                                            </div>
                                            </div>
                                                  <div class="form-group">
                                            <label for="RW" class="control-label col-lg-3">RW</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="rw" value="<?=$data_edit->rw;?>" class="form-control" readonly maxlength="2" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Kodepos" class="control-label col-lg-3">Kode Pos </label>
                                            <div class="col-lg-9">
                                                <input type="text" name="kode_pos" value="<?=$data_edit->kode_pos;?>" class="form-control" readonly maxlength="5" onkeypress="return isNumberKey(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="control-label col-lg-3">Email </label>
                                            <div class="col-lg-9">
                                                <input type="text" data-rule-email="true" name="email" value="<?=$data_edit->email;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        
                                            </div>
                                    </div>                                       

                                        
                                    </div>
                                    <!-- Orang Tua Tab -->
                                    <div class="tab-pane" id="orang_tua">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                  <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;">Data Ayah</div>
                                        <br>
                                        <div class="form-group">
                                            <label for="NIK Ayah" class="control-label col-lg-4">NIK Ayah</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nik_ayah" value="<?=$data_edit->nik_ayah;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Ayah" class="control-label col-lg-4">Nama Ayah</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nm_ayah" value="<?=$data_edit->nm_ayah;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tanggal Lahir Ayah" class="control-label col-lg-4">Tgl Lahir Ayah</label>
                                            <div class="col-lg-8">
                                                 <input type='text' class="form-control" name="tgl_lahir_ayah" value="<?=tgl_indo($data_edit->tgl_lahir_ayah);?>" readonly/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pendidikan Ayah" class="control-label col-lg-4">Pendidikan Ayah</label>
                                            <div class="col-lg-8">
                                                 <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_jenjang_pendidikan_ayah==$isi->id_jenjang) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->jenjang;?>" class="form-control" readonly>
                                                             <?php
                                                                $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                    if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }  ?>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Ayah" class="control-label col-lg-4">Pekerjaan Ayah</label>
                                            <div class="col-lg-8">
                                                  <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                      $has_wali = 0;
                                                        if ($data_edit->id_pekerjaan_ayah==$isi->id_pekerjaan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->pekerjaan;?>" class="form-control" readonly>
                                                             <?php
                                                               $has_wali = 1;
                                                             break;
                                                        }
                                                    }  if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Ayah" class="control-label col-lg-4">Penghasilan Ayah</label>
                                            <div class="col-lg-8">
                                                  <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_penghasilan_ayah==$isi->id_penghasilan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->penghasilan;?>" class="form-control" readonly>
                                                             <?php
                                                              $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                     if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }
                                                     ?>
                                            </div>
                                        </div>
                                            </div>
                                              <div class="col-lg-6">
                                                    <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;">Data Ibu</div>
                                        <br>
                                        <div class="form-group">
                                            <label for="NIK Ibu" class="control-label col-lg-4">NIK Ibu</label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nik_ibu_kandung" value="<?=$data_edit->nik_ibu_kandung;?>" class="form-control" minlength="16" maxlength="16" onkeypress="return isNumberKey(event)" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Nama Ibu" class="control-label col-lg-4">Nama Ibu </label>
                                            <div class="col-lg-8">
                                                <input type="text" name="nm_ibu_kandung" value="<?=$data_edit->nm_ibu_kandung;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tanggal Lahir Ibu" class="control-label col-lg-4">Tgl Lahir Ibu</label>
                                            <div class="col-lg-8">
                                                 <input type='text' class="form-control" name="tgl_lahir_ayah" value="<?=tgl_indo($data_edit->tgl_lahir_ibu);?>" readonly/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pendidikan Ibu" class="control-label col-lg-4">Pendidikan Ibu</label>
                                            <div class="col-lg-8">
                                                   <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_jenjang_pendidikan_ibu==$isi->id_jenjang) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->jenjang;?>" class="form-control" readonly>
                                                             <?php
                                                                $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                    if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }  ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Ibu" class="control-label col-lg-4">Pekerjaan Ibu</label>
                                            <div class="col-lg-8">
                                                  <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                      $has_wali = 0;
                                                        if ($data_edit->id_pekerjaan_ibu==$isi->id_pekerjaan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->pekerjaan;?>" class="form-control" readonly>
                                                             <?php
                                                               $has_wali = 1;
                                                             break;
                                                        }
                                                    }  if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Ibu" class="control-label col-lg-4">Penghasilan Ibu</label>
                                            <div class="col-lg-8">
                                                  <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_penghasilan_ibu==$isi->id_penghasilan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->penghasilan;?>" class="form-control" readonly>
                                                             <?php
                                                              $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                     if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }
                                                     ?>
                                            </div>
                                        </div>

                                              </div>

                                        </div>
                                      <div class="callout callout-info" style="font-size: 20px; margin: 0; padding: 7px;">Data Orang Tua Wali</div>
                                        <br>
                                        <div class="form-group">
                                            <label for="Nama Wali" class="control-label col-lg-3">Nama Wali</label>
                                            <div class="col-lg-9">
                                                <input type="text" name="nm_wali" value="<?=$data_edit->nm_wali;?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Tanggal Lahir Wali" class="control-label col-lg-3">Tanggal Lahir Wali</label>
                                            <div class="col-lg-3">
                                                   <input type='text' class="form-control" name="tgl_lahir_ayah" value="<?=tgl_indo($data_edit->tgl_lahir_wali);?>" readonly/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Jenjang Pendidikan Wali" class="control-label col-lg-3">Jenjang Pendidikan Wali</label>
                                            <div class="col-lg-9">
                                                     <?php foreach ($db->fetch_all("jenjang_pendidikan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_jenjang_pendidikan_wali==$isi->id_jenjang) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->jenjang;?>" class="form-control" readonly>
                                                             <?php
                                                                $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                    if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }  ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Pekerjaan Wali" class="control-label col-lg-3">Pekerjaan Wali</label>
                                            <div class="col-lg-9">
                                                  <?php foreach ($db->fetch_all("pekerjaan") as $isi) {
                                                      $has_wali = 0;
                                                        if ($data_edit->id_pekerjaan_wali==$isi->id_pekerjaan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->pekerjaan;?>" class="form-control" readonly>
                                                             <?php
                                                               $has_wali = 1;
                                                             break;
                                                        }
                                                    }  if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Penghasilan Wali" class="control-label col-lg-3">Penghasilan Wali</label>
                                            <div class="col-lg-9">
                                                    <?php foreach ($db->fetch_all("penghasilan") as $isi) {
                                                          $has_wali = 0;
                                                        if ($data_edit->id_penghasilan_wali==$isi->id_penghasilan) {
                                                           ?>
                                                             <input type="text" name="tmpt_lahir" value="<?=$isi->penghasilan;?>" class="form-control" readonly>
                                                             <?php
                                                              $has_wali = 1;
                                                             break;
                                                        }
                                                    }
                                                     if( $has_wali==0) {
                                                            ?>
                                                            <input type="text" name="tmpt_lahir" value="" class="form-control" readonly>
                                                            <?php
                                                        }
                                                     ?>

                                            </div>
                                        </div>
                                        
                                    </div>
                                      <div class="tab-pane" id="dokumen">
                                        <div class="callout callout-info">
                                        <h4>Dokumen</h4>
                                    </div>
                                        <?php

                                        if(!empty(checkUploadDokumen($_SESSION['username']))) {
                                        ?>
                                         <div id="errorAlert" class="alert alert-danger alert-dismissible fade in alert-container" >
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h4><i class="icon fa fa-ban"></i> Mohon lengkapi Dokumen berikut!</h4>
                                        <ul id="errorList">
                                            <?php
                                            foreach (checkUploadDokumen($_SESSION['username']) as $error) {
                                               echo "<li>".$error."</li>";
                                            }
                                            ?>
                                            </ul>
                                              </div>
                                        <?php
                                    }
                                    ?>
                                  
                                        

          <div class="box-body table-responsive no-padding">
                                    <table id="dtb_file_upload" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                  <th>Jenis File</th>
                                  <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                              $data_file_label = $db->query("select * from tb_data_file_label  where is_show='Y'");

                              foreach ($data_file_label as $label) {
                                $is_exist = $db2->checkExist('tb_data_file',array('id_file_label' => $label->id_file_label,'nim' => $_SESSION['username']));
                                    $class_s1 = $jenj;
                                    $required = '';
                                    $wajib = '';
                                    
                                if (!$is_exist) {
                                 
                                  ?>
                                <tr>
                                  <th><?=$label->nama_label;?> <?=$wajib;?></th>
                                  <th class="th_<?=$label->id_file_label;?> file-data">
                                     <p class="help-block" style="color:#1dc0ef"><?=$label->helper;?></p>
                                  </th>
                                  <?php
                                } else {
                                  $check_ext = $db->fetch_single_row("tb_data_file_extention","type",$is_exist->getData()->type_file);
                                   $icon = base_admin()."modul/biodata/document.png";
                                  if ($check_ext->file_type=='image') {
                                    $icon = base_admin()."modul/biodata/gambar.png";
                                  }
                                  ?>
                                  <tr>
                                  <th><?=$label->nama_label;?> <?=$wajib;?></th>
                                  <th class="th_<?=$label->id_file_label;?>"><a target="_blank" data-toggle="tooltip" data-title="Lihat File" href="<?=$is_exist->getData()->link_file;?>"><img style="cursor:pointer" src="<?=$icon?>" width="30"></a>
                                  </th>
                                </tr>
                                  <?php
                                }
                                ?>

                                <?php
                              }
                              ?>
                            </tbody>
                        </table>

          </div>
                                    </div>
                                  
                                </div>
                                
                                </div>
                              </div>
                            </form>
                        </div>
                    </div>
</div>
</div>
        </section>
<script type="text/javascript">
$(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    $('#myTab a[href="#'+hash[1]+'"]').tab("show");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);
  } 
   
  $('a[data-toggle="tab"]').on("click", function() {
    let newUrl;
    const hash = $(this).attr("href");
    if(hash == "#data_diri") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    newUrl += "/";
    history.replaceState(null, null, newUrl);
  });
});  

$(document).ready(function() {
    // Initialize datepicker
    $(".tgl_picker").datepicker({
        format: "yyyy-mm-dd",
        viewMode: 'years',
        changeMonth: true,
        changeYear: true,
    }).on("change", function() {
        $(":input", this).valid();
    });

    // Initialize Select2
    $(".select2").select2();
    $("#kecamatan").select2({
        ajax: {
            url: '<?=base_admin();?>modul/mahasiswa/get_kecamatan.php',
            dataType: 'json'
        },
        placeholder: "Cari Kecamatan/Kabupaten",
        width: "100%",
    });

    // Province and Kabupaten change handlers
    $("#provinsi_provinsi").change(function() {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/biodata/get_kabupaten.php",
            data: { provinsi: this.value },
            success: function(data) {
                $("#kabupaten_kabupaten").html(data);
                $("#kabupaten_kabupaten").trigger("chosen:updated");
            }
        });
    });

    $("#kabupaten_kabupaten").change(function() {
        $.ajax({
            type: "post",
            url: "<?=base_admin();?>modul/biodata/get_kec.php",
            data: { id_kab: this.value },
            success: function(data) {
                $("#id_kec_tea").html(data);
                $("#id_kec_tea").trigger("chosen:updated");
            }
        });
    });

    // KPS toggle
    $("#a_terima_kps").change(function() {
        $('#no_kps').prop('readonly', this.value !== '1');
    });

    // Trigger validation on change
    $('select').on('change', function() {
        $(this).valid();
    });

    // Ignore hidden fields for validation except select
    $.validator.setDefaults({ ignore: ":hidden:not(select)" });

    // Form validation
    $("#edit_biodata").validate({
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass("has-success").addClass("has-error");
            if ($(element).hasClass("chzn-select") || $(element).hasClass("select2")) {
                $("#" + $(element).attr("id") + "_chosen").addClass("has-error");
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass("has-error").addClass("has-success");
            if ($(element).hasClass("chzn-select") || $(element).hasClass("select2")) {
                $("#" + $(element).attr("id") + "_chosen").removeClass("has-error");
            }
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select") || element.hasClass("select2")) {
                error.insertAfter("#" + element.attr("id") + "_chosen");
            } else if (element.attr("type") == "checkbox" || element.attr("type") == "radio") {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            nama: { readonly: true },
            nik: { readonly: true, minlength: 16, maxlength: 16 },
            kewarganegaraan: { readonly: true },
            id_jalur_masuk: { readonly: true },
            tmpt_lahir: { readonly: true },
            tgl_lahir_tanggal: { readonly: true },
            tgl_lahir_bulan: { readonly: true },
            tgl_lahir_tahun: { readonly: true },
            id_agama: { readonly: true },
            jln: { readonly: true },
            kode_pos: { readonly: true, maxlength: 5 },
            no_hp: { readonly: true },
            email: { readonly: true, email: true },
            nm_ibu_kandung: { readonly: true },
            id_jenis_sekolah: { readonly: true },
            nama_asal_sekolah: { readonly: true }
        },
        messages: {
            nama: { readonly: "Wajib di Isi" },
            nik: { readonly: "Wajib di Isi", minlength: "Must be 16 digits", maxlength: "Must be 16 digits" },
            kewarganegaraan: { readonly: "Wajib di Isi" },
            id_jalur_masuk: { readonly: "Wajib di Isi" },
            tmpt_lahir: { readonly: "Wajib di Isi" },
            tgl_lahir_tanggal: { readonly: "Wajib di Isi" },
            tgl_lahir_bulan: { readonly: "Wajib di Isi" },
            tgl_lahir_tahun: { readonly: "Wajib di Isi" },
            id_agama: { readonly: "Wajib di Isi" },
            jln: { readonly: "Wajib di Isi" },
            kode_pos: { readonly: "Wajib di Isi" },
            no_hp: { readonly: "Wajib di Isi" },
            email: { readonly: "Wajib di Isi", email: "Please enter a valid email address" },
            nm_ibu_kandung: { readonly: "Wajib di Isi" },
            id_jenis_sekolah: { readonly: "Wajib di Isi" },
            nama_asal_sekolah: { readonly: "Wajib di Isi" }
        },
        submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                type: "post",
                url: $(form).attr("action"),
                data: $("#edit_biodata").serialize(),
                success: function(data) {
                    $("#loadnya").hide();
                    if (data == "good") {
                        $(".notif_top_up").fadeIn(1000).fadeOut(1000, function() {
                            window.history.back();
                        });
                    } else if (data == "die") {
                        $("#informasi").modal("show");
                    } else {
                        $(".errorna").fadeIn();
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>