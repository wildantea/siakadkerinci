                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                       Proses Drive
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>download-data-dosen">Download </a></li>
                        <li class="active">Download Data Dosen</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                             
                                <div class="box-body table-responsive" style="overflow:visible;">

<div class="box box-danger" >
            <div class="box-header with-border">
              <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body" >
           <form class="form-horizontal"  action="<?=base_admin();?>modul/download_data_dosen/download_data_dosen_file.php">
                      <div class="form-group">
                        <label for="Semester" class="control-label col-lg-2">Type User</label>
                        <div class="col-lg-5" >
                        <select id="type_user" name="type_user" data-placeholder="Pilih Semester ..." class="form-control chzn-select" tabindex="2" style="z-index: 1" required="">
                        <option value="3">Mahasiswa</option>
                        <option value="4">Dosen</option>
              </select>
              <div id="error_sem"></div>

 </div>
                      </div><!-- /.form-group -->
                      
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                        <span class="btn btn-primary" id="down_kelas_now"><i class="fa fa-recycle"></i> Proses Upload to Drive</span>
                        </div>
                      </div>

                    </form>
            </div>
            <!-- /.box-body -->
          </div>

            <div class="row" id="show_progress" style="display: none">
   <div class="col-md-11">
    Mohon Tunggu sedang Generate File <img src="<?=base_admin();?>assets/dist/img/squares.gif">
    </div>
               <div class="col-md-11">
                        <div class="progress">
                          <div class="progress-bar progress-bar-striped" id="progressbar" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                            10%
                          </div>

                        </div></div><div class='col-md-1' id="message"><span class='current-count'>1</span>/<span class="total-count">13 Kelas</span></div></div>
                      <div class="alert alert-danger alert-dismissible" id="ada_error" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Error! , Contact saya ke wildannudin@gmail.com, screenshoot, kasih tahu saya error dibawah</h4><span class="isi_error"></span>
              </div>
              

                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
 
                </section><!-- /.content -->
        <script type="text/javascript">
$(document).ready(function() {


$("#down_kelas_now").click(function(){
          $("#isi_drop_up").hide();
          $("#isi_drop").hide();
          total();
});


});


function millisToMinutesAndSeconds(s) {
  var ms = s % 1000;
  s = (s - ms) / 1000;
  var secs = s % 60;
  s = (s - secs) / 60;
  var mins = s % 60;
  var hrs = (s - mins) / 60;

  return (hrs < 1 ? '' : hrs+' Jam : ') + (mins < 1 ? '' : mins+' Menit : ') + secs + ' detik';
}

 function proses(percent){
   $("#progressbar").css("width",percent+"%");
      $("#progressbar").html(percent+"%");
/*    if(percent>10){
      
     
      } else {
        $("#progressbar").css("width",10+"%");
        $("#progressbar").html(10+"%");
      }*/
    } 

function total() {
          $("#show_progress").show();
          $("#down_krs_now").attr("disabled", true);
          var start_time = new Date().getTime();
          var totaldata;
          var datas = {
          type_user : $("#type_user").val()
        }
       
             $.ajax({
              url: "<?=base_admin();?>modul/download_data_dosen/get_jumlah.php",
              type : "post",
              dataType: 'json',
              //'async':false,
              data : datas,
              success: function(data) {
                  totaldata = data.jumlah;
                  total_data = parseInt(totaldata);
                  var bagi = Math.ceil(total_data/5);
                    
                    getData(bagi,total_data,start_time);

              }
            });

    //return totaldata;
}



   
    var counters = 0;
    var persen = 0;
    var progress=1;
    var all_id = "";
    var error_msg = "";
    var token = "";
    var last = "";

    var data_recs = [];
    var random_number = Math.floor(1000 + Math.random() * 9000);
   
   
    
   
    
window.getData=function(bagi,total_data,start_time)
{
    var start = start_time;


     
 
    if ((bagi*1)==progress) {
      data = {
          type_user : $("#type_user").val(),
          id_data : all_id,
          error_msg : error_msg,
          total_data : total_data,
          random_number : random_number,
          offset : counters,
          token : token,
          data_rec : data_recs,
          last : 'yes'
          }
    } else {
      data = {
             type_user : $("#type_user").val(),
            total_data : total_data,
            random_number : random_number,
            offset : counters,
            token : token,
            //data_rec : data_rec,
            last : 'no'
          }
    }


    $.ajax({
        /* The whisperingforest.org URL is not longer valid, I found a new one that is similar... */
        url: "<?=base_admin();?>modul/download_data_dosen/download_data_dosen_file.php",
        //async:false,
        data : data,
        type : "post",
        dataType: 'json',
        success:function(data){
          $.each(data, function(index) {
            persen = ((progress/total_data)*100).toFixed(1);
            if (persen>100) {
              persen=100+ "%";
              progress = total_data;
            } else {
              persen=persen+ "%";
              progress = progress;
            }

            data_recs.push(data[index].data_rec);

           //console.log(data[index].limit);

            //data_rec.push(data[index].data_rec);
           
            $(".current-count").html(progress);
            $(".total-count").html(total_data);
            persen = parseInt(persen);
            proses(persen);
              
              counters+=1;
              progress+=1;
              token = data[index].token;

              //console.log(data[index].offset);
               if (counters < total_data) {
                  getData(bagi,total_data,start);
                } else {
                  var end_time = new Date().getTime();
                  //waktu = "Total Waktu Generate : "+millisToMinutesAndSeconds(end_time-start);
                  alert('Generate File Download Selesai');
                  $("#ok_info_download").attr("data-uri",data[index].data_uri);
                  $("#ok_info_download").attr("data-file",data[index].json_file);
                  $("#isi_informasi_download").html(data[index].last_notif);
                  $('#informasi_download').modal('show');
                  //console.log('done');
                } 
              });

        },
      error: function (xhr, ajaxOptions, thrownError) {
        alert('oops ada error');
        $("#loadnya").hide();
         $("#ada_error").show();
        $(".isi_error").html(xhr.responseText);
        
        }

    });
}
</script>  
            