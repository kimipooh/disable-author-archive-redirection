=== Disable Author Archive Redirection ===
Contributors: kimipooh
Tags: author,security
Requires at least: 6.0
Tested up to: 6.4.1
Requires PHP: 7.4
Stable tag: 2.1.1
License: GPL v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin disables the author archive redirection.
 
== Description ==
WordPress redirects /?author=(number) to /author/(userID) if the author id exists. This is the security vulnerability because an internet user might be able to know all User ID and the user name in a website using WordPress.

If you can control the configuration of a web server, you had better use the rewrite rule (Search as "Block Author URLs") for reducing  the system load of WordPress. 

The plugin prevents the security vulnerability regarding the author archive redirection.

Detailed Behavior
1. It isn't applied in the admin dashboard.
2. If "author" query in URL (QUERY_STRING) exists, displays 404 error.
3. If "/author/" in REQUEST_URI involves, redirects to the top page.
4. Apply to the "redirect_canonical" hook, too.

== Installation ==

The operation is the simple.
Please install this plugin and activate it.

== Frequently Asked Questions ==


== Changelog ==

= 2.1.1 =
* Fixed a problem with missing include files.

= 2.1 =
* Fixed the problem with a warning message when using WP-CLI.

= 2.0 =
* Supported the multisite.
* Added the setting menu. 
* Tested up 5.6 with PHP 7.4
* Tested up 5.8
* Tested up 6.0

= 1.0 =
* First Released.

== Upgrade Notice ==
