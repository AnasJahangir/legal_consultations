function step_forward(n) {
	app_step = n;

	if (app_step == 2) {

		let cur_date = $("#cur_date").html().trim();
		let cur_time = $("#cur_time").html().trim();
		if (cur_date == "" || cur_time == "") {
			toastr.error("Please select Appoint date and time");
			return;
		}
	}
	$("#sub_btn")[0].click();
	// Calculate the Price
	if (app_step == 2) {

		let subservice_id = $("#subservice_id").val();
		let call_method = $("#call_method").val();

		let service_option = $("#service_id option:selected");
		let price = parseFloat(service_option.data("price"));

		if (call_method != "") {
			let call_method_option = $("#call_method option:selected");
			let per = parseFloat(call_method_option.data("per"));
			price = price * per;
		}
		else {
			if (subservice_id != "") {
				let sub_service_option = $("#subservice_id option:selected");
				let per = parseFloat(sub_service_option.data("per"));
				price = price * per;
			}
		}

		if(glob_service_fee != ""){
			let textExtran = "";
			if(glob_service_fee <= price){
				let newPrice =  price - glob_service_fee;
				newPrice =  fcurrency(newPrice);
				let prevPay =  fcurrency(glob_service_fee);
				textExtran = `<div class="alert alert-info">You will be charge  <b>${newPrice}</b>.<br>Previous payment was ${prevPay}</div>`;
			}
			else{
				let newPrice =   glob_service_fee - price;
				newPrice =  fcurrency(newPrice);
				let prevPay =  fcurrency(glob_service_fee);
				textExtran = `<div class="alert alert-info">You will be refundend  <b>${newPrice}</b>.<br>Previous payment was ${prevPay}</div>`;
			}
			$("#textExtran").html(textExtran);
		}

		// Format the amount as SAR currency
		let formattedPrice = fcurrency(price);

		$("#sfee").html(formattedPrice);

	}
	$(".main_form")[0].scrollIntoView();
}

function step_backward(event, n) {
	event.preventDefault();
	app_step = n;
	if (app_step == 2) {
		$("#step_2").fadeOut(700, function () {
			$("#step2").removeClass('active');
			$("#step_1").fadeIn(700);
		});
		return;
	}
	else if (app_step == 3) {
		$("#step_3").fadeOut(700, function () {
			$("#step3").removeClass('active');
			$("#step_2").fadeIn(700);
		});
		return;
	}
	$(".main_form")[0].scrollIntoView();
}

function submitAppointment(event) {
	event.preventDefault();
	if (app_step == 1) {
		$("#step_1").fadeOut(700, function () {
			$("#step2").addClass('active');
			$("#step_2").fadeIn(700);
		});
		return;
	}
	else if (app_step == 2) {
		$("#step_2").fadeOut(700, function () {
			$("#step3").addClass('active');
			$("#step_3").fadeIn(700);
		});
		return;
	}
	if (app_step == 3) {
		let paymethod = $(".pay_method.active");
		if (paymethod.length == 0) {
			toastr.error("Please select Payment Method");
			return;
		}
		let payment_method = paymethod.data("value");
		let service_id = $("#service_id").val();
		let subservice_id = $("#subservice_id").val();
		let call_method = $("#call_method").val();
		let service_description = $("#service_description").val();

		let reservation_id = $("#reservation_id").val();

		let appointment_date = $("#cur_date").html().trim();
		let appointment_time = $("#cur_time").data('value').trim();

		let fdata = { ch: glob_action, reservation_id, payment_method, service_id, subservice_id, call_method, service_description, appointment_date, appointment_time };

		let parent = $(".main_form");

		let btnx = $("#sbutton_app");
		let sbutton = btnx.html();
		$(".formError").remove();
		let url = "./connect/appointment.php";
		if(glob_action == "new_appointment"){
			btnx.html('<span class="fa fa-spin fa-spinner fa-2x"></span>Booking appointment...');
		}
		else{
			url = "../connect/appointment.php";
			btnx.html('<span class="fa fa-spin fa-spinner fa-2x"></span>Updating appointment...');
		}

		$.ajax({
			type: "POST",
			url,
			data: fdata,
			success: function (data) {
				if (data == "PASS") {
					parent.trigger("reset");
					if(glob_action == "new_appointment"){
						parent.html('<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>Appointment Booking Successful</h4> <br><br> You appointment booking is successful. Please keep up with this date and time. You may be able to modify this appointment in your <a href="./account/my-reservations.php">My Reservations</a>  </div>');
					}
					else if(glob_action == "modify_appointment"){
						parent.html('<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>Appointment Modified</h4> <br><br> Your appointment has been modified successful. Please keep up with this date and time.</div>');
					}
					else if(glob_action == "reschedule_appointment"){
						parent.html('<div class="alert my-5 alert-success text-center py-5"><span class="fa fa-4x fa-check-circle"></span> <br> <h4>Appointment Date Reschedule</h4> <br><br> Your appointment adte and time  has been reschedule.</div>');
					}
				} else {
					btnx.html(sbutton);
					showFormError(data, parent);
				}
			},
		});


	}
}



function updateSubService() {
	let service_id = $("#service_id").val();
	$(".subservice_n").addClass('d-none');
	$("#subservice_id").html('<option value=""></option>');
	$("#subservice_id").prop('required', false);
	$("#subservice_id").val('');
	$(".call_method").addClass('d-none');
	$("#call_method").html('<option value=""></option>');
	$("#call_method").val('');
	$("#call_method").prop('required', false);

	if (service_id != "") {
		let service_option = $("#service_id option:selected");
		let price = service_option.data("price");
		let option = service_option.data("option");


		if (option == "has_subservice") {
			$(".subservice_n").removeClass('d-none');
			$("#subservice_id").prop('required', true);
			for (sub_id in glob_sub_services) {
				let subserv = glob_sub_services[sub_id];
				if (subserv.type == "sub_service") {
					selected = "";
					if(subserv.name == glob_subservice){
						selected = " selected ";
					}
					$("#subservice_id").append(`<option ${selected} data-per="${subserv.per}" data-children="${subserv.children}" value="${sub_id}">${subserv.name}</option>`);
				}
			}
		}
		else if (option == "consultation") {
			$(".subservice_n").removeClass('d-none');
			$("#subservice_id").prop('required', true);
			for (sub_id in glob_sub_services) {
				let subserv = glob_sub_services[sub_id];
				if (subserv.type == "consultation") {
					selected = "";
					if(subserv.name == glob_subservice){
						selected = " selected "; 
					}
					$("#subservice_id").append(`<option ${selected} data-per="${subserv.per}" data-children="${subserv.children}" value="${sub_id}">${subserv.name}</option>`);
				}
			}
		}
		
	}
	glob_subservice = "";
	updateCallMethod();


}



function updateCallMethod() {

	let subservice_id = $("#subservice_id").val();
	$(".call_method").addClass('d-none');
	$("#call_method").prop('required', false);
	$("#call_method").html('<option value=""></option>');

	let subservice_option = $("#subservice_id option:selected");
	let children = subservice_option.data("children");

	if (children == "remote") {
		$("#call_method").prop('required', true);
		$(".call_method").removeClass('d-none');
		for (sub_id in glob_sub_services) {
			let subserv = glob_sub_services[sub_id];
			if (subserv.type == "remote") {
				selected = "";
				if(subserv.name == glob_call_method){
					selected = " selected ";
				}
				$("#call_method").append(`<option ${selected} data-per="${subserv.per}" data-children="${subserv.children}" value="${sub_id}">${subserv.name}</option>`);
			}
		}
		glob_call_method = "";
	}
}


let appointment_days = {};
glob_appointment_dates.forEach(function (tday) {
	let aday = tday[0];
	let atime = tday[1];
	if (aday in appointment_days) {
		appointment_days[aday].push(atime);
	}
	else {
		appointment_days[aday] = [atime];
	}

});

$(document).ready(function () {

	
	$(".pay_method").on('click', function () {
		$(".pay_method").removeClass('active');
		$(this).closest(".pay_method").addClass('active');
	});

	$('#app_date').Zebra_DatePicker({
		format: 'Y-m-d',
		first_day_of_week: 0,
		// fast_navigation: false,
		// direction: [1, 360]
		direction: [true, 360],
		disabled_dates: ['* * * 5-6'], // Only accept sunday to thursday
		always_visible: $('#date_h_container'),
		container: $('#date_h_container'),
		show_clear_date: false,
		onChange: function (view, elements) {
			// on the "days" view...
			if (view == 'days') {
				// iterate through the active elements in the view
				elements.each(function () {
					// to simplify searching for particular dates, each element gets a
					// "date" data attribute which is the form of:
					// - YYYY-MM-DD for elements in the "days" view
					// - YYYY-MM for elements in the "months" view
					// - YYYY for elements in the "years" view
					let this_day = $(this).data('date');
					let app_days = [];
					let totalSlots = 16;
					if (isThursday(this_day)) {
						totalSlots = 18;
					}
					if (this_day in appointment_days) {
						app_days = appointment_days[this_day];
					}
					if (app_days.length == totalSlots) {
						//Filled
						$(this).addClass('filled');
						$(this).append('<span>34 slots</span>');
					}
					else {
						let this_dayClass = this_day.replaceAll("-", "_");
						$(this).addClass('active');
						$(this).addClass(this_dayClass);
						if (!isToday(this_day)) {
							let slot = totalSlots - app_days.length;
							$(this).append(`<span class="slot">${slot} slots</span>`);
						}
					}
				})
			}
		},
		onSelect: function (format, dateString, datan) {
			$("#cur_date").html(dateString);
			generateTimeSlots(dateString);
		}
	});

	$(".timeslots").on('click', 'button', function () {
		$(".timeslots button").removeClass('selected');
		$(this).addClass('selected');
		$("#cur_time").html($(this).html());
		$("#cur_time").data("value", $(this).data('value'));
	});

	updateSubService();

	setTimeout(function(){
		if(glob_appointment_date != ""){ 
			let this_dayClass = glob_appointment_date.replaceAll("-", "_");
			if($(`.${this_dayClass}`).length){
				$(`.${this_dayClass}`)[0].click();

				setTimeout(function(){
					let this_timeClass = glob_appointment_time.replaceAll(":", "_");
					if($(`.${this_timeClass}`).length){
						$(`.${this_timeClass}`)[0].click();
					}
					glob_appointment_time = "";
				}, 1000);
			}
			glob_appointment_date = "";

		}
	}, 1000);



});



function isThursday(dateString) {
	const date = new Date(dateString);
	if (isNaN(date.getTime())) {
		console.log('Invalid date format');
	}
	return date.getDay() === 4;
}

function isToday(dateString) {
	// Create a new Date object from the dateString
	const date = new Date(dateString);

	// Get today's date
	const today = new Date();

	// Compare year, month, and day
	return date.getFullYear() === today.getFullYear() &&
		date.getMonth() === today.getMonth() &&
		date.getDate() === today.getDate();
}

function generateTimeSlots(dateString) {
	// Create a new Date object from the dateString
	const date = new Date(dateString);

	// Get the day of the week (0 for Sunday to 6 for Saturday)
	const dayOfWeek = date.getDay();

	// Define time slots for Sunday to Wednesday
	let timeSlots = [];
	if (dayOfWeek === 0 || (dayOfWeek >= 1 && dayOfWeek <= 3)) {
		// Morning slots
		for (let hour = 8; hour < 13; hour++) {
			for (let minute = 0; minute < 60; minute += 30) {
				timeSlots.push(`${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}:00`);
			}
		}
		// Evening slots
		for (let hour = 17; hour < 20; hour++) {
			for (let minute = 0; minute < 60; minute += 30) {
				timeSlots.push(`${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}:00`);
			}
		}
	} else if (dayOfWeek === 4) { // Thursday
		for (let hour = 8; hour < 17; hour++) {
			for (let minute = 0; minute < 60; minute += 30) {
				timeSlots.push(`${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}:00`);
			}
		}
	}

	$(".timeslots").html("");

	usedSlots = [];
	if (dateString in appointment_days) {
		usedSlots = appointment_days[dateString];
	}
	// Add the buttons
	timeSlots.forEach(slot => {
		let dateTimeString = dateString + " " + slot;

		let slotHtml = "";
		let fslot = convertTo12Hour(slot);

		// alert(JSON.stringify(usedSlots));

		if (isPastDateTime(dateTimeString)) {
			//Time passed
			slotHtml = `<button data-value="${slot}" type="button" class="none" disabled>${fslot}</button>`;
		}
		else {
			if (usedSlots.includes(slot)) {
				// used
				slotHtml = `<button data-value="${slot}" type="button" class="filled" disabled>${fslot}</button>`;
			}
			else {
				//available
				let this_timeClass = slot.replaceAll(":", "_");
				slotHtml = `<button data-value="${slot}" type="button" class="active ${this_timeClass}">${fslot}</button>`;
			}
		}

		$(".timeslots").append(slotHtml);
	});

}




function isPastDateTime(dateTimeString) {
	// Create a new Date object from the dateTimeString
	const givenDateTime = new Date(dateTimeString);

	// Get the current date and time
	const currentDateTime = new Date();

	return givenDateTime < currentDateTime;
}

function convertTo12Hour(timeString) {
	// Split the time string into components
	let [hours, minutes, seconds] = timeString.split(':').map(Number);
	// Determine AM or PM suffix
	let period = (hours >= 12) ? 'PM' : 'AM';
	// Convert hours from 24-hour format to 12-hour format
	hours = hours % 12 || 12;
	// Format the result as a 12-hour time string with AM/PM
	let formattedTime = `${hours}:${minutes.toString().padStart(2, '0')} ${period}`;

	return formattedTime;
}



function fcurrency(price){
	// Format the amount as SAR currency
return new Intl.NumberFormat('en-SA', {
		style: 'currency',
		currency: 'SAR'
	}).format(price);

}

