<style type="text/css">
.modal.fade:not(.in) .modal-dialog {
    -webkit-transform: translate3d(-25%, 0, 0);
    transform: translate3d(-25%, 0, 0);
}
.peserta-kelas {
  cursor: pointer;
}
.modal-abs {
  width: 98%;
  padding: 0;
}

.modal-content-abs {
  height: 99%;
}
  .nav-tabs-custom>.nav-tabs {
    border-bottom-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-left-color: #3c8dbc;
    border-right-color: #3c8dbc;
  }
.nav-tabs-custom>.nav-tabs>li {
  border-top: 2px solid transparent;
    margin-bottom: -1px;
}
.nav-tabs-custom>.nav-tabs>li.active>a {
    border-top-color: #3c8dbc;
}
#modal_pendaftaran {
      z-index: 1500;
}
.nav-tabs {
    border-bottom: 1px solid #ddd;
}
</style>
<!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pengaturan Pendaftaran
                    </h1>
                        <ol class="breadcrumb">
                        <li><a href="<?=base_index();?>"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>pengaturan-pendaftaran">Pengaturan Pendaftaran</a></li>
                        <li class="active">Pengaturan Pendaftaran List</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                   

                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs" id="myTab">
                             <li class="active"><a href="#pengaturan-pendaftaran" data-toggle="tab"><i class="fa fa-gear"></i> Pengaturan Pendaftaran</a></li>
                             <li ><a href="#penomoran" data-toggle="tab"><i class="fa fa-envelope"></i> Penomoran Surat</a></li>
                             <li ><a href="#headsurat" data-toggle="tab"><i class="fa fa-envelope"></i> Header Surat</a></li>
                          </ul>
                          <div class="tab-content">
                            <div class="tab-pane active" id="pengaturan-pendaftaran">
                               <?php
                                  include "pengaturan/pengaturan_view.php";
                                  ?>
                            </div>
                             <div class="tab-pane" id="penomoran">
                              <?php
                                  include "penomoran_surat/penomoran_surat_view.php";
                                  ?>
                            </div>
                            <div class="tab-pane" id="headsurat">
                              <?php
                                  include "header_surat/header_surat_view.php";
                                  ?>
                            </div>

                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div>

                </section>
<script>
//tab keep on reload
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
    if(hash == "#pengaturan-pendaftaran") {
      newUrl = url.split("#")[0];
    } else {
      newUrl = url.split("#")[0] + hash;
    }
    //newUrl += "/";
    history.replaceState(null, null, newUrl);
  });
}); 

</script>
            