<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

class Seo_Lite_Public
{
	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	// Add SEO meta tags to the page
	public function seo_head()
	{
		$title = $this->get_meta_title();
		?>
		<!------------ SEO ------------>
		<meta property="og:locale" content="<?php echo get_locale(); ?>">
		<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>">
		<meta property="og:description" content="<?php echo $this->get_meta_description(); ?>">
		<meta property="og:type" content="<?php echo $this->get_meta_type(); ?>">
		<meta property="og:url" content="<?php echo $this->get_meta_url(); ?>">
		<meta property="og:title" content="<?php echo $title; ?>">
		<?php 
		if (get_option('seo_lite_add_title_tag') == 1)
		{
			?>
			<title><?php echo $title; ?></title>
			<?php
		}

		// Add img tags if we have one
		if ($image_info = $this->get_meta_image_info())
		{
			foreach ($image_info as $property => $content)
			{
				?>
				<meta property="<?php echo $property; ?>" content="<?php echo $content; ?>">
				<?php
			}
		}

		// Print publish date if we are a page/post
		if (is_page() || is_single())
		{
			?>
			<meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
			<meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">
			<?php
		}

		// Do we have a facebook app id?
		if ($facebook_app_id = get_option('seo_lite_facebook_app_id'))
		{
			?>
			<meta property="fb:app_id" content="<?php echo $facebook_app_id; ?>">
			<?php
		}

		// Do we have a twitter username?
		if ($twitter_username = get_option('seo_lite_twitter_username'))
		{
			?>
			<meta name="twitter:site" content="@<?php echo $twitter_username; ?>">
			<?php
		}

		// Add validation codes if this is the front page
		if (is_front_page())
		{
			if ($option = get_option('seo_lite_google_code'))
			{
				echo '<meta name="google-site-verification" content="' . $option . '">';
			}

			if ($option = get_option('seo_lite_bing_code'))
			{
				echo '<meta name="msvalidate.01" content="' . $option . '">';
			}
		}
		?>
		<!------------ SEO ------------>
		<?php
	}

	private function get_meta_title()
	{
		return apply_filters('seo_lite_meta_title', wp_get_document_title());
	}

	private function get_meta_description()
	{
		$description = apply_filters('seo_lite_meta_description', '');

		if ($description == '')
		{
			if (is_archive())
			{
				$description = get_the_archive_description();
			}
			elseif (is_single() || is_page())
			{
				$description = get_the_excerpt();
			}
		}

		if ($description == '')
		{
			$description = get_option('seo_lite_site_description', '');
		}

		return strlen($description) > 200 ? substr($description, 0, 197) . '...' : $description;
	}

	private function get_meta_type()
	{
		return apply_filters('seo_lite_meta_type', (is_single() || is_page() ? 'article' : 'website'));
	}

	private function get_meta_url()
	{
		$url = '';

		// Permalinks set to plain
		if (is_single() || is_page())
		{
			$url = get_the_permalink();
		}
		elseif (is_front_page())
		{
			$url = home_url();
		}
		else
		{
			global $wp;

			// Permalinks set to plain
			if (get_option('permalink_structure') == '')
			{
				$url = add_query_arg($wp->query_vars, home_url());
			}
			else
			{
				$url = home_url($wp->request);
			}
		}

		return $url;
	}

	private function get_meta_image_info()
	{
		$attachment_id = apply_filters('seo_lite_meta_attachment_id', false);
		$image_info = array();

		if ($attachment_id == false)
		{
			// Try to get attackment id from page/post
			if ((is_single() || is_page()) && has_post_thumbnail())
			{
				$attachment_id = get_post_thumbnail_id();
			}
		}

		if ($attachment_id != false)
		{
			$attachment_meta = wp_get_attachment_metadata($attachment_id);
			$attachment_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

			$image_info['og:image'] = wp_get_attachment_url($attachment_id);
			$image_info['og:image:type'] = get_post_mime_type($attachment_id);
			$image_info['og:image:width'] = $attachment_meta['width'];
			$image_info['og:image:height'] = $attachment_meta['height'];
			$image_info['og:image:alt'] = ($attachment_alt == '' ? get_bloginfo('name') : $attachment_alt);
			$image_info['twitter:image:alt'] = $image_info['og:image:alt'];
			$image_info['twitter:card'] = 'summary_large_image';
		}
		elseif (has_site_icon())
		{
			$url = get_site_icon_url();

			$image_info['og:image'] = $url;
			// Try and get the mime type of the icon
			if ($attachment_id = attachment_url_to_postid($url))
			{
				$image_info['og:image:type'] = get_post_mime_type($attachment_id);
			}
			$image_info['og:image:width'] = 512;
			$image_info['og:image:height'] = 512;
			$image_info['og:image:alt'] = get_bloginfo('name');
			$image_info['twitter:image:alt'] = $image_info['og:image:alt'];
			$image_info['twitter:card'] = 'summary_large_image';
		}
		else
		{
			$image_info['twitter:card'] = 'summary';
		}

		return $image_info;
	}
}
