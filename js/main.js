function show_form(event, form_id) {
  $(".form-selector button").removeClass("active");
  $(event.target).addClass("active");

  var alt_form_id = form_id === "login_form" ? "register_form" : "login_form";

  $("#" + alt_form_id).fadeOut(1000, function () {
    $("#" + form_id).fadeIn(1000);
  });
}

function toggleFPasswordForm(event, form_id) {
  event.preventDefault();

  var alt_form_id = form_id === "auth" ? "fpassword" : "auth";

  $("#" + alt_form_id + "_form").fadeOut(1000, function () {
    $("#" + form_id + "_form").fadeIn(1000);
  });
}


function sign_up_form(event) {
  event.preventDefault();

  let parent = $(event.target);

  let fdata = $(event.target).serialize();

  let btnx = $(event.target).find(".sbutton");

  let sbutton = btnx.html();

  $(".formError").remove();

  btnx.html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Creating Account. Please wait...'
  );

  $.ajax({
    type: "POST",
    url: "./connect/main.php",
    data: fdata,
    success: function (data) {
      if (data == "PASS") {
        parent.trigger("reset");
        parent.html(
          '<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>You have successfully created an account</h4> <br><br> <a style="cursor:pointer"  href="login">Login to continue</a> </div>'
        );
      } else {
        btnx.html(sbutton);
        showFormError(data, parent);
      }
    },
  });
}

function sign_in_form(event) {

  event.preventDefault();

  let parent = $(event.target);

  let fdata = $(event.target).serialize();

  let btnx = $(event.target).find(".sbutton");

  let sbutton = btnx.html();

  $(".formError").remove();

  btnx.html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Authyenticating. Please wait...'
  );
  let url = "./connect/main.php";
  if(glob_user == "admin"){
    url = "../connect/main.php";
  }

  $.ajax({
    type: "POST",
    url,
    data: fdata,
    success: function (data) {
      if (data.substr(0, 4) == "PASS") {
          if(glob_user == "user"){ 
            window.location.href = "./account";
          }
          else if(glob_user == "admin"){
            window.location.href = "./";
          }
          
      } else {
        btnx.html(sbutton);
        showFormError(data, parent);
      }
    },
  });
}


function forgot_pw_form(event, type) {
  event.preventDefault();

  let parent = $(event.target);

  let fdata = $(event.target).serialize();

  let btnx = $(event.target).find(".sbutton");

  let sbutton = btnx.html();

  $(".formError").remove();

  btnx.html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Sending, please waite...'
  );

  $.ajax({
    type: "POST",
    url: (type == 'user')?"./connect/main.php":"../connect/main.php",
    data: fdata,
    success: function (data) {
      if (data == "PASS") {
        parent.trigger("reset");
        parent.html(
          '<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>Password Reset Link Sent</h4> <br><br>  A password Reset Link has been sent to your email address. You should receive a message if there is an account with the email address.</div>'
        );
      } else {
        btnx.html(sbutton);
        showFormError(data, parent);
      }
    },
  });
}


function reset_pw_form(event, type) {
  event.preventDefault();

  let parent = $(event.target);

  let fdata = $(event.target).serialize();

  let btnx = $(event.target).find(".sbutton");

  let sbutton = btnx.html();

  $(".formError").remove();

  btnx.html(
    '<span class="fa fa-spin fa-spinner fa-2x"></span>Updating, please waite...'
  );

  $.ajax({
    type: "POST",
    url: (type == 'user')?"./connect/main.php":"../connect/main.php",
    data: fdata,
    success: function (data) {
      if (data == "PASS") {
        parent.trigger("reset");
        parent.html(
          '<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>Your Password has been Reset</h4> <br><br> You can now <a href="login.php">Login</a>.</div>'
        );
      } else {
        btnx.html(sbutton);
        showFormError(data, parent);
      }
    },
  });
}


function switchTab(class_n, np) {
  var np2 = np == 0 ? 1 : 0;

  $(`.${class_n}_btn`).removeClass("active");

  $(`.${class_n}_btn_${np}`).addClass("active");

  $(`.${class_n}_${np2}`).fadeOut(500, function () {
    $(`.${class_n}_${np}`).fadeIn(1000);
  });
}

function showFormError(data, parent) {
  try {
    let rdata = JSON.parse(data);
    rdata.forEach(function (row) {
      if(parent.find(`#${row[0]}`).closest('.input-group').length){
        parent.find(`#${row[0]}`).closest('.input-group').after('<div class="formError error_' + row[0] + '">' + row[1] + "</div>");
      }
      else{
        parent.find(`#${row[0]}`).after('<div class="formError error_' + row[0] + '">' + row[1] + "</div>");
      }
      parent.find(`#${row[0]}`)[0].scrollIntoView();
    });
  } catch (exception) {
    toastr.error(data);
  }
}


function pwd_vtoggle(event){
  let elm = $(event.target).closest('.input-group');
  elm.find('.fa').toggleClass('fa-eye fa-eye-slash');
  if(elm.find('.fa').hasClass('fa-eye')){
      elm.find('input').prop('type', 'password');
  }
  else{
      elm.find('input').prop('type', 'text');
  }

}



$(document).ready(function() {
  $('.has_pattern').on('change', function() { 
      var $input = $(this);
      // Remove any existing error message
      $input.next('.formError').remove();
      $input.closest('.input-group').next('.formError').remove();
      
      
      // Check if the pattern is met
      var pattern = new RegExp($input.attr('pattern'));
      if (!pattern.test($input.val())) { 
          // Get the error message from the data attribute
          var errorMessage = $input.data('error_message');
          if (errorMessage) {
              var errorSpan = $('<span></span>').addClass('formError').text(errorMessage);
              // Insert the error message after the input
              if($input.closest('.input-group').length){
                $input.closest('.input-group').after(errorSpan);
              }
              else{
                $input.after(errorSpan);
              }
          }
      }
  });

  $(".service .service_header").on('click', function(){

    if($(this).data('open') == 1){
      $(this).closest('.service').find('.subservices').slideUp();
      $(this).data('open', 0);
    }
    else{
      $(this).closest('.service').find('.subservices').slideDown();
      $(this).data('open', 1);
    }
    $(this).find('.fa').toggleClass('fa-caret-up fa-caret-down');

  });


});



function dismiss_message(id, type) {
  let fdata = {ch: "dismiss_message", id};
  $.ajax({
    type: "POST",
    url: (type == 'user')?"./connect/main.php":"../connect/appointment_data.php",
    data: fdata,
    success: function (data) {
      if (data == "PASS") {
        $("#message_counter").html((parseInt($("#message_counter").html()) - 1));
      } else {
          }
    },
  });
}


