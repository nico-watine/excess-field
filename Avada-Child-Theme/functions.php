<?php

function theme_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'avada-stylesheet' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );


// Be sure Avada JS Compiler is functioning
add_filter( 'fusion_compiler_js_file_is_readable', '__return_true' );


// Slim down <head> requests
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rel_alternate');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action( 'wp_head', 'wp_resource_hints', 2, 99 );
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
add_filter('embed_oembed_discover', '__return_false');
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_action('wp_print_styles', 'print_emoji_styles');


//Remove JQuery migrate
function remove_jquery_migrate($scripts)
{
	if (!is_admin() && isset($scripts->registered['jquery'])) {
		$script = $scripts->registered['jquery'];

		if ($script->deps) { // Check whether the script has any dependencies
			$script->deps = array_diff($script->deps, array(
				'jquery-migrate'
			));
		}
	}
}
add_action('wp_default_scripts', 'remove_jquery_migrate');


// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
	wp_dequeue_style( 'wp-block-library' ); // WordPress core
	wp_dequeue_style( 'wp-block-library-theme' ); // WordPress core
	wp_dequeue_style( 'wc-block-style' ); // WooCommerce
	wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}


// Add Custom CSS to Admin Area
add_action('admin_head', 'custom_dashboard_css');

function custom_dashboard_css() {
	// Hide all plugin registration notices after installing WP-Optimize Premium
	echo '<style>
		.updated#udmupdater_not_connected {
			display: none !important;
			visibility: hidden !important;
		}
		[data-slug="wp-optimize-premium"] + tr {
			display: none !important;
			visibility: hidden !important;
		}
	</style>';
}
