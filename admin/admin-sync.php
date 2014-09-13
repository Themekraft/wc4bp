<?php


/**
 * The Admin Page
 *
 * @author Sven Lehnert
 * @package WC4BP
 * @since 1.3
 */

function wc4bp_screen_sync() { ?>

    <div class="wrap">

        <div id="icon-options-general" class="icon32"><br></div>
        <h2>WooCommerce BuddyPress Integration</h2>
       <div style="overflow: auto;">

            <span style="font-size: 13px; float:right;">Proudly brought to you by <a href="http://themekraft.com/" target="_new">Themekraft</a>.</span>

        </div>
        <br>





    <?php
    $number     = 10;
    $paged      = isset($_GET['wc4bppage']) ? $_GET['wc4bppage'] : 1;;
    $offset     = ($paged - 1) * $number;
    $users      = get_users();
    $query      = get_users('&offset='.$offset.'&number='.$number);
    $total_users = count($users);
    $total_query = count($query);
    $total_pages = intval($total_users / $number) + 1;



?>

    <div class="tablenav top">

        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <input type="submit" name="" id="doaction" class="button action" value="Apply">
        </div>
        <div class="alignleft actions">
            <label class="screen-reader-text" for="new_role">Change role to…</label>
            <select name="new_role" id="new_role">
                <option value="">Change role to…</option>

                <option value="shop_manager">Shop Manager</option>
                <option value="customer">Customer</option>
                <option value="subscriber">Subscriber</option>
                <option value="contributor">Contributor</option>
                <option value="author">Author</option>
                <option value="editor">Editor</option>
                <option value="administrator">Administrator</option>		</select>
            <input type="submit" name="changeit" id="changeit" class="button" value="Change"></div><div class="tablenav-pages"><span class="displaying-num">592 items</span>
<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://spielwiese/wp-admin/users.php">«</a>
<a class="prev-page disabled" title="Go to the previous page" href="http://spielwiese/wp-admin/users.php?paged=1">‹</a>
<span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Select Page</label><input class="current-page" id="current-page-selector" title="Current page" type="text" name="paged" value="1" size="3"> of <span class="total-pages">592</span></span>
<a class="next-page" title="Go to the next page" href="http://spielwiese/wp-admin/users.php?paged=2">›</a>
<a class="last-page" title="Go to the last page" href="http://spielwiese/wp-admin/users.php?paged=592">»</a></span></div>
        <br class="clear">
    </div>
    <table class="wp-list-table widefat fixed users">
        <thead>
        <tr>
            <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label
                    class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1"
                                                                                              type="checkbox"></th>
            <th scope="col" id="username" class="manage-column column-username sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=login&amp;order=asc"><span>Username</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" id="name" class="manage-column column-name sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=name&amp;order=asc"><span>Name</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" id="email" class="manage-column column-email sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=email&amp;order=asc"><span>E-mail</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" id="role" class="manage-column column-role" style="">Role</th>

        </tr>
        </thead>

        <tfoot>
        <tr>
            <th scope="col" class="manage-column column-cb check-column" style=""><label class="screen-reader-text"
                                                                                         for="cb-select-all-2">Select
                    All</label><input id="cb-select-all-2" type="checkbox"></th>
            <th scope="col" class="manage-column column-username sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=login&amp;order=asc"><span>Username</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" class="manage-column column-name sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=name&amp;order=asc"><span>Name</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" class="manage-column column-email sortable desc" style=""><a
                    href="http://spielwiese/wp-admin/users.php?orderby=email&amp;order=asc"><span>E-mail</span><span
                        class="sorting-indicator"></span></a></th>
            <th scope="col" class="manage-column column-role" style="">Role</th>
        </tr>
        </tfoot>

        <tbody id="the-list" data-wp-lists="list:user">
<?php     foreach($query as $q) { ?>
        <tr id="user-1" class="alternate">
            <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-1">Select admin</label>
                <input type="checkbox" name="users[]" id="user_1" class="administrator" value="1">
            </th>
            <td class="username column-username"><?php echo get_avatar( $q->ID, 40 ); ?> <strong>
                     <?php echo get_the_author_meta('user_nicename', $q->ID);?>
                    </strong><br><div class="row-actions"></div>
            </td>
            <td class="name column-name"><?php echo get_the_author_meta('display_name', $q->ID);?> </td>
            <td class="email column-email"><a href="<?php echo get_the_author_meta('user_email', $q->ID);?>" title="E-mail: <?php echo get_the_author_meta('user_email', $q->ID);?>"><?php echo get_the_author_meta('user_email', $q->ID);?></a></td>
            <td class="role column-role"><?php echo implode(',',get_the_author_meta('roles', $q->ID));?></td>

        </tr>
        </tbody>
 <?php } ?>
    </table>

    <div class="tablenav bottom">

        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="delete">Delete</option>
            </select>
            <input type="submit" name="" id="doaction2" class="button action" value="Apply">
        </div>
        <div class="tablenav-pages"><span class="displaying-num">592 items</span>
<span class="pagination-links"><a class="first-page disabled" title="Go to the first page" href="http://spielwiese/wp-admin/users.php">«</a>
<a class="prev-page disabled" title="Go to the previous page" href="http://spielwiese/wp-admin/users.php?paged=1">‹</a>
<span class="paging-input">1 of <span class="total-pages">592</span></span>
<a class="next-page" title="Go to the next page" href="http://spielwiese/wp-admin/users.php?paged=2">›</a>
<a class="last-page" title="Go to the last page" href="http://spielwiese/wp-admin/users.php?paged=592">»</a></span></div>
        <br class="clear">
    </div>


    <?php
    if ($total_users > $total_query) {
        echo '<div id="pagination" class="clearfix">';
        echo '<span class="pages">Pages:</span>';
        $current_page = isset($_GET['wc4bppage']) ? $_GET['wc4bppage'] : 1; ;
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '&wc4bppage=%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_next'    => false,
            'type'         => 'list',
        ));
        echo '</div>';
    }
        ?>








        <form method="post" action="options.php">
            <?php wp_nonce_field( 'update-options' ); ?>
            <?php settings_fields( 'wc4bp_options_sync' ); ?>
            <?php do_settings_sections( 'wc4bp_options_sync' ); ?>

        </form>
    </div><?php

}

/**
 * Register the admin settings
 *
 * @author Sven Lehnert
 * @package TK Loop Designer
 * @since 1.0
 */

add_action( 'admin_init', 'wc4bp_register_admin_settings_sync' );

function wc4bp_register_admin_settings_sync() {

    register_setting( 'wc4bp_options_sync', 'wc4bp_options_sync' );

    // Settings fields and sections
    add_settings_section(	'section_sync'	    , 'Profile Field Synchronisation Settings'							, ''	, 'wc4bp_options_sync' );
    add_settings_section(	'section_general'	, 'Default BuddyPress WooCommerce Profile Field Settings'			, ''	, 'wc4bp_options_sync' );

    add_settings_field(		'wc4bp_shop_profile_sync'                       , '<b>WooCommerce BuddyPress Profile Fields Sync </b>'   , 'wc4bp_shop_profile_sync'                         , 'wc4bp_options_sync' , 'section_sync' );
    add_settings_field(		'wc4bp_change_xprofile_visabilyty_by_user'	    , '<b>Change Profile Field Visibility for all Users</b>'    , 'wc4bp_change_xprofile_visabilyty_by_user'	    , 'wc4bp_options_sync' , 'section_sync' );

    add_settings_field(		'wc4bp_change_xprofile_visabilyty_default'	    , '<b>Set the Default Profile Fields Visibility</b>'	    , 'wc4bp_change_xprofile_visabilyty_default'	    , 'wc4bp_options_sync' , 'section_general' );
    add_settings_field(		'wc4bp_change_xprofile_allow_custom_visibility'	, '<b>Allow Custom Visibility Change by User</b>'	        , 'wc4bp_change_xprofile_allow_custom_visibility'	, 'wc4bp_options_sync' , 'section_general' );
}

function wc4bp_shop_profile_sync(){ ?>
    <p>Sync all WooCommerce User Data with BuddyPress</p>
    <input type="button" name="wc4bp_options_sync[wc_bp_sync]" class="button wc_bp_sync_all_user_data" value="Sync Now">
    <div id="result"></div>
    <?php
}

function wc4bp_shop_profile_sync_ajax(){

        $all_user = wc4bp_get_all_user();
        echo '<h3> Depance on your Userbase this can take a while. Please wait and do not refresh this page.</h3>';

        foreach ( $all_user as $userid ) {
            $user_id       = (int) $userid->ID;
            $display_name  = stripslashes($userid->display_name);

            wc4bp_sync_from_admin($user_id);

            $return  = '';
            $return .= "\t" . '<li>'.$user_id .' - '. $display_name .'</li>' . "\n";

            print($return);
        }
        echo '<h3>All Done!</h3>';



}
add_action( 'wp_ajax_wc4bp_shop_profile_sync_ajax', 'wc4bp_shop_profile_sync_ajax' );
add_action( 'wp_ajax_nopriv_wc4bp_shop_profile_sync_ajax', 'wc4bp_shop_profile_sync_ajax' );

function  wc4bp_sync_from_admin( $user_id ) {

    // get the woocommerce fields
    // $fields = WC_Countries::get_default_address_fields();
    $fields = wc4bp_get_customer_meta_fields();

    // get the mapped fields
    $mapped_fields =  wc4bp_get_mapped_fields();

    // get the corresponding  wc4bp fields
    $shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
    $billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

    foreach( $fields as $type => $fieldset ) :

        if( ! in_array( $type, array( 'billing', 'shipping' ) ) )
            continue;

        // get the kind of address to update
        $kind_of = $$type;

        foreach( $fieldset['fields'] as $key => $field ) :
            // update the field

            // get the profile field id to update
            $mapped_key = str_replace( $type, '', $key );

            // get the field id
            $field_id = $kind_of[$mapped_fields[$mapped_key]];

            // update if it isn't empty
            if( ! empty( $field_id ) )
                xprofile_set_field_data( $field_id, $user_id, get_user_meta( $user_id, $key, true ) );

        endforeach;
    endforeach;
}


function select_visibility_levels($name){
    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );

    $visibility_levels = '<select name="wc4bp_options_sync[' . $name . ']"><option value="none" '.selected( $wc4bp_options_sync[$name], 'none', false ).'>Select Visibility</option>';

    foreach (bp_xprofile_get_visibility_levels() as $level) {

        $visibility_levels .= '<option value="' . $level['id'] . '"  '.selected( $wc4bp_options_sync[$name], $level['id'], false ).' >' . $level['label'] . '</option>';

    }
    $visibility_levels .= '</select>';

    echo $visibility_levels;
}


function  wc4bp_change_xprofile_visabilyty_by_user(){ ?>



    <p>Set the Profile Field Visibility for all Users:</p>
        <?php select_visibility_levels('visibility_levels'); ?> <input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visabilyty]" value="Sync Now">
    <?php

    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );



    if ( isset( $wc4bp_options_sync['change_xprofile_visabilyty'] ) ) {
        unset($wc4bp_options_sync['change_xprofile_visabilyty']);
        update_option('wc4bp_options_sync', $wc4bp_options_sync);
echo 'was geht daab<br>';

        $all_user = wc4bp_get_all_user();

        // get the corresponding  wc4bp fields
        $shipping = bp_get_option('wc4bp_shipping_address_ids');
        $billing = bp_get_option('wc4bp_billing_address_ids');
        echo '<h3> Depance on your Userbase this can take a while. Please wait and do not refresh this page.</h3>';
        foreach ($all_user as $userid) {
            $user_id = (int)$userid->ID;
            $display_name = stripslashes($userid->display_name);

            foreach ($shipping as $key => $field_id) {
                xprofile_set_field_visibility_level($field_id, $user_id, $wc4bp_options_sync['visibility_levels']);
            }
            foreach ($billing as $key => $field_id) {
                xprofile_set_field_visibility_level($field_id, $user_id, $wc4bp_options_sync['visibility_levels']);
            }

            $return = '';
            $return .= "\t" . '<li>User ID: ' . $user_id . ' Display Name: ' . $display_name . ' Set to '. $wc4bp_options_sync['visibility_levels'] .'</li>' . "\n";

            print($return);
        }
        echo '<h3>All Done!</h3>';

    }

}


function wc4bp_change_xprofile_visabilyty_default(){ ?>
    <p>Set the default profile field viability to</p>
    <?php select_visibility_levels('default_visibility'); ?>
    <input type="submit" class="button" name="wc4bp_options_sync[change_xprofile_visabilyty_field_default]" value="Change now">
    <?php
    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' );

    $billing = bp_get_option('wc4bp_billing_address_ids');
    $shipping = bp_get_option('wc4bp_shipping_address_ids');

    if ( isset( $wc4bp_options_sync['change_xprofile_visabilyty_field_default'] ) ) {



        echo '<ul>';

        foreach($billing as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
            echo '<li>billing_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>';
        }
        echo '</ul>';

        echo '<ul>';
        foreach($shipping as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'default_visibility', $wc4bp_options_sync['default_visibility'] );
            echo '<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['default_visibility'] . '</li>';
        }
        echo '</ul>';
        echo '<h3>All Done!</h3>';
        unset($wc4bp_options_sync['change_xprofile_visabilyty_field_default']);
        update_option('wc4bp_options_sync', $wc4bp_options_sync);
    }
}

function wc4bp_change_xprofile_allow_custom_visibility(){

    $wc4bp_options_sync = get_option( 'wc4bp_options_sync' ); ?>

    <p>Set custom visibility by user</p>

    <p>
        <select name="wc4bp_options_sync[custom_visibility]">
            <option value="allowed" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'allowed', false ); ?>>Let members change this field's visibility</option>
            <option value="disabled" <?php echo selected( $wc4bp_options_sync['custom_visibility'], 'disabled', false ); ?>>Enforce the default visibility for all members</option>
         </select>

        <input type="submit" class="button" name="wc4bp_options_sync[allow_custom_visibility]" value="Change Now">
    </p><?php


    $billing = bp_get_option('wc4bp_billing_address_ids');
    $shipping = bp_get_option('wc4bp_shipping_address_ids');

    if ( isset( $wc4bp_options_sync['allow_custom_visibility'] ) ) {
        echo '<ul>';
        foreach($billing as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['custom_visibility'] );
            echo '<li>billing_' .$key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>';
        }
        echo '</ul>';

        echo '<ul>';
        foreach($shipping as $key => $field_id){
            bp_xprofile_update_field_meta( $field_id, 'allow_custom_visibility', $wc4bp_options_sync['visibility_levels'] );
            echo '<li>shipping_' . $key . ' default visibility changed to ' . $wc4bp_options_sync['custom_visibility'] . '</li>';
        }
        echo '</ul>';

        echo '<h3>All Done!</h3>';
        unset($wc4bp_options_sync['allow_custom_visibility']);
        update_option('wc4bp_options_sync', $wc4bp_options_sync);
    }

}

function wc4bp_get_all_user(){

    $all_user = get_users( array( 'fields' => array( 'ID', 'display_name' ) ) );

    foreach($all_user as $key => $user){
        echo $user->ID . ',';
    }
}


add_action( 'wp_ajax_wc4bp_get_all_user', 'wc4bp_get_all_user' );
add_action( 'wp_ajax_nopriv_wc4bp_get_all_user', 'wc4bp_get_all_user' );
?>