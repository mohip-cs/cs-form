<?php
/**
 *  Loader.
 *
 * @package CS Form
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Cs Form setting page
 *
 * @since 1.0.0
 *
 * @return void
 */
class Settings {
     /**
     * Add actions.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'cs_form_setting_menu_page'));
        add_action( 'wp_ajax_cs_form_recaptcha', [ $this, 'save_cs_form_recaptcha' ], 10 );
		add_action( 'wp_ajax_nopriv_cs_form_recaptcha', [ $this, 'save_cs_form_recaptcha' ], 10 );
    }

    /**
     * Register a custom menu page.
     */
    public function cs_form_setting_menu_page(){
        add_submenu_page( 
            'edit.php?post_type=cs_forms',
            __( 'CS Settings', 'cs-form' ),
            __( 'Settings', 'cs-form' ),
            'manage_options',
            'cs-form-setting',
            array($this, 'cs_form_setting_page_callback'),
        ); 
    }

    public function cs_form_setting_page_callback(){
        $site_key   = ( get_option( 'cs_form_site_key' ) ) ? get_option( 'cs_form_site_key' ) : '';
        $secret_key = ( get_option( 'cs_form_secret_key' ) ) ? get_option( 'cs_form_secret_key' ) : '';
        ?>
        <div class="wrap">
       
        <div class="notice notice-error recaptcha-error">
            <p>
                <strong><?php _e('Error', 'cs-form'); ?></strong><?php _e(': Invalid key values.', 'cs-form'); ?>
            </p>
        </div>
        <div class="notice notice-success recaptcha-success">
            <p>
                <?php _e('Settings saved.', 'cs-form'); ?>
            </p>
        </div>
        <div>
                <h1><?php _e( 'Settings', 'cs-form' ); ?></h1>
                <p><?php _e( ' reCAPTCHA', 'cs-form' ); ?></p>
            </div>
            <div>
                <form method="post" action="" id="recaptcha-setting-form"></form>
                    <label for="site-key">Site key </label>
                    <input type="text" name="site-key" id="recaptcha-site-key" class="site-key" value="<?php echo esc_attr($site_key); ?>">
                    <label for="site-key">Secret  key </label>
                    <input type="text" name="secret-key" id="recaptcha-secret-key" class="secret-key" value="<?php echo esc_attr($secret_key); ?>">
                    <button id="recaptcha-submit" >Save Changes</button>
                </form>
            </div>
        </div>
        <?php   
    }

     /**
	 * Ajax function for store recaptcha keys.
	 *
	 * @return void
	 */
	public function save_cs_form_recaptcha() {
        $nonce = sanitize_text_field($_POST['Nonce']);
        if ( empty( $_POST ) || ! wp_verify_nonce( $nonce, 'cs-form-recaptch-key' ) ) {
            wp_send_json_error();
        }
        $site_key = isset( $_POST['formData']['siteKey'] ) ? sanitize_text_field( $_POST['formData']['siteKey'] ) : '';
        $secret_key = isset( $_POST['formData']['secretKey'] ) ? sanitize_text_field( $_POST['formData']['secretKey'] ) : '';

        if( !empty($site_key) && !empty($secret_key)  ) {
            update_option( 'cs_form_site_key', $site_key );
            update_option( 'cs_form_secret_key', $secret_key );

            wp_send_json_success('keys added successfully.', 200 );
        }
        wp_send_json_error();
    }
}

if (class_exists('Loader')) {
    new Settings();
}