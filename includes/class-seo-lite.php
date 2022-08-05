<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

class Seo_Lite
{
	private $actions;
	private $filters;

	// Defines the core functionality of the plugin.
	public function __construct()
	{
		$this->actions = array();
		$this->filters = array();

		// Set locale
		$this->add_action('plugins_loaded', $this, 'load_plugin_textdomain');

		// Register relevant hooks
		if (is_admin()) {
			$this->define_admin_hooks();
		} else {
			$this->define_public_hooks();
		}
	}

	// Execute all of the hooks with WordPress.
	public function run()
	{
		foreach ($this->filters as $hook => $args) add_filter($hook, array($args['object'], $args['callback']), $args['priority'], $args['accepted_args']);
		foreach ($this->actions as $hook => $args) add_action($hook, array($args['object'], $args['callback']), $args['priority'], $args['accepted_args']);
	}

	// Sets the locale of the plugin
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain('seo-lite', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/');
	}

	// Register all of the hooks related to the admin area functionality of the plugin.
	private function define_admin_hooks()
	{
		require_once plugin_dir_path( dirname(__FILE__ )) .	'admin/class-seo-lite-admin.php';
		$plugin_admin = new Seo_Lite_Admin();

		$this->add_action('admin_menu',	$plugin_admin, 'add_options_page');
		$this->add_action('admin_init',	$plugin_admin, 'options_page_init');
	}

	// Register all of the hooks related to the public-facing functionality of the plugin.
	private function define_public_hooks()
	{
		require_once plugin_dir_path( dirname(__FILE__ )) .	'public/class-seo-lite-public.php';
		$plugin_public = new Seo_Lite_Public();

		$this->add_action('wp_head', $plugin_public, 'seo_head');
	}

	// Convenience functions for registering actions and filters with the plugin
	private function add_action($hook, $object, $callback, $priority = 10, $accepted_args = 1) { $this->actions[$hook] = array('object' => $object, 'callback' => $callback, 'priority' => $priority, 'accepted_args' => $accepted_args); }
	private function add_filter($hook, $object, $callback, $priority = 10, $accepted_args = 1) { $this->filters[$hook] = array('object' => $object, 'callback' => $callback, 'priority' => $priority, 'accepted_args' => $accepted_args); }
}