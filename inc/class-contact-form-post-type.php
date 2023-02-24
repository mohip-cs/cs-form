<?php

/**
 *  Contact form post type.
 *
 * @package CS Form
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
    const POST_TYPE_SLUG = 'cs_forms';
    /**
     * Add actions.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init'], 9999);
        add_filter('allowed_block_types_all', [$this, 'CSCF_allowed_block_types'], 10, 2);
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ], 10, 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 10, 1 );
        add_filter( 'post_row_actions', [ $this, 'remove_view_link' ] );
        add_action( 'wp_ajax_cs_form_submit', [ $this, 'validate_cs_form_data' ], 10 );
		add_action( 'wp_ajax_nopriv_cs_form_submit', [ $this, 'validate_cs_form_data' ], 10 );
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
                    'name'                  => __('Cs Form', 'cs-form'),
                    'singular_name'         => __('Form', 'cs-form'),
                    'all_items'             => __('All Forms', 'cs-form'),
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
        if ( self::POST_TYPE_SLUG === $editor_context->post->post_type ) {
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
        wp_enqueue_script( 
            'cs-form-recaptcha-js', 
            'https://www.google.com/recaptcha/api.js', 
            [],
            '1.0.0',
            false 
        );

		wp_enqueue_script( 
            'cs-form-js', 
            plugins_url( "/src/scripts/cs-from-script.js", __DIR__ ), 
            ['jquery'],
            '1.0.0',
            false 
        );

		$ajax_data = [
			'nonce'   => wp_create_nonce( 'cs-form-validation' ),
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		];
		wp_localize_script( 'cs-form-js', 'csFormSubmitAjax', $ajax_data );
		
    }
    /**
	 * Enqueue custom admin field assets.
	 *
	 * @return void
	 */
	public function admin_enqueue_assets() {
        wp_enqueue_style(
			'cs-form-admin-css',
			plugins_url( "/src/styles/cs-form-admin-style.css", __DIR__ ),
			[],
			'1.0.0',
			'all'
		);
        wp_enqueue_script( 
            'cs-form-admin-js', 
            plugins_url( "/src/scripts/cs-form-admin-script.js", __DIR__ ), 
            array( 'jquery' ),
            '1.0.0',
            false 
        );

        $ajax_data = [
			'nonce'   => wp_create_nonce( 'cs-form-recaptch-key' ),
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		];
		wp_localize_script( 'cs-form-admin-js', 'csFormAjax', $ajax_data );
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
        if ( self::POST_TYPE_SLUG === $post->post_type ) {
            unset ($action['view']);
            return $action;
        }
        return $action;
    }

	 /**
	 * Ajax function for store recaptcha keys.
	 *
	 * @return void
	 */
	public function validate_cs_form_data() {
		$data = $_POST['formData'];
		// print_r($data); 
		if(false){ 
			$to = 'patelmohip9@gmail.com';  
			$subject = 'New Contact Request Submitted';  
			$htmlContent = "  
				<h4>Contact request details</h4>  
				<p><b>Name: </b></p>  
				<p><b>Email: </b></p>  
				<p><b>Message: </b></p>  
			";  
			
			// Always set content-type when sending HTML email  
			$headers = "MIME-Version: 1.0" . "\r\n";  
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";  
			// Sender info header  
			$headers .= 'From:'.$name.' <patelmohip911@gmail.com>' . "\r\n";  
			
			// Send email  
			// @mail($to, $subject, $htmlContent, $headers);  
			wp_mail( $to, $subject, $htmlContent, $headers );  
		}   
		wp_send_json_success('keys added successfully.', 200 );
	}
}

if (class_exists('ContactForm')) {
    new ContactForm();
}
