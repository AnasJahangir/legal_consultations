let table =  null;

let glob_entry_elm = null;
glob_entry_id = 0;


let ddt = {reservation_id: 0, created_at: 1, service: 2, subservice: 3, call_method: 4, payment_method: 5, service_fee: 6,  appointment_date: 7, appointment_time: 8, service_description: 9, updated_at: 10, modify_count: 11, first_name: 12, last_name: 13, phone: 14, email: 15};

if($('#myTable').length){
  
	table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
		url: "../connect/appointment_data.php?ch=get_data",
		data: function(d){
		} 
	},
	"bSortCellsTop" : true,
	dom: '<B<"datatable_dom_pull_left"f><t>lip>',
	initComplete : function(setting, json){ //When table has been fully initialize
		setTimeout(set_up_data_plugs, 100);
	},
		//responsive: true,
		'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
		columns: [
			{ data: ddt.created_at, className: "editor_btn text-center", render: function(data, type, row, meta){  return data + ( (row[ddt.modify_count] == 0)? '' : '<br><br><a onclick="open_changes(event)" href="#" style="background-color:#990; color:#FFF !important; display:inline-block; padding: 0px 5px; border-radius:7px;  marging-left: 10px; text-decoration:none" ><i class="fa fa-newspaper-o"></i> '+row[ddt.modify_count]+'</a><br><br>' ); }},
			{ data: ddt.first_name},
			{ data: ddt.last_name},
			{ data: ddt.phone},
			{ data: ddt.email},
			{ data: ddt.service},
			{ data: ddt.subservice, render: function(data, b, c){
				return data + ((c[ddt.call_method]  == "")? "" : ` ( ${c[ddt.call_method]} )`);
			}},
			{ data: ddt.appointment_date, className: "text-center", render: function(data, b, c){
				return data + "<br> @" +  convertTo12Hour(c[ddt.appointment_time]);
			}},
			{ data: ddt.service_fee, className: "text-center", render: function(data, b, c){
				return fcurrency(data) + `<br> ( ${c[ddt.payment_method]} )`
			}},
			{ data: ddt.service_description, className: "service_desc"},
			{ data: ddt.updated_at}
		], 
		"order" : [[7, "desc"]],
		//select: true,
		buttons: [
			'csv',
			'excel',
			{ extend: 'colvis', text: 'Column Visibility', collectionLayout: 'fixed three-column' }			
		]
	} );
	
}

function set_up_data_plugs(){

	$('#myTable thead tr:eq(1) th').each( function (i) {
		if($(this).hasClass('sch')){
			$(this).html('<input type="text" placeholder="search"/>')
			$('input', this).on('keyup change', function(){
			if ( table.column((i)).search() !== this.value ) {
				table
					.column((i))
					.search( this.value)
					.draw();
			}
		} );
		
		} 
	});

	setTimeout(function(){
			table.columns([1]).visible(false);
			table.columns([1]).visible(true);
	}, 500);
}

table.on('column-visibility.dt', function(e, settings, column, state){
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
});




 
function remove_entry(event, np){
	    
	if(np == 0){
	  event.preventDefault();
	  glob_tr = $(event.target).closest('tr');
	  $("#modal_delete .modal-body").html("Do you want to cancel this Reservation ?"); 
	  $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', 'remove_entry(event, 1)');
	  $("#modal_delete .delete_action_btn").removeClass('btn-success');
	  $("#modal_delete .delete_action_btn").addClass('btn-danger');
	  $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  Cancel Reservation');
	  $("#modal_delete").modal('show');  
	  
		}

   else{

	 let parent = glob_tr;
	 rdata = table.row(parent).data();
	 let reservation_id = rdata[0];
 
		let sbutton = parent.find('.editor_btn').html();
		parent.find('.editor_btn').html('<span class="fa fa-spin fa-spinner"></span>')
		 
	  $.ajax({
	  type: "POST",
	  data: {ch: 'cancel_reservation', reservation_id},
	  url:  "../connect/user_main.php",
	  success: function(data){
			 if(data == "PASS"){
				 parent.remove();
				 toastr.success("Reservation has been deleted");
			 }else{
				 toastr.error(data);
				 parent.find('.editor_btn').html(sbutton);
			 }
			 },
			 error: function (xhr, status, error) {
				 parent.find('.editor_btn').html(sbutton);
				 toastr.error(xhr.responseText);
			 }
		   });	
	 }
}




function open_changes(event){
	event.preventDefault();

	 let parent = $(event.target).closest('tr');;
	 rdata = table.row(parent).data();
	 let reservation_id = rdata[0];

	 $("#modal_changes").modal('show');
	 $("#modal_changes .modal-body tbody").html('<tr><td colspan="3" class="text-center py-5"><span class="fa fa-spin fa-spinner fa-5x"></span></td></tr>');
		
	  $.ajax({
	  type: "GET",
	  url:  "../connect/appointment_data.php?ch=reservation_changes&reservation_id="+reservation_id,
	  success: function(data){
			$("#modal_changes .modal-body tbody").html('');
			let cdata = JSON.parse(data);
			if(cdata.length == 0){
				$("#modal_changes .modal-body tbody").html('<tr><td colspan="3" class="text-center py-5"><span class="alert alert-info"> There has not been any modification for this Appointment</div></td></tr>');
			}
			else{
				cdata.forEach(function(app){
					$("#modal_changes .modal-body tbody").append(`<tr><td>${app[0]}</td><td>${app[1]}</td><td>${app[2]}</td></tr>`);
				})

			}
		},
		error: function (xhr, status, error) {
			parent.find('.editor_btn').html(sbutton);
			toastr.error(xhr.responseText);
		}
	});	
	 
}


function fcurrency(price){
		// Format the amount as SAR currency
	return new Intl.NumberFormat('en-SA', {
			style: 'currency',
			currency: 'SAR'
		}).format(price);

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



