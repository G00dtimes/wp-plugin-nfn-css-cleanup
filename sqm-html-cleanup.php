<?php
/**
 * Plugin Name: NFN CSS Cleanup
 * Plugin URI: https://github.com/G00dtimes/wp-plugin-nfn-css-cleanup
 * Version: 1.0.0
 * Description: A plugin that handles CSS cleanup.
 * Author: G00dtimes
 * Author URI: nikfr80s@gmail.com
 */

 /**
 * Clean up the `wp_head`
 */
add_action('init', function() {
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10);
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('use_default_gallery_style', '__return_false');
	add_filter('emoji_svg_url', '__return_false');
	add_filter('the_generator', '__return_false');
	add_filter('xmlrpc_enabled', '__return_false');
});

/**
 * Clean the output of stylesheet <link> tags
 *
 * @param $input
 *
 * @return string
 */
add_filter('style_loader_tag', function($input) {
	preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
	if (empty($matches[2])) {
		return $input;
	}

	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
});

/**
 * Clean up the <script> tags
 * @param $input
 *
 * @return mixed
 */
add_filter('script_loader_tag', function($input) {
	$input = str_replace("type='text/javascript' ", ' ', $input);
	return str_replace("'", '"', $input);
});

/**
 * Add and remove body_class() classes
 * @param $classes
 *
 * @return array
 */
add_filter('body_class', function($classes) {
	// Add the post/page slug
	if (is_single() || is_page() && !is_front_page()) {
		if (!in_array(basename(get_permalink()), $classes)) {
			$classes[] = basename(get_permalink());
		}
	}

	 // Remove unnecessary classes
	$home_id_class = 'page-id-' . get_option('page_on_front');
	$remove_classes = [
		'page-template-default',
		$home_id_class,
	];
	return array_diff($classes, $remove_classes);
});

/**
 * Wraps media as suggested by Readability
 * @param $cache
 *
 * @return string
 */
add_filter('embed_oembed_html', function($cache) {
	return "<div class='entry-content-asset'>{$cache}</div>";
});

/**
 *  Remove dashboard widgets we don't need
 */
add_action('admin_init', function() {
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
});
/**
 * @param $input
 *
 * @return mixed
 */
function remove_self_closing_tags($input) {
	return str_replace(' />', '>', $input);
}
add_filter('get_avatar', 'remove_self_closing_tags');
add_filter('comment_id_fields', 'remove_self_closing_tags');
add_filter('post_thumbnail_html',  'remove_self_closing_tags');

add_filter('get_bloginfo_rss', function($bloginfo) {
	$default_tagline = 'Just another WordPress site';
	return ($bloginfo === $default_tagline) ? '' : $bloginfo;
});