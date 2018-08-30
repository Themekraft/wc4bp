function wc4bpAdministration() {

	function wc4bp_update_user() {
		wc4bp_this_user_pages ++
		jQuery.ajax( {
			async: false,
			type: 'POST',
			url: ajaxurl,
			data: {'action': 'wc4bp_shop_profile_sync_ajax', 'visibility_level': visibility_level, 'update_type': update_type, 'wc4bp_page': wc4bp_this_user_pages},
			success: function ( data ) {
				jQuery( '#result' ).html( data )
			},
			error: function () {
				alert( 'Something went wrong.. ;-(sorry)' )
			}
		} )
		if ( wc4bp_total_user_pages > wc4bp_this_user_pages ) {
			window.setTimeout( function () {
				wc4bp_update_user()
			}, 0 )
		}
		if ( wc4bp_total_user_pages == wc4bp_this_user_pages ) {
			jQuery( '#result' ).html( '<h2>All Donne! Update Complete ;)</h2>' )
		}
	}

	function continue_update_paged() {
		wc4bp_total_user_pages = jQuery( '#wc4bp_total_user_pages' ).val()
		wc4bp_this_user_pages = jQuery( '#continue_update_paged' ).val()
		wc4bp_update_user()
	}

	function wc_bp_sync_all_user_data() {
		update_type = jQuery( this ).attr( 'id' )
		visibility_level = jQuery( '#' + update_type + '_options' ).val()

		wc4bp_total_user_pages = jQuery( '#wc4bp_total_user_pages' ).val()
		wc4bp_this_user_pages = 0
		wc4bp_update_user()
	}

	/* <fs_premium_only> */
	function addSortable( tabs ) {
		tabs.sortable( {
			cursor: 'move',
			axis: 'y',
			items: '> p',
			stop: function ( event, ui ) {
				jQuery.each( jQuery( this ).find( 'p' ), function ( index ) {
					jQuery( this ).find( 'input[type="hidden"]' ).val( index )
				} )
			}
		} )
	}

	function addPage() {
		var wc4bp_page_id = jQuery( '#wc4bp_page_id' ).val()
		var wc4bp_tab_slug = jQuery( '#wc4bp_tab_slug' ).val()
		var wc4bp_tab_name = jQuery( '#wc4bp_tab_name' ).val()
		var wc4bp_position = jQuery( '#wc4bp_position' ).val()
		var wc4bp_children = (
			jQuery( '#wc4bp_children' ).attr( 'checked' ) === 'checked'
		)

		jQuery.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'wc4bp_add_page',
				'wc4bp_page_id': wc4bp_page_id,
				'wc4bp_tab_slug': wc4bp_tab_slug,
				'wc4bp_tab_name': wc4bp_tab_name,
				'wc4bp_position': wc4bp_position,
				'wc4bp_children': wc4bp_children
			},
			success: function ( data ) {
				window.location.reload( true )
			},
			error: function () {
				alert( 'Something went wrong.. ;-(sorry)' )
			}
		} )
	}

	function editPage() {
		var wc4bp_page_id = jQuery( this ).attr( 'id' )

		var t = jQuery( this ).attr( 'title' ) || jQuery( this ).attr( 'name' ) || null
		var a = jQuery( this ).attr( 'href' ) || jQuery( this ).attr( 'alt' )
		var g = jQuery( this ).attr( 'rel' ) || false

		a = a.replace( /\s+/g, '' )

		jQuery.ajax( {
			type: 'POST',
			url: ajaxurl,
			cache: false,
			data: {
				'action': 'wc4bp_edit_entry',
				'wc4bp_page_id': wc4bp_page_id
			},
			success: function ( data ) {
				jQuery( '#add_page' ).html( data )
				tb_show( t, a, g )
				onLoadThickBoc()
			},
			error: function () {
				alert( 'Something went wrong.. ;-(sorry)' )
			}
		} )
	}

	function deletePage() {
		var wc4bp_tab_id = jQuery( this ).attr( 'id' )

		if ( confirm( 'Delete Permanently' ) ) {
			jQuery.ajax( {
				type: 'POST',
				url: ajaxurl,
				data: {
					'action': 'wc4bp_delete_page',
					'wc4bp_tab_id': wc4bp_tab_id
				},
				success: function ( data ) {
					window.location.reload( true )
				},
				error: function () {
					alert( 'Something went wrong.. ;-(sorry)' )
				}
			} )
		}
	}

	function openThickBox() {
		var t = jQuery( this ).attr( 'title' ) || jQuery( this ).attr( 'name' ) || null
		var a = jQuery( this ).attr( 'href' ) || jQuery( this ).attr( 'alt' )
		var g = jQuery( this ).attr( 'rel' ) || false

		a = a.replace( /\s+/g, '' )
		jQuery.ajax( {
			type: 'POST',
			url: ajaxurl,
			cache: false,
			data: {
				'action': 'wc4bp_edit_entry'
			},
			success: function ( data ) {
				jQuery( '#add_page' ).html( data )
				tb_show( t, a, g )
				onLoadThickBoc()
			},
			error: function () {
				alert( 'Something went wrong.. ;-(sorry)' )
			}
		} )
	}

	function onLoadThickBoc() {
		var addPageElement = jQuery( '.add_cpt4bp_page' )
		if ( addPageElement.length > 0 ) {
			addPageElement.on( 'click', addPage )
		}
	}

	/* </fs_premium_only> */

	return {
		init: function () {
			jQuery( function () {
				jQuery( '#tabs' ).tabs()
			} )

			var continueUpdatePaged = jQuery( '#continue_update_paged' )
			if ( continueUpdatePaged.length > 0 ) {
				continueUpdatePaged.click( continue_update_paged )
			}

			var syncAllUserData = jQuery( '.wc_bp_sync_all_user_data' )
			if ( syncAllUserData.length > 0 ) {
				syncAllUserData.click( wc_bp_sync_all_user_data )
			}

			/* <fs_premium_only> */

			var tabs = jQuery( '.wc4bp-tabs-order' )
			if ( tabs.length > 0 ) {
				addSortable( tabs )
			}

			var openThickBoxElement = jQuery( '.cptfbp_thickbox_add' )
			if ( openThickBoxElement.length > 0 ) {
				openThickBoxElement.on( 'click', openThickBox )
			}

			var deletePageElement = jQuery( '.wc4bp_delete_page' )
			if ( deletePageElement.length > 0 ) {
				deletePageElement.on( 'click', deletePage )
			}

			var editPageElement = jQuery( '.wc4bp_editinline' )
			if ( editPageElement.length > 0 ) {
				editPageElement.on( 'click', editPage )
			}

			/* </fs_premium_only> */
		}
	}
}

var wc4bpImplementation = wc4bpAdministration()
jQuery( document ).ready( function () {
	wc4bpImplementation.init()
} )

