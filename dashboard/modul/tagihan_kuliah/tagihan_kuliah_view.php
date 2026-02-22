<!-- Content Header (Page header) -->
 <style type="text/css"> .datepicker {z-index: 1200 !important; }
.modal-dialog {
  width: 90%;
  min-height: 40%;
  margin: auto auto;
  padding: 0;
}

.modal-content {
  height: auto;
  min-height: 90%;
  border-radius: 0;
}
.table-bordered>tbody>tr>td {
      border: 1px solid #ddd;
}

.radio label::before {
    top: 20px;
    display: none;
}
.radio label::after {
    top: 23px;
    display: none;
}
input[type="radio"] {
  display: none;
  &:not(:disabled) ~ label {
    cursor: pointer;
  }
  &:disabled ~ label {
    color: hsla(150, 5%, 75%, 1);
    border-color: hsla(150, 5%, 75%, 1);
    box-shadow: none;
    cursor: not-allowed;
  }
}
input[type="radio"] + label {
  height: 100%;
  display: block;
  background: white;
  border: 1px solid hsl(202deg 51% 49%);
  border-radius: 10px;
  margin-bottom: 1rem;
  padding: 10px;
  text-align: center;
  box-shadow: 0px 3px 10px -2px hsla(150, 5%, 65%, 0.5);
  position: relative;
}
input[type="radio"]:checked + label {
  background: hsl(190deg 81% 59%);
  color: hsla(215, 0%, 100%, 1);
  box-shadow: 0px 0px 20px hsl(193deg 65% 67%);
  &::after {
    color: hsla(215, 5%, 25%, 1);
    font-family: FontAwesome;
    content: "\f00c";
    font-size: 24px;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    height: 80px;
    width: 80px;
    line-height: 80px;
    text-align: center;
    border-radius: 50%;
    background: white;
    box-shadow: 0px 2px 5px -2px hsla(0, 0%, 0%, 0.25);
  }
}
input[type="radio"]#control_05:checked + label {
  background: red;
  border-color: red;
}
p {
  font-weight: 900;
}


@media only screen and (max-width: 700px) {
  section {
    flex-direction: column;
  }
}

</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Tagihan Kuliah
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>tagihan-kuliah">Tagihan Kuliah</a></li>
                        <li class="active">Tagihan Kuliah List</li>
                    </ol>
                </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title">Tagihan Biaya Kuliah</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-info btn-sm"><i class="fa fa-money"></i></button>
            </div>
          </div>
          <div class="box-body clear-end">
 <div class="alert alert-danger error_data" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <span class="isi_warning"></span>
        </div>
<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Keterangan</h4>
                Halaman ini berisi informasi tagihan pembayaran. Lakukan centang item pembayaran yang dibayarkan. Tagihan bisa dibayar/aktif jika bisa dicentang. 
              </div>
<form id="input_mahasiswa" autocomplete="off" method="post" class="form-horizontal foto_banyak" action="<?=base_admin();?>modul/tagihan_kuliah/tagihan_kuliah_action.php?act=in">
                      
    <div class="form-group att-tambahan" style="display: none">
          <label for="nama" class="control-label col-lg-2">&nbsp;</label>
          <div class="col-lg-4">
            <input type="text" id="nama" name="nama" class="form-control"  readonly>
          </div>
          <div class="col-lg-4">
            <input type="text" id="jurusan" name="jurusan" class="form-control" readonly>
          </div>
        </div>

    
                  <div class="form-group" style="margin-bottom: 0;">
                       
                        <div class="col-lg-12 table-responsive" id="detail_tagihan">
<?php
$id_pasca = array(
35,40
);
  //get nama
$mhs = $db->fetch_single_row('view_simple_mhs_data','nim',$_SESSION['username']);
$jenjang = $db->fetch_single_row('jurusan','kode_jur',$mhs->jur_kode);
$nim= $_SESSION['username'];
//$nim = '1210705092'; 
//periode bayar semester
// $periode_bayar_semester=$db2->query("select periode,angkatan as nama_periode_bayar from keu_tagihan_mahasiswa 
// inner join view_semester on periode=id_semester
// WHERE nim='$nim' and now() between keu_tagihan_mahasiswa.tanggal_awal and keu_tagihan_mahasiswa.tanggal_akhir 
// and id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id is not null)
// group by periode order by periode desc");
//get periode aktif pembayaran
$periode_aktif = $db->fetch_single_row("periode_pembayaran","is_active","Y");
$periode_bayar_semester=$db2->query("select periode,angkatan as nama_periode_bayar from keu_tagihan_mahasiswa 
inner join view_semester on periode=id_semester
WHERE nim='$nim'
 
and id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id is not null)
group by periode order by periode desc"); 


// echo "select periode,angkatan as nama_periode_bayar from keu_tagihan_mahasiswa 
// inner join view_semester on periode=id_semester
// WHERE nim='$nim' and periode='$periode_aktif->periode_bayar'
// and id not in(select id_keu_tagihan_mhs from keu_bayar_mahasiswa where id is not null)
// group by periode order by periode desc";
?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 50px">#</th>
                <th style="text-align:center">Jenis Tagihan</th>
                <th style="text-align:center">Tagihan</th>
                <th style="text-align:center">Potongan</th>
                <th style="text-align:center">Tunggakan</th>
                <th style="text-align:center">Keterangan</th>
                
            </tr>
        </thead>
        <tbody id="isi_body">
            <?php
$total_tagihan=0;
$total_potongan = 0;
$total_dibayar=0;
$total_tunggakan=0;
$has_tagihan = 0;

foreach ($periode_bayar_semester as $periode_bayar) {
    ?>

                <tr>
                    <td colspan='6' style="vertical-align: middle;font-weight: bold;" class="lead">
                        <?=ganjil_genap($periode_bayar->periode);?> 
                         - Semester <?=getSemesterMahasiswa($nim,$periode_bayar->periode);?>
                    </td>
                </tr>
                <?php
    //detail tagihan dan detail pembayaran mahasiswa
//                 echo "SELECT vsm.mhs_id,vsm.jur_kode as kode_jur, ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan
// from keu_tagihan_mahasiswa ktm
// INNER JOIN mahasiswa vsm USING(nim)
// INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
// INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
//         WHERE ktm.nim='$nim' AND ktm.periode='$periode_bayar->periode'";

// echo "SELECT vsm.mhs_id,ktm.tanggal_awal,ktm.tanggal_akhir,vsm.jur_kode as kode_jur, ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan
// from keu_tagihan_mahasiswa ktm
// INNER JOIN mahasiswa vsm USING(nim)
// INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
// INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
//         WHERE ktm.nim='$nim'  
// and kt.nominal_tagihan-potongan>0 AND ktm.periode='$periode_bayar->periode'";  

      $detail_tagihan_mhs = $db2->query("SELECT vsm.mhs_id,pm.tgl_mulai as tanggal_awal,pm.tgl_selesai as tanggal_akhir,vsm.jur_kode as kode_jur, ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan from 
keu_tagihan_mahasiswa ktm INNER JOIN mahasiswa vsm USING(nim) INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
join periode_pembayaran pm on pm.periode_bayar=ktm.periode
 WHERE ktm.nim='$nim'  
and kt.nominal_tagihan-potongan>0 AND ktm.periode='$periode_bayar->periode'  and now() between ktm.tanggal_awal and ktm.tanggal_akhir");
    //  print_r($detail_tagihan_mhs); 
    foreach ($detail_tagihan_mhs as $detail_tagihan) {  



        //if ($detail_tagihan->syarat_krs==1) {

        //nominal tagihan per id tagihan mahasiswa
        $nominal_tagihan = $detail_tagihan->nominal_tagihan;

        //potongan
        $potongan = $detail_tagihan->potongan;

        //nominal setelah potongan
        $nominal_tagihan_akhir = $nominal_tagihan-$potongan;

        //jumlah sudah dibayar
        //get jumlah cicilan pembayaran
        $jml_dibayarkan=0;
        $jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs'");
        if ($jumlah_cicilan) {
            $jml_dibayarkan=$jumlah_cicilan->jml;
        }

      

        //tunggakan/sisa yang belum dibayar
        $tunggakan = $nominal_tagihan_akhir-$jml_dibayarkan;

        //total_tagihan
        $total_tagihan+=$nominal_tagihan;
        //total potongan
        $total_potongan+=$potongan;
        //total sudah dibayarkan
        $total_dibayar+=$jml_dibayarkan;
        //total tunggakan
        $total_tunggakan+=$tunggakan;
        // echo "SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs'";

        if ($tunggakan!=0) {
                echo '<tr>';
              /*  if (strtotime(date('Y-m-d H:i:s')) >= strtotime($detail_tagihan->tanggal_awal." 00:00:00") && strtotime(date('Y-m-d H:i:s')) <= strtotime($detail_tagihan->tanggal_akhir." 23:59:59")) { */
                    ?>
                        <td style="vertical-align: middle;">
                            <label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '>
                                <input type='checkbox' data-payment-id='<?=$detail_tagihan->id_keu_tagihan_mhs;?>' data-payment-value='<?=$tunggakan;?>' class='group-checkable check-tagihan'><span></span>
                            </label>
                        </td>
                    <?php
            /*    } else {
                    ?>
                    <td>
                        &nbsp;
                    </td>
                    <?php
                }*/
            ?>
                        <td style="vertical-align: middle;">
                            <?=$detail_tagihan->nama_tagihan;?>
                            <input type="hidden" name="nama_tagihan[]" value="<?= $detail_tagihan->nama_tagihan ?>">
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($nominal_tagihan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($potongan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($tunggakan);?></span>
                        </td>
                        <td style='text-align:right;padding:4px'>
                            <input type="hidden" name="nominal_tagihan[<?=$detail_tagihan->id_keu_tagihan_mhs;?>]" data-payment-value='<?=$tunggakan;?>' class="form-control text-right nominal-tagihan item-tagihan-<?=$detail_tagihan->id_keu_tagihan_mhs;?>" data-a-sep="." data-a-dec=",">
                        </td>

                    </tr>
                    <?php
                    $has_tagihan++;
        } 


        $jml_sudah = 0;
        $nominal_tagihan = 0;

        ?>

                        <?php

}

//end first foreach
}
//echo "$has_tagihan";

if ($has_tagihan!=0) {
?>
                            <tr>
                                <td colspan="2" class="lead">Total</td>
                        <td style='vertical-align: middle;'>
                            <span style='float:left;font-weight:bold'>Rp. </span> 
                            <span style='float:right;font-weight:bold'><?=rupiah($total_tagihan);?></span>
                        </td>
                        <td style='vertical-align: middle;'>
                            <span style='float:left;font-weight:bold'>Rp. </span> 
                            <span style='float:right;font-weight:bold'><?=rupiah($total_potongan);?></span>
                        </td>
                        <td style='vertical-align: middle;'>
                            <span style='float:left;font-weight:bold'>Rp. </span> 
                            <span style='float:right;font-weight:bold'><?=rupiah($total_tunggakan);?></span>
                        </td>
<?php

    ?>
                                
                            </tr>

<?php
}

if ($has_tagihan==0) {
    ?>
                            <tr>
                                <td colspan="9" class="text-center lead">Tidak Ada Tagihan</td>
                            </tr>
<?php
}
?>
                            <input type="hidden" name="mhs_id" value="<?=$detail_tagihan->mhs_id;?>">
                            <input type="hidden" name="kode_jur" value="<?=$detail_tagihan->kode_jur;?>">
<?php
//check saldo deposit
/*$saldo = $db2->fetchCustomSingle("select sum(pemasukan)-sum(pengeluaran) as saldo from keu_saldo_potongan where nim=?",array('nim' => $nim));*/
$saldo = 0;
if ($saldo) {
    //if ($saldo->saldo>0) {
        if ($saldo>0) {
        ?>
                            <tr>
                                <td colspan="4" class="text-right lead">Saldo Deposit</td>
                                <td class="text-right lead"><input type="hidden" id="saldo_awal" name="saldo_awal" value="<?=$saldo->saldo;?>"><?=rupiah($saldo->saldo);?></td>
                                <td style="padding:4px;font-weight: bold">
                                    <input id="saldo_deposit" type="text" name="saldo_deposit" data-a-sep="." data-a-dec="," class="form-control text-right saldo-deposit" value="0">
                                </td>
                                <td style="vertical-align: middle;">
                                    <label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '>
                                        <input type='checkbox' class='group-checkable use-saldo-deposit'><span></span>
                                    </label>
                                </td>
                            </tr>
        <?php
    } else {
        ?>
 <input id="saldo_deposit" type="hidden" name="saldo_deposit" data-a-sep="." data-a-dec="," class="form-control text-right saldo-deposit" value="0">
        <?php
    }
}
?>

                            <tr>
                                <td colspan="9" class="bg-gray disabled color-palette"></td>
                            </tr>

        </tbody>
    </table>
<?php 
if ($has_tagihan!=0) {
?>
                  <div class="form-group">
              <label for="nama" class="control-label col-lg-2">Jumlah harus dibayar</label>
                <div class="col-lg-3" style="margin-left:20px">
                    <div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <b><input id="total_tagihan" type="text" name="total_tagihan" data-a-sep="." data-a-dec="," class="form-control total-tagihan" readonly=""></b>
              </div>
                </div>
              </div><!-- /.form-group -->
                <div class="form-group">
                  <label for="bank" class="control-label col-lg-2" style="margin-top: 17px;">Pilih Bank </label>
                  <div class="col-lg-10 ">

                      <?php
                      $data_bank = $db->query("select * from keu_bank where aktif='Y' and JSON_CONTAINS(peruntukan, '$jenjang->id_jenjang')");
                      $i=1;
                      foreach ($data_bank as $bank) {
                      ?>
                    <div class="radio radio-success" style="float:left;">
                      <input type="radio" name="bank"  id="radio<?=$i;?>" value="<?=$bank->kode_bank;?>" required class="bank-data">
                      <label for="radio<?=$i;?>" style="padding-left: 5px;">
                      
                       <img class="attachment-img" src="<?=base_url();?>upload/logo/<?=$bank->logo;?>" alt="Attachment Image">
                      </label>

                    </div>

              

                      <?php
                          $i++;
                      }
                      ?>
                      <div class="help-block" style="clear:both;margin-left:20px" id="bank_kosong"></div>
                </div>
                  </div><!-- /.form-group -->


                  <div class="form-group">
              <label for="nama" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-8">
                  <button type="submit" class="btn btn-primary save-data" style="margin-left: 20px;"><i class="fa fa-money"> Proses Pembayaran</i></button>
                 <!--  <a style="cursor: pointer" onclick="cetak_tagihan()" class="btn btn-success"><i class="fa fa-print"></i> Cetak Tagihan</a> -->
                </div>
              </div><!-- /.form-group -->
<?php 
}
?>
    <hr>
    <script type="text/javascript">
        $("#metode_bayar").change(function() {
            if (this.value == 2) {
                $('#tgl_bayar').prop('required', true);
                $('#bank').prop('required', true);
                $(".transfer").show();
            } else {
                $('#tgl_bayar').prop('required', false);
                $('#bank').prop('required', false);
                $(".transfer").hide();
            }
        });

        $('.nominal-tagihan').autoNumeric("init", {
            vMin: '0',
            vMax: '999999999'
        });
        $('.saldo-deposit').autoNumeric("init", {
            vMin: '0',
            vMax: '999999999'
        });
        $('.use-saldo-deposit').on('click', function() { // bulk checked
            var status = this.checked;
            if (status) {
                total_bayar = formatNumber($(".total-tagihan").val())-$("#saldo_awal").val();
                $(".saldo-deposit").val(convertToRupiah($("#saldo_awal").val()));
                $(".total-bayar").val(convertToRupiah(total_bayar));
            } else {
                $(".total-bayar").val($('.total-tagihan').val());
                $(".saldo-deposit").val(0);
            }
            hitung_tagihan();
            $(".total-bayar").valid();
        });

        $(".saldo-deposit").keyup(function() {
            if (formatNumber($(this).val()) > $("#saldo_awal").val()) {
                $(this).val(convertToRupiah($("#saldo_awal").val()));
            }
            hitung_tagihan();
            $(".total-bayar").valid();
            $('.saldo-pembayaran').val('');
        });

        $("#total_bayar").keyup(function() {
            total_bayar = formatNumber($(".total-tagihan").val())-formatNumber($(".saldo-deposit").val());
            current_value = formatNumber($(this).val());
            if (current_value > total_bayar) {
                $('.saldo-pembayaran').val(convertToRupiah(current_value-total_bayar));
            } else {
                $('.saldo-pembayaran').val('');
            }
        });



        $('.check-tagihan').on('click', function() { // bulk checked
            var status = this.checked;
            id = $(this).attr("data-payment-id");
            value = $(this).attr("data-payment-value");
            if (status) {
                $(".item-tagihan-" + id).val(convertToRupiah(value));
            } else {
                $(".item-tagihan-" + id).val('');
            }
            hitung_tagihan();
            $(".total-tagihan").valid();
        });

        $('.total-bayar').autoNumeric("init", {
            vMin: '0',
            vMax: '999999999'
        });
/*        $('.get-total-tagihan').on('click', function() { // bulk checked
            var status = this.checked;
            if (status) {
                $(".total-bayar").val(convertToRupiah(formatNumber($('.total-tagihan').val())-formatNumber($(".saldo-deposit").val())));
            } else {
                $(".total-bayar").val('');
            }
                $('.saldo-pembayaran').val('');
            hitung_tagihan();
            $(".total-bayar").valid();
        });*/

        function hitung_tagihan() {
            tagihan = $(".nominal-tagihan");
            total = 0;
            for (i = 0; i < tagihan.length; i++) {
                total += formatNumber($(tagihan[i]).val());
            }
            if (total > 0) {
                //$(".total-tagihan").addClass('bg-light-blue disabled color-palette');
                $(".total-tagihan").val(convertToRupiah(total));
            } else {
                //$(".total-tagihan").removeClass('bg-light-blue disabled color-palette');
                $(".total-tagihan").val('');
            }
            hitung_total_bayar();
        }

        function hitung_total_bayar() {
            total_bayar = formatNumber($(".total-tagihan").val())-formatNumber($(".saldo-deposit").val());
            if (total_bayar>0) {
                $(".total-bayar").val(convertToRupiah(total_bayar));
            } else {
                $(".total-bayar").val(0);
            }
        }
        function hitung_saldo_pembayaran() {

        }

        function formatNumber(number) {
            if (typeof number == 'undefined') {
                return 0;
            } else {
                if (number == '') {
                    return 0;
                } else {
                    return parseFloat(number.split('.').join(''));
                    // parseFloat(number.replace(".", ""));
                }
            }
        }

        function convertToRupiah(angka) {
            var rupiah = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for (var i = 0; i < angkarev.length; i++)
                if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
            return rupiah.split('', rupiah.length - 1).reverse().join('');
        }

        $(".nominal-tagihan").keyup(function() {
            if (formatNumber($(this).val()) > formatNumber($(this).attr("data-payment-value"))) {
                $(this).val(convertToRupiah($(this).attr("data-payment-value")));
            }
            hitung_tagihan();
        });

        $(document).ready(function() {
            $(".tgl_picker").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            }).on("change", function() {
                $(":input", this).valid();
            });
            //chosen select
            $(".chzn-select").chosen();
            $(".chzn-select-deselect").chosen({
                allow_single_deselect: true
            });

            //trigger validation onchange
            $('select').on('change', function() {
                $(this).valid();
            });
        });
    </script>
                        </div>
              </div>
                      


            </form>
          </div>

      </div>
      </div>

    </div>

    </section><!-- /.content -->
<script type="text/javascript">


  function updateJumlah(jml,id){
 /*    $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/input_pembayaran_action.php?act=get_nominal",
              type : "POST",
              data : "id_tagihan="+id,
              success: function(data) {*/
                var nominal = parseInt($("#data2-"+id).val());
                if (nominal>jml) {
                   //alert("test");
                   var jml_bayar = $("#auto").val();

                   var jumlah = jml_bayar.toString().replace('.','');

                  var jml_auto = parseInt(jumlah);

                  $("#auto").val(jml_auto+(nominal-jml));
                  $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
                  $("#data2-"+id).val(jml);
                  $("#data-"+id).val(jml);
                }else{
                  //alert("xxx");
                   var jml_bayar = $("#auto").val();

                   var jumlah = jml_bayar.toString().replace('.','');

                  var jml_auto = parseInt(jumlah);

                  var jml_tambah = parseInt(jml-nominal);
                  var jml_all = jml_auto-jml_tambah;
                //  alert(jml_tambah);
                  if (jml_all<0) {
                    alert("Nominal yang diinput melebihi uang yang dibayarkan");
                    $("#data-"+id).val(nominal);
                   // $("#data-"+id).val(0);
                  }else{
                    $("#auto").val(jml_all);
                    $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
                    $("#data2-"+id).val(jml);
                  }
                  
                }
    }
/*              }
          });
  }*/

  function cetak_tagihan() {
    var nim = $("#nim").val();
    window.open("<?= base_admin() ?>modul/input_pembayaran/cetak_tagihan.php?nim="+nim);
  }

  function set_bayar(id,jml_tagihan){
    //  alert(id);
    if($("#cek-"+id).prop('checked') == true){
      if ($("#auto").val()=='') {
         alert("isi jumlah yang akan dibayarkan");
         $("#auto").focus();
         $("#cek-"+id).prop('checked', false);
      }else{
         $("#form-"+id).html("<input type='hidden' name='ket_id-"+id+"' id='ket_id-"+id+"' value='"+id+"'> <input type='text' class='form-control' id='data-"+id+"' name='data-"+id+"' value='"+jml_tagihan+"' onchange='updateJumlah(this.value,"+id+")' > <input type='hidden' class='form-control' id='data2-"+id+"' name='jml_bayar["+id+"]'>  ");

        

       var jml_bayar = $("#auto").val();

       console.log(jml_bayar);

       var jumlah = jml_bayar.toString().replace('.','');

       console.log(jumlah);

      var jml_bayar = parseInt(jumlah);

      console.log(jml_bayar);

      //var input_jml = $("#data-"+id).val();
      //var jml_input = input_jml.toString().replace('.','');

       var jml_input = parseInt($("#data-"+id).val());


       if (jml_bayar<=jml_input) {
          $("#data-"+id).val(jml_bayar);
          $("#data2-"+id).val(jml_bayar);
          $("#auto").val(0);
       }else{
         $("#auto").val(jml_bayar-jml_input);
         $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
         $("#data2-"+id).val(jml_input);
       }
      }
     
       
    }else{
       var jml_bayar = $("#auto").val();

       var jumlah = jml_bayar.toString().replace('.','');

      var jml_bayar = parseInt(jumlah);

       var jml_input = parseInt($("#data-"+id).val());
       $("#auto").val(jml_bayar+jml_input);
       $('#auto').autoNumeric("update", { vMin: '0', vMax: '999999999'  });
       $("#form-"+id).html('');
       //$("#form-"+id).html(convertToRupiah(jml_tagihan));

    }
    
  }
    $(document).ready(function() {
         // $('#auto').autoNumeric("init",{vMin: '0', vMax: '999999999' });

            $("#import_mat").click(function() {
          $.ajax({
              url : "<?=base_admin();?>modul/input_pembayaran/import.php",
              type : "GET",
              success: function(data) {
                  $("#isi_import_mat").html(data);
              }
          });

      $('#modal_import_mat').modal({ keyboard: false,backdrop:'static',show:true });

    });


          $("#nim").autocomplete({
            source: "<?=base_admin();?>modul/input_pembayaran/search_nim.php",
            select: function( event, ui ) {
              $('.att-tambahan').fadeIn();
              $("#nama").val(ui.item.nama);
              $("#jurusan").val(ui.item.jurusan);
              $("#isi_body").html('');
              $("#jenis_pembayaran").val('').trigger("chosen:updated");
              $.ajax({
                url : "<?=base_admin();?>modul/input_pembayaran/get_detail_tagihan.php",
                type : "POST",
                data : "nim="+ui.item.value,
                success: function(data) {
                  // alert(data);
                  $("#detail_tagihan").html(data);
                  $("#btn_cetak").show();
                  $.each($('.make-switch'), function () {
                    $(this).bootstrapSwitch({
                      onText: $(this).data('onText'),
                      offText: $(this).data('offText'),
                      onColor: $(this).data('onColor'),
                      offColor: $(this).data('offColor'),
                      size: $(this).data('size'),
                      labelText: $(this).data('labelText')
                    });
                  });
                }
              });
            },
            minLength: 2
        });       
    
  
});

                   $("#periode").change(function(){
                        $("#isi_body").html('');
                        $("#jenis_pembayaran").val('').trigger("chosen:updated");
                   });

                  $("#jenis_pembayaran").change(function(){
                    if($("#nim").valid()) {
                      $.ajax({
                        type : "post",
                        url : "<?=base_admin();?>modul/input_pembayaran/get_content_pembayaran.php",
                        data : {nim:$("#nim").val(),periode:$("#periode").val(),jenis_pembayaran:this.value},
                        success : function(data) {
                            $("#isi_body").html(data);
                        }
                      });
                    }


                  });

   $(document).ready(function() {


      //hidden validate because we use chosen select
      $.validator.setDefaults({ ignore: ":hidden:not(select)" });

       $.validator.addMethod("myFunc", function(val) {
         if (formatNumber(val) < formatNumber($('.total-tagihan').val())-formatNumber($('.saldo-deposit').val())) {
            return false;
          } else {
            return true;
          }
      }, "Total bayar harus lebih besar atau sama dengan total tagihan");

  var input_bayar =  $("#input_mahasiswa").validate({
     ignore:'',
        errorClass: "help-block",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-success").addClass("has-error");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".form-group").removeClass(
                "has-error").addClass("has-success");
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("chzn-select")) {
                var id = element.attr("id");
                error.insertAfter("#" + id + "_chosen");
            } else if (element.hasClass("tgl_picker_input")) {
               element.parent().parent().append(error);
            } else if (element.hasClass("total-tagihan")) {
               element.parent().parent().parent().append(error);
            } else if (element.hasClass("input-group")) {
               element.parent().parent().append(error);
            } else if (element.attr("accept") == "image/*") {
                element.parent().parent().parent().append(error);
            } else if (element.hasClass("total-bayar")) {
              error.appendTo('.error-bayar');
            }
            else if (element.hasClass("file-upload-data")) {
               element.parent().parent().parent().append(error);
            }
             else if (element.attr("type") == "checkbox") {
                element.parent().parent().append(error);
            }  else if (element.hasClass("bank-data")) {
                //element.parent().parent().parent().append(error);
                error.appendTo('#bank_kosong');
            } else {
                error.insertAfter(element);
            }
        },
          rules: {
            
          nim: {
          required: true,
          //minlength: 2
          },
          total_bayar : {
            myFunc:true
          },
          bank : {
            required : true,
          },
        
          jenis_pembayaran: {
          required: true,
          //minlength: 2
          },
          total_tagihan: {
          required: true,
          //minlength: 2
          },
        
        },
         messages: {
            
          nim: {
          required: "Silakan Masukan NIM",
          //minlength: "Your username must consist of at least 2 characters"
          },
          bank: {
          required: "Silakan Pilih Bank",
          //minlength: "Your username must consist of at least 2 characters"
          },
          jenis_pembayaran: {
          required: "Pilih Jenis Pembayaran",
          //minlength: "Your username must consist of at least 2 characters"
          },
          total_tagihan: {
          required: "Silakan centang tagihan yang akan dibayarkan",
          //minlength: "Your username must consist of at least 2 characters"
          },
        
        },
      submitHandler: function(form) {
            $("#loadnya").show();
            $(form).ajaxSubmit({
                url : $(this).attr("action"),
                dataType: "json",
                type : "post",
                error: function(data ) { 
                  $("#loadnya").hide();
                  console.log(data); 
                  $(".isi_warning").html(data.responseText);
                  $(".error_data").focus()
                  $(".error_data").fadeIn(); 
                },
                success: function(responseText) {
                  $("#loadnya").hide();
                  console.log(responseText);
                      $.each(responseText, function(index) {
                          console.log(responseText[index].status);
                          if (responseText[index].status=="die") {
                            $("#informasi").modal("show");
                          } else if(responseText[index].status=="error") {
                             $(".isi_warning").text(responseText[index].error_message);
                             $(".error_data").focus()
                             $(".error_data").fadeIn();
                          } else if(responseText[index].status=="good") {
                            $(".save-data").attr("disabled", "disabled");
                             $(".isi_warning").text(responseText);
                             location.reload();
                          } else {
                            $(".isi_warning").text(responseText);
                          }
                    });
                }

            });
        }
    });

});
                  </script>