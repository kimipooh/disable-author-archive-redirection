<?php
/*
Plugin Name: Disable Author Archive Redirection
Plugin URI: 
Description: The plugin disables the author archive redirection.
Version: 2.1
Author: Kimiya Kitani
Author URI: https://profiles.wordpress.org/kimipooh/
Text Domain: disable-author-archive-redirection
Domain Path: /languages
*/

// Prevent the security vulnerability: http://www.securityspace.com/smysecure/catid.html?id=1.3.6.1.4.1.25623.1.0.103222

define('DAAR_DEFAULT_VAR', '2.0');
define('DAAR_PLUGIN_NAME', 'disable-author-archive-redirection');
define('DAAR_PLUGIN_DIR',  'disable-author-archive-redirection');
define('DAAR_PLUGIN_BASENAME', DAAR_PLUGIN_DIR . '/' . DAAR_PLUGIN_NAME . '.php');
define('DAAR_SITEADMIN_SETTING_FILE', 'disable-author-archive-redirection-network_array');
define('DAAR_SETTING_FILE', 'disable-author-archive-redirection_array');

require_once( dirname( __FILE__  ) . '/includes/admin.php');

// Add Setting to WordPress 'Settings' menu for Multisite.
if(is_multisite()){
	add_action('network_admin_menu', 'daar_network_add_to_settings_menu');
	require_once( dirname( __FILE__  ) . '/includes/network-admin.php');
}
add_action('admin_menu', 'daar_add_to_settings_menu');
add_action('plugins_loaded', 'daar_enable_language_translation');
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook( __FILE__, 'daar_uninstaller' );

if ( ! function_exists( 'is_plugin_active_for_network' ) ) 
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
// disable_author_archive = /author/*** ('yes', 'no': default is 'no')
// disable_except_admin_dashboard = the plugin also applies in the \\ dashboard ('yes', 'no': default is 'no')
// author_archive_type = '404' or 'redirect': default is 'redirect'
if(is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))
	$settings = get_site_option(DAAR_SITEADMIN_SETTING_FILE);
else
	$settings = get_option(DAAR_SETTING_FILE);

$disable_author_archive = false;
if(isset($settings['disable_author_archive']) && $settings['disable_author_archive'] === 'yes')
	$disable_author_archive = true;

// Reference: https://m0n.co/enum
if (!is_admin() || (isset($settings['disable_except_admin_dashboard']) && $settings['disable_except_admin_dashboard'] === 'yes')):
	if (isset($_SERVER['QUERY_STRING']) && preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])):
		error_404();
	elseif (preg_match('#/author/.*#i', $_SERVER['REQUEST_URI'])):
		if($disable_author_archive === false):
			if(isset($settings['author_archive_type']) && $settings['author_archive_type'] === '404'):
				error_404();
			else:
				header('Location: '. home_url());
				exit;
			endif;
		endif;
	endif;
	add_filter('redirect_canonical', 'daar_disable_author_archive_redirection', 10, 2);
endif;

function daar_uninstaller(){
	// Remove Save data.
	if(is_multisite())
		delete_site_option(DAAR_SITEADMIN_SETTING_FILE);
	delete_option(DAAR_SETTING_FILE);
}
// Multi-language support.
function daar_enable_language_translation(){
	load_plugin_textdomain(DAAR_PLUGIN_NAME);
}
// Reason to use http_status_code: https://toolset.com/forums/topic/how-to-redirect-the-single-page-to-404-page-not-found-page/
function error_404(){
	http_response_code(404);
	exit();
}

function daar_disable_author_archive_redirection($redirect, $request) {
	if(is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))
		$settings = get_site_option(DAAR_SITEADMIN_SETTING_FILE);
	else
		$settings = get_option(DAAR_SETTING_FILE);

	$disable_author_archive = false;
	if(isset($settings['disable_author_archive']) && $settings['disable_author_archive'] === 'yes')
		$disable_author_archive = true;
	if (isset($_SERVER['QUERY_STRING']) && preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])):
		error_404();
	elseif (preg_match('#/author/.*#i', $_SERVER['REQUEST_URI'])):
		if($disable_author_archive === false):
			if(isset($settings['author_archive_type']) && $settings['author_archive_type'] === '404'):
				error_404();
			else:
				wp_redirect(home_url());
				exit();
			endif;
		endif;
	else:
		return $redirect;
	endif;
}
