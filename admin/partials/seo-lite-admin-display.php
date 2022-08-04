<?php

if (!defined('ABSPATH')) { die; } // If this file is called directly, abort.

?>
<div class="wrap">
	<h1>SEO Lite Settings</h1>
	<form method="post" action="options.php">
		<?php
		do_settings_sections('seo-lite-options-page');		// string $page from add_options_page() call
		settings_fields('seo_lite_settings_option_group');	// string $option_group from register_settings calls
		submit_button();
		?>
	</form>
</div>