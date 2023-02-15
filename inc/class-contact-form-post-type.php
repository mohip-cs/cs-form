<?php

/**
 *  Contact form post type.
 *
 * @package CF
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Manage Contact form.
 *
 * @since 1.0.0
 *
 * @return void
 */
class ContactForm
{
    const POST_TYPE_SLUG = 'contact_forms';
    /**
     * Add actions.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init'], 9999);
        add_filter('allowed_block_types_all', [$this, 'CSCF_allowed_block_types'], 10, 2);
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ], 10, 1 );
        add_filter( 'post_row_actions', [ $this, 'remove_view_link' ] );

    }

    /**
     * Initalize function.
     *
     * @return void
     */
    public function init(): void
    {
        $this->register_contact_form_post_type();
    }

    /**
     * Register the Contact form post type.
     *
     * @return void
     */
    private function register_contact_form_post_type(): void {
        register_post_type(
            self::POST_TYPE_SLUG,
            [
                'labels'             => [
                    'name'                  => __('Contact Form', 'cs-contact-form'),
                    'singular_name'         => __('Contact Form', 'cs-contact-form'),
                    'all_items'             => __('All Contact Forms', 'cs-contact-form'),
                ],
                'has_archive'        => true,
                'menu_icon'          => 'dashicons-visibility',
                'supports'           => ['title', 'editor', 'custom-fields'],
                'show_in_rest'       => true,
                'show_in_menu'       => true,
                'public'             => false,
                'publicly_queryable' => true,
                'query_var'          => true,
                'template'           => [['contact-form-cs/contact-form']],
                'show_ui'            => true,
            ]
        );
    }

    /**
	 * Function for allowed contact form post type.
	 *
	 * @param array $allowed_block_types Allowed block types.
	 * @param object $editor_context Current editor context.
	 *
	 * @return array
	 */
    public function CSCF_allowed_block_types($allowed_block_types, $editor_context) {
        if ( 'contact_forms' === $editor_context->post->post_type ) {
            return ['contact-form-cs/contact-form'];
        }
        return $allowed_block_types;
    }

    /**
	 * Enqueue custom field assets.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
        wp_enqueue_style(
			'contact-form-admin-css',
			plugins_url( "/src/styles/contact-form-admin-style.css", __DIR__ ),
			[],
			filemtime("/src/styles/contact-form-admin-style.css" ),
			'all'
		);
    }

    /**
	 * Function for allowed contact form post type.
	 *
	 * @param array $action Allowed block types.
	 *
	 * @return array
	 */
    public function remove_view_link( $action ) {
        global $post;
        if ( 'contact_forms' === $post->post_type ) {
            unset ($action['view']);
            return $action;
        }
        return $action;
    }
}

if (class_exists('ContactForm')) {
    new ContactForm();
}