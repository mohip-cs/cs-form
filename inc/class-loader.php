<?php

/**
 *  Loader.
 *
 * @package CF
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
        add_action('init', array($this, 'register_post_meta_cf'));
        add_action('init', array($this, 'contact_form_cs_contact_form_block_init'));
        add_action('init', array($this, 'contact_form_cs_listing_contact_form_block_init'));
    }

    function contact_form_cs_contact_form_block_init()
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
        // var_dump($attributes);
        // die('testtt123');
        update_post_meta($attributes['postId'], 'cf_attributes', $attributes);
        // ob_start();
    }

    function register_post_meta_cf()
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

    function contact_form_cs_listing_contact_form_block_init()
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

    function render_callback_post($attributes)
    {
        $data = get_post_meta($attributes['postId'], 'cf_attributes');

        ob_start();
?>
        <pre>
            <?php
                var_dump($data[0]['FormData']);
            ?>
        </pre>
        <form action="action_page.php">

        <?php
            foreach ($data[0]['FormData'] as $index=>$field) {
                $placeholder = isset($field['placeholder']) ? ($field['placeholder']) : '';
                $required = isset($field['required']) ? ($field['required']) : false;
                $class = isset($field['class']) ? ($field['class']) : 'cs_form_'.$field['type'];
                $name = isset($field['name']) ? ($field['name']) : 'cs_form_'.$field['type'].$index;
                // print_r($field);
                if ( 'textarea' === $field['type'] ) {
                    ?>
                          <div>  <?php
                            if(isset($field['label'])) {
                                ?>
                                <label for=""><?php  echo esc_attr($field['label']); ?></label><br>
                                <?php
                            }
                            ?>
                              <textarea class="<?php echo esc_attr($class); ?>" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" placeholder="<?php  echo esc_attr($placeholder); ?>"></textarea> 
                          </div>
                    <?php
                } elseif ('action' === $field['type'] ) {
                    // doaction will perform here
                } elseif ('drop-down' === $field['type'] ) {
                    $multiple = isset($field['multiple']) ? ($field['multiple']) : false;
                    $options = isset($field['multiple']) ? explode(",",$field['multiple']) : '';
                    ?>
                        <div><?php
                            if(isset($field['label'])) {
                                ?>
                                <label for="<?php echo esc_attr($field['label']); ?>"><?php echo esc_attr($field['label']); ?></label><br>
                                <?php
                            }
                            ?>
                            <select id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($class); ?>">
                                <option value="australia">Australia</option>
                                <option value="canada">Canada</option>
                                <option value="usa">USA</option>
                            </select>
                        </div>
                    <?php

                } elseif ('checkboxes' === $field['type'] ) {
                    ?>
                        <div>
                            <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                            <label for="vehicle1"> I have a bike</label><br>
                            <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
                            <label for="vehicle2"> I have a car</label><br>
                            <input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
                            <label for="vehicle3"> I have a boat</label><br>
                        </div>
                    <?php

                } elseif ('radio_buttons' === $field['type'] ) {
                    ?>
                        <div>
                            <input type="radio" id="html" name="fav_language" value="HTML">
                            <label for="html">HTML</label><br>
                            <input type="radio" id="css" name="fav_language" value="CSS">
                            <label for="css">CSS</label><br>
                            <input type="radio" id="javascript" name="fav_language" value="JavaScript">
                            <label for="javascript">JavaScript</label>
                        </div>
                    <?php

                } else {
                    ?>
                        <div>
                            <label for=""><?php  echo esc_attr($field['label']); ?></label><br>
                            <input type="<?php  echo esc_attr($field['type']); ?>" >
                        </div>
                    <?php
                }
            }
        ?>

            <!-- <label for="fname">First Name</label>
            <input type="text" id="fname" name="firstname" placeholder="Your name..">

            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lastname" placeholder="Your last name..">

            <label for="country">Country</label>
            <select id="country" name="country">
                <option value="australia">Australia</option>
                <option value="canada">Canada</option>
                <option value="usa">USA</option>
            </select>

            <label for="subject">Subject</label>
            <textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea> -->

            <input type="submit" value="Submit">

        </form>
<?php
        return ob_get_clean();
    }
}

if (class_exists('Loader')) {
    $class = new Loader();
}
