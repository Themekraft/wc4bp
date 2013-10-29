jQuery(document).ready(function(){
		
	jQuery('.cptfbp_thickbox_add').click(function(){	
		
		var action = jQuery(this);
		var t = this.title || this.name || null;
	    var a = this.href || this.alt;
	    var g = this.rel || false;
	    
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			cache: false,
			data: {"action": "wc4bp_edit_entry"},
			success: function(data) {
				jQuery("#add_page").html(data);
				tb_show(t,a,g);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
		
	});
		
	jQuery('.wc4bp_delete_page').click(function(){	
		
		var action			= jQuery(this);
		var wc4bp_page_id	= this.id;
				
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
		
		var action = jQuery(this);
		var wc4bp_page_id = this.id;	
		var t = this.title || this.name || null;
	    var a = this.href || this.alt;
	    var g = this.rel || false;

		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			cache: false,
			data: {"action": "wc4bp_edit_entry", "wc4bp_page_id": wc4bp_page_id},
			success: function(data){
				jQuery("#add_page").html(data);
				tb_show(t,a,g);
			},
			error: function() { 
				alert('Something went wrong.. ;-(sorry)');
			}
		});
		
	});
	
	jQuery(".add_cpt4bp_page").live("click", function() { 

		var action = jQuery(this);
		
		var wc4bp_page_id	= jQuery( '#wc4bp_page_id'	).val();
		var wc4bp_tab_name	= jQuery( '#wc4bp_tab_name'	).val();
		var wc4bp_position	= jQuery( '#wc4bp_position'	).val();
		var wc4bp_main_nav	= jQuery( '#wc4bp_main_nav'	).val();
		
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