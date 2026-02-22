
<style type="text/css">
  .direct-chat-messages {
    height: 333px;
}

.search_result li:hover{
      background: #ECF0F5;
}

.direct-chat-img {
    border-radius: 50%;
    float: left;
    width: 33px;
    height: 33px;
}
.direct-chat-text {
  word-wrap: break-word;

}
.direct-chat-messages {
  height: 60vh;
}
.meta-day {
    -webkit-box-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    align-items: center;
    margin-bottom: 0;
    color: #8b95a5;
    opacity: .8;
    font-weight: 400;
   
}
.meta-day::before, .media-meta-day::after {
    content: '';
    -webkit-box-flex: 1;
    flex: 1 1;
    border-top: 1px solid #ebebeb;
}
.media-meta-day::after {
    margin-left: 16px;
}
.meta-day::after {
    content: '';
    -webkit-box-flex: 1;
    flex: 1 1;
    border-top: 1px solid #ebebeb;
}
.medias {
     display: flex;
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
.direct-chat-message {
    position: relative;
}

.direct-chat-delete {
    position: absolute;
    left: 0;
    top: 15px;
    transform: translateY(-50%);
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.direct-chat-message:hover .direct-chat-delete {
    opacity: 1;
}
.box {
    border-right: 1px solid #d2d6de;
    border-top: 1px solid #d2d6de;
    border-left: 1px solid #d2d6de;

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

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
<div class="box-header">
  <?php
if ($_SESSION['group_level']=='mahasiswa') {
    $mhs_data = $db->fetch_custom_single("select * from view_simple_mhs where nim=?",array('nim' => $_SESSION['username']));
    $mahasiswa_id = $_SESSION['username'];
    $dosen_id = $mhs_data->nip_dosen_pa;
    $foto = $db->fetch_custom_single("select (select foto_user from sys_users where username='$dosen_id') as foto_dosen,(select foto_user from sys_users where username='$mahasiswa_id') as foto_mhs"); 
    $foto_mhs = $foto->foto_mhs;
    $foto_dosen = base_url().'upload/back_profil_foto/'.$foto->foto_dosen;
} elseif ($_SESSION['group_level']=='dosen') {
    $mahasiswa_id = uri_segment(3);
    $dosen_id = $_SESSION['username'];
        $foto = $db->fetch_custom_single("select (select foto_user from sys_users where username='$dosen_id') as foto_dosen,(select foto_user from sys_users where username='$mahasiswa_id') as foto_mhs"); 
    $foto_mhs = $foto->foto_mhs;
    $foto_dosen = base_url().'upload/back_profil_foto/'.$foto->foto_dosen;
} 

           $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$mahasiswa_id);
             //ip semester sebelumnya
             $ip_sebelumnya = $db->fetch_custom_single("select fungsi_get_ip_semester_sebelumnya(".$mahasiswa_id.",".get_sem_aktif().") as ip_sebelumnya");
             if ($ip_sebelumnya) {
               $ip_semester_lalu = $ip_sebelumnya->ip_sebelumnya;
             }
             $check_paket_semester = $db->fetch_single_row("data_paket_semester","id",1);
              $data_jatah_sks = $db->fetch_custom_single("select fungsi_get_jatah_sks('".$mahasiswa_id."','".get_sem_aktif()."') as jatah_sks,fungsi_jml_sks_diambil('".$mahasiswa_id."','".get_sem_aktif()."') as diambil ");
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
             $data_mhsx = $db->query("select `m`.`nim` AS `nim`,`m`.`nama` AS `nama`,`m`.`mulai_smt` AS `mulai_smt`,`m`.`jk` AS `jk`,`jd`.`id_jenis_daftar` AS `id_jenis_daftar`,`jd`.`nm_jns_daftar` AS `nm_jns_daftar`,ifnull(`jk`.`ket_keluar`,'Aktif') AS `jenis_keluar`,`view_semester`.`angkatan` AS `angkatan`,`m`.`mhs_id` AS `mhs_id`,`vpj`.`kode_jur` AS `jur_kode`,`view_dosen`.`nip` AS `nip_dosen_pa`,`view_dosen`.`dosen` AS `dosen_pa`,`vpj`.`jurusan` AS `jurusan` from (((((`mahasiswa` `m` join `view_prodi_jenjang` `vpj` on((`m`.`jur_kode` = `vpj`.`kode_jur`))) left join `jenis_keluar` `jk` on((`m`.`id_jns_keluar` = `jk`.`id_jns_keluar`))) left join `view_dosen` on((`m`.`dosen_pemb` = `view_dosen`.`nip`))) join `view_semester` on((`m`.`mulai_smt` = `view_semester`.`id_semester`))) join `jenis_daftar` `jd` on((`m`.`id_jns_daftar` = `jd`.`id_jenis_daftar`))) where nim='".$mahasiswa_id."' ");
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


        <?php
      }

$krs_time = $db->fetch_custom_single("select * from semester s where id_semester='".get_sem_aktif()."' and kode_jur='$data_mhs->jur_kode'");

$check_jadwal = $db->fetch_custom_single("select view_nama_kelas.nm_matkul,view_nama_kelas.sem_matkul,view_nama_kelas.sks,view_nama_kelas.kls_nama,ruang_ref.nm_ruang,jadwal_kuliah.hari,jadwal_kuliah.jam_mulai,jadwal_kuliah.jam_selesai,
 (select group_concat(distinct nama_gelar separator '#')
    from view_nama_gelar_dosen inner join dosen_kelas on view_nama_gelar_dosen.nip=dosen_kelas.id_dosen where dosen_kelas.id_kelas=view_nama_kelas.kelas_id) as nama_dosen,
view_nama_kelas.jurusan as nama_jurusan,jadwal_kuliah.jadwal_id,view_nama_kelas.kelas_id

 from view_nama_kelas
left join jadwal_kuliah using(kelas_id)
left join ruang_ref using(ruang_id)
where view_nama_kelas.kelas_id in(select id_kelas from krs_detail where nim='".$mahasiswa_id."' and 
krs_detail.id_kelas=view_nama_kelas.kelas_id) and view_nama_kelas.sem_id='".get_sem_aktif()."'
and jadwal_id is not null");
if ($check_jadwal) {
  //krs time mulai
  $is_krs_mulai = 0;
  if (date('Y-m-d') >= $krs_time->tgl_mulai_krs) {
    $is_krs_mulai = 1;
  } else {
    $is_krs_mulai = 0;
  }

  if (date('Y-m-d') <= $krs_time->tgl_selesai_krs) {
    $is_krs_mulai = 1;
  } else {
    $is_krs_mulai = 0;
  }

  //get pertemuan date
  $pertemuan_8 = $db->fetch_custom_single("select tanggal_pertemuan from tb_data_kelas_pertemuan where pertemuan='8' and kelas_id='$check_jadwal->kelas_id' order by tanggal_pertemuan desc");
  $is_aktif_tengah = 0;
  $tanggal_tengah_mulai = "";

  if ($pertemuan_8) {
    $tanggal_tengah_mulai = tgl_indo($pertemuan_8->tanggal_pertemuan);
    if (date('Y-m-d') > $pertemuan_8->tanggal_pertemuan) {
      $is_aktif_tengah = 1;
    } else {
      $is_aktif_tengah = 0;
    }
  }
  
  $pertemuan_15 = $db->fetch_custom_single("select tanggal_pertemuan from tb_data_kelas_pertemuan where pertemuan='15' and kelas_id='$check_jadwal->kelas_id' order by tanggal_pertemuan desc");
  $tanggal_tengah_selesai = "";

  if ($pertemuan_15) {
    $tanggal_tengah_selesai = tgl_indo($pertemuan_15->tanggal_pertemuan);
    if (date('Y-m-d') <= $pertemuan_15->tanggal_pertemuan) {
      $is_aktif_tengah = 1;
    } else {
      $is_aktif_tengah = 0;
    }
  }
  //check jadwal input nilai terakhir and pertemuan 16
  $pertemuan_16 = $db->fetch_custom_single("select tanggal_pertemuan from tb_data_kelas_pertemuan where pertemuan='16' and kelas_id='$check_jadwal->kelas_id' order by tanggal_pertemuan desc");
  $is_aktif_pasca_mulai = 0;
  $tanggal_pasca_mulai = "";

  if ($pertemuan_16) {
    $tanggal_pasca_mulai = tgl_indo($pertemuan_16->tanggal_pertemuan);
    if (date('Y-m-d') > $pertemuan_16->tanggal_pertemuan) {
      $is_aktif_pasca_mulai = 1;
    } else {
      $is_aktif_pasca_mulai = 0;
    }
  }

   $is_aktif_pasca_selesai = 0;

    $tgl_selesai_input_nilai = tgl_indo($krs_time->tgl_selesai_input_nilai);
    if (date('Y-m-d') <= $krs_time->tgl_selesai_input_nilai) {
      $is_aktif_pasca_selesai = 1;
    } else {
      $is_aktif_pasca_selesai = 0;
    }

  ?>

  <div class="alert alert-info alert-dismissible"> 
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Informasi!</h4>
                Halaman ini digunakan untuk konsultasi/chat ke Dosen Pembimbing anda.<p></p>
                Waktu Konsultasi KRS <span class="btn btn-success btn-xs"><?=tgl_indo($krs_time->tgl_mulai_krs);?></span> s/d <span class="btn btn-success btn-xs"><?=tgl_indo($krs_time->tgl_selesai_krs);?></span> <p>
                Waktu Konsultasi Tengah Semester adalah pertemuan 8 perkuliahan <span class="btn btn-success btn-xs"><?=$tanggal_tengah_mulai;?></span> s/d pertemun 15 perkuliahan <span class="btn btn-success btn-xs"><?=$tanggal_tengah_selesai;?></span><p>
                Waktu konsultasi pasca perkuliahan adalah pertemuan 16 perkuliahan <span class="btn btn-success btn-xs"><?=$tanggal_pasca_mulai?></span> s/d tanggal akhir input nilai <span class="btn btn-success btn-xs"><?=$tgl_selesai_input_nilai?></span>. 

              </div>     

  <?php
} else {
  ?>
  <div class="alert alert-danger" style="text-align:center" role="alert" id="warning">
                  <strong><i class="fa fa-warning"></i></strong> Bukan Periode Konsultasi
                </div>
<?php
}
      ?>
         
        </div><!-- /.box-header -->
        <div class="box-body">
          
  <div class="row">
  <!-- ================= KONSULTASI KRS ================= -->
  <div class="col-lg-3 col-md-6">
    <div class="box box-success direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Konsultasi KRS</h3>
      </div>
      <div class="box-body">
        <div class="direct-chat-messages" id="chat-box-krs"></div>
      </div>
      <?php
      if ($is_krs_mulai) {
        ?>
        <div class="box-footer">
        <div class="input-group">
          <input id="msg-krs" type="text" class="form-control" placeholder="Ketik pesan...">
          <span class="input-group-btn">
            <button class="btn btn-success btn-flat" onclick="sendMessage(1, 'msg-krs')">Kirim</button>
          </span>
        </div>
      </div>
        <?php
      }
      ?>
      
    </div>
  </div>

  <!-- ================= KONSULTASI TENGAH SEMESTER ================= -->
  <div class="col-lg-3 col-md-6">
    <div class="box box-success direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Konsultasi Tengah Semester</h3>
      </div>
      <div class="box-body">
        <div class="direct-chat-messages" id="chat-box-tengah"></div>
      </div>
       <?php
      if ($is_aktif_tengah) {
        ?>
      <div class="box-footer">
        <div class="input-group">
          <input id="msg-tengah" type="text" class="form-control" placeholder="Ketik pesan...">
          <span class="input-group-btn">
            <button class="btn btn-success btn-flat" onclick="sendMessage(2, 'msg-tengah')">Kirim</button>
          </span>
        </div>
      </div>
       <?php
      }
      ?>
    </div>
  </div>

  <!-- ================= KONSULTASI PASCA PERKULIAHAN ================= -->
  <div class="col-lg-3 col-md-6">
    <div class="box box-success direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Konsultasi Pasca Perkuliahan</h3>
      </div>
      <div class="box-body">
        <div class="direct-chat-messages" id="chat-box-pasca"></div>
      </div>
        <?php
      if ($is_aktif_pasca_mulai && $is_aktif_pasca_selesai) {
        ?>
      <div class="box-footer">
        <div class="input-group">
          <input id="msg-pasca" type="text" class="form-control" placeholder="Ketik pesan...">
          <span class="input-group-btn">
            <button class="btn btn-success btn-flat" onclick="sendMessage(3, 'msg-pasca')">Kirim</button>
          </span>
        </div>
      </div>
       <?php
      }
      ?>
    </div>
  </div>

    <!-- ================= KONSULTASI PASCA PERKULIAHAN ================= -->
  <div class="col-lg-3 col-md-6">
    <div class="box box-success direct-chat direct-chat-success">
      <div class="box-header with-border">
        <h3 class="box-title">Konsultasi Umum</h3>
      </div>
      <div class="box-body">
        <div class="direct-chat-messages" id="chat-box-umum"></div>
      </div>
      <div class="box-footer">
        <div class="input-group">
          <input id="msg-umum" type="text" class="form-control" placeholder="Ketik pesan...">
          <span class="input-group-btn">
            <button class="btn btn-success btn-flat" onclick="sendMessage(4, 'msg-umum')">Kirim</button>
          </span>
        </div>
      </div>
    </div>
  </div>
  
</div>
<audio id="newMessageAudio">
  <source src="<?=base_admin()?>assets/chat_sound.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
           
        </div>

        </div>
    </div>
</section><!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/socket.io/socket.io.js"></script>
<script>
let audioUnlocked = false;

function unlockAudio() {
  const audio = document.getElementById("newMessageAudio");
  if (audio && !audioUnlocked) {
    audio.play()
      .then(() => {
        audio.pause();
        audio.currentTime = 0;
        audioUnlocked = true;
        console.log("🔓 Audio unlocked");
      })
      .catch(() => {});
  }
}

// unlock on first click, keypress, or touch
document.addEventListener("click", unlockAudio, { once: true });
document.addEventListener("keydown", unlockAudio, { once: true });
document.addEventListener("touchstart", unlockAudio, { once: true });

const socket = io("https://siakad.iainkerinci.ac.id"); // domain proxy nginx → Node.js
const mahasiswa_id = "<?=$mahasiswa_id?>"; // ganti dengan PHP session user ID
const dosen_id = "<?=$dosen_id;?>";      // dosen pembimbing user ID
const role = "<?=$_SESSION['group_level']?>"; // atau "dosen"

// mapping kategori ke elemen box
const chatBoxes = {
  1: "#chat-box-krs",
  2: "#chat-box-tengah",
  3: "#chat-box-pasca",
  4: "#chat-box-umum"
};

// 🔗 saat socket terkoneksi
socket.on("connect", () => {
  const userId = role === "mahasiswa" ? mahasiswa_id : dosen_id;
  socket.emit("register", { user_id: userId });

    // load semua kategori (1–4)
  [1, 2, 3, 4].forEach(loadMessages);

});

$(document).on("click", ".hapus-pesan", function() {
  const id = $(this).data("id");
  Swal.fire({
    title: "Hapus pesan ini?",
    text: "Pesan akan dihapus permanen.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, hapus",
    cancelButtonText: "Batal",
    confirmButtonColor: "#d9534f"
  }).then((result) => {
    if (result.isConfirmed) {
      socket.emit("delete-message", id);
    }
  });
});

socket.on("message-deleted", (data) => {
  const el = $("#chatid_" + data.id);
  el.fadeOut(300, function() {
    $(this).remove();
  });
});



// 🧩 fungsi load riwayat pesan dari PHP API
function loadMessages(category_id) {
$.getJSON(`<?=base_admin()?>modul/konsultasi/get_message.php?category_id=${category_id}&mahasiswa_id=${mahasiswa_id}&dosen_id=${dosen_id}`, (messages) => {
  const box = $(chatBoxes[category_id]);
  box.empty();

  // ✅ pastikan messages berupa array
  if (Array.isArray(messages) && messages.length > 0) {
    messages.forEach((m) => appendChatMessage(m, category_id));
  } else {
    // tampilkan pesan placeholder (opsional)
    box.append(``);
  }
});

}

// 🧱 fungsi render pesan ke masing-masing box
function appendChatMessage(m, category_id) {
  const box = $(chatBoxes[category_id]);
  const align = m.sender_role === "mahasiswa" ? "right" : "left";
  const avatar = 
    m.sender_role === "mahasiswa"
      ? "<?=$foto_mhs;?>"
      : "<?=$foto_dosen;?>";

  const time = m.created_at
    ? new Date(m.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
    : new Date().toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });

// Tentukan apakah pesan ini milik user yang sedang login
const isMine = (m.sender_role === role);

const deleteButton = isMine
  ? `
    <div class="direct-chat-text" style="margin-top:0px;margin-left:30px;">
        ${m.message}
      </div>
    <div class="direct-chat-delete">
      <span class="btn btn-xs btn-danger hapus-pesan" data-id="${m.id}">
        <i class="fa fa-trash"></i>
      </span>
    </div>
    `
  : `<div class="direct-chat-text" style="margin-top:0px;margin-left:42px;">
        ${m.message}
      </div>`; // kalau bukan milik sendiri, kosong

box.append(`
  <div class="direct-chat-msg ${align}" style="margin-bottom:0px;" id="chatid_${m.id}">
    <div class="direct-chat-info clearfix" style="margin-top: 10px;">
      <span class="direct-chat-timestamp">${time}</span>
    </div>
    <img class="direct-chat-img" src="${avatar}" alt="user">
    <div class="direct-chat-message">
      ${deleteButton}
    </div>
  </div>
`);
  box.scrollTop(box[0].scrollHeight);
}

// ✉️ saat pesan baru diterima realtime
socket.on("receive_message", (data) => {
  appendChatMessage(data, data.category_id);

  // play sound only for messages from others
  if (data.sender_role !== role && audioUnlocked) {
    const audio = document.getElementById("newMessageAudio");
    if (audio) {
      audio.currentTime = 0;
      audio.play().catch(err => console.log("🔇 Audio play error:", err));
    }
  }
});




// ======== KIRIM PESAN (DIPANGGIL DARI TOMBOL DI SETIAP BOX) =========
function sendMessage(category_id, inputId) {
  const input = document.getElementById(inputId);
  const pesan = input.value.trim();
  if (!pesan) return;

  // Data pengirim — ambil dari session / variable global kamu
  const mahasiswa_id = "<?=$mahasiswa_id?>";  // contoh
  const dosen_id = "<?=$dosen_id?>";     // contoh
  const role = "<?=$_SESSION['group_level']?>";         // atau "dosen"

  const data = {
    mahasiswa_id,
    dosen_id,
    category_id,
    sender_role: role,
    message: pesan,
  };

  // Kirim ke Socket.IO server
  socket.emit("send_message", data);

  // Tampilkan langsung di UI
/*  appendChatMessage({
    sender_role: role,
    message: pesan,
    category_id: category_id,
  }, category_id);*/

  input.value = "";
}

// =============== KIRIM PESAN DENGAN ENTER ===============
document.querySelectorAll("input[id^='msg-']").forEach((input) => {
  input.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault(); // mencegah newline
      const inputId = this.id;

      // ambil category_id dari id input
      const category_id = {
        "msg-krs": 1,
        "msg-tengah": 2,
        "msg-pasca": 3,
        "msg-umum": 4
      }[inputId];

      sendMessage(category_id, inputId);
    }
  });
});


</script>
