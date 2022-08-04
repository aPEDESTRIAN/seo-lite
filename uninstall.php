<?php

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN'))
{
	die;
}
else
{
	delete_option('seo_lite_google_code');
	delete_option('seo_lite_bing_code');
	delete_option('seo_lite_site_description');
	delete_option('seo_lite_add_title_tag');
	delete_option('seo_lite_facebook_app_id');
	delete_option('seo_lite_twitter_username');
}