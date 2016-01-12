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

        $number     = 20;

        $count_users = count_users();
        $total_users = $count_users['total_users'];
        $total_pages = intval($total_users / $number) + 1;

    ?>

        <input id="wc4bp_total_user_pages" type="hidden" value="<?php echo $total_pages?>">

        <div id="result"></div>

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
    <p><b>Sync WooCommerce Customer Billing and Shipping Address with BuddyPress.</b></p>
    <p>The billing and shipping address fields are WooCommerce default user fields. You can sync all default WooCommerce customer fields with BuddyPress.</p>
    <p>During checkout or if a user edit his profile all fields will be synced automaticly.</p>
    <p>If you have already customers and start using WooCommerce BuddyPress Integration on an existing store, you need to sync your user once.</p>
    <p>The Customer Billing and Shipping Address fields will be created in BuddyPress during the plugin installation, but the user sync can take a while depends on the size of your user base and needs to be done once after the first install.</p>

<br>
    <a onclick="document.getElementById('wc_default_fields').style.display='';return false;" href="" style="text-decoration:none;border-bottom:1px dotted blue;">
        Default WooCommerce Checkout Fields</a>
    <br />
    <div id="wc_default_fields" style="display:none;margin:15px 15px 0px 15px;padding:5px;border:1px solid #aaa;">
        <b><p>Customer Billing Address</p></b>
        <ul>
            <li>First name</li>
            <li>Last name</li>
            <li>Company</li>
            <li>Address 1</li>
            <li>Address 2</li>
            <li>City</li>
            <li>Postcode</li>
            <li>State/County <i>(Country or state code)</i></li>
            <li>Country(2 letter Country code)</li>
            <li>Telephone</li>
            <li>Email</li>
        </ul>
        <b><p>Customer Shipping Address</p></b>
        <ul>
            <li>First name</li>
            <li>Last name</li>
            <li>Company</li>
            <li>Address 1</li>
            <li>Address 2</li>
            <li>City</li>
            <li>Postcode</li>
            <li>State/County <i>(Country or state code)</i></li>
            <li>Country(2 letter Country code)</li>
        </ul>
        <a onclick="document.getElementById('div_name2').style.display='none';return false;" href=""
           style="text-decoration:none;border-bottom:1px dotted blue;">hide</a>
    </div>

<br>



    <input type="button" id="wc4bp_sync_wc_user_with_bp_ajax" name="wc4bp_options_sync[wc_bp_sync]" class="button wc_bp_sync_all_user_data" value="Sync Now">

    <?php
}

function wc4bp_shop_profile_sync_ajax(){

    $update_type = $_POST['update_type'];

    $number     = 20;
    $paged      = isset($_POST['wc4bp_page']) ? $_POST['wc4bp_page'] : 1;
    $offset     = ($paged - 1) * $number;
    $query      = get_users('&offset='.$offset.'&number='.$number);

    ?>
    <input id="continue_update_paged" type="hidden" value="<?php echo $paged ?>">
    <p>If the Update stops for some reason you can continue the update manually</p>
    <input type="button" id="continue_update" value="Continue Updating from here">

    <table class="wp-list-table widefat fixed users">
        <thead>
        <tr>
            <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                <input id="cb-select-all-1" type="checkbox">
            </th>
            <th scope="col" id="username" class="manage-column column-username sortable desc" style="">
                <span>Username</span>
            </th>
            <th scope="col" id="name" class="manage-column column-name sortable desc" style=""><a
    <span>Name</span>
            </th>
            <th scope="col" id="email" class="manage-column column-email sortable desc" style="">
                <span>E-mail</span>
            </th>
            <th scope="col" id="role" class="manage-column column-role" style="">Role</th>

        </tr>
        </thead>

        <tbody id="result" data-wp-lists="list:user">

        <?php foreach($query as $q) {

            if($update_type == 'wc4bp_sync_wc_user_with_bp_ajax')
                wc4bp_sync_from_admin($q->ID);
            if($update_type == 'wc4bp_set_bp_field_visibility')
                wc4bp_change_xprofile_visabilyty_by_user_ajax($q->ID);
            ?>


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

        <?php } ?>

        </tbody>
    </table>

<?php
    die();
}
add_action( 'wp_ajax_wc4bp_shop_profile_sync_ajax', 'wc4bp_shop_profile_sync_ajax' );
add_action( 'wp_ajax_nopriv_wc4bp_shop_profile_sync_ajax', 'wc4bp_shop_profile_sync_ajax' );

function  wc4bp_sync_from_admin( $user_id ) {

    // get the profile fields
    $shipping = bp_get_option( 'wc4bp_shipping_address_ids' );
    $billing  = bp_get_option( 'wc4bp_billing_address_ids'  );

    $groups = BP_XProfile_Group::get(array(
        'fetch_fields' => true
    ));


    if ( !empty( $groups ) ) : foreach ( $groups as $group ) :

        if ( empty( $group->fields ) )
            continue;

        foreach ( $group->fields as $field ) {

            $billing_key    = array_search( $field->id  , $billing  );
            $shipping_key   = array_search( $field->id  , $shipping );

            if( $shipping_key ){
                $type       = 'shipping';
                $field_slug = $shipping_key;
            }

            if( $billing_key ){
                $type       = 'billing';
                $field_slug = $billing_key;
            }

            if( isset($field_slug) )
                xprofile_set_field_data( $field->id, $user_id, get_user_meta( $user_id, $type . '_' . $field_slug, true ) );
        }

    endforeach; endif;

}


function select_visibility_levels($name){

    $visibility_levels = '<select id="wc4bp_set_bp_' . $name . '" name="wc4bp_options_sync[' . $name . ']">

    <option value="none">Select Visibility</option>';

    foreach (bp_xprofile_get_visibility_levels() as $level) {

        $visibility_levels .= '<option value="' . $level['id'] . '" >' . $level['label'] . '</option>';

    }
    $visibility_levels .= '</select>';

    echo $visibility_levels;
}


function  wc4bp_change_xprofile_visabilyty_by_user(){ ?>
    <b><p>Set the Profile Field Visibility for all Users:</p></b>
    <p>You can change the Field Visability for all users. This will only work if the option</p>
    <p>"Allow Custom Visibility Change by User" is set to "Let members change this field's visibility"</p>

    <?php select_visibility_levels('visibility_levels'); ?>

    <input type="button" id="wc4bp_set_bp_field_visibility" name="wc4bp_options_sync[change_xprofile_visabilyty]" class="button wc_bp_sync_all_user_data" value="Sync Now">
<?php
}

function wc4bp_change_xprofile_visabilyty_by_user_ajax($user_id){

    // get the corresponding  wc4bp fields
    $shipping = bp_get_option('wc4bp_shipping_address_ids');
    $billing = bp_get_option('wc4bp_billing_address_ids');
    $visibility_level = $_POST['visibility_level'];

    foreach ($shipping as $key => $field_id) {
        xprofile_set_field_visibility_level($field_id, $user_id, $visibility_level);
    }
    foreach ($billing as $key => $field_id) {
        xprofile_set_field_visibility_level($field_id, $user_id, $visibility_level);
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
