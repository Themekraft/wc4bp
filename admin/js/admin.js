jQuery(document).ready(function(){
		
	jQuery('.wc4bp_delete_page').click(function(){	
		var wc4bp_page_id = this.id;
		var action = jQuery(this);
		if (confirm('Delete Permanently'))
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "wc4bp_delete_page", "wc4bp_page_id": wc4bp_page_id},
			success: function(data){
				window.location.reload(true);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
	});
	
	jQuery('.wc4bp_editinline').click(function(){
		var wc4bp_target_id = this.id;	
		var wc4bp_page_id = this.id.split("_");
		var action = jQuery(this);
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "wc4bp_edit_entry", "wc4bp_page_id": wc4bp_page_id[2]},
			success: function(data){
				jQuery(wc4bp_target_id).removeClass('hidden');
				jQuery(wc4bp_target_id).append(data);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
	});
			
	jQuery('.add_cpt4bp_page').click(function(){
	alert('wc4bp_page_id');	
		var action = jQuery(this);
		var wc4bp_page_id	= jQuery( '#wc4bp_page_id'	).val();
		var wc4bp_tab_name	= jQuery( '#wc4bp_tab_name'			).val();
		var wc4bp_position	= jQuery( '#wc4bp_position'			).val();
		var wc4bp_main_nav	= jQuery( '#wc4bp_main_nav'			).val();
	
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "wc4bp_add_page", "wc4bp_page_id": wc4bp_page_id, "wc4bp_tab_name": wc4bp_tab_name, "wc4bp_position": wc4bp_position, "wc4bp_main_nav": wc4bp_main_nav},
			success: function(data){
				window.location.reload(true);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
	});

});