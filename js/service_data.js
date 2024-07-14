// JavaScript Document


// JavaScript Document

let table = null;

let global_ch_dir = '';

let glob_entry_id = 0;

let glob_tr = '';

let glob_sub_service_index = 0;

let buttons = [
	{
			text: 'New Service',
			action: function () {
			   create_row(null);
			}
	},
	'csv',
];

  
table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
   "ajax": "../connect/service_data.php?ch=get_data",
   "bSortCellsTop" : true,
   "rowId" : "0",
   "createdRow": function(row, data, dataIndex) {
		$(row).attr('data-id', data[0]);
	},
   dom: '<B<t>l>',
   'initComplete' : function(setting, json){
		set_up_data_plugs();
		// setTypeSorting();
	},
	//responsive: true,
	'lengthMenu': [[50, 100,  -1], [50, 100, 'All']],
    columns: [
        { data: 1, render: function(data, type, row, meta){ return data + ` at <b>${row[3]}</b><br><div class="editor_btn mt-2"><button class="editor_update" onclick="edit_row(event)"><span class="fa fa-edit"></span> Edit </button> | <button onclick="remove_entry(event, 0)" class="editor_remove"><span class="fa fa-trash-o"> Delete</button></div>`;}},
		{ data: 2, className: 'service_description draging'},
		// { data: 5,  sortable:false, searchable:false, render: function(data, b, c){ return  process_subservices(data, c[0]);}},
		{data: 0, visible: false, searchable:false}
    ], 
	"order" : [[2, "asc"]],
	buttons: buttons
} );



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
		else if($(this).hasClass('sch_sel')){
				$('select', this).on('change', function(){
				let n_value = (this.value == "" || this.value == null)? this.value : this.value;
				if ( table.column(i).search() !== n_value ) {
					table
						.column(i)
						.search( n_value )
						.draw();
				}
			} );
		}
	
	});
	setTimeout(function(){
		table.columns([0]).visible(false);
		table.columns([0]).visible(true);
	}, 500);

}
table.on('column-visibility.dt', function(e, settings, column, state){
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
})



function create_row(event){
	
	$("#modal_entry form").trigger('reset');
	
	global_ch_dir = 'create';
	
	$('#modal_entry .modal-header h4').html("New Service");
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-plus"></span> Create');
	
	$('#modal_entry').modal('show');
	
	$("#errmsg").html('');
	
	$('#modal_entry .modal-header h4').html(`New Main Service`);
	
}


function edit_row(event){

	event.preventDefault();

	let rdata = null;
	$("#modal_entry form").trigger('reset');
	let parent = $(event.target).closest('tr');

		
		rdata = table.row(parent).data();
		$("#entry_id").val(rdata[0]); 	
		$("#name").val(rdata[1]);
		$("#description").val(rdata[2]);
		$("#price").val(rdata[3]);
		$("#option").val(rdata[4]);

		$('#modal_entry .modal-header h4').html(`Update Main Service: ${rdata[1]}`);
	
	
	global_ch_dir = 'edit';
	
	
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-save"></span> Update');
		
	$('#modal_entry').modal('show');
	$("#errmsg").html('');
}




function update_entry(event){
	
	event.preventDefault();
	
	let name = $("#name").val().trim();
	let description = $("#description").val().trim();
	let price = $("#price").val().trim();
	let option = $("#option").val().trim();

	let e_id =  $("#entry_id").val();
	
	$(".form_error").remove();

	let fdata = {ch: global_ch_dir, service_id: e_id, name, description, option, price};

	let sbutton = $("#sbutton").html(); //grab the initial content
	$("#errmsg").html('');
	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "../connect/service_data.php",
	 data: fdata,
	 dataType: 'json',
	 success: function(data){
		     
				$("#sbutton").html(sbutton);

				 e_id = data.service_id;

				 let sub_services = [];
				 let table_row = "";

				 table_row = "#myTable  #" + e_id;
				
				let bdata = table.row(table_row).data();
					
				 if(global_ch_dir == 'edit'){
					 sub_services = bdata[5];
					 table.row(table_row).data([e_id, name, description, price, option]).invalidate();	
					 $("#modal_entry form").trigger('reset');
					 $("#errmsg").html('<div  class="alert alert-success">The service has been updated successfully</div>');
					 toastr.success("The service has been updated successfully");
				 }
				 else{
					 var rowNode = table.row.add([e_id, name, description, price, option]).draw().node();
					 $(rowNode).css('color', 'green').animate({color: 'black'});
					$("#errmsg").html('<div class="alert alert-success">The service has been created successfully</div>');
					toastr.success("The service has been created  successfully");
				}
				setTimeout(function(){
				 	$('#modal_entry').modal('hide');
				}, 1300);		 
		    },
			error: function (xhr, status, error) {
				$("#sbutton").html(sbutton);
				toastr.error(xhr.responseText);
			}
		  });
	
}



 
function remove_entry(event, np){
	    
	   if(np == 0){
		 event.preventDefault();
		 glob_tr = $(event.target).closest('tr');
		 $("#modal_delete .modal-body").html("Do you want to delete this Service ?"); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', 'remove_entry(event, 1)');
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  Remove');
		 $("#modal_delete").modal('show');  
		 
		   }

	  else{

		let parent = glob_tr;
		let service_id = "";
		let sub_services = null;
		let pdata = null;

		rdata = table.row(parent).data();
		service_id = rdata[0];
	
	   	let sbutton = parent.find('.editor_btn').html();
	   	parent.find('.editor_btn').html('<span class="fa fa-spin fa-spinner"></span>')
			
		 $.ajax({
		 type: "POST",
		 data: {ch: 'remove', service_id},
		 url:  "../connect/service_data.php",
		 success: function(data){
					if(data == "PASS"){
					if(parent.closest('.sub_table').length){
						pdata[5] = sub_services;
						table.row(parent.closest('.sub_table').closest('tr')).data(pdata).invalidate();
					}
					parent.remove();
					toastr.success("Service has been deleted");
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



function process_subservices(services, service_id){
	
	var html = `<table class="table table-bodered sub_table"><thead><tr class="filtered"><td   style="border: 1px solid #dee2e6;" class="editor_btn_n text-end"><button class="btn btn-sm btn-primary" onclick="create_row(event)"><span class="fa fa-plus"></span> Add</button></td></tr></thead><tbody id="service_${service_id}">`;
	
	setTimeout(function(){
		// setSorting(service_id);
	}, 2000);

	if(services == null || services.length == 0){
		return  html + "</tbody></table>";
	}

	let index = 0;
	services.forEach(service => {
		html += `<tr data-index="${index}"><td style="cursor:pointer" class="service_area draging">${service.service_name} at <b>${service.default_price}</b> <br> <div class="editor_btn mt-2"><button class="editor_update" onclick="edit_row(event)"><span class="fa fa-edit"></span> Edit </button> | <button onclick="remove_entry(event, 0)" class="editor_remove"><span class="fa fa-trash-o"> Delete</button></div></td></tr>`;
		index++;
	});
	html += `</tbody></table>`;
	
	return html;
}

