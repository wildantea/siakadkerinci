<style type="text/css">
  .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-left-color: #3c8dbc;
    border-right-color: #3c8dbc;
  }
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pendaftaran Proposal
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pendaftaran-proposal">Pendaftaran Proposal</a></li>
                        <li class="active">Pendaftaran Proposal List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#peserta" data-toggle="tab" aria-expanded="true"><i class="fa fa-user"></i> Peserta Pendaftaran</a></li>
              <li><a href="#penguji" data-toggle="tab"><i class="fa fa-gear"></i> Pengaturan Penguji</a></li>
              <li><a href="#rekap" data-toggle="tab"><i class="fa fa-bar-chart-o"></i>Rekap Penguji</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="peserta">
                <?php
                include "peserta.php";
                ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="penguji">
                <?php
                include "penguji_view.php";
                ?>
              </div>
              <div class="tab-pane" id="rekap">
                 <?php
                include "rekap_penguji_view.php";
                ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
                 
                </div>
              </div>
    </section><!-- /.content -->

        <script type="text/javascript">
$(document).ready(() => {
  let url = location.href.replace(/\/$/, "");
 
  if (location.hash) {
    const hash = url.split("#");
    $('#myTab a[href="#'+hash[1]+'"]').tab("show");
    url = location.href.replace(/\/#/, "#");
    history.replaceState(null, null, url);
    setTimeout(() => {
      $(window).scrollTop(0);
    }, 400);
  } 
   
  $('a[data-toggle="tab"]').on("click", function() {
    let newUrl;
    const hash = $(this).attr("href");
    if(hash == "#peserta") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    newUrl += "/";
    history.replaceState(null, null, newUrl);
  });
});  
</script>
            