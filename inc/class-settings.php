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
    }

    /**
     * Register a custom menu page.
     */
    public function cs_form_setting_menu_page(){
        add_submenu_page( 
            'edit.php?post_type=contact_forms',
            __( 'CS Settings', 'cs-form' ),
            __( 'Settings', 'cs-form' ),
            'manage_options',
            'cs-form-setting',
            array($this, 'cs_form_setting_page_callback'),
        ); 
    }

    public function cs_form_setting_page_callback(){
        ?>
        <div class="wrap">
            <div>
                <h1><?php _e( 'Settings', 'cs-form' ); ?></h1>
                <p><?php _e( ' reCAPTCHA', 'cs-form' ); ?></p>
            </div>
            <div>
                <label for="site-key">Site key </label>
                <input type="text" name="site-key" id="recaptcha-site-key" class="site-key">
                <label for="site-key">Secret  key </label>
                <input type="text" name="secret-key" id="recaptcha-secret-key" class="secret-key">
                <button>Save Changes</button>
            </div>
        </div>
        <?php   
    }

}

if (class_exists('Loader')) {
    new Settings();
}