<?php
/*
Plugin Name: WPKMKZ Registration Select Role
Plugin URI: http://wpkamikaze.com
Description: Add a dropdown menu of roles the registration form
Author: Kostas Skapator Charalampidis
Version: 1.0
Author URI: http://wpkamikaze.com

Copyright (c) 2012.
WPKMKZ Registration Select Role is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

require_once ('plugin-options.php');


/*
|-------------------------------------------------
| Add the role select to the registration form
|-------------------------------------------------
|
*/
add_action('register_form','add_kmkz_select_role_field');

function add_kmkz_select_role_field() {

    global $wp_roles;

    $options = get_option('wpkmkz_registration_select_role');

    $available_roles = $options['available_roles'] ? maybe_unserialize( $options['available_roles'] ) : array();

    if ( !empty($available_roles) )
    {
      $out  = '<p><label for="user_role">' . __('Select role', 'kmkz_select_role' ) . '</label>';
      $out .= '<select class="input" id="user_role" name="user_role">';

      foreach ( $wp_roles->get_names() as $slug => $name )
      {
        if ( !in_array( $slug, $options['available_roles'] ) ) continue;
        $out .= '<option value="' . esc_attr( $slug ) . '">' . esc_attr( $name ) . '</option>';
      }

      $out .= '</select></p>';

      echo $out;
    }

    return;
}


/*
|-------------------------------------------------
| Save the the role
|-------------------------------------------------
|
*/
add_action('user_register', 'kmkz_update_role');

function kmkz_update_role($user_id, $password="", $meta=array()) {
   if ( isset($_POST['user_role']) )
   {
       $userdata              = array();
       $userdata['ID']        = $user_id;
       $userdata['user_role'] = $_POST['user_role'];

       $options = get_option('wpkmkz_registration_select_role');

       $available_roles = $options['available_roles'] ? maybe_unserialize( $options['available_roles'] ) : array();

       //only allow if user role is my_role to avoid a few new admins to the site
       if ( in_array( $userdata['user_role'], $options['available_roles'] ) )
       {
          wp_update_user($userdata);
       }
   }
}