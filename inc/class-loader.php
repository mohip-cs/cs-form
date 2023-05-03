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
 * CF register block and rander callback.
 *
 * @since 1.0.0
 *
 * @return void
 */
class Loader
{
    /**
     * Add actions.
     */
    public function __construct()
    {
        add_action('init', array($this, 'cs_form_register_post_meta'));
        add_action('init', array($this, 'cs_form_contact_form_block_init'));
        add_action('init', array($this, 'cs_form_listing_contact_form_block_init'));
    }

    function cs_form_contact_form_block_init()
    {
        register_block_type(
            plugin_dir_path(__DIR__) . '/build',
            array(
                'render_callback' => array($this, 'render_callback_cf'),
            )
        );
    }

    function render_callback_cf($attributes)
    {
        update_post_meta($attributes['postId'], 'cf_attributes', $attributes);
    }

    function cs_form_register_post_meta()
    {
        register_post_meta(
            'contact-form',
            'cf_attributes',
            [
                'type' => 'array',
                'show_in_rest' => true,
            ]
        );
    }

    function cs_form_listing_contact_form_block_init()
    {
        register_block_type(
            'listing-contact-form-cs/listing-contact-form',
            array(
                'render_callback' => array($this, 'render_callback_post'),
                'attributes'      => array(
                    'postId' => array(
                        'type'    => 'integer',
                        'default' => 0,
                    )
                ),
            )
        );
    }

    function render_callback_post($attributes) {
        $data        = get_post_meta($attributes['postId'], 'cf_attributes');
        $site_key    = ( get_option( 'cs_form_site_key' ) ) ? get_option( 'cs_form_site_key' ) : '';
        $secret_key  = ( get_option( 'cs_form_secret_key' ) ) ? get_option( 'cs_form_secret_key' ) : '';
        $fields_name = [];
        ob_start(); ?>
        <form method="post" action="" id="contactForm">
            <?php
            if(!empty($data[0]['FormData'])) {
                foreach ($data[0]['FormData'] as $index=>$field) {
                    $placeholder = ('' !== $field['placeholder']) ? ($field['placeholder']) : '';
                    $required    = ('' !== $field['required']) ? ($field['required']) : false;
                    $class       = ('' !== $field['class']) ? ($field['class']) : 'cs_form_'.$field['type'];
                    $name        = ('' !== $field['name']) ? ($field['name']) : 'cs_form_'.$field['type'].$index;
                    $options     = ('' !== $field['options']) ? explode(",",$field['options']) : '';
                    $min         = ('' !== $field['min']) ? ($field['min']) : '';
                    $max         = ('' !== $field['max']) ? ($field['max']) : '';
                    $class       = ($required) ? $class.' required' : $class;
                    
                    if ( 'textarea' === $field['type'] ) { ?>
                            <div> <?php
                                if(isset($field['label'])) {
                                    ?>
                                    <label for=""><?php  echo esc_attr($field['label']); ?></label>
                                    <span class="cs-from-error-msg">This field is required</span><br>
                                    <?php
                                } ?>
                                <textarea class="<?php echo esc_attr($class); ?>" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php  echo esc_attr($placeholder); ?>"></textarea> 
                            </div> <?php
                    } elseif ('action' === $field['type'] ) {
                        // doaction will perform here
                    } elseif ('drop-down' === $field['type'] ) {
                        $multiple = ( isset($field['multiple']) && true === $field['multiple'] ) ? 'multiple' : '';
                        ?>
                            <div><?php
                                if(isset($field['label'])) {
                                    ?>
                                    <label for="<?php echo esc_attr($field['label']); ?>"><?php echo esc_attr($field['label']); ?></label>
                                    <span class="cs-from-error-msg">This field is required</span><br>
                                    <?php
                                }
                                ?>
                                <select id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>" <?php echo esc_attr($multiple); ?>>
                                <option value="" selected disabled hidden>Select an Option</option>
                                    <?php  
                                        foreach ($options as $option) {
                                            ?>
                                                <option value="<?php echo esc_attr($option); ?>"><?php echo esc_attr($option); ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        <?php
                    } elseif ('checkboxes' === $field['type'] ) {
                        ?>
                            <div> <?php
                                if(isset($field['label'])) {
                                    ?>
                                    <label for=""><?php  echo esc_attr($field['label']); ?></label>
                                    <span class="cs-from-error-msg">This field is required</span><br>
                                    <?php
                                } 
                                foreach ($options as $option) {
                                    ?>
                                        <input type="checkbox" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>" value="<?php echo esc_attr($option); ?>">
                                        <label for="<?php echo esc_attr($option); ?>"> <?php echo esc_attr($option); ?></label><br>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php
                    } elseif ('radio_buttons' === $field['type'] ) {
                        ?>
                            <div> <?php
                                if(isset($field['label'])) {
                                    ?>
                                    <label for=""><?php  echo esc_attr($field['label']); ?></label>
                                    <span class="cs-from-error-msg">This field is required</span><br>
                                    <?php
                                } 
                                foreach ($options as $option) {
                                ?>
                                    <input type="radio" id="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($option); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>">
                                    <label for="<?php echo esc_attr($option); ?>"> <?php echo esc_attr($option); ?></label><br>
                                <?php
                                }
                            ?>
                            </div>
                        <?php
                    } else {
                        ?>
                            <div> <?php
                                if(isset($field['label'])) {
                                    ?>
                                    <label for=""><?php echo esc_attr($field['label']); ?></label>
                                    <span class="cs-from-error-msg">This field is required</span><br>
                                    <?php
                                } ?>
                                <input type="<?php echo esc_attr($field['type']); ?>" id="<?php echo esc_attr($name); ?>" placeholder="<?php  echo esc_attr($placeholder); ?>" name="<?php echo esc_attr($name); ?>" min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" class="<?php echo esc_attr($class); ?>">
                            </div>
                        <?php
                    }
                    $fields_name[]= $name;
                }
            }
        ?>
            <button type="submit"  class="g-recaptcha"
            id="cs-form-button"
            data-sitekey="<?php echo esc_attr($site_key); ?>" 
            data-callback='csFormOnSubmit' 
            data-action='submit'><?php echo esc_attr($data[0]['buttonText']); ?></button>
        </form>
        <?php
        $this->recaptcha_validation($secret_key, $data[0]['mailBody'], $fields_name);
        return ob_get_clean();
    }

    /**
	 * Function for validate recaptcha.
	 *
	 * @param array $action Allowed block types.
	 *
	 * @return void
	 */
    private function recaptcha_validation($secret_key, $mail_body, $fields_name) {
        $field_data = [];

        foreach($mail_body as $text) {
            switch ($text['label']) {
                case 'To':
                   $body_text['to'] = $text['text'];
                    break;
                case 'From':
                   $body_text['from'] = $text['text'];
                    break;
                case 'Subject':
                   $body_text['subject'] = $text['text'];
                    break;
                case 'cc':
                   $body_text['cc'] = $text['text'];
                    break;
                case 'bcc':
                   $body_text['bcc'] = $text['text'];
                    break;
                case 'Message Body':
                   $body_text['message'] = $text['text'];
                    break;
                case 'File attachments':
                   $body_text['file'] = $text['text'];
                    break;
                default:
                    break;
            }
        }

        if( str_contains($body_text['to'], '[') && str_contains($body_text['to'], ']') ) {
            $to = trim($body_text['to'], '[]');
            $to = ($to === 'site_admin_email') ? get_option( 'admin_email' ) : '';
        } elseif (str_contains($body_text['to'], '{') && str_contains($body_text['to'], '}')) {
            $to = trim($body_text['to'], '{}');
        }

        if( str_contains($body_text['from'], '[') && str_contains($body_text['from'], ']') ) {
            $from = trim($body_text['from'], '[]');
            $from = ($from === 'site_admin_email') ? get_option( 'admin_email' ) : '';
        } elseif (str_contains($body_text['from'], '{') && str_contains($body_text['from'], '}')) {
            $from = trim($body_text['from'], '{}');
        }

        if( !empty($secret_key) && isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  
            var_dump($fields_name); die();
            foreach($fields_name as $fields_name) {
                $field_data[] = sanitize_text_field( filter_input( INPUT_POST, $fields_name ) );
            }
            
            // Google reCAPTCHA verification API Request  
            $api_url = 'https://www.google.com/recaptcha/api/siteverify';
            $resq_data = array(  
                'secret' => $secret_key,  
                'response' => $_POST['g-recaptcha-response'],  
                'remoteip' => $_SERVER['REMOTE_ADDR']  
            );  

            $curlConfig = array(  
                CURLOPT_URL => $api_url,  
                CURLOPT_POST => true,  
                CURLOPT_RETURNTRANSFER => true,  
                CURLOPT_POSTFIELDS => $resq_data  
            );  

            $ch = curl_init();  
            curl_setopt_array($ch, $curlConfig);  
            $response = curl_exec($ch);  
            curl_close($ch);  

            // Decode JSON data of API response in array  
            $responseData = json_decode($response);  

            //If the reCAPTCHA API response is valid  
            if($responseData->success){ 
                // Send email notification to the site admin  
                // $to = 'patelmohip9@gmail.com';  
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
                $status = 'success';  
                $statusMsg = 'Thank you! Your contact request has been submitted successfully.';  
                $postData = '';  
            }else{  
                $statusMsg = 'The reCAPTCHA verification failed, please try again.';  
            }
        }
        if(!empty($statusMsg)){ ?>
             <p class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></p>
             <?php
        }
    }
}

if (class_exists('Loader')) {
    new Loader();
}
