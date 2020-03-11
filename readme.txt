=== Disable Author Archive Redirection ===
Contributors: Kimiya Kitani
Tags: author,security
Requires at least: 4.0
Tested up to: 5.3.2
Requires PHP: 5.6
Stable tag: 1.0
License: GPL v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin disables the author archive redirection.
 
== Description ==
WordPress redirects /?author=(number) to /author/(userID) if the author id exists. This is the security vulnerability because an internet user might be able to know all User ID and the user name in a website using WordPress.

If you can control the configuration of a web server, you had better use the rewrite rule (Search as "Block Author URLs") for reducing  the system load of WordPress. 

The plugin prevents the security vulnerability by forcibly redirecting /?author=(number) to / (the home of the website) except the admin dashboard.

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

= 1.0 =
* First Released.

== Upgrade Notice ==
