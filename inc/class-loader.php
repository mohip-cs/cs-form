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
        // 	var_dump('hello'); die();

        register_block_type(
            'listing-contact-form-cs/listing-contact-form',
            array(
                'render_callback' => array($this, 'render_callback_post'),
                'attributes'      => array(
                    'postId'        => array(
                        'type'    => 'integer',
                        'default' => 0,
                    )
                ),
            )
        );
    }

    function render_callback_post($attributes) {
        $data       = get_post_meta($attributes['postId'], 'cf_attributes');
        $site_key   = ( get_option( 'cs_form_site_key' ) ) ? get_option( 'cs_form_site_key' ) : '';
        $secret_key = ( get_option( 'cs_form_secret_key' ) ) ? get_option( 'cs_form_secret_key' ) : '';
        ob_start(); ?>
        <form method="post" action="" id="contactForm">
        <script src="https://www.google.com/recaptcha/api.js"></script>
            <?php
            foreach ($data[0]['FormData'] as $index=>$field) {
                $placeholder = ('' !== $field['placeholder']) ? ($field['placeholder']) : '';
                $required = ('' !== $field['required']) ? ($field['required']) : false;
                $class = ('' !== $field['class']) ? ($field['class']) : 'cs_form_'.$field['type'];
                $name = ('' !== $field['name']) ? ($field['name']) : 'cs_form_'.$field['type'].$index;
                $options = ('' !== $field['options']) ? explode(",",$field['options']) : '';
                $min = ('' !== $field['min']) ? ($field['min']) : '';
                $max = ('' !== $field['max']) ? ($field['max']) : '';
                // print_r($field);
                if ( 'textarea' === $field['type'] ) { ?>
                          <div> <?php
                            if(isset($field['label'])) {
                                ?>
                                <label for=""><?php  echo esc_attr($field['label']); ?></label><br>
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
                                <label for="<?php echo esc_attr($field['label']); ?>"><?php echo esc_attr($field['label']); ?></label><br>
                                <?php
                            }
                            ?>
                            <select id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>" <?php echo esc_attr($multiple); ?>>
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
                                <label for=""><?php  echo esc_attr($field['label']); ?></label><br>
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
                                <label for=""><?php  echo esc_attr($field['label']); ?></label><br>
                                <?php
                            } 
                            foreach ($options as $option) {
                            ?>
                                <input type="radio" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>">
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
                                <label for=""><?php echo esc_attr($field['label']); ?></label><br>
                                <?php
                            } ?>
                            <input type="<?php echo esc_attr($field['type']); ?>" id="<?php echo esc_attr($name); ?>" placeholder="<?php  echo esc_attr($placeholder); ?>" name="<?php echo esc_attr($name); ?>" min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" class="<?php echo esc_attr($class); ?>">
                        </div>
                    <?php
                }
            }
        ?>
            <!-- <input type="submit" value="<?php // echo esc_attr($data[0]['buttonText']); ?>"> -->
            <button class="g-recaptcha" 
            data-sitekey="<?php echo esc_attr($site_key); ?>" 
            data-callback='onSubmit' 
            data-action='submit'>Submit</button>
        </form>

        <script>
            function onSubmit(token) {
                document.getElementById("contactForm").submit();
            }
        </script>
<?php
        if( !empty($secret_key) && isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  
        
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

            // If the reCAPTCHA API response is valid  
            if($responseData->success){ 
                // Send email notification to the site admin  
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
                @mail($to, $subject, $htmlContent, $headers);  
                
                $status = 'success';  
                $statusMsg = 'Thank you! Your contact request has been submitted successfully.';  
                $postData = '';  
            }else{  
                $statusMsg = 'The reCAPTCHA verification failed, please try again.';  
            }  
        }else{  
            $statusMsg = 'Something went wrong, please try again.';  
        }  
         if(!empty($statusMsg)){ ?>
            <p class="status-msg <?php echo $status; ?>"><?php echo $statusMsg; ?></p>
        <?php } 
        return ob_get_clean();
    }
}

if (class_exists('Loader')) {
    new Loader();
}
