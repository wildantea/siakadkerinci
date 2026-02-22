                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                      Reset Password
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="<?=base_index();?>profil">Profil</a></li>
                        <li class="active">Reset Password</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
                                <div class="box-header">
                                </div><!-- /.box-header -->

                  <div class="box-body">
                  
                    <form id="pass_reset_pengguna" method="post" class="form-horizontal" action="<?=base_admin();?>modul/manajemen_pengguna/manajemen_pengguna_action.php?act=reset">
                      <div class="form-group">
                        <label for="Password Baru" class="control-label col-lg-2">Password Baru</label>
                        <div class="col-lg-10">
                          <input type="password" id="password_baru" name="password_baru"  class="form-control" > 
                        </div>
                      </div><!-- /.form-group -->
                       <div class="form-group">
                        <label for="Password Baru" class="control-label col-lg-2">Ulangi Password Baru</label>
                        <div class="col-lg-10">
                          <input type="password" id="password_confirm" name="password_confirm" class="form-control" > 
                        </div>
                      </div><!-- /.form-group -->

                      <input type="hidden" name="id" value="<?=uri_segment(3)?>">
                      <input type="hidden" id="redirect" value="<?=base_index();?>manajemen-pengguna">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
 <a href="<?=base_index();?>manajemen-pengguna" class="btn btn-default"><i class="fa fa-step-backward"></i> Cancel</a>
                          <input type="submit" class="btn btn-primary" value="Rubah Password">
                        </div>
                      </div><!-- /.form-group -->
                    </form>
                   
          
                  </div>
                  </div>
              </div>
</div>
                  
                </section><!-- /.content -->
<script type="text/javascript">
$(function(){
      $("#pass_reset_pengguna").validate({
 errorClass: 'help-block',
        errorElement: 'span',
        highlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
        },
       rules : {
                 password_baru : {
                    minlength : 3,
                    required:true
                },
                password_confirm : {
                    minlength : 3,
                    required:true,
                    equalTo : "#password_baru"
                }
               },
                  messages:
             {
                 password_baru: {
                  required:"Masukan password baru",
                  minlength:"Minimal 3 Karakter",
                 },
                 password_confirm: {
                  required:"Masukan lagi password baru",
                  minlength:"Minimal 3 Karakter",
                  equalTo:"Password baru harus sama"
                 }
             },

            submitHandler: function(form) {
               $('#loadnya').show();
                   $(form).ajaxSubmit({
                          type: "post",
                          url: $(this).attr('action'),
                          data: $("#pass_reset").serialize(),
                       //  enctype:  'multipart/form-data'
                        success: function(data){
                          console.log(data);
                          $('#loadnya').hide();
                              if (data=='false') {
                         $('.pass_salah').fadeIn();
                                //$('.sukses').html(data);
                              } else {
                                $('.notif_top_up').fadeIn(1000);
                                 setTimeout(function () {
                                 window.location = $("#redirect").val(); //will redirect to your blog page (an ex: blog.html)
                              }, 2000); //will call the function after 2 secs.
                                //redirect jika berhasil login
                              }
                      }
                    });
            }

        });
});
</script>
 