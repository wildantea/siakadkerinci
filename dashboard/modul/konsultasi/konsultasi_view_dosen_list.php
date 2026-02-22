
<style type="text/css">
  .direct-chat-messages {
    height: 333px;
}

.search_result li:hover{
      background: #ECF0F5;
}

.centered-text {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  height: 100%;
}

.product-description.new-message {
  color: #1c84c6;
}
.products-list .product-title {
    color:#050505;
}
.chat-badge {
  background-color: #1e74e5;
  color: #fff;
  border-radius: 50%;
  font-size: 0.7em;
  padding: 7px 7px;
  margin-right: 13px;
  float: right;
}
.products-list>.new-item {
    background: #cbe3ff;
    padding-left:10px;
}

</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Konsultasi
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>konsultasi">Konsultasi</a></li>
                        <li class="active">Konsultasi List</li>
                    </ol>
                </section>


     <section class="content">
          <div class="row">
            <div class="col-md-4">

<?php
if ($_SESSION['group_level'] == 'mahasiswa') {
    $mhs_data = $db->fetch_custom_single("SELECT * FROM view_simple_mhs WHERE nim=?", ['nim' => $_SESSION['username']]);
    $mahasiswa_id = $_SESSION['username'];
    $dosen_id = $mhs_data->nip_dosen_pa;
    $foto = $db->fetch_custom_single("
        SELECT 
          (SELECT foto_user FROM sys_users WHERE username='$dosen_id') AS foto_dosen,
          (SELECT foto_user FROM sys_users WHERE username='$mahasiswa_id') AS foto_mhs
    ");
    $foto_mhs = $foto->foto_mhs;
    $foto_dosen = base_url() . 'upload/back_profil_foto/' . $foto->foto_dosen;
} else {
    $mahasiswa_id = uri_segment(3);
    $dosen_id = $_SESSION['username'];
    $foto = $db->fetch_custom_single("
        SELECT 
          (SELECT foto_user FROM sys_users WHERE username='$dosen_id') AS foto_dosen,
          (SELECT foto_user FROM sys_users WHERE username='$mahasiswa_id') AS foto_mhs
    ");
    $foto_mhs = $foto->foto_mhs;
    $foto_dosen = base_url() . 'upload/back_profil_foto/' . $foto->foto_dosen;
}

function escape_html($string) {
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Ambil pesan terakhir tiap mahasiswa–dosen
$pesan = $db->query("
  SELECT 
  cm.*,
  (SELECT foto_user FROM sys_users WHERE username=cm.nim) AS foto_mhs,
  CASE 
    WHEN cm.category_id = 1 THEN 'Konsultasi KRS'
    WHEN cm.category_id = 2 THEN 'Konsultasi Tengah Semester'
    WHEN cm.category_id = 3 THEN 'Konsultasi Pasca Perkuliahan'
    ELSE 'Lainnya'
  END AS kategori_konsultasi
FROM chat_message cm
JOIN (
  SELECT nim, MAX(created_at) AS last_time
  FROM chat_message
  WHERE nip = '$dosen_id'
  GROUP BY nim
) latest ON cm.nim = latest.nim AND cm.created_at = latest.last_time
ORDER BY cm.created_at DESC
LIMIT 5;
");
echo $db->getErrorMessage();
?>
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Semua Pesan</h3>
  </div>
  <div class="box-body" style="padding:0">
    <ul class="products-list product-list-in-box" id="refresh_kiri">
      <?php if ($pesan->rowCount() > 0): ?>
        <?php foreach ($pesan as $ps): ?>
          <?php
          $mhs_data = $db->fetch_custom_single("select * from view_simple_mhs where nim=?",array('nim' => $ps->nim));
            // tentukan avatar berdasarkan role pengirim
            $avatar = $ps->sender_role == 'mahasiswa' ? $ps->foto_mhs : $foto_dosen;
            $avatar = $ps->foto_mhs;
            // batasi preview pesan
            $you = '';
            $preview = substr(escape_html($ps->message), 0, 50);
            if ($ps->sender_role=='dosen') {
              $you = 'Anda: ';
            }
          ?>
          <li class="item" style="cursor:pointer; padding:10px;"
              onclick="location.href='<?= base_index(); ?>konsultasi/lihat/<?= $ps->nim; ?>'">
            <div class="product-img">
              <img src="<?= $avatar ?>" alt="User Image">
            </div>
            <div class="product-info">
              <a href="javascript:void(0)" class="product-title">
                <?= $mhs_data->nama ?>
                <span class="label label-default pull-right">
                  <?= date('H:i', strtotime($ps->created_at)) ?>
                </span>
              </a>
              <span class="product-description">
                <?=$you. $preview ?><?= strlen($ps->message) > 50 ? '...' : '' ?>
              </span>
            </div>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <li class="item text-center" style="padding:15px;">Belum ada pesan</li>
      <?php endif; ?>
    </ul>


                </div><!-- /.box-body -->
              </div>

            </div><!-- /.col -->
                <div class="col-md-8">
                <div class="box box-primary">
<?php
if ($_SESSION['group_level']=='dosen') {

$jml_bimbingan = $db->fetch_custom_single("select count(*) as jml from mahasiswa where dosen_pemb=? and nim not in(select nim from tb_data_kelulusan)",array('dosen_pemb' => $_SESSION['username']));
$list_bimbingan = $db->query("select *,(SELECT foto_user FROM sys_users WHERE username=view_simple_mhs.nim) AS foto_mhs from view_simple_mhs where nip_dosen_pa=? and nim not in(select nim from tb_data_kelulusan)",array('dosen_pemb' => $_SESSION['username']));
?>
  <div class="box-header with-border">
    <h3 class="box-title">List Bimbingan Mahasiswa</h3>

    <div class="box-tools pull-right">
      <span class="label label-primary"><?=$jml_bimbingan->jml?> Mahasiswa</span>
      <button type="button" class="btn btn-box-tool" data-widget="collapse">
        <i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove">
        <i class="fa fa-times"></i>
      </button>
    </div>
  </div>
  <!-- /.box-header -->

  <div class="box-body no-padding">
    <ul class="users-list clearfix">
      <?php
      foreach ($list_bimbingan as $bimbingan) {
        $foto = $bimbingan->foto_mhs;
        if ($bimbingan->foto_mhs=='default_user.png') {
          $foto = base_url().'upload/back_profil_foto/'.$bimbingan->foto_mhs;
        }
        ?>
      <li>
        <img src="<?=$foto?>" style="max-height: 150px;">
        <a class="users-list-name" href="#"><?=$bimbingan->nama?></a>
        <span class="users-list-date">NIM: <?=$bimbingan->nim?></span>
        <span class="users-list-date">Angkatan: <?=$bimbingan->angkatan?></span>
        <a href="<?=base_index();?>konsultasi/lihat/<?=$bimbingan->nim?>" class="btn btn-primary btn-xs"><i class="fa fa-envelope"></i> Kirim Pesan</a>
      </li>
        <?php
      }
      ?>
    </ul>
    <!-- /.users-list -->
  </div>
  <?php

} else {
  ?>
<div class="box-header with-border">
    <h3 class="box-title">Penasehat Akademik</h3>

    <div class="box-tools pull-right">
      
    </div>
  </div>
  <!-- /.box-header -->

  <div class="box-body no-padding">
    <ul class="users-list clearfix">
      <?php
      $mhs_data = $db->fetchCustomSingle("select * from view_simple_mhs where nim=?",array('nim' => $_SESSION['username']));
      $dosen = $db->fetchSingleRow("view_dosen","nip",$mhs_data->nip_pembimbing);
        ?>
      <li>
        <img src="<?=$dosen->foto?>" style="max-height: 150px;">
        <a class="users-list-name" href="#"><?=$dosen->nama_gelar?></a>
        <span class="users-list-date">NIDN: <?=$dosen->nidn?></span>
        <span class="users-list-date">No Hp: <?=$dosen->no_hp?></span>
        <a href="<?=base_index();?>konsultasi/lihat/<?=$dosen->nip?>" class="btn btn-primary btn-xs"><i class="fa fa-envelope"></i> Kirim Pesan</a>
      </li>
        <?php
      ?>
    </ul>
    <!-- /.users-list -->
  </div>
  <?php
}
?>
  <!-- /.box-body -->

  <!-- <div class="box-footer text-center">
    <a href="javascript:void(0)" class="uppercase">Lihat Semua Mahasiswa</a>
  </div> -->
  <!-- /.box-footer -->
</div>

            </div>

          </div><!-- /.row -->
        </section>
   <div class="modal fade" id="modal_tujuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"> <div class="modal-dialog modal-lg"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="glyphicon glyphicon-remove"></i></span></button> <h4 class="modal-title"><?php echo $lang["add_button"];?> Jadwal Sidang</h4> </div> <div class="modal-body" id="isi_tujuan"> </div> </div><!-- /.modal-content --> </div><!-- /.modal-dialog --> </div>

<script type="text/javascript">

/*$(window).on('load', function() {


});*/


$(document).ready(function(){
    // Do this before you initialize any of your modals
$.fn.modal.Constructor.prototype.enforceFocus = function() {};
      $("#add_tujuan").click(function() {
        $(".show-to").show();
              $('.pengajar').select2({
                allowClear: true,
                placeholder: 'cari nama siswa',
                ajax: {
                  dataType: 'json',
                  url: '<?=base_admin();?>modul/konsultasi/get_contact.php',
                }
              }).on('select2:select', function (e) {
                  var data = e.params.data;
                  // redirect to a specific URL based on the selected option
                  window.location.href = '<?=base_index();?>konsultasi/lihat/' + data.id;
              }).select2('open');

    });

/*  $(".tujuan").select2({
    allowClear: true,
    width: "100%",
    ajax: {
      url: '<?=base_admin();?>modul/konsultasi/get_contact.php',
      dataType: 'json',
      success : function(data) {
        console.log(data);
      },
      error: function(xhr, status, error) {
        console.log("Error: " + error);
      }
    },
    formatInputTooShort: "Cari & pilih nama tujuan"
  });

  $('.js-data-example-ajax').select2({
    debug : true,
  ajax: {
    url: '<?=base_admin();?>modul/konsultasi/get_contact.php',
    dataType: 'json',
    processResults: function(data) {
  console.log(data);
  // rest of the code
}

    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
});*/

/*$( ".pengajar" ).select2({
  ajax: {
    url: '<?=base_admin();?>modul/kelas_jadwal/jadwal/tujuan_pengajar/data_tujuan.php',
    dataType: 'json'
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  },
  formatInputTooShort: "Cari & Pilih Nama tujuan",
  allowClear: true,
  width: "100%",
});*/

});


// Function to escape HTML
function escapeHtml(str) {
  var div = document.createElement('div');
  div.appendChild(document.createTextNode(str));
  return div.innerHTML;
}

$("#chat_pesan").animate({ scrollTop: $("#chat_pesan")[0].scrollHeight }, 1000);
  var userId = "<?=$user;?>";
  var socketIO = io();


  socketIO.on('connect', function () {
    console.log('Connected to the socket server');
    socketIO.emit("connected", userId);
  });

  socketIO.on('disconnect', function () {
    console.log('Disconnected from the socket server');
  });

  socketIO.on('error', function (error) {
    console.error('An error occurred while connecting to the socket server:', error);
  });

  socketIO.emit("connected", userId);

socketIO.on('newMessage', function(message) {



    if (message.receiver==userId) {
 notificationSound.play();

  // Check if the chat list already exists
  var chatList = $('#refresh_kiri');
  var chatItem = chatList.find('#' + message.sender);

  // If the chat list exists, update the last message and move the chat item to the top
  if (chatItem.length > 0) {
    chatItem.find('.product-description').html('<span class="chat-badge"></span>' + escapeHtml(message.message));
    chatItem.addClass('new-item');
    chatItem.find('.product-description').addClass('new-message');
    chatItem.prependTo(chatList);
  } 
  // If the chat list does not exist, create a new chat item and prepend it to the chat list
  else {

    var newItem = '<li id="' + message.sender + '" class="item" onclick="location.href=\'<?=base_index();?>konsultasi/lihat/' + message.sender + '\'" style="background: #D2D6DE;padding-left:10px;cursor:pointer">' +
      '<div class="product-img">' +
      '<img src="' + message.photo_user + '" alt="Product Image">' +
      '</div>' +
      '<div class="product-info">' +
      '<a href="javascript:;" class="product-title">' + message.full_name + '</a>' +
      '<span class="product-description"><span class="chat-badge"></span> ' + escapeHtml(message.message) + '</span>' +
      '</div>' +
      '</li>';

    chatList.prepend(newItem);
  }

  // Scroll to the top of the chat list
  chatList.scrollTop(0);

}
});
</script>

