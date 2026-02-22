// var username;
// var password;
$(document).ready(function(){


$('#login').click(function() 
{
      //$(".invalid").html('');
      //$(".invalid").hide('');
      var username=$("#username").val();
      var password=$("#password").val();

      if (username=='' || password=='')
      {
            if (password=='') {
              $("#password").addClass('error-input');
              $("#password").focus();
              $( "#password" ).keyup(function() {
              $("#password").removeClass('error-input');
              }); 
          }
          if (username=='') {
              $("#username").addClass('error-input');
              $("#username").focus();
              $( "#username" ).keyup(function() {
              $("#username").removeClass('error-input');
              });
          }

      } else {

        var data_login = {
            username: username,
            password: password
        }
             $.ajax({
              url: "inc/login.php",
              type : "post",
              dataType: 'json',
              data : data_login,
              //beforeSend: function(){ $("#login").text('Connecting...');},
              success: function(data) {
               $.each(data, function(index) {
                console.log(data[index]);
                if (data[index].status=='good') {
                    //redirect jika berhasil login
                    window.location="./index.php/";
                }else if (data[index].status=='survey') {
                  // alert("test");
                   $("#myModal").modal('show'); 
                }
                 else {
                  //$(".container").shake();
                  //$("#login").text('SIGN IN');
                  $(".error_login").fadeIn();
                   $(".error_login").fadeOut(2000);
                  $(".invalid").html(" Username Atau Password Salah, Silakan Coba Lagi ");
                }
              });

 
              }
            });
      }

return false;  
});

/*  $.backstretch([
      "assets/login/img/1.jpg"
    , "assets/login/img/2.jpg"
    , "assets/login/img/war.jpg"
  ], {duration: 3000, fade: 1000});

*/

	}); 



