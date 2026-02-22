
<?php
$jur=$_SESSION['id_jur'];

?>

<div class="box-header">

                            </div><!-- /.box-header -->

                            <a href="<?= base_index()."rekap-input-nilai/sudah/".'$jur'."/".$_GET['sem'] ?>" class="btn btn-info "><i class="fa fa-pencil" method="GET"></i> Sudah Input Nilai</a>
                            <a href="<?= base_index()."rekap-input-nilai/belum/".'$jur'."/".$_GET['sem'] ?>" class="btn btn-danger "><i class="fa fa-pencil" method="GET"></i> Belum Input Nilai</a>
                            <a href="<?= base_index()."rekap-input-nilai/belum_lengkap/".'$jur'."/".$_GET['sem'] ?>" class="btn btn-warning "><i class="fa fa-pencil" method="GET"></i> Belum Lengkap</a><br><br>
<br>
                    
