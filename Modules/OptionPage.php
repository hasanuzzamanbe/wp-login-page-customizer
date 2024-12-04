<?php


namespace WPMLoginPage\Modules;


class OptionPage {

  private $pluginSlug;  

  public function __construct( $pluginSlug ) {  
    $this->pluginSlug = $pluginSlug;  
    add_action( 'admin_menu', array( $this, 'addSettingsPage' ) );  
    add_action( 'admin_init', array( $this, 'registerSettings' ) );  
  }  


  public function addSettingsPage() {  
    add_options_page(  
      'WPM Login Page Settings',  
      'WPM Login Page',  
      'manage_options',  
      $this->pluginSlug,  
      array( $this, 'renderSettingsPage' )  
    );  
  }  

  public function renderSettingsPage() {  
    ?>  
    <div class="wrap">  
      <h1>WPM Login Page Settings</h1>  
      <form method="post" action="options.php">  
        <?php  
        settings_fields( $this->pluginSlug . '-settings-group' );  
        do_settings_sections( $this->pluginSlug );  
        submit_button();  
        ?>  
      </form>  
    </div>  
    <?php  
  }  

  public function registerSettings() {  
    register_setting( $this->pluginSlug . '-settings-group', 'wpm_login_page_welcome_title' );  
    register_setting( $this->pluginSlug . '-settings-group', 'wpm_login_page_welcome_description' );  

    add_settings_section(  
      $this->pluginSlug . '-section-general',  
      'General Settings',  
      array( $this, 'sectionGeneralCallback' ),  
      $this->pluginSlug  
    );  

    add_settings_field(  
      'wpm_login_page_welcome_title',  
      'Welcome Title',  
      array( $this, 'textOptionCallback' ),  
      $this->pluginSlug,  
      $this->pluginSlug . '-section-general'  
    );  

    add_settings_field(  
        'wpm_login_page_welcome_description',  
        'Welcome Description',  
        array( $this, 'textDescriptionCallback' ),  
        $this->pluginSlug,  
        $this->pluginSlug . '-section-general'  
      ); 
  }  

  public function sectionGeneralCallback() {  
    echo '<p>Enter your settings below.</p>';  
  }  

  public function textOptionCallback() {  
    $option = get_option( 'wpm_login_page_welcome_title' );  
    echo '<input type="text" name="wpm_login_page_welcome_title" value="' . esc_attr( $option ) . '" />';  
  }  

  public function textDescriptionCallback() {  
    $option = get_option( 'wpm_login_page_welcome_description' );  
    echo '<textarea name="wpm_login_page_welcome_description" rows="5" cols="50">' . esc_textarea( $option ) . '</textarea>';  
  }  
}