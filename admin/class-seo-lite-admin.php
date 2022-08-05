<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

class Seo_Lite_Admin
{
	// Add menu item to admin area
	public function add_options_page()
	{
		add_options_page(
			'SEO Lite Settings', // string $page_title
			'SEO Lite', // string $menu_title
			'manage_options', // string $capability
			'seo-lite-options-page', // string $menu_slug
			array($this, 'display_options_page') // callable $callback
		);
	}

	// Displays the admin page
	public function display_options_page()
	{
		?>
		<div class="wrap">
			<h1>SEO Lite Settings</h1>
			<form method="post" action="options.php">
				<?php
				do_settings_sections('seo-lite-options-page'); // $menu_slug from add_options_page() call
				settings_fields('seo_lite_settings_option_group'); // $option_group from register_setting calls
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	public function options_page_init()
	{
		// Add sections to form
		// add_settings_section(string $id, string $title, callable $callback (section description), string $page ($menu_slug from add_options_page))
		add_settings_section('seo_lite_general', 'General Settings', null, 'seo-lite-options-page');
		add_settings_section('seo_lite_optional', 'Optional Settings', null, 'seo-lite-options-page');

		// Register settings
		// register_setting(string $option_group, string $option_name, array $args)
		register_setting('seo_lite_settings_option_group', 'seo_lite_site_description',	array('type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'));
		register_setting('seo_lite_settings_option_group', 'seo_lite_add_title_tag',	array('type' => 'boolean','sanitize_callback' => function($val) { return intval($val) > 0 ? 1 : 0; }));
		register_setting('seo_lite_settings_option_group', 'seo_lite_google_code',		array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
		register_setting('seo_lite_settings_option_group', 'seo_lite_bing_code',		array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
		register_setting('seo_lite_settings_option_group', 'seo_lite_facebook_app_id',	array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));
		register_setting('seo_lite_settings_option_group', 'seo_lite_twitter_username',	array('type' => 'string', 'sanitize_callback' => 'sanitize_text_field'));

		// Add the registered settings fields to sections
		// add_settings_field(string $option_name,		string $title,				callable $callback (field input),		string $page,				string $section)
		add_settings_field('seo_lite_site_description', 'Site Description',			array($this, 'site_description_input'),	'seo-lite-options-page',	'seo_lite_general');
		add_settings_field('seo_lite_add_title_tag',	'Add &lt;title&gt; Tag',	array($this, 'add_title_tag_input'),	'seo-lite-options-page',	'seo_lite_general');
		add_settings_field('seo_lite_google_code',		'Google Validation Code',	array($this, 'google_code_input'),		'seo-lite-options-page',	'seo_lite_optional');
		add_settings_field('seo_lite_bing_code',		'Bing Validation Code',		array($this, 'bing_code_input'),		'seo-lite-options-page',	'seo_lite_optional');
		add_settings_field('seo_lite_facebook_app_id',	'Facebook App ID',			array($this, 'facebook_app_id_input'),	'seo-lite-options-page',	'seo_lite_optional');
		add_settings_field('seo_lite_twitter_username',	'Twitter Username',			array($this, 'twitter_username_input'),	'seo-lite-options-page',	'seo_lite_optional');
	}

	public function site_description_input()
	{
		$this->print_option_input(
			'seo_lite_site_description',
			'textarea',
			'The site description is used for the meta description of your front page. It is also the fallback in the case that a description cannot be generated for a page/post (excerpt) or archive page (tag/category description)'
		);
	}

	public function add_title_tag_input()
	{
		$this->print_option_input(
			'seo_lite_add_title_tag',
			'checkbox',
			htmlspecialchars('Have SEO Lite add the <title> tag between your sites <head> tags. Leave this as false if your theme already takes care of this')
		);
	}

	public function google_code_input() { $this->print_option_input('seo_lite_google_code', 'text'); }
	public function bing_code_input() { $this->print_option_input('seo_lite_bing_code', 'text'); }
	public function facebook_app_id_input() { $this->print_option_input('seo_lite_facebook_app_id', 'text', 'Used for Facebook analytics'); }
	public function twitter_username_input() { $this->print_option_input('seo_lite_twitter_username', 'text', 'Used for Twitter analytics'); }

	private function print_option_input($option_name, $type, $description_text = '')
	{
		switch ($type)
		{
			case 'text':
			{
				?>
				<input name="<?php echo esc_attr($option_name); ?>" type="text" id="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr(get_option($option_name, '')); ?>" class="regular-text code">
				<?php
				if ($description_text != '')
				{
					echo '<p>' . esc_html($description_text) . '</p>';
				}
				break;
			}
			case 'checkbox':
			{
				?>
				<label for="<?php echo esc_attr($option_name); ?>">
					<input name="<?php echo esc_attr($option_name); ?>" type="checkbox" id="<?php echo esc_attr($option_name); ?>" value="1" <?php checked(1, get_option($option_name), true); ?>>
					<?php
					if ($description_text != '')
					{
						echo esc_html($description_text);
					}
					?>
				</label>
				<?php
				break;
			}
			case 'textarea':
			{
				?>
				<textarea name="<?php echo esc_attr($option_name); ?>" id="<?php echo esc_attr($option_name); ?>" class="large-text" rows="4"><?php echo esc_textarea(get_option($option_name, '')); ?></textarea>
				<?php
				if ($description_text != '')
				{
					echo '<p>' . esc_html($description_text) . '</p>';
				}
				break;
			}
		}
	}
}
