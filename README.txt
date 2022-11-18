=== SEO Lite ===
Contributors: aPEDESTRIAN
Donate link: https://www.paypal.com/donate/?hosted_button_id=JTBPY8ZWAXG6N
Stable tag: 2.0.0
Tested up to: 6.1.1
Tags: seo, open graph, og
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds all of the basic Open Graph meta tags to the site head.

== Description ==

SEO Lite is meant to be that: lite. In under 100 lines of code, SEO Lite adds the following tags:

* og:locale - Uses get_locale()
* og:site_name - Uses get_bloginfo('name')
* og:title - Uses wp_get_document_title()
* og:description (trimmed to 200 chars) - Uses get_bloginfo('description') on the front page, get_the_excerpt() for posts/pages, and get_the_archive_description() if availble on archive pages
* og:url - URL of page
* og:type - Uses 'article' on posts/pages and 'website' everywhere else
* article:published_time - Uses get_the_date('c') on posts/pages
* article:modified_time - Uses get_the_modified_date('c') on posts/pages
* og:image - Uses featured image on posts/pages and the site icon on the front page
* og:image:{type/width/height/alt} - Added if we have an 'og:image' (only adds 'alt' if availble)