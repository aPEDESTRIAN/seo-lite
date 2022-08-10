<?php

/**
 * The plugin bootstrap file
 *
 * Plugin Name:			SEO Lite
 * Plugin URI:			https://github.com/apedestrian/SEO-Lite
 * Description:			Adds all of the best practice SEO meta tags to you site.
 * Version:				1.0.2
 * Author:				apedestrian
 * Author URI:			https://github.com/apedestrian
 * License:				GPL-2.0+
 * License URI:			http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:			seo-lite
 * Domain Path:			/languages
 */

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

register_activation_hook(__FILE__, function()
{
	// Set default site description if we don't have one yet
	$option = get_option('seo_lite_site_description', '');
	if ($option == '')
	{
		update_option('seo_lite_site_description', get_bloginfo('description'));
	}
});

require plugin_dir_path(__FILE__) . 'includes/class-seo-lite.php';

// Begins execution of the plugin.
function run_seo_lite()
{
	$plugin = new Seo_Lite();
	$plugin->run();
}
run_seo_lite();