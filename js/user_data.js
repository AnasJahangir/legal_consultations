let table =  null;
let table2 =  null;

let glob_entry_elm = null;
glob_entry_id = 0;

let ddt = {user_id: "0", first_name: "1", last_name: "2",  email: "3", phone: "4", reg_date: "5"};


if($('#myTable').length){
  
	table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "../connect/user_data.php?ch=get_data",
	"bSortCellsTop" : true,
	"rowId" : "0",
	dom: '<B<"datatable_dom_pull_left"f><t>lip>',
	initComplete : function(setting, json){ //When table has been fully initialize
		setTimeout(set_up_data_plugs, 100);
	},
		//responsive: true,
		'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
		columns: [
			{ data: ddt.user_id, searchable: false, className: "editor_btn",  sortable: false,  render: function(data, type, row, meta){  return (meta.row + meta.settings._iDisplayStart + 1) + ' <button class="btn btn-xs pentry_update btn-warning" onclick="edit_prow(event)"><span class="fa fa-lock"></span></button> |<button onclick="remove_entry(event, 0)" class="editor_remove"><span class="fa fa-trash-o"> Delete</button>'}},
			{ data: ddt.first_name},
			{ data: ddt.last_name},
			{ data: ddt.email},
			{ data: ddt.phone},
			{ data: ddt.reg_date}
		], 
		
		"order" : [[1, "desc"]],
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
		table.columns([9]).visible(false);
		if(glob_admin_type == "region"){
			table.columns([10]).visible(false);
		}
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
		 glob_entry_id = $(event.target).closest('tr').prop('id');
		 $("#modal_delete .modal-body").html('Do you want to remove this User?<br> <b>Note:</b> this will clear all users\'s record in the database.'); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', 'remove_entry(event, 1)');
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  REMOVE');
		 $("#modal_delete").modal('show');  
		   
		}

	  else{
	
	   fdata = {ch: 'remove_user', user_id: glob_entry_id};
	   
	   var sbutton = $('#myTable  #'+ glob_entry_id + ' .editor_btn').html();
	   
	   $('#myTable  #'+ glob_entry_id +' .editor_btn').html('<span class="fa fa-spin fa-spinner"></span>')
			
		 $.ajax({
		 type: "POST",
		 url:  "../connect/user_data.php",
		 data: fdata,
		 success: function(data){  console.log(data);
		 		data = data.trim();	
				 $("#modal_delete .delete_action_btn").html('Delete');
				 if(data.substr(0,4) == 'PASS'){
					
					$('#myTable  #'+ glob_entry_id).remove();
					 
					}
				   else{
					   $('#myTable  #'+ glob_entry_id + ' .editor_btn').html(sbutton)
					   toastr.error(data);
					   
					   }
					
				},
			  });	
		}
}



function random_string(length) {
	var result           = '';
	var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	var charactersLength = characters.length;
	for ( var i = 0; i < length; i++ ) {
	  result += characters.charAt(Math.floor(Math.random() * 
  charactersLength));
   }
   return result;
  }
  


//////////////////////////////////////////////////////////////////////////////////

var glob_edit_id = 0;


function edit_prow(event){

	event.preventDefault();

	var parent = $(event.target).closest('tr');
	var rdata = table.row(parent).data();
	
	glob_entry_id = rdata[0]; 

	$("#modal_pentry form").trigger('reset');
	$('#modal_pentry .modal-header h4').html('Update  Password for: <br> '+rdata[2]);
	$('#modal_pentry').modal('show');
	$("#errmsg3").html('');

}


function update_pentry(event){
	
	event.preventDefault();
	
	let password = $("#password2").val().trim();
	
	let user_id =  glob_entry_id;
	
	let fdata = {ch: 'update_password', user_id, password};

	let sbutton = $("#sbutton3").html(); //grab the initial content
	$("#errmsg3").html('');
	$("#sbutton3").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "../connect/user_data.php",
	 data: fdata,
	 success: function(data){
		     data = data.trim();
			 $("#sbutton3").html(sbutton);
			
			 if(data.substr(0, 4) == "PASS"){

				$("#errmsg3").html('');					 		 
				$('#modal_pentry').modal('hide');
				toastr.success('Password updated successfully');
				 
			 }
			 else{
			   $("#errmsg3").html('<span class="text-danger">' +data + '</span>');
			  }
		    },
		});
	
}



function getOption(val, id){
	if($(`#${id} option[value="${val}"]`).length){
		return $(`#${id} option[value="${val}"]`).text();
	}
	return val;
}

