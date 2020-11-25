<?php
function daar_add_to_settings_menu(){
	$admin_permission = 'manage_options';
	// add_options_page (Title, Setting Title, Permission, Special Definition, function name); 
	add_options_page(__('Disable Author Archive Redirection Settings', DAAR_PLUGIN_NAME), __('Disable Author Archive Redirection Settings',DAAR_PLUGIN_NAME), $admin_permission, __FILE__,'daar_admin_settings_page');
}

// Processing Setting menu for the plugin.
function daar_admin_settings_page(){
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) 
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );	

	$admin_permission = 'manage_options';
	// Loading the stored setting data (disable-author-archive-redirection_array) from WordPress database.
	if(is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))
		$settings = get_site_option(DAAR_SITEADMIN_SETTING_FILE);
	else
		$settings = get_option(DAAR_SETTING_FILE);

	$permission = false;
	// The user who can manage the WordPress option can only access the Setting menu of this plugin.
	if(current_user_can($admin_permission)) $permission = true; 

	// When the adding data is saved (posted) at the setting menu, the data will update to the WordPress database after the security check
	if(isset($_POST["daar-form"]) && $_POST["daar-form"]):
		if(check_admin_referer("daar-nonce-key", "daar-form")):
			if(!isset($settings['disable_author_archive'])):
				$settings['disable_author_archive'] = "no";
			else:
				if(isset($_POST['disable_author_archive']))
					$settings['disable_author_archive'] = wp_strip_all_tags($_POST['disable_author_archive']);
			endif;
			if(!isset($settings['disable_except_admin_dashboard'])):
				$settings['disable_except_admin_dashboard'] = "no";
			else:
				if(isset($_POST['disable_except_admin_dashboard']))
					$settings['disable_except_admin_dashboard'] = wp_strip_all_tags($_POST['disable_except_admin_dashboard']);
			endif;
			if(!isset($settings['author_archive_type'])):
				$settings['author_archive_type'] = "redirect";
			else:
				if(isset($_POST['author_archive_type']))
					$settings['author_archive_type'] = wp_strip_all_tags($_POST['author_archive_type']);
			endif;
		endif;
	endif;
	// Update to WordPress Data.
	if(is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME)):
		get_site_option(DAAR_SITEADMIN_SETTING_FILE, $settings);
	else:
		if(isset($_POST["daar-form"]) && $_POST["daar-form"]):
			if(check_admin_referer("daar-nonce-key", "daar-form")):
				update_option(DAAR_SETTING_FILE, $settings);
?>
<div class="daar_admin_settings_page_updated"><p><strong><?php _e('Updated', DAAR_PLUGIN_NAME); ?></strong></p></div>
<?php				
			endif;
		endif;
	endif;
	
?>

<div id="daar_admin_settings_page_menu">
  <h2><?php _e('Disable Author Archive Redirection Admin Settings', DAAR_PLUGIN_NAME); ?></h2>

  <form method="post" action="">
	<?php // for CSRF (Cross-Site Request Forgery): https://propansystem.net/blog/2018/02/20/post-6279/
		wp_nonce_field("daar-nonce-key", "daar-form"); ?>
	<fieldset style="border:1px solid #777777; width: 750px; padding-left: 6px; padding-bottom: 1em;">
		<legend><h3><?php _e('Explanation',DAAR_PLUGIN_NAME); ?></h3></legend>
		<p><?php  _e('WordPress redirects /?author=(number) to /author/(userID) if the author id exists. This is the security vulnerability because an internet user might be able to know all User ID and the user name in a website using WordPress.',DAAR_PLUGIN_NAME); ?></p>
		<p><?php  _e('If you can control the configuration of a web server, you had better use the rewrite rule (Search as "Block Author URLs") for reducing the system load of WordPress.',DAAR_PLUGIN_NAME); ?></p>
		<p><?php  _e('The plugin prevents the security vulnerability regarding the author archive redirection.', DAAR_PLUGIN_NAME); ?></p>
		<p><?php  _e('Detailed Behavior
1. It is not applied in the admin dashboard.
2. If "author" query in URL (QUERY_STRING) exists, displays 404 error.
3. If "/author/" in REQUEST_URI involves, redirects to the top page.
4. Apply to the "redirect_canonical" hook, too.', DAAR_PLUGIN_NAME); ?></p>
     </fieldset>

	<fieldset style="border:1px solid #777777; width: 750px; padding-left: 6px; padding-bottom: 1em;">
		<legend><h3><?php _e('Options',DAAR_PLUGIN_NAME); ?></h3></legend>
		<?php  _e('* The plugins work for /?author=(number) and /author/(UserID). If you want to change the behavior, please turn on the following settings.',DAAR_PLUGIN_NAME); ?></p>
		<p><span style="color:red;"><?php  if(is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME)) _e('* The options cannot be permitted to change because this plugin has been enabled and controlled by the multisite administrator . <br/> In detail, please contact the multisite administrator.',DAAR_PLUGIN_NAME); ?></span></p>

	<?php //  ?>
		<p>
			<input type="hidden" name="disable_author_archive" value="no" />
			<input type="checkbox" name="disable_author_archive" value="yes" <?php if( isset($settings['disable_author_archive']) && $settings['disable_author_archive'] === "yes" ) echo "checked"; ?> <?php if(!$permission || (is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))) echo "disabled"; ?>/> <?php _e('Disable to work for "/author/UserID".',DAAR_PLUGIN_NAME); ?>
		</p>
		<p>
			<input type="hidden" name="disable_except_admin_dashboard" value="no" />
			<input type="checkbox" name="disable_except_admin_dashboard" value="yes" <?php if( isset($settings['disable_except_admin_dashboard']) && $settings['disable_except_admin_dashboard'] === "yes" ) echo "checked"; ?> <?php if(!$permission || (is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))) echo "disabled"; ?>/> <?php _e('Apply the filter of this plugin to not only the public area but also the admin dashboard.',DAAR_PLUGIN_NAME); ?></p>
		<p>
		<?php
			$author_archive_type = false;
			if(isset($settings['author_archive_type']) && $settings['author_archive_type'] === "404")
				$author_archive_type = true;
		?>
			<?php _e('Selection of the behavior in case of working "/author/UserID": ',DAAR_PLUGIN_NAME); ?>
			<br/>
			<input type="radio" name="author_archive_type" value="redirect" <?php if( $author_archive_type === false ) echo "checked"; ?> <?php if(!$permission || (is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))) echo "disabled"; ?>/> Redirect to the top page.
			<br/>
			<input type="radio" name="author_archive_type" value="404" <?php if($author_archive_type === true ) echo "checked"; ?>
			<?php if(!$permission || (is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))) echo "disabled"; ?>/> 404 error.
			</p>
	</fieldset>
	<br/>

	<input type="submit" value="<?php _e('Save', DAAR_PLUGIN_NAME); ?>" <?php if(!$permission || (is_multisite() && is_plugin_active_for_network(DAAR_PLUGIN_BASENAME))) echo "disabled"; ?>/>
</form>

</div>

<?php
}
