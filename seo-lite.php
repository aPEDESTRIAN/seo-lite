<?php

/**
 * The plugin bootstrap file
 *
 * Plugin Name:			SEO Lite
 * Plugin URI:			https://github.com/apedestrian/SEO-Lite
 * Description:			Adds all of the best practice SEO meta tags to you site.
 * Version:				1.0.0
 * Author:				apedestrian
 * Author URI:			https://github.com/apedestrian
 * License:				GPL-2.0+
 * License URI:			http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:			seo-lite
 * Domain Path:			/languages
 */

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

define('SEO_LITE_SLUG', 'seo-lite');	// Slug of the plugin
define('SEO_LITE_VERSION', '0.0.1');	// Current plugin version.

// The code that runs during plugin activation.
register_activation_hook(__FILE__, function()
{
	// Make sure these values have autoload explicitly set to false
	// We only need the validation codes on the front page; Is autoload = false the right move?
	update_option('seo_lite_google_code',	get_option('seo_lite_google_code', ''),	false);
	update_option('seo_lite_bing_code',		get_option('seo_lite_bing_code', ''),	false);
});

// The code that runs during plugin deactivation.
register_deactivation_hook(__FILE__, function()
{
	// Nothing to do
});

// The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
require plugin_dir_path(__FILE__) . 'includes/class-seo-lite.php';

// Begins execution of the plugin.
function run_seo_lite()
{
	$plugin = new Seo_Lite(SEO_LITE_SLUG, SEO_LITE_VERSION);
	$plugin->run();
}
run_seo_lite();