window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});



	
function update_password(event){
	event.preventDefault();
	
	let old_password = $("#old_password").val().trim();
    let new_password = $("#new_password").val().trim();
    let rnew_password = $("#rnew_password").val().trim();

    
    
	let sbutton = $("#pwd_sbutton").html(); //grab the initial content
	$(".form_error").remove();
    $("#pwd_sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Please waite...');

    let fdata = {ch: 'update_password', lag_index: "0",  old_password, new_password, rnew_password};
	
	let url = "../connect/user_main.php";
	if(glob_user == 'admin'){
		url = "../connect/admin_main.php";
	}
	
	
	$.ajax({
	 type: "POST",
	 url,
	 data: fdata,
	 success: function(data){
        $("#pwd_sbutton").html(sbutton);	
        if(data.substr(0,4) == 'PASS'){
            toastr.success('Password updated');
            $("#login_form")[0].reset();
        }
        else{
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){
					if($("#"+row[0]).closest('.input-group').length){
						$("#"+row[0]).closest('.input-group').after('<div class="form_error">'+row[1]+'</div>');
					}
					else{
						$("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>');
					}

                });
            }catch(exception){
               toastr.error(data);
            }
            
        }
     }
    });	
}


function update_user_email(event){
	event.preventDefault();
	
	let email = $("#update_email").val().trim();
    
	let sbutton = $("#ema_sbutton").html(); //grab the initial content
	$(".form_error").remove();
    $("#ema_sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Please waite...');

    let fdata = {ch: 'update_email', lag_index: "0",  email};
	
	let url = "../connect/user_main.php";
	if(glob_user == 'admin'){
		url = "../connect/admin_main.php";
	}
	

   $.ajax({
	 type: "POST",
	 url,
	 data: fdata,
	 success: function(data){
        $("#ema_sbutton").html(sbutton);	
        if(data.substr(0,4) == 'PASS'){
            toastr.success('Email updated');
            $("#login_form")[0].reset();
        }
        else{
            try{
                let rdata = JSON.parse(data);
                rdata.forEach(function(row){
					if($("#"+row[0]).closest('.input-group').length){
						$("#"+row[0]).closest('.input-group').after('<div class="form_error">'+row[1]+'</div>');
					}
					else{
						$("#"+row[0]).after('<div class="form_error">'+row[1]+'</div>');
					}

                });
            }catch(exception){
               toastr.error(data);
            }
            
        }
     }
    });	
}



function toggleEditMode(event, selector){
	event.preventDefault();

	var state = !$(event.target).closest(selector).find('.main_form input:eq(0)').prop('disabled');
	$(event.target).closest(selector).find('input, select, textarea').prop('disabled', state);

	$("#country").prop('disabled', state);

	if(state){
		$(event.target).closest(selector).find('button').hide();
	}
	else{
		$(event.target).closest(selector).find('button').show();
	}
	
	$(event.target).toggleClass('fa-edit fa-times');

	
}


function updateGeneralInfo(event){
	event.preventDefault();
	let fdata = {ch: "personal_information"}
	$(".update_form input, .update_form textarea, .update_form select").each(function(){
		let id = $(this).prop('id');
		let val = $(this).val();
		fdata[id] = val;
	});
	let url = "../connect/user_main.php";
	if(glob_user == 'admin'){
		url = "../connect/admin_main.php";
	}
	
	
	let sbutton = $("#update_sbutton").html(); //grab the initial content
	$(".formError").remove();
    $("#update_sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span>Creating Account. Please wait...');

	$.ajax({
		type: "POST",
		url:   url,
		data: fdata,
		success: function(data){	
			$("#update_sbutton").html(sbutton);
			if(data == 'PASS'){
			   toastr.success('Updated successfully');
			   $("#toglle_gen_btn")[0].click();
		   }
		   else{
			   toastr.error(data);	   
		   }
		}
	});
}




function logoutAccount(event){
	
	event.preventDefault();

	$("#modal_alert  .modal-body").html('<h4 style="margin-top:30px">Do you want to logout?</h4>');

	$("#modal_alert  .action_btn").show().html('Yes! logout now').off().on('click', function(){

		window.location.href = "../logout"
		
	});
	$("#modal_alert").modal('show');

}


