<?php
/*
Plugin Name: Disable Author Archive Redirection
Plugin URI: 
Description: The plugin disables the author archive redirection.
Version: 1.0
Author: Kimiya Kitani
Author URI: https://profiles.wordpress.org/kimipooh/
Text Domain: disable-author-archive-redirection
Domain Path: /languages
*/

// Prevent the security vulnerability: http://www.securityspace.com/smysecure/catid.html?id=1.3.6.1.4.1.25623.1.0.103222

// Reference: https://m0n.co/enum

if (!is_admin()):
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])):
		http_response_code(404);
		die();
	elseif (preg_match('#/author/.*#i', $_SERVER['REQUEST_URI'])):
		header('Location: '. home_url());
		exit;
	endif;
	add_filter('redirect_canonical', 'disable_author_archive_redirection', 10, 2);
endif;

function disable_author_archive_redirection($redirect, $request) {
	if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])):
		http_response_code(404);
		die();
	elseif (preg_match('#/author/.*#i', $_SERVER['REQUEST_URI'])):
		wp_redirect(home_url());
		exit;
	else:
		return $redirect;
	endif;
	
}
