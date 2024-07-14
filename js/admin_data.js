let table =  null;

let glob_entry_elm = null;

let ddt = {admin_id: "0", fname: "1", lname: "2", email: "3", phone: "4", role: "5"}

if($('#myTable').length){
  
	table = $('#myTable').DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "../connect/admin_data.php?ch=get_data",
	"bSortCellsTop" : true,
	"rowId" : "0",
	dom: '<B<"datatable_dom_pull_left"f><t>lip>',
	initComplete : function(setting, json){ //When table has been fully initialize
		setTimeout(set_up_data_plugs, 100);
	},
		//responsive: true,
		'lengthMenu': [[50, 100, 200, 500, 2000, -1], [50, 100, 200, 500, 2000, 'All']],
		columns: [
			{ data: ddt.admin_id, searchable: false, className: "editor_btn",  sortable: false,  render: function(data, type, row, meta){  return (meta.row + meta.settings._iDisplayStart + 1) + ' <button class="btn btn-xs pentry_update btn-warning" onclick="edit_prow(event)"><span class="fa fa-lock"></span></button> | <button class="editor_update" onclick="edit_row(event)"><span class="fa fa-edit"></span> Edit </button> | <button onclick="remove_entry(event, 0)" class="editor_remove"><span class="fa fa-trash-o"> Delete</button>'}},
			{ data: ddt.fname},
			{ data: ddt.lname},
			{ data: ddt.email},
			{ data: ddt.phone},
			{ data: ddt.role, render: function(data, b, row){ return getOption(data, 'role'); }}
		], 
		
		"order" : [[1, "desc"]],
		//select: true,
		buttons: [
			{
				text: 'New Admin',
				action: function () {
				   create_row();
				}
			},
			'csv',
			'excel'
			// { extend: 'colvis', text: 'Column Visibility', collectionLayout: 'fixed three-column' }			
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
		table.columns([0]).visible(false);
		table.columns([0]).visible(true);
	}, 500);
}

table.on('column-visibility.dt', function(e, settings, column, state){
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '0%');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('opacity', '1');
	$('#myTable thead tr:eq(1) th input, #myTable thead tr:eq(1) th select').css('width', '100%');
});



function create_row(){


	$("#modal_entry form").trigger('reset');

	$("#admin_id").val(''); 
	
	global_ch_dir = 'create_admin';
	
	$('#modal_entry .modal-header h4').html('New Admin');
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-plus"></span> Create');
		
	$('#modal_entry').modal('show');
	$("#errmsg").html('');

}



function edit_row(event){

	event.preventDefault();

	$("#modal_entry form").trigger('reset');
	
	var parent = $(event.target).closest('tr');
	var rdata = table.row(parent).data();
	
	for(key in ddt){
		
		$("#"+key).val(rdata[ddt[key]]);
	}
	global_ch_dir = 'edit_admin';
	
	$('#modal_entry .modal-header h4').html('Update Administrator');
	$('#modal_entry .modal-footer button:eq(1)').html('<span class="fa fa-save"></span> Update');
		
	$('#modal_entry').modal('show');
	$("#errmsg").html('');
}



function update_entry(event){
	
	event.preventDefault();

	let fdata = {ch: global_ch_dir};

	for(key in ddt){
		//console.log(key);
		fdata[key] =  $("#"+key).val().trim();
	}

	let sbutton = $("#sbutton").html(); //grab the initial content
	$("#errmsg").html('');
	$("#sbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "../connect/admin_data.php",
	 data: fdata,
	 success: function(data){
		     data = data.trim();
			 $("#sbutton").html(sbutton);
			
			 if(data.substr(0, 4) == "PASS"){

				 if(global_ch_dir == 'edit_admin'){

					 var table_row = "#myTable  #" + $("#admin_id").val();
					 
					 let bdata = table.row(table_row).data();

					 for(key in ddt){
						bdata[ddt[key]] =  $("#"+key).val().trim();
					 }
					 
					 table.row(table_row).data(bdata).invalidate();
					 $("#modal_entry form").trigger('reset');
					 $("#errmsg").html('<div style="font-size:16px; color:#092; font-weight: bold" class="text-success">Updating entry was successful</div>');
					 		 
				 }
				 else{

					var admin_id = data.substr(4);
					let bdata = [];
					for(key in ddt){
						bdata[ddt[key]] =  $("#"+key).val().trim();
					}
					bdata[0] = admin_id;
					var rowNode = table.row.add(bdata).draw().node();
					$(rowNode).css('color', 'green').animate({color: 'black'});
				 }
				 $('#modal_entry').modal('hide');
				 
			 }
			 else{
			   $("#errmsg").html('<span class="text-danger">' +data + '</span>');
			  	
			  }
		    },
		  });
	
}



 
 function remove_entry(event, np){
	    
	   if(np == 0){
		 event.preventDefault();
		 glob_entry_id = $(event.target).closest('tr').prop('id');
		 $("#modal_delete .modal-body").html('Do you want to remove this Administrator?'); 
		 $("#modal_delete .delete_action_btn")[0].setAttribute('onClick', 'remove_entry(event, 1)');
		 $("#modal_delete .delete_action_btn").removeClass('btn-success');
		 $("#modal_delete .delete_action_btn").addClass('btn-danger');
		 $("#modal_delete .delete_action_btn").html('<span class="fa fa-trash-o"></span>  REMOVE');
		 $("#modal_delete").modal('show');  
		 
		}

	  else{

	
	   fdata = {ch: 'remove_admin', admin_id: glob_entry_id};
	   
	   var sbutton = $('#myTable  #'+ glob_entry_id + ' .editor_btn').html();
	   
	   $('#myTable  #'+ glob_entry_id +' .editor_btn').html('<span class="fa fa-spin fa-spinner"></span>')
			
		 $.ajax({
		 type: "POST",
		 url:  "../connect/admin_data.php",
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




  function get_roles(r_str){

	if(r_str == '' || r_str == null) return '';

	let role = [];

	r_str += " ";
	r_str.trim().split(',').forEach(function(role_n){ 
		role_n = role_n.trim();
		if(role_n in glob_roles){ 
			role.push(glob_roles[role_n]); 
		}
	});
	return '<small style="font-size:11px">'+role.join(', ')+'</small>';;
  }



//////////////////////////////////////////////////////////////////////////////////

var glob_edit_id = 0;

function update_password(event, ch){
	
	event.preventDefault();
	
	var fdata = new FormData($("#" + ch + "_form")[0]);
			
	var sbutton = $(".msbutton").html(); //grab the initial content
	
	$(".errmsg").html('');
	
	
	$(".msbutton").html('<span class="fa fa-spin fa-spinner fa-2x"></span> please wait...');
	   
	   $.ajax({
		 type: "POST",
		 url:   "../connect/admin.php",
		 data: fdata,
		 cache: false,
		 processData : false,
		 contentType : false,
		 success: function(data){ console.log(data);
		 		
				$(".msbutton").html(sbutton);
				 if(data === 'PASS'){
					 
					 $("form")[0].reset();
					 
					$(".errmsg").html('<div class="text-success fa fa-check"  style="padding:15px; background-color:#FFF; font-size:12px; border: 1px solid #0F0; width:94%; margin: 3%;  border-radius:4px;"> Updated successfully</div>');
				 }
				  else{
					$(".errmsg").html('<div class="text-danger" style="padding:15px; background-color:#FFF; font-size:12px; border: 1px solid #F00; width:93%; margin: 3%; border-radius:4px;">' +data + '</div>');
					//var elmnt = document.getElementById("errmsg");
					 //elmnt.scrollIntoView();
					
				  }
				},
			  });
	
}






function edit_prow(event){

	event.preventDefault();

	var parent = $(event.target).closest('tr');
	var rdata = table.row(parent).data();
	
	$("#admin_id2").val(rdata[0]); 

	$("#modal_pentry form").trigger('reset');
	$('#modal_pentry .modal-header h4').html('Update  Password for: <br> '+rdata[1]);
	$('#modal_pentry').modal('show');
	$("#errmsg3").html('');

}


function update_pentry(event){
	
	event.preventDefault();
	
	let password = $("#password2").val().trim();
	
	let admin_id =  $("#admin_id2").val();
	
	let fdata = {ch: 'update_password', admin_id, password};

	let sbutton = $("#sbutton3").html(); //grab the initial content
	$("#errmsg3").html('');
	$("#sbutton3").html('<span class="fa fa-spin fa-spinner fa-2x"></span> Submitting...');
   
   $.ajax({
	 type: "POST",
	 url:   "../connect/admin_data.php",
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


