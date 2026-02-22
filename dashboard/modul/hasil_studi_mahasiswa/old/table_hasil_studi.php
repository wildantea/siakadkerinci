<div class="box-header">

        </div><!-- /.box-header -->
<div class="box-body table-responsive">

    <table id="dtb_manual" class="table table-bordered table-striped">
        <thead>
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>Jurusan</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php
          $no=1;
          $where_nama = $_GET['nama']=="" ? "" : "m.nama like '%".clean($_GET['nama'])."%'";
          $or_kondisi = $where_nama!="" ? "and" : "";
          $where_jur  = $_GET['jur']!="" ? "and m.jur_kode='".de($_GET['jur'])."'" : "";
          $where_nim  ="m.nim like '%".clean($_GET['nim'])."%'";
          if ($_SESSION['level']=='1') {
              $where_fak = "";
          }elseif ($_SESSION['level']=='5' || $_SESSION['level']=='6') {
            $where_fak = "and f.kode_fak='".$_SESSION['id_fak']."'";
          }
          $qq = $db->query("select m.mhs_id,m.nim,m.nama,j.nama_jur,m.stat_pd from mahasiswa m
                            join jurusan j on m.jur_kode=j.kode_jur
                            join fakultas f on f.kode_fak=j.fak_kode where
                            ($where_nama $or_kondisi $where_nim) $where_jur $where_fak");
  /*        echo "select m.mhs_id,m.nim,m.nama,j.nama_jur,m.stat_pd from mahasiswa m
                            join jurusan j on m.jur_kode=j.kode_jur
                            join fakultas f on f.kode_fak=j.fak_kode where
                            ($where_nama $or_kondisi $where_nim) $where_jur $where_fak";*/
          if ($qq->rowCount()>0) {
           foreach ($qq as $k) {
           ?>
              <tr id="line_<?=$k->mhs_id;?>">
              <td><?= $no ?></td>
              <td><?= $k->nim ?></td>
              <td><?= $k->nama ?></td>
              <td><?= $k->nama_jur ?></td>
              <td><?= $k->stat_pd ?></td>
              <td>
                <?php
                   echo '<a href="'.base_index().'hasil-studi-mahasiswa/show-nilai/'.en($k->nim).'" data-id="'.$k->nim.'" class="btn edit_data btn-success "><i class="fa fa-book"></i> Lihat Hasil Studi</a> ';

                   ?>
              </td>
            </tr>
           <?php
           $no++;
          }
          }

          ?>
        </tbody>
    </table>
</div><!-- /.box-body -->
<script type="text/javascript">
$(document).ready(function() {
    selesai();
});
 
function selesai() {
  setTimeout(function() {
    update();
    selesai();
  }, 200);
}
</script>