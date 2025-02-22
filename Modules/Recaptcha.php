<?php

namespace WPMLoginPage\Modules;

class Recaptcha
{

    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'enqueue_recaptcha_script']);
        add_action('register_form', [$this, 'add_custom_registration_fields']);
        add_filter('registration_errors', [$this, 'validate_custom_registration_fields'], 10, 3);
        add_action('user_register', [$this, 'save_custom_registration_fields']);
    }

    public function enqueue_recaptcha_script()
    {
        //get value from get_option( 'wpm_login_page_recaptcha_site_key' )
        $site_key = get_option( 'wpm_login_page_recaptcha_site_key' );

        wp_enqueue_script('recaptcha-v3-api', 'https://www.google.com/recaptcha/api.js?render=' . esc_attr($site_key), array(), '3.0', true);
        wp_add_inline_script('recaptcha-v3-api', 'grecaptcha.ready(function() {
        grecaptcha.execute("' . esc_attr($site_key) . '", {action: "register_user"}).then(function(token) {
            var recaptchaResponse = document.getElementById("recaptchaResponse");
            if (recaptchaResponse) {
                recaptchaResponse.value = token;
            }
        });
    });', 'after');
    }

    public function add_custom_registration_fields()
    {
        ?>
        <p>
            <label for="full_name"><?php _e('Full Name', 'recaptcha-user-registration') ?><br />
                <input type="text" name="full_name" id="full_name" class="input" value="<?php echo esc_attr(wp_unslash($_POST['full_name'] ?? '')); ?>" size="25" /></label>
        </p>

        <input type="hidden" id="recaptchaResponse" name="recaptcha_response">
        <?php
    }

    public function validate_custom_registration_fields($errors, $sanitized_user_login, $user_email)
    {
        // Full Name validation
        if (empty($_POST['full_name'])) {
            $errors->add('empty_full_name', __('<strong>ERROR</strong>: Please enter your name.', 'recaptcha-user-registration'));
        }

        $secret_key = get_option( 'wpm_login_page_recaptcha_secret_key' );
        // Verify the reCAPTCHA response
        if (!empty($_POST['recaptcha_response'])) {
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_response = $_POST['recaptcha_response'];

            // Make and decode POST request
            $response = wp_remote_post($recaptcha_url, array(
                'body' => array(
                    'secret' => esc_attr($secret_key),
                    'response' => $recaptcha_response,
                ),
            ));
            $response = json_decode(wp_remote_retrieve_body($response), true);

            // Verify response
            if (empty($response['success']) || $response['score'] < 0.5) {
                $errors->add('invalid_captcha', __('<strong>Error</strong>: Failed to verify reCAPTCHA response.', 'recaptcha-user-registration'));
            }
        } else {
            $errors->add('no_captcha', __('<strong>Error</strong>: captcha is empty.', 'recaptcha-user-registration'));
        }

        return $errors;
    }

    public function save_custom_registration_fields($user_id)
    {
        if (!empty($_POST['full_name'])) {
            update_user_meta($user_id, 'first_name', trim($_POST['full_name']));
        }
    }
}
