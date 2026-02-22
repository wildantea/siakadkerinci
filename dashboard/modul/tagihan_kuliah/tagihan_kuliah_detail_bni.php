<style type="text/css">
    .text-center {
        text-align: center;
    }
    hr {
        margin: 0;
    }
    .input-group {
        width: 300px;
        margin: auto;
        margin-top:7px;
    }
    .salin {
        cursor: pointer;
    }
    .salin-va {
        cursor: pointer;
    }
    .va-kode {
        border: 1px dashed;
        font-size: 18px;
        text-align: center;
    }
    .panel-collapse {
        font-weight: normal;
    }

 .va-box {
  display: flex;
  align-items: center;
  padding: 6px;
  border-radius: 5px;
  margin-top: 10px;
  background-color: rgb(249, 249, 249);
}

.va-number {
  flex-grow: 1;
  font-size: 16px;
  font-weight: bold;
  color: rgb(84, 84, 84);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.btn-copy {
  margin-left: 10px;
  padding: 6px 16px;
  font-size: 14px;
  background-color: #fff;
  border: 1px solid #FD6542;
  color: #ff4d4d;
  border-radius: 50px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.btn-copy:hover {
  background-color: #FD6542;
  color: #fff;
}

.copy-btn {
  color: #f44336;
  border-color: #f44336;
  background-color: white;
}

.copy-btn:hover {
  color: white;
  background-color: #f44336;
}

.timer-container {
  display: inline-block;
  padding: 5px;
  background-color: #fce8ea;
  border-radius: 50px;
  margin-bottom:10px;
  font-family: Arial, sans-serif;
}

.time-box {
  color: #b13044;
  font-weight: bold;
  padding: 0 5px;
}


</style>
<?php
//dump($check_va);
$bank = $db->fetch_single_row('keu_bank','kode_bank',$check_va->id_bank);
?>
<!-- Content Header (Page header) -->
                <section class="content-header" style="padding-left: 30px;">
                    <h1>Tagihan Kuliah</h1>
                   <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>tagihan-ukt">Tagihan Kuliah</a></li>
                        <li class="active">Status Pembayaran</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="col-lg-12">
                        <div class="box box-solid box-primary">
                            <div class="box-header">
                            <h3 class="box-title">Detail Transaksi</h3>
                            </div>
                        </div>
<div class="row">
        <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border  text-center">
                <h3 class="box-title">Bank yang dipilih untuk pembayaran</h3><br>
              <img src="<?=base_url();?>upload/logo/<?=$bank->logo;?>">
              <hr>
              <h4>Total Pembayaran</h4>
              <h3>
                <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <b><input type="text" id="total_tagihan" name="total_tagihan" data-a-sep="." data-a-dec="," class="form-control va-kode" readonly="" value="<?=rupiah($check_va->total_nominal);?>">
                </b>
                 <span class="input-group-addon salin" data-clipboard-text="<?=$check_va->total_nominal;?>" title="Copied!">Copy</span>
              </div>
          </h3>
              <hr>
               <h4>Kode Virtual Account</h4>
               <h3>
                <div class="input-group">
                <b><input id="kode_va" type="text" name="total_tagihan" data-a-sep="." data-a-dec="," class="form-control va-kode" readonly="" value="<?=$check_va->no_va;?>">
                </b>
                 <span class="input-group-addon salin-va" data-clipboard-target="#kode_va" title="Copied!">Copy</span>
              </div>
          </h3>
              <hr>
              <h4>Lakukan Pembayaran Sebelum tanggal <b><?=tgl_indo($check_va->exp_date);?> Pukul <?=substr($check_va->exp_date, -8);?><b> </h4>
                <h4>Status Pembayaran : <span style="color:#f00;font-weight: bold;">Belum Bayar</span></h4>
                 <br>
                <button type="submit" class="btn btn-danger batalkan"><i class="fa fa-undo"> Batalkan Pembayaran</i></button>
              </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Petunjuk Pembayaran<b></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <?php 
                if ($bank->kode_bank=='04') {
                  $nim_replace = $check_va->no_va;
                  $replace = "882621209705001";
                  $bank_lain = "008882621209705001";
                }
                $panduans = $db->query("select * from panduan_pembayaran where id_bank='$bank->kode_bank' order by urutan asc");
                $iteration = 0;
                foreach ($panduans as $panduan) {
 $collapse = '';
                    if ($counter==0) {
                        $collapse = 'in';
                    }
                    $replacer = '<div class="va-box">
                                  <span class="va-number">'.$check_va->no_va.'</span>
                                  <button class="btn btn-copy" data-clipboard-text="'.$check_va->no_va.'" title="Disalin!">Salin</button>
                                </div>';
                    $replacer_rupiah = '<div class="va-box">
                                  <span class="va-number">'.rupiah($check_va->total_nominal).'</span>
                                  <button class="btn btn-copy" data-clipboard-text="'.$check_va->total_nominal.'" title="Disalin!">Salin</button>
                                </div>';

                    $replacer_only = $check_va->no_va;

                    $replacer_rupiah_only = $check_va->total_nominal;     

                    $isi_panduan = html_entity_decode($panduan->isi_panduan);



                      $isi_panduan = str_replace('54321', $replacer, $isi_panduan);
$isi_panduan = str_replace('10000', $replacer_rupiah, $isi_panduan);
$isi_panduan = str_replace('nomorva', $replacer_only, $isi_panduan);
$isi_panduan = str_replace('jumlahbayar', $replacer_rupiah_only, $isi_panduan);

// Now apply preg_replace for <code> tags
$result = preg_replace(
    '/<code\s*>(.*?)<\/code>/si',
    '<div class="va-box">
        <span class="va-number">$1</span>
        <button class="btn btn-copy" data-clipboard-text="$1" title="Disalin!">Salin</button>
     </div>',
    $isi_panduan
);
                    ?>

                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$panduan->id;?>">
                        <?=$panduan->judul;?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse<?=$panduan->id;?>" class="panel-collapse collapse <?=$class;?>">
                    <div class="box-body">
                     <?=$isi_panduan;?>
                    </div>
                  </div>
                </div>
                  <?php
                  $iteration++;
                }
                ?>
              </div>
            </div>
            <!-- /.box-body -->
        <!-- /.col -->
      </div>
  </div>
                        
</div>
</section><!-- /.content -->
</div>

<script type="text/javascript" src="<?=base_admin();?>assets/plugins/clipboard/clipboard.min.js"></script>
<script type="text/javascript">
  $(".batalkan").click(function(){
    $("#loadnya").show();
        $.ajax({
        type : "post",
        url : "<?=base_admin();?>modul/tagihan_kuliah/tagihan_kuliah_action.php?act=batal_va",
        success : function(data) {
            $("#loadnya").hide();
            console.log(data);
            location.reload();
        }
    });

    });
var clipboard = new ClipboardJS('.salin-va');
var clipboard2 = new ClipboardJS('.salin');
var clipboard2 = new ClipboardJS('.btn-copy');
$(document).ready(function(){
$('.btn-copy').tooltip({
    animated: 'fade',
    placement: 'bottom',
    trigger: 'click'
});
$('.btn-copy').on('mouseleave', function () {
      $(this).tooltip('hide');
});

$('.salin-va').tooltip({
    animated: 'fade',
    placement: 'bottom',
    trigger: 'click'
});
$('.salin-va').on('mouseleave', function () {
      $(this).tooltip('hide');
});
$('.salin').tooltip({
    animated: 'fade',
    placement: 'bottom',
    trigger: 'click'
});
$('.salin').on('mouseleave', function () {
      $(this).tooltip('hide');
});
});

</script>

