<?php

add_action('admin_menu', 'wpkmkz_registration_select_role_add_options_page');

function wpkmkz_registration_select_role_add_options_page() {
    add_plugins_page(
        __('WPKMKZ Registration Select Role', 'kmkz_select_role'),
        __('WPKMKZ Registration Select Role', 'kmkz_select_role'),
        'manage_options',
        'wpkmkz-registration-select-role',
        'wpkmkz_registration_select_role_cb'
    );
}

function wpkmkz_registration_select_role_cb() {
    ?>
    <div>
        <form action="options.php" method="post">
            <?php settings_fields('wpkmkz_registration_select_role'); ?>
            <?php do_settings_sections('wpkmkz-registration-select-role'); ?>
            <input name="Submit" type="submit" class="button button-primary" value="<?php esc_attr_e( __('Save Changes', 'kmkz_select_role') ); ?>" />
        </form>
    </div>
    <?php
}

/**
 * Register Settings
 */
add_action('admin_init', 'register_setting_wpkmkz_greeklish_slugs');

function register_setting_wpkmkz_greeklish_slugs() {

    register_setting( 'wpkmkz_registration_select_role', 'wpkmkz_registration_select_role', 'wpkmkz_registration_select_role_options_validate' );

    add_settings_section(
         'wpkmkz_registration_select_role_main_section',
         __('WPkmkz Registration Select Roles', 'kmkz_select_role'),
         'wpkmkz_select_role_options_main_section_cb',
         'wpkmkz-registration-select-role'
    );

    add_settings_field(
        'wpkmkz_select_role_array',
        __('Select wich roles will be available on the registration', 'kmkz_select_role'), 'wpkmkz_select_role_array_cb',
        'wpkmkz-registration-select-role',
        'wpkmkz_registration_select_role_main_section'
    );
}

/**
 * Settings section
 */
function wpkmkz_select_role_options_main_section_cb() {
    echo '<b>' . __('Options', 'kmkz_select_role' ) . '</b><hr>';
}

/**
 * Settings section fields
 */
function wpkmkz_select_role_array_cb() {

    global $wp_roles;

    $options = get_option('wpkmkz_registration_select_role');

    $available_roles = $options['available_roles'] ? maybe_unserialize( $options['available_roles'] ) : array();

    $out  = '';

    foreach ( $wp_roles->get_names() as $slug => $name )
    {
      $out .= '<label for="available_roles">';
      $out .= '<input type="checkbox" name="wpkmkz_registration_select_role[available_roles][]" id="available_roles" value="' . esc_attr( $slug ) . '" ';
      $out .= in_array( $slug, $available_roles ) ? 'checked="checked" ' : '';
      $out .= '/>';
      $out .= '' . esc_attr( $name ) . '</label><br />';
    }

    echo $out;
}

/**
 * Validation
 */
function wpkmkz_registration_select_role_options_validate($input) {

    $roles   = array_map( 'esc_attr', $input['available_roles'] );
    $options = get_option('wpkmkz_registration_select_role');
    $options['available_roles']  = $roles;

    return $options;
}