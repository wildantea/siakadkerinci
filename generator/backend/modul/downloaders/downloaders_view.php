<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Downloaders
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>downloaders">Downloaders</a></li>
                        <li class="active">Downloaders List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                               <button class="btn btn-primary btn-flat download-data-download"><i class="fa fa-cloud-download"></i> Download Data</button>
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">


                            </div>

<!-- button 2. place inside box body -->
<!--progress block -->
<div class="row" id="show_progress" style="display: none">
    <div class="col-md-11">
        <div class="progress">
            <div class="progress-bar progress-bar-striped" id="progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 1%"> 1% </div>
        </div>
    </div>
    <div class="col-md-1" id="message"> <span class="current-count">1</span>/<span class="total-count">13</span> </div>
</div>
<div class="alert alert-danger alert-dismissible" id="ada_error" style="display: none">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4>
    <i class="icon fa fa-ban"></i> Error!
  </h4>
  <span class="isi_error"></span>
</div>
<!--end progress block -->
<!--start filter block -->
<div id="download-filter-download" style="display:none">
  <form class="form-horizontal">
    
        <div class="form-group">
          <label for="Jurusan" class="control-label col-lg-1" style="text-align:right;">Angkatan</label>
          <div class="col-lg-3">
            <select id="mulai_smt_download" name="mulai_smt_download" data-placeholder="Pilih Jurusan ..." class="form-control chzn-select" tabindex="2">
              <option value="20221">20221</option>
            </select>
          </div>
        </div>
    
    <!-- /.form-group -->
    <div class="form-group">
      <label for="Jurusan" class="control-label col-lg-1">&nbsp;</label>
      <div class="col-lg-3">
        <span class="btn btn-success btn-flat download-data-download-now">
          <i class="fa fa-cloud-download"></i> Download Data </span>
      </div>
    </div>
  </form>
</div>
<!--end filter block -->

                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div>
              </div>
 
    </section><!-- /.content -->

        <script type="text/javascript">
      

//place this in js script
function millisToMinutesAndSeconds(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  var hrs = (s - mins) / 60;

  return (hrs < 1 ? "" : hrs+" Jam : ") + (mins < 1 ? "" : mins+" Menit : ") + secs + " detik";
}

function proses(percent){
    $("#progressbar").css("width",percent+"%");
    $("#progressbar").html(percent+"%");
} 


$(".download-data-download").on("click", function() {
  if ($("#download-filter-download").is(":visible")){
    $("#download-filter-download").hide();
  } else {
    $("#download-filter-download").show();
  }
});

$(".download-data-download-now").on("click", function() {
    total_down_download();
});


function total_down_download() {
    $("#show_progress").show();
    var totaldata;
    var start_time = new Date().getTime();
        
    var datas = {
        mulai_smt : $("#mulai_smt_download").val()
    }

     $.ajax({
      url: "<?=base_admin();?>modul/downloaders/stream.php?ask=jumlah",
      type : "post",
      dataType: "json",
      "async":false,
      data : datas,
      success: function(data) {
          totaldata = data.jumlah;
          total_data = parseInt(totaldata);
          var bagi = Math.ceil(total_data/5);
          getDataDowndownload(bagi,total_data,start_time);
      }
    });

    return totaldata;
}


    var counters = 0;
    var persen = 0;
    var progress_down=5;
    var last = "";
    
window.getDataDowndownload=function(bagi,total_data,start_time)
{

    console.log(total_data);
    var start = start_time;
    if ((bagi*5)==progress_down) {
        data = {
            offset : counters,
            total_data : total_data,
            mulai_smt : $("#mulai_smt_download").val(),
            last : "yes"
        }
    } else {
            data = {
            offset : counters,
            total_data : total_data,
            mulai_smt : $("#mulai_smt_download").val(),
            last : "no"
        }
    }




    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/downloaders/stream.php",
        //async:false,
        data : data,
        type : "post",
        dataType: "json",
        success:function(data){
          $.each(data, function(index) {
            persen = ((progress_down/total_data)*100).toFixed(1);
            if (persen>100) {
              persen=100+ "%";
              progress_down = total_data;
            } else {
              persen=persen+ "%";
              progress_down = progress_down;
            }

            //data_rec.push(data[index].data_rec);
           
            $(".current-count").html(progress_down);
            $(".total-count").html(total_data);
            persen = parseInt(persen);
            proses(persen);
              
              counters+=5;
              progress_down+=5;
            
              //console.log(data[index].offset);
               if (counters < total_data) {
                  getDataDowndownload(bagi,total_data,start);
                } else {
                 $("#loadnya").hide();
                  var end_time = new Date().getTime();
                  waktu = "Total Waktu Upload : "+millisToMinutesAndSeconds(end_time-start);
                  alert("Download Data download Selesai");
                  $("#isi_informasi_download").html(data[index].last_notif.concat(waktu));
                  $("#informasi_download").modal("show");
                  //console.log("done");
                } 
              });

        },
      error: function (xhr, ajaxOptions, thrownError) {
        alert("oops ada error");
        $("#loadnya").hide();
         $("#ada_error").show();
        $(".isi_error").html(xhr.responseText);
        
        }

    });
}
   
  
</script>
            