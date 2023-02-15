<?php

/**
 * Plugin Name:       Contact Form
 * Description:       utenberg block to adjust sticky video on frontend side.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Creole Studios
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       contact-form
 *
 * @package           contact-form-cs
 */

if (!version_compare(PHP_VERSION, '5.0', '>=')) {
	add_action('admin_notices', 'fail_php_version');
} elseif (!version_compare(get_bloginfo('version'), '5.0', '>=')) {
	add_action('admin_notices', 'fail_wp_version');
} else {
	require_once 'inc/class-loader.php';
	require_once 'inc/class-contact-form-post-type.php';
}

/**
 * Sticky Video for Youtube admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function fail_php_version()
{
	/* translators: %s: PHP version */
	$message      = sprintf(esc_html__('Sticky Video for Youtube requires PHP version %s+, plugin is currently NOT RUNNING.', 'youtube-sticky-video'), '5.0');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}


/**
 * Sticky Video for Youtube admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.0.0
 *
 * @return void
 */
function fail_wp_version()
{
	/* translators: %s: WordPress version */
	$message      = sprintf(esc_html__('Sticky Video for Youtube requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'youtube-sticky-video'), '5.0');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
// var_dump(__DIR__ . '/build'); die();
// function contact_form_cs_contact_form_block_init() {
// 	register_block_type( __DIR__ . '/build',
// 	array(
// 		'render_callback' => array( 'render_callback_cf' ),
// 		'attributes'      => array(
// 			'post_id'        => array(
// 				'type'    => 'string',
// 				'default' => '',
// 			)
// 		),
// 	)
// 	);
// }
// add_action( 'init', 'contact_form_cs_contact_form_block_init' );

// function contact_form_cs_listing_contact_form_block_init() {
// 	register_block_type( 'listing-contact-form-cs/listing-contact-form',
// 	array(
// 		'render_callback' => array( 'render_callback_post' ),
// 		'attributes'      => array(
// 			'post_id'        => array(
// 				'type'    => 'string',
// 				'default' => '',
// 			)
// 		),
// 	)
// );
// }
// add_action( 'init', 'contact_form_cs_listing_contact_form_block_init' );

// function render_callback_post($attributes) {
// 	var_dump('hello'); die();

// 	ob_start();
// 	return ob_get_clean();
// }

// function render_callback_cf($attributes) {
// 	// var_dump('hello'); die();

// 	ob_start();
// 	return ob_get_clean();
// }