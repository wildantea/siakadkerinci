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
                if ($bank->kode_bank=='03') {
                  $nim_replace = $check_va->no_va;
                  $replace = "882621209705001";
                  $bank_lain = "008882621209705001";
                }
                $panduans = $db->query("select * from panduan_pembayaran where id_bank='$bank->kode_bank' order by urutan asc");
                $iteration = 0;
                foreach ($panduans as $panduan) {
                    $class = "";
                    if ($iteration==0) {
                        $class = "in";
                    }
                    $isi_panduan = $panduan->isi_panduan;
                    if ($panduan->id_bank=='03') {
                        $to_replace = [rupiah($check_va->total_nominal), $nim_replace,'008'.$nim_replace];
                        $replacer   = ["5000", $replace,$bank_lain];
                        $isi_panduan = str_replace($replacer, $to_replace, $panduan->isi_panduan);
                    }
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
$(document).ready(function(){
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

