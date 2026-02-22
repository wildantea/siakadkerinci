<?php
session_start();
include "../../inc/config.php";
session_check_json();

$nim=$_POST['nim'];
//periode bayar semester
$periode_bayar_semester=$db2->query("select periode,angkatan as nama_periode_bayar,nim from keu_tagihan_mahasiswa 
inner join view_semester on periode=id_semester
WHERE nim='$nim' group by periode order by periode desc");
?>
    <i class="btn-info" style="float:right;">* Silahkan centang item yang akan di bayarkan</i>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="text-align:center">Jenis Tagihan</th>
               <th style="text-align:center">Syarat KRS</th>
                <th style="text-align:center">Tagihan</th>
                <th style="text-align:center">Potongan</th>
                <th style="text-align:center">Sudah Bayar</th>
                <th style="text-align:center">Tunggakan</th>
                <th style="text-align:center">Nominal Bayar</th>
                <th style="width: 50px"></th>
                <th style="width: 50px"></th>
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
                        <?=$periode_bayar->nama_periode_bayar;?> - Semester <?=getSemesterMahasiswa($periode_bayar->nim,$periode_bayar->periode);
                        ?>
                    </td>
                </tr>
                <?php
	//detail tagihan dan detail pembayaran mahasiswa
	$detail_tagihan_mhs = $db2->query("SELECT vsm.mhs_id,vsm.jur_kode as kode_jur, ktm.periode, ktm.id as id_keu_tagihan_mhs,ktt.nama_tagihan,kt.nominal_tagihan,vsm.nim,vsm.nama,ktt.syarat_krs,potongan
from keu_tagihan_mahasiswa ktm
INNER JOIN mahasiswa vsm USING(nim)
INNER JOIN keu_tagihan kt on ktm.id_tagihan_prodi=kt.id
INNER JOIN keu_jenis_tagihan ktt USING(kode_tagihan)
		WHERE ktm.nim='$nim' AND ktm.periode='$periode_bayar->periode'");
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
        $jumlah_cicilan=$db2->fetchCustomSingle("SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs' and is_removed='0'");
        // echo "SELECT SUM(nominal_bayar) AS jml FROM keu_bayar_mahasiswa WHERE id_keu_tagihan_mhs='$detail_tagihan->id_keu_tagihan_mhs' and is_removed='0' <br>";
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


		if ($tunggakan!=0) {
			?>
                    <tr>
                        <td style="vertical-align: middle;">
                            <?=$detail_tagihan->nama_tagihan;?>
                        </td>
                        <td style="vertical-align: middle;text-align: center;">
                            <?php
                            if ($detail_tagihan->syarat_krs==1) {
                                echo "<span class='btn btn-info btn-xs'>Yes</span>";
                            } else {
                                echo "<span class='btn btn-warning btn-xs'>No</span>";
                            }
                            ?>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($nominal_tagihan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($potongan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($jml_dibayarkan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($tunggakan);?></span>
                        </td>
                        <td style='text-align:right;padding:4px'>
                            <input type="text" name="nominal_tagihan[<?=$detail_tagihan->id_keu_tagihan_mhs;?>]" data-payment-value='<?=$tunggakan;?>' class="form-control text-right nominal-tagihan item-tagihan-<?=$detail_tagihan->id_keu_tagihan_mhs;?>" data-a-sep="." data-a-dec=",">
                        </td>
                        <td style="vertical-align: middle;">
                            <label class='mt-checkbox mt-checkbox-single mt-checkbox-outline '>
                                <input type='checkbox' data-payment-id='<?=$detail_tagihan->id_keu_tagihan_mhs;?>' data-payment-value='<?=$tunggakan;?>' class='group-checkable check-tagihan'><span></span>
                            </label>
                        </td>
                        <td style="vertical-align: middle;">
                           <a style='cursor:pointer' data-toggle="tooltip" data-title="Lihat History Pembayar/cicilan" data-placement="left" class='btn btn-success' onclick='showDetilCicilan(<?=$detail_tagihan->id_keu_tagihan_mhs;?>)'><span id='btn-history-<?=$detail_tagihan->id_keu_tagihan_mhs;?>' ><i class="fa  fa-hourglass-end"></i> </span></a>
                        </td>
                    </tr>
                    <?php
                    $has_tagihan++;
		} else {
        ?>
                    <tr style="background:#b8ead3;">
                        <td style="vertical-align: middle;">
                            <?=$detail_tagihan->nama_tagihan;?>
                        </td>
                        <td style="vertical-align: middle;text-align: center;">
                            <?php
                            if ($detail_tagihan->syarat_krs==1) {
                                echo "<span class='btn btn-info btn-xs'>Yes</span>";
                            } else {
                                echo "<span class='btn btn-warning btn-xs'>No</span>";
                            }
                            ?>
                        </td>
                           <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($nominal_tagihan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($potongan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($jml_dibayarkan);?></span>
                        </td>
                        <td style='text-align:right;vertical-align: middle;'>
                            <span style='float:right'><?=rupiah($tunggakan);?></span>
                        </td>
                        <td style="vertical-align: middle;">
                            Tagihan ini Sudah Lunas
                        </td>
                        <td style="vertical-align: middle;" colspan="2">
                           <a style='cursor:pointer' class='btn btn-success' data-toggle="tooltip" data-title="Lihat History Pembayar/cicilan" data-placement="left" onclick='showDetilCicilan(<?=$detail_tagihan->id_keu_tagihan_mhs;?>)'><span id='btn-history-<?=$detail_tagihan->id_keu_tagihan_mhs;?>'><i class="fa  fa-hourglass-end"></i> </span></a>
                        </td>
                    </tr>
                    <?php
        }

        $jml_sudah = 0;
        $nominal_tagihan = 0;

		?>

                        <?php

}

//end first foreach
}

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
                            <span style='float:right;font-weight:bold'><?=rupiah($total_dibayar);?></span>
                        </td>
                        <td style='vertical-align: middle;'>
                            <span style='float:left;font-weight:bold'>Rp. </span> 
                            <span style='float:right;font-weight:bold'><?=rupiah($total_tunggakan);?></span>
                        </td>
<?php
if ($has_tagihan==0) {
    ?>
<td style="vertical-align: middle;">
                            Semua Tagihan Sudah Lunas
                        </td>
<?php
} else {
?>
 <td style="padding:4px;font-weight: bold">
<div class="input-group">
                <span class="input-group-addon">Rp.</span>
                <input id="total_tagihan" type="text" name="total_tagihan" data-a-sep="." data-a-dec="," class="form-control text-right total-tagihan" readonly="">
              </div>
                                </td>
<?php
}
?>
                                <td colspan="2">&nbsp;</td>
                            </tr>

<?php

if ($has_tagihan==0) {
    ?>
                            <tr>
                                <td colspan="9" class="text-center lead">Tidak Ada Tagihan</td>
                            </tr>
<?php
exit();
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
<!--                             <tr>
                                <td colspan="5" class="text-right lead">Total Bayar</td>
                                <td style="padding:4px;font-weight: bold">
                                    <input id="total_bayar" type="text" name="total_bayar" data-a-sep="." data-a-dec="," class="form-control text-right total-bayar">
                                </td>
                                <td style="vertical-align: middle;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right error-bayar"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right lead">Saldo Pembayaran</td>
                                <td style="padding:4px;font-weight: bold">
                                    <input id="saldo_pembayaran" type="text" name="saldo_pembayaran" data-a-sep="." data-a-dec="," class="form-control text-right saldo-pembayaran" readonly="">
                                </td>
                                <td style="vertical-align: middle;">
                                </td>
                            </tr> -->
        </tbody>
    </table>
    <div class="form-group">
        <label for="nama" class="control-label col-lg-2">Metode Pembayaran</label>
        <div class="col-lg-3">
            <select id="metode_bayar" name="metode_bayar" data-placeholder="Metode Pembayaran..." class="form-control chzn-select" tabindex="2" required="">
                <option value="">Pilih Metode Pembayaran</option>
                <option value="1">CASH</option>
                <option value="2">TRANSFER BANK</option>
            </select>
        </div>
    </div>
    <div class="form-group transfer" style="display: none">
        <label class="control-label col-lg-2">Tanggal Bayar Ke Bank</label>
        <div class="col-lg-3">
            <div class='input-group date tgl_picker' data-date-end-date="0d">
                <input type='text' id="tgl_bayar" autocomplete="false" class="form-control tgl_picker_input" type="date" name="tgl_bayar"  pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"  readonly/>
                <span class="input-group-addon">
                          <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group transfer" style="display: none">
        <label class="control-label col-lg-2">Bank</label>
        <div class="col-lg-3">
            <select id="bank" name="bank" data-placeholder="Pilih Bank ..." class="form-control chzn-select" tabindex="2">
                <option value="">Pilih Bank</option>
                <?php 
                      $bank = $db2->query("select * from keu_bank where aktif='Y'");
                      foreach ($bank as $bk) {
                        echo "<option value='$bk->kode_bank'>$bk->nama_bank - $bk->nomor_rekening</option>";
                      } ?>
            </select>
            <i style="color:red;display: none" id="bank_kosong">Silahkan Pilih bank</i>
        </div>
    </div>
          <div class="form-group">
              <label for="keterangan" class="control-label col-lg-2">Keterangan </label>
              <div class="col-lg-10">
                <textarea class="form-control col-xs-12" rows="5" name="keterangan" ></textarea>
              </div>
          </div><!-- /.form-group -->
                  <div class="form-group">
              <label for="nama" class="control-label col-lg-2">&nbsp;</label>
                <div class="col-lg-8" style="display: none" id="btn_cetak">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-money"> Proses Pembayaran</i></button>
                 <!--  <a style="cursor: pointer" onclick="cetak_tagihan()" class="btn btn-success"><i class="fa fa-print"></i> Cetak Tagihan</a> -->
                </div>
              </div><!-- /.form-group -->
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
                $(".total-tagihan").addClass('bg-light-blue disabled color-palette');
                $(".total-tagihan").val(convertToRupiah(total));
            } else {
                $(".total-tagihan").removeClass('bg-light-blue disabled color-palette');
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
                todayHighlight: true,
                clearBtn : true,
                language : 'id'
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