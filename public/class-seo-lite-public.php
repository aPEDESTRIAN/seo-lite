<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

class Seo_Lite_Public
{
	// Add SEO meta tags to the page
	public function seo_head()
	{
		?>
		<!------------ SEO ------------>
		<?php

		$meta_tags = $this->get_meta_tags();

		if (get_option('seo_lite_add_title_tag') == 1)
		{
			echo '<title>' . esc_html($meta_tags['og:title']) . '</title>';
		}

		foreach ($meta_tags as $property => $content)
		{
			echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '">';
		}

		?>
		<!------------ SEO ------------>
		<?php
	}

	private function get_meta_tags()
	{
		$meta_tags = array(
			'og:locale' =>		get_locale(),
			'og:site_name' =>	get_bloginfo('name'),
			'og:title' => 		apply_filters('seo_lite_meta_title', wp_get_document_title()),
			'og:description' =>	$this->get_meta_description(),
			'og:type' =>		apply_filters('seo_lite_meta_type', (is_single() || is_page() ? 'article' : 'website')),
			'og:url' =>			$this->get_meta_url()
		);

		$this->add_image_meta_tags($meta_tags);

		// Add publish/modified date if we are a page/post
		if (is_page() || is_single())
		{
			$meta_tags['article:published_time'] = get_the_date('c');
			$meta_tags['article:modified_time'] = get_the_modified_date('c');
		}

		// Do we have any social media analytics?
		if ($facebook_app_id = get_option('seo_lite_facebook_app_id'))		$meta_tags['fb:app_id'] = $facebook_app_id;
		if ($twitter_username = get_option('seo_lite_twitter_username'))	$meta_tags['twitter:site'] = "@{$twitter_username}";

		// Add validation codes if this is the front page
		if (is_front_page())
		{
			if ($google_code = get_option('seo_lite_google_code'))	$meta_tags['google-site-verification'] = $google_code;
			if ($bing_code = get_option('seo_lite_bing_code'))		$meta_tags['msvalidate.01'] = $bing_code;
		}

		return apply_filters('seo_lite_meta_tags', $meta_tags);
	}

	// Attempts to get the content for the og:description tag
	private function get_meta_description()
	{
		$description = apply_filters('seo_lite_meta_description', '');

		// No override description? Try and find one...
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

		// Still no description? Use fallback description
		if ($description == '')
		{
			$description = get_option('seo_lite_site_description', '');
		}

		// 200 chars is the MAX recommended size for the og:description tag; enforce it here!
		return strlen($description) > 200 ? substr($description, 0, 197) . '...' : $description;
	}

	// Gets the url of the current page
	private function get_meta_url()
	{
		if (is_single() || is_page()) {
			return get_the_permalink();
		} elseif (is_front_page()) {
			return home_url();
		} else {
			global $wp;

			// Permalinks set to plain?
			if (get_option('permalink_structure') == '') {
				return add_query_arg($wp->query_vars, home_url());
			} else {
				return home_url($wp->request);
			}
		}
	}

	// Adds image related meta tags to $meta_tags array
	private function add_image_meta_tags(&$meta_tags)
	{
		// Check for an override $attachment_id
		$attachment_id = apply_filters('seo_lite_meta_attachment_id', false);

		if ($attachment_id == false)
		{
			// Try to get attachment id from page/post
			if ((is_single() || is_page()) && has_post_thumbnail())
			{
				$attachment_id = get_post_thumbnail_id();
			}
			// Do we have a site icon we can use?
			elseif (has_site_icon())
			{
				$attachment_id = attachment_url_to_postid(get_site_icon_url());
			}
		}

		if ($attachment_id != false && $attachment_id != 0)
		{
			$attachment_meta = wp_get_attachment_metadata($attachment_id);
			$attachment_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

			$meta_tags['og:image'] = wp_get_attachment_url($attachment_id);
			$meta_tags['og:image:type'] = get_post_mime_type($attachment_id);
			$meta_tags['og:image:width'] = $attachment_meta['width'];
			$meta_tags['og:image:height'] = $attachment_meta['height'];
			$meta_tags['og:image:alt'] = ($attachment_alt == '' ? get_bloginfo('name') : $attachment_alt);
			$meta_tags['twitter:image:alt'] = $meta_tags['og:image:alt'];
			$meta_tags['twitter:card'] = 'summary_large_image';
		}
		else
		{
			$meta_tags['twitter:card'] = 'summary';
		}
	}
}
