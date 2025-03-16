<?php

/**
 * Plugin Name: WPM Login Page Customizer
 * Plugin URI: https://github.com/hasanuzzamanbe/wp-login-page-customizer
 * Description: A collection of snippets for WordPress.
 * Author: Hasanuzzaman Shamim
 * Author URI: http://wpminers.com/
 * Version: 1.0.6
 */
define('WPM_LOGIN_PAGE_URL', plugin_dir_url(__FILE__));
define('WPM_LOGIN_PAGE_DIR', plugin_dir_path(__FILE__));

define('WPM_LOGIN_PAGE_VERSION', '1.0.0');


class WPMLoginPage {
    public function init()
    {
        $this->loadRequiredFiles();
        $this->loginRedirectControl();
        $this->registerPageDesign();
    }

    public function loadRequiredFiles()
    {
        require WPM_LOGIN_PAGE_DIR . 'Modules/OptionPage.php';
        (new WPMLoginPage\Modules\OptionPage('wpm-login-page'));


        require WPM_LOGIN_PAGE_DIR . 'Modules/Recaptcha.php';
        (new WPMLoginPage\Modules\Recaptcha());
    }


    public function loginRedirectControl()
    {
        //logout and login redirect to home
        add_action('wp_logout', function(){
            wp_safe_redirect(home_url());
            exit();
        });
        add_filter( 'login_redirect', function ($redirect_to, $request, $user) {
            if (isset($user->roles) && is_array($user->roles)) {
                return home_url('/dashboard');
            } else {
                return $redirect_to;
            }
        }, 10, 3 );

        add_action( 'template_redirect', function () {
            if ( !is_user_logged_in() && is_page('dashboard') ) {
                wp_redirect( wp_login_url( get_permalink() ) ); // Redirect to login page with return URL
                exit;
            };
        });
    }

    public function registerPageDesign()
    {

       add_action( 'login_enqueue_scripts', function() {
            $title = esc_html(get_option( 'wpm_login_page_welcome_title'));
            $description = esc_html(get_option( 'wpm_login_page_welcome_description'));

           wp_enqueue_style('wpm_login_page_css', WPM_LOGIN_PAGE_URL . 'assets/css/login_page_styling.css');
           wp_enqueue_script('wpm_login_page_js', WPM_LOGIN_PAGE_URL . 'assets/js/login_page.js', array('jquery'), WPM_LOGIN_PAGE_VERSION, true);
           wp_localize_script('wpm_login_page_js', 'wpmLoginPageAdmin', array(
               'assets_url' => WPM_LOGIN_PAGE_URL . 'assets/',
               'home_url' => home_url(),
               'login_title' => $title,
               'login_desc' => $description
           ));
       });

    }
}

(new WPMLoginPage)->init();
