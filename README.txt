=== Plugin Name ===
Contributors: apedestrian
Tags: seo, facebook, twitter
Tested up to: 6.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds all of the best practice SEO meta tags to you site.

== Description ==

SEO Lite is meant to be that: lite. Adds all the best practive SEO meta tags and nothing more. It is reccomended that you fill out the Google and Bing verification code in the settings section. Additionally, there is a setting to add your Facebook App ID or Twitter Username for analytics on these platforms.

== For Developers ==

Available Filters:

* seo_lite_meta_title:		SEO Lite will use wp_get_document_title() unless overriden with this filter
* seo_lite_meta_description:	SEO Lite will use the excerpt on pages/posts, archive descriptions on
				archive pages, and the value you set for Site Description inside SEO Lite Settings.
* seo_lite_meta_type:		SEO Lite will use the type article on pages/posts and website everywhere else unless
				overriden with this filter
* seo_lite_meta_attachment_id:	SEO Lite will use a thumbnail on pages/posts and your site icon everywhere else
				unless overriden with this filter