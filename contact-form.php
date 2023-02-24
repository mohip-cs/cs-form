<?php

/**
 * Plugin Name:       CS Form
 * Description:       utenberg block For create contact form.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           1.0.0
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
	require_once 'inc/class-settings.php';
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
	$message      = sprintf(esc_html__('CS Form requires PHP version %s+, plugin is currently NOT RUNNING.', 'cs-form'), '5.0');
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
	$message      = sprintf(esc_html__('CS Form requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'cs-form'), '5.0');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}