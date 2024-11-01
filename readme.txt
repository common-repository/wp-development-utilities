=== WP Development Utilities ===
Contributors: Cicerchia Fabio 
Tags: utilities, dev, development
Requires at least: 2.0.2
Tested up to: 2.6.3
Stable tag: 4.3

Extends the template tags with more functions that are very useful.

== Description ==

This script is useful during the development of WordPress, because will extend with a set of functions.

== Installation ==

Follow the below instruction for the installation of this plugin.

1. Upload `wp-dev-utilities.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the functions wherever you need (use the `$wpdu` variable).

== Usage ==

class `WP_Dev_Utilities`:
<ul>
    <li>_debug() - A function to print variable only if the user logged as administrator</li>
    <li>_retrieve_user_data() - Retrive user data informations only if user is logged in</li>
    <li>count_num_pages_post($id) - Returns the number of pages of a post</li>
    <li>get_current_execution_time($precision = 3) - Get the current execution time</li>
    <li>get_logged_username() - Returns the name of the user logged on</li>
    <li>get_post_content_from_id($id) - Return the content of the post from his id</li>
    <li>get_post_content_from_permalink($permalink) - Return the contents of the post permalink</li>
    <li>get_post_id_from_permalink($permalink) - Return the id of the post permalink</li>
    <li>is_administrator() - Check if logged user has the administrator privileges</li>
    <li>get_web_browser($shortname = false, $uppercase = false) - Returns the user's browser name</li>
    <li>get_web_server($uppercase = false) - Returns the web server is use</li>
    <li>get_wp_version() - Get the current wordpress version</li>
    <li>get_tag_slug($title) - Get the tag slug from tag title</li>
    <li>get_current_tag_slug($title) - Get the tag slug from the current tag title</li>
</ul>
