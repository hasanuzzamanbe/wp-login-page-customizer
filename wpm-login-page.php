<?php

/**
 * Plugin Name: WPM Login Page
 * Plugin URI: http://wpminers.com/
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
        $this->optionPageSetup();
    }

    public function loadRequiredFiles()
    {
        require WPM_LOGIN_PAGE_DIR . 'Modules/OptionPage.php';
        (new WPMLoginPage\Modules\OptionPage('wpm-login-page'));
    }


    public function loginRedirectControl()
    {
        //logout and login redirect to home
        add_action('wp_logout', function(){
            wp_safe_redirect(home_url());
            exit();
        });
        add_filter( 'login_redirect', function () {
            return home_url();
        }, 10, 3 );
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

    public function optionPageSetup()
    {
        

    }
}

(new WPMLoginPage)->init();
