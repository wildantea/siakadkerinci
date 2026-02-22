<?php $data_edit=$db->fetch_single_row('sys_users','id',$_SESSION['id_user']);

if ($_SESSION['group_level']=='dosen') {
    $nama = $db->fetch_single_row("view_nama_gelar_dosen","nip",$data_edit->username)->nama_gelar;
} elseif($_SESSION['group_level']=='mahasiswa') {
  $nama = $db->fetch_single_row("mahasiswa","nip",$data_edit->username)->nama;
}
?>
 
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                      View Profil
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>profil">Profil</a></li>
                        <li class="active">Profil</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<div class="row">
                        <div class="col-xs-12">
                            <div class="box" style="padding-bottom:30px;padding-left:10px">
                                <div class="box-header">
                                   
                                </div><!-- /.box-header -->
                                <div class="row">
                                    <div class="col-lg-2">
                                          <span class="foto_profil"><img src="../../upload/back_profil_foto/<?=$data_edit->foto_user;?>" class="img-thumbnail"></span>
                                    </div>
                                     <div class="col-lg-8">

                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">

                                        <tbody>
                                        <tr>
                                            <th class="col-md-2">Username</th>
                                            <td><?=$data_edit->username;?></td>
                                            </tr>
                                        <tr>
                                            <th>Full Name</th>
                                            <td><?=ucwords($nama);?></td>
                                            </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?=ucwords($data_edit->email);?></td>
                                            </tr>
                                    </tbody></table>
                                </div><!-- /.box-body -->
                                <p></p>
                                <a href="<?=base_index();?>profil/edit" class="btn btn-primary">Ubah Profil</a> <a href="<?=base_index();?>profil/change-password" class="btn btn-primary">Ubah Password</a>
                            <p></p>
                                     </div>
                                </div>
                              
                            </div><!-- /.box -->
                        </div>
                    </div>
                  
                </section><!-- /.content -->
        
 