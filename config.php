<?php
// Set timezone for php
date_default_timezone_set( 'Europe/Berlin' );

// Path
define( 'AUBU_BASE_PATH', plugin_dir_path(__FILE__) );
define( 'AUBU_BASE_URL', plugin_dir_url(__FILE__) );
define( 'AUBU_PLUGIN', [ 'name' => 'auto_parts_query', 'version' => '1.1.2' ] );

// REST API
define( 'AUBU_REST_API_NAMASPACE', 'aubu/v1' );
define( 'AUBU_ENDPOINT_SAVE_USER_DATA', '/api/save_user_data' );
define( 'AUBU_ENDPOINT_HAS_NO_USERNAME', '/api/has_no_username' );
define( 'AUBU_ENDPOINT_HAS_NO_EMAIL', '/api/has_no_email' );
define( 'AUBU_ENDPOINT_GET_PENDING_USER', '/api/get_pending_user' );
define( 'AUBU_ENDPOINT_CREATE_USER', '/api/create_user' );

// shortcodes
define( 'AUBU_SHORTCODE_ADD_USER_FORM', 'aubu_add_new_user_form' );
define( 'AUBU_SHORTCODE_SET_PASSWORD_FORM', 'aubu_set_password_form' );


// Others
define( 'AUBU_ADD_NEW_USER_PAGE_SLUG', 'aubu-add-new-user');
define( 'AUBU_SET_PASSWORD_PAGE_SLUG', 'aubu-set-password');
define( 'AUBU_NEW_USER_ROLE', 'ubu_mitglied' );
define( 'AUBU_PAGE_APPROVAL_LIST', 'user_by_user_approval' );
define( 'AUBU_PAGE_PASSWORD_CONFIRMATION_LIST', 'user_by_user_password_confirmation' );

// Email
define('AUBU_SENDER_EMAIL', get_bloginfo('admin_email'));
// TODO: Mario - Translate email sender name - 'Newcloud Media'
define('AUBU_SENDER_NAME', __('Newcloud Media', 'aubu') );

// Profile
define( 'AUBU_KEY_CREATED_USERS_INFO', 'created_users_info' );
define( 'AUBU_KEY_REQUESTED_BY_USER_INFO', 'requested_by_user_info' );
