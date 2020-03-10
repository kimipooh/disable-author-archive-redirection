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

function disable_author_archive_redirection() {
    // If ?author=, or /author/** except the admin dashboard in "REQUEST_URI", forcibly redirect to the home. 
    if(!is_admin()):
        if (isset($_REQUEST['author'])  || preg_match('#/author/.+#', $_SERVER['REQUEST_URI'])):
            wp_redirect(home_url());
            exit;
        endif;
    endif;
}
add_action('init', 'disable_author_archive_redirection');
