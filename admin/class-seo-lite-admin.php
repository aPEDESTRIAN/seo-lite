<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

class Seo_Lite_Admin
{
	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	// Add menu item to admin area
	public function add_options_page()
	{
		add_options_page(
			'SEO Lite Settings',					// string $page_title
			'SEO Lite', 							// string $menu_title
			'manage_options', 						// string $capability
			'seo-lite-options-page',				// string $menu_slug
			array($this, 'display_options_page')	// callable $callback
		);
	}

	// Displays the admin page
	public function display_options_page()
	{
		include 'partials/seo-lite-admin-display.php';
	}

	public function options_page_init()
	{
		// ====== General ======
		add_settings_section(
			'seo_lite_general',						// string $id
			'General Settings',						// string $title
			null,									// callable $callback
			'seo-lite-options-page'					// string $page ($menu_slug from add_options_page)
		);
		// Site Description
		register_setting(
			'seo_lite_settings_option_group', 		// string $option_group
			'seo_lite_site_description',			// string $option_name
		);
		add_settings_field(
			'seo_lite_site_description',			// string $id (same as $option_name of linked register_setting)
			'Site Description',						// string $title
			array($this, 'site_description_input'),	// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_general'						// string $section ($id from add_settings_section)
		);
		// Add Title Tag
		register_setting(
			'seo_lite_settings_option_group', 		// string $option_group
			'seo_lite_add_title_tag',				// string $option_name
		);
		add_settings_field(
			'seo_lite_add_title_tag',				// string $id (same as $option_name of linked register_setting)
			htmlspecialchars('Add <title> to Head'),// string $title
			array($this, 'add_title_tag_input'),	// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_general'						// string $section ($id from add_settings_section)
		);

		// ====== Validation ======
		add_settings_section(
			'seo_lite_validation_codes',					// string $id
			'Validation Codes',								// string $title
			array($this, 'print_validation_codes_section'),	// callable $callback
			'seo-lite-options-page'							// string $page ($menu_slug from add_options_page)
		);
		// Google Code
		register_setting(
			'seo_lite_settings_option_group', 		// string $option_group
			'seo_lite_google_code',					// string $option_name
		);
		add_settings_field(
			'seo_lite_google_code',					// string $id (same as $option_name of linked register_setting)
			'Google',								// string $title
			array($this, 'google_code_input'),		// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_validation_codes'				// string $section ($id from add_settings_section)
		);
		// Bing Code
		register_setting(
			'seo_lite_settings_option_group',		// string $option_group
			'seo_lite_bing_code',					// string $option_name
		);
		add_settings_field(
			'seo_lite_bing_code',					// string $id (same as $option_name of linked register_setting)
			'Bing',									// string $title
			array($this, 'bing_code_input'),		// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_validation_codes'				// string $section ($id from add_settings_section)
		);

		// ====== Social Media Analytics ======
		add_settings_section(
			'seo_lite_social_analytics',			// string $id
			'Social Media Analytics',				// string $title
			null,									// callable $callback
			'seo-lite-options-page'					// string $page ($menu_slug from add_options_page)
		);
		// Facebook
		register_setting(
			'seo_lite_settings_option_group', 		// string $option_group
			'seo_lite_facebook_app_id',				// string $option_name
		);
		add_settings_field(
			'seo_lite_facebook_app_id',				// string $id (same as $option_name of linked register_setting)
			'Facebook App ID',						// string $title
			array($this, 'facebook_app_id_input'),	// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_social_analytics'				// string $section ($id from add_settings_section)
		);
		// Twitter
		register_setting(
			'seo_lite_settings_option_group', 		// string $option_group
			'seo_lite_twitter_username',				// string $option_name
		);
		add_settings_field(
			'seo_lite_twitter_username',			// string $id (same as $option_name of linked register_setting)
			'Twitter Username',						// string $title
			array($this, 'twitter_username_input'),	// callable $callback
			'seo-lite-options-page',				// string $page ($menu_slug from add_options_page)
			'seo_lite_social_analytics'				// string $section ($id from add_settings_section)
		);
	}

	public function site_description_input()
	{
		?>
		<label for="seo_lite_site_description">
			<textarea name="seo_lite_site_description" id="seo_lite_site_description" class="large-text" rows="3"><?php echo get_option('seo_lite_site_description', ''); ?></textarea>
			The site description is used for the meta description tag of your front page.
			It is also the fallback in the case that a description cannot be generated for
			a page/post (uses excerpt) or archive page (no tag/category description).
		</label>
		<?php
	}

	public function add_title_tag_input()
	{
		?>
		<label for="seo_lite_add_title_tag">
			<input name="seo_lite_add_title_tag" type="checkbox" id="seo_lite_add_title_tag" value="1" <?php checked(1, get_option('seo_lite_add_title_tag'), true); ?>>
			<?php echo htmlspecialchars('Have SEO Lite add the <title> tag between the <head> tags. Leave this as fasle if your theme already takes care of this.'); ?>
		</label>
		<?php
	}

	public function print_validation_codes_section()
	{
		?>
		<p>
			Add your <a href="https://developers.google.com/search" target="_blank" rel="noopener noreferrer">Google</a>
			and <a href="https://www.bing.com/webmasters" target="_blank" rel="noopener noreferrer">Bing</a>
			validation codes below.
		</p>
		<?php
	}

	public function google_code_input()
	{
		?>
		<input name="seo_lite_google_code" type="text" id="seo_lite_google_code" value="<?php echo get_option('seo_lite_google_code', ''); ?>" class="regular-text code">
		<?php
	}

	public function bing_code_input()
	{
		?>
		<input name="seo_lite_bing_code" type="text" id="seo_lite_bing_code" value="<?php echo get_option('seo_lite_bing_code', ''); ?>" class="regular-text code">
		<?php
	}

	public function facebook_app_id_input()
	{
		?>
		<input name="seo_lite_facebook_app_id" type="text" id="seo_lite_facebook_app_id" value="<?php echo get_option('seo_lite_facebook_app_id', ''); ?>" class="regular-text code">
		<?php
	}

	public function twitter_username_input()
	{
		?>
		<input name="seo_lite_twitter_username" type="text" id="seo_lite_twitter_username" value="<?php echo get_option('seo_lite_twitter_username', ''); ?>" class="regular-text code">
		<?php
	}
}
