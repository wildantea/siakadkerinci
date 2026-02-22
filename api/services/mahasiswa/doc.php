
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mahasiswa Rest API Documentation</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="../../../dashboard/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
     <link href="../../../dashboard/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="../../../dashboard/assets/plugins/ionic/css/ionicons.min.css" rel="stylesheet" type="text/css" />

     <link href="../../../dashboard/assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link href="../../../dashboard/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="../../../dashboard/assets/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
  .content-wrapper p{padding:0 10px;font-size:16px}#components>h3{font-size:25px;color:#000}#components>h4{font-size:20px;color:#000}ul{margin-bottom:20px}.page-header{margin:20px 0;position:relative;z-index:1;font-size:30px}.page-header a,.page-header span{z-index:5;display:block;background-color:#ecf0f5;color:#000}.page-header a::before,.page-header span::before{content:'#';font-size:25px;margin-right:10px;color:#3c8dbc}#components>h3:before,.page-header:before{display:block;content:" ";margin-top:-60px;height:60px;visibility:hidden}.lead{font-size:18px;font-weight:400}.eg{position:absolute;top:0;left:0;display:inline-block;background:#d2d6de;padding:5px;border-bottom-right-radius:3px;border-top-left-radius:3px;border-bottom:1px solid #d2d6dc;border-right:1px solid #d2d6dc}.eg+*{margin-top:30px}.content{padding:10px 25px}.hierarchy{background:#333;color:#fff}.plugins-list li{width:50%;float:left}pre{border:none}.sidebar{margin-top:0;padding-top:0!important}.box .main-header{z-index:1000;position:relative}.treeview .nav li a:active,.treeview .nav li a:hover{background:0 0}p{padding:0!important}.pln{color:#111}@media screen{.kwd,.str{color:#739200}.com{color:#999}.typ{color:#f05}.lit{color:#538192}.clo,.opn,.pun,.tag{color:#111}.atn{color:#739200}.atv{color:#f05}.dec,.var{color:#111}.fun{color:#538192}}@media print,projection{.kwd,.tag,.typ{font-weight:700}.str{color:#060}.kwd{color:#006}.com{color:#600;font-style:italic}.typ{color:#404}.lit{color:#044}.clo,.opn,.pun{color:#440}.tag{color:#006}.atn{color:#404}.atv{color:#060}}ol.linenums{margin-top:0;margin-bottom:0}
  .table {
    border: 1px solid #000000;
  }
  .table-bordered > thead > tr > th,
  .table-bordered > tbody > tr > th,
  .table-bordered > tfoot > tr > th,
  .table-bordered > thead > tr > td,
  .table-bordered > tbody > tr > td,
  .table-bordered > tfoot > tr > td {
     border: 1px solid #000000;
  }
  .dl-horizontal dt {
    text-align: left;
  }
</style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue fixed" data-spy="scroll" data-target="#scrollspy">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a style="font-size: 13px;" href="<?=base_url();?>api/index.php/mahasiswa/doc" class="logo"><b>Mahasiswa Restful API</b></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar" id="scrollspy">

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="nav sidebar-menu">
            <li class="header">TABLE OF CONTENTS</li>
             <li><a href="#overview"><i class='fa fa-circle-o'></i> Overview</a></li>
            <li><a href="#status"><i class='fa fa-circle-o'></i> API Status</a></li>
             <li><a href="#method"><i class='fa fa-circle-o'></i> API Method</a></li>
            <li><a href="#list_user"><i class='fa fa-circle-o'></i> GET /api/index.php/mahasiswa</a></li>
            <li><a href="#detail_user"><i class='fa fa-circle-o'></i> GET /api/index.php/mahasiswa/:id</a></li>
            <li><a href="#search"><i class='fa fa-circle-o'></i> GET /api/index.php/mahasiswa/search/:search keyword</a></li>
            <li><a href="#token"><i class='fa fa-circle-o'></i> Key Token</a></li>
            <li><a href="#post_user"><i class='fa fa-circle-o'></i> POST /api/index.php/mahasiswa</a></li>
            <li><a href="#update"><i class='fa fa-circle-o'></i> PUT /api/index.php/mahasiswa/update/:id</a></li>
            <li><a href="#delete"><i class='fa fa-circle-o'></i> DELETE /api/delete/:id</a></li>



          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <h1>
            Mahasiswa Rest API Documentation
            <small>Current version 1.0</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Documentation</li>
          </ol>
        </div>

        <!-- Main content -->
        <div class="content body" style="padding-bottom:30%">

<section id="status">
  <h2 class='page-header'><a href="#status">API Status</a></h2>
<p class='lead'>
  <h3>Each time Request is performed, API will return the status whether it's success or not <a class="headerlink" href="#defining-the-api" title="Permalink to this headline"></a></h3>
    <p>Below is the list of response's status when request is performed</p>
    <table border="1" class="table table-bordered">
    <colgroup>
    <col width="50%">
    <col width="9%">
    <col width="30%">
    </colgroup>
    <thead valign="bottom">
    <tr class="row-odd"><th class="head">Type Of Status</th>
    <th class="head">Status Code</th>
    <th class="head">Description</th>
    </tr>
    </thead>
    <tbody valign="top">
    <tr class="row-even"><td>The request success or Data found</td>
    <td>200</td>
    <td>Ok</td>
    </tr>
    <tr class="row-even"><td>The requested resource doesn't exists</td>
    <td>404</td>
    <td>Nothing Found</td>
    </tr>
    <tr class="row-even"><td>The request was well-formed but was unable to process the contained instructions due to semantic errors</td>
    <td>422</td>
    <td>Unprocessable Entity</td>
    </tr>
    </tbody>
    </table>
</p>
</section>

<section id="method">
  <h2 class="page-header"><a href="#method">API Method</a></h2>
  <p class="lead"></p>
    <div class="section" id="defining-the-api">
    <p>The API consists of the following methods:</p>
    <table border="1" class="table table-bordered">
    <colgroup>
    <col width="9%">
    <col width="30%">
    <col width="62%">
    </colgroup>
    <thead valign="bottom">
    <tr class="row-odd"><th class="head">Method</th>
    <th class="head">URL</th>
    <th class="head">Action</th>
    </tr>
    </thead>
    <tbody valign="top">
    <tr class="row-even"><td>GET</td>
    <td><?=base_url();?>api/index.php/mahasiswa</td>
    <td>Retrieve all mahasiswa</td>
    </tr>
     <tr class="row-even"><td>GET</td>
    <td><?=base_url();?>api/index.php/mahasiswa/nim</td>
    <td>Retrieve single mahasiswa based on nomor induk mahasiswa</td>
    </tr>
    </tbody>
    </table>
    </div>
</section>

          <!-- ============================================================= -->

<section id='list_user'>
  <h2 class='page-header'><a href="#list_user">GET /api/index.php/mahasiswa</a></h2>
  <p class='lead'>
    Return all mahasiswa data. By default Json response will show 10 data per page, but you can add 'perpage' parameter on url to show less or more than 10 data. and alswo to show the next 10 records, you can add 'page' parameter on url<br>
  </p>
<h3>Resource URL</h3>
<h4><?=base_url();?>api/index.php/mahasiswa</h4>
Go to page 3 by adding 'page' parameter  
<h4><?=base_url();?>api/index.php/mahasiswa?page=3</h4>
Go to page 3 and show 15 data with 'perpage' parameter
<h4><?=base_url();?>api/index.php/mahasiswa?page=3&perpage=15</h4>

<h3>Example Result</h3>
    <pre class="prettyprint">
{
  "status": {
    "code": 200,
    "message": "Ok"
  },
  "meta": {
    "total-records": "17490",
    "current-records": 1,
    "total-pages": 17490,
    "current-page": 1
  },
  "results": [
    {
      "nama_mahasiswa": "NOVITA TESTIANA",
      "nim": "220019001",
      "tanggal_lahir": "1986-11-04",
      "jenis_kelamin": "P",
      "email": "novitaummiaffan@gmail.com",
      "no_hp": "085288235228",
      "agama": "1",
      "status_pernikahan": null,
      "status_kemahasiswaan": "Lulus",
      "tanggal_masuk_kuliah": "2019-09-02",
      "nama_perguruan_tinggi": "Institut Agama Islam Negeri Kerinci",
      "kode_perguruan_tinggi": "202036",
      "nama_program_studi": "Hukum Keluarga Islam (Ahwal Syakhshiyyah) (S2)",
      "jenjang": "S2",
      "semester": "12",
      "ipk": "2.21",
      "jumlah_sks_ditempuh": "23",
      "provinsi_kota_kampus": "Provinsi Jambi - Kota Sungai Penuh",
      "alamat_domisili": "DESA SAWAHAN KOTO MAJIDIN NO 31",
      "provinsi_kota_mahasiswa": "Prov. Jambi - Kab. Kerinci"
    }
  ],
  "paginations": {
    "self": "http://siakad.iainkerinci.ac.id/api/index.php/mahasiswa?page=1&perpage=1",
    "first": "http://siakad.iainkerinci.ac.id/api/index.php/mahasiswa?page=1&perpage=1",
    "prev": "http://siakad.iainkerinci.ac.id/api/index.php/mahasiswa?page=1&perpage=1",
    "next": "http://siakad.iainkerinci.ac.id/api/index.php/mahasiswa?page=2&perpage=1",
    "last": "http://siakad.iainkerinci.ac.id/api/index.php/mahasiswa?page=17490&perpage=1"
  }
}

</pre>
<ul>
  <li>total-records  	: All record available</li>
  <li>current-records   : Total Current Page records</li>
  <li>total-pages   	: Total Pages available, this total pages depends on how many records to show perpage</li>
  <li>current-page   	: Current active page</li>
</ul>
</section>

<section id="detail_user">
  <h2 class="page-header"><a href="#detail_user">GET /api/index.php/mahasiswa/:nim</a></h2>
  <p class="lead">Show Single mahasiswa</p>

<h3>Resource URL</h3>
<h4><?=base_url();?>api/index.php/mahasiswa/:nim</h4>
<h3>Example Result</h3>
<h4><?=base_url();?>api/index.php/mahasiswa/220019001</h4>
    <pre class="prettyprint">
 {
  "status": {
    "code": 200,
    "message": "Ok"
  },
  "results": [
    {
      "nama_mahasiswa": "NOVITA TESTIANA",
      "nim": "220019001",
      "tanggal_lahir": "1986-11-04",
      "jenis_kelamin": "P",
      "email": "novitaummiaffan@gmail.com",
      "no_hp": "085288235228",
      "agama": "1",
      "status_pernikahan": null,
      "status_kemahasiswaan": "Lulus",
      "tanggal_masuk_kuliah": "2019-09-02",
      "nama_perguruan_tinggi": "Institut Agama Islam Negeri Kerinci",
      "kode_perguruan_tinggi": "202036",
      "nama_program_studi": "Hukum Keluarga Islam (Ahwal Syakhshiyyah) (S2)",
      "jenjang": "S2",
      "semester": "-4038",
      "ipk": "2.21",
      "jumlah_sks_ditempuh": "23",
      "provinsi_kota_kampus": "Provinsi Jambi - Kota Sungai Penuh",
      "alamat_domisili": "DESA SAWAHAN KOTO MAJIDIN NO 31",
      "provinsi_kota_mahasiswa": "Prov. Jambi - Kab. Kerinci"
    }
  ]
}

</pre>
</section>


        </div><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
      </footer>

    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="../../../dashboard/assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../../dashboard/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../../../dashboard/assets/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../../dashboard/assets/dist/js/app.min.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../../dashboard/assets/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="../../../dashboard/assets/plugins/google-code-prettify/run_prettify.js"></script>
    <script>
/*
 * Documentation specific JS script
 */
$(function () {
  var slideToTop = $("<div />");
  slideToTop.html('<i class="fa fa-chevron-up"></i>');
  slideToTop.css({
    position: 'fixed',
    bottom: '20px',
    right: '25px',
    width: '40px',
    height: '40px',
    color: '#eee',
    'font-size': '',
    'line-height': '40px',
    'text-align': 'center',
    'background-color': '#222d32',
    cursor: 'pointer',
    'border-radius': '5px',
    'z-index': '99999',
    opacity: '.7',
    'display': 'none'
  });
  slideToTop.on('mouseenter', function () {
    $(this).css('opacity', '1');
  });
  slideToTop.on('mouseout', function () {
    $(this).css('opacity', '.7');
  });
  $('.wrapper').append(slideToTop);
  $(window).scroll(function () {
    if ($(window).scrollTop() >= 150) {
      if (!$(slideToTop).is(':visible')) {
        $(slideToTop).fadeIn(500);
      }
    } else {
      $(slideToTop).fadeOut(500);
    }
  });
  $(slideToTop).click(function () {
    $("body").animate({
      scrollTop: 0
    }, 500);
  });
  $(".sidebar-menu li a").click(function () {
    var $this = $(this);
    var target = $this.attr("href");
    if (typeof target === 'string') {
      $("body").animate({
        scrollTop: ($(target).offset().top) + "px"
      }, 500);
    }
  });
});
    </script>
  </body>
</html>
